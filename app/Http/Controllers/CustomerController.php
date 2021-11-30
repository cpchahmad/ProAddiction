<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Mail\SendEmail;
use App\Professional;
use App\User;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function ShopifyCustomers($next = null)
    {
        $shop = Auth::user();

        $customers = $shop->api()->rest('GET', '/admin/customers.json', [
            'limit' => 250,
            'page_info' => $next
        ]);
        $customers = json_decode(json_encode($customers));
        foreach ($customers->body->customers as $customer) {
            $this->createShopifyCustomer($customer, $shop);
        }
        if (isset($customers->link->next)) {
            $this->ShopifyCustomers($customers->link->next);
        }
        return redirect()->back()->with('success', 'Customers synced successfully');
    }

    public function createShopifyCustomer($customer, $shop)
    {

        $c = Customer::where('shopify_id', $customer->id)->first();
        if ($c === null) {
            $c = new Customer();
        }
        $c->shopify_id = $customer->id;
        if ($c->email) {
            $c->email = $customer->email;
        } else {
            $c->email = 'none';
        }
        $c->first_name = $customer->first_name;
        $c->last_name = $customer->last_name;
        $c->email = $customer->email;
        $c->phone_no = $customer->phone;
        if (isset($customer->addresses[0])) {
            $c->seller_code = $customer->addresses[0]->zip;
            $c->seller_area = $customer->addresses[0]->address1 . ' ' . $customer->addresses[0]->address2;
        }
        $c->shop_id = $shop->id;
        $c->save();
    }

    /*public function storeCustomersToDB($request)
    {
        $customers = json_decode($request['response']->getBody(), JSON_PRETTY_PRINT);
        if (count($customers) > 0)
        {
            $customerInDb = Customer::query();
            foreach ($customers as $indexs)
            {
                foreach ($indexs as $customer)
                {
                    if (!$customerInDb->where('shopify_customer_id', $customer['id'])->exists()) {
                        $customerInDb->create([
                            'shopify_customer_id' => $customer['id'],
                            'name' => $customer['first_name'] . ' ' . $customer['last_name'],
                            'email' => $customer['email'],
                            'phone_no' => $customer['phone'],
                            'seller_code' => $customer['addresses'][0]['zip'],
                            'seller_area' => $customer['addresses'][0]['address1'] . ' ' . $customer['addresses'][0]['address2'],
                        ]);
                    }
                    else {
                        $customerInDb->where('shopify_customer_id', $customer['id'])->update([
                            'shopify_customer_id' => $customer['id'],
                            'name' => $customer['first_name'] . ' ' . $customer['last_name'],
                            'email' => $customer['email'],
                            'phone_no' => $customer['phone'],
                            'seller_code' => $customer['addresses'][0]['zip'],
                            'seller_area' => $customer['addresses'][0]['address1'] . ' ' . $customer['addresses'][0]['address2'],
                        ]);
                    }
                }
            }
        }
    }*/

    public function addAgent(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:customers',
            'phone_no' => 'required|unique:customers',
        ]);
        $shop = Auth::user();

        $customers = $shop->api()->rest('post', '/admin/customers.json', [

            'customer' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'addresses' => [0 => [
                    'zip' => $request->seller_code,
                    'address1' => $request->city . ' ' . $request->state . ' ' . $request->country,
                ]
                ],
                "password" => $request->password,
                "password_confirmation" => $request->password,
                "tags" => "agent",
                "metafields" =>
                    array(
                        0 =>
                            array(
                                "key" => 'is_agent',
                                "value" => 1,
                                "value_type" => "boolean",
                                "namespace" => "customers",
                            ),
                        1 =>
                            array(
                                "key" => 'discount',
                                "value" => $request->discount,
                                "value_type" => "float",
                                "namespace" => "customers",
                            ),
                        2 =>
                            array(
                                "key" => 'commission',
                                "value" => $request->commission_rate,
                                "value_type" => "float",
                                "namespace" => "customers",
                            ),
                        3 =>
                            array(
                                "key" => 'seller_code',
                                "value" => $request->seller_code,
                                "value_type" => "string",
                                "namespace" => "customers",
                            ),
                    ),

            ]
        ]);

        $customers = json_decode(json_encode($customers['body']['container']['customer']));

        if (isset($request->discount)) {
            list($couponCode, $price_rule_id,$discount_id) = $this->createDiscount($request->discount, $shop,$customers);
        }
        else
            $couponCode = 'none';
        $c = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'shopify_id' => $customers->id,
            'seller_code' => $request->seller_code,
            'seller_area' => $request->city.' '.$request->state.' '.$request->country,
            'phone_no' => $request->phone_no,
            'is_agent' => 1,
            'shop_id' => $shop->id,
            'coupon_code' => $couponCode,
            'discount' => $request->discount,
            'seller_color' => $request->seller_color,
            'commission' => $request->commission_rate,
            'price_rule_id' => $price_rule_id,
            'discount_id' => $discount_id,
            'tag' => "agent",

        ]);

        $customers = json_decode(json_encode($customers));
//        $test = $shop->api()->rest('GET', '/admin/customers/'.$customers->body->customer->id.'/metafields.json');

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'customer_id' => $c->id,
        ]);
        $user->assignRole('agent');
        try{

            $data['subject'] = "ProAddiction";
            $data['message'] = "You just added as a new agent. Please visit Store.";
            $send_to = $c->email;
            $data['from_address'] = env('MAIL_FROM_ADDRESS');//Sender Email
            Mail::to($send_to)->send(new SendEmail($data));

            $data2['subject'] = "New Agent";
            $data2['message'] = "You just added a new AGENT";
            $send_to2 = "info@proaddiction.com";
            $data2['from_address'] = env('MAIL_FROM_ADDRESS');//Sender Email
            Mail::to($send_to2)->send(new SendEmail($data2));


            return redirect()->back()->with('success', 'Agent Added Successfully');

        }catch (\Exception $exception){
            return redirect()->back()->with('success', 'Agent Added Successfully');

        }


    }

    public function customer_detail($id)
    {

        $customer = Customer::findorfail($id);
        return view('customerdetail')->with(
            'customer', $customer
        );

    }


    public function createDiscount($discount, $shop, $customers)
    {
        $customers = json_decode(json_encode($customers));
        $priceRule = $shop->api()->rest('post', '/admin/price_rules.json', [
            'price_rule' => [
                'title' => "$customers->first_name"."$customers->last_name"."$discount" . "%OFFONEACHITEM" . Carbon::now(),
                'customer_selection' => 'prerequisite',
                "prerequisite_customer_ids" => [
                    $customers->id
                ],
                'value' => '-' . $discount,
                'value_type' => 'percentage',
                'target_type' => "line_item",
                'target_selection' => "all",
                "allocation_method" => "across",
                'starts_at' => Carbon::now(),
            ]
        ]);
        $priceRule = json_decode(json_encode($priceRule));

        $discountCode = $shop->api()->rest('post', '/admin/price_rules/' . $priceRule->body->price_rule->id . '/discount_codes.json', [
            'discount_code' => [
                'code' => $priceRule->body->price_rule->title,
            ]
        ]);
        $discountCode = json_decode(json_encode($discountCode));
        if (!$discountCode->errors) {
            return [$discountCode->body->discount_code->code, $discountCode->body->discount_code->price_rule_id,$discountCode->body->discount_code->id];
        }
        return 'none';
    }

    public function customer_delete($id){
        $customer = Customer::findorfail($id);
        if ($customer){
            $customer->delete();
        }

        $shop = Auth::user();
        $customer_delete = $shop->api()->rest('delete',  '/admin/customers/'.$customer->shopify_id.'.json');
        $discount_delete = $shop->api()->rest('delete', '/admin/price_rules/' . $customer->price_rule_id . '/discount_codes/' . $customer->discount_id . '.json');
        return back()->with('success','Customer Deleted Successfully');
    }

    public function check_customer_email(Request $request)
    {

        $customer = Customer::where('email', $request->email)->first();

        if (isset($customer)) {
            return response()->json([
                'discount' => $customer->discount,
                'coupon_code' => $customer->coupon_code,
                'agent' => 'you are an agent',
            ]);
        } else {
            return response()->json([
                'agent' => 'you are not an agent',
            ]);
        }
    }

    public function professional_form(){

        return view('professional_form');
    }
    public function professional_form_submit(Request $request){
        $request->validate([
            'name'     =>  'required',
            'email'           => 'required',
            'password'           => 'required',
            'phone'           => 'required',
            'address'           => 'required',
            'file'           => 'required'
        ]);
        if ($request->hasFile('file')) {
            $date = Carbon::now();
            $date = strtotime($date->toDateTimeString());
            $file = $request->file('file');
            $name = str_replace(' ', '', $file->getClientOriginalName());
            $name = "file_".$date ."_" .$name;
            $file->move(public_path() . '/professional_files/', $name);
            $file_name = '/professional_files/' . $name;
        }else{
            $file_name="";
        }
        $p_data=new Professional();
        $p_data->name=$request->input('name');
        $p_data->email=$request->input('email');
        $p_data->password=$request->input('password');
        $p_data->address=$request->input('address');
        $p_data->phone_no=$request->input('phone');
        $p_data->file_name=$file_name;
        $p_data->save();
        try{

            $data['subject'] = "New Professional";
            $data['message'] = "You have a new requests - PROFESSIONAL ONLY.";
            $send_to = "info@proaddiction.com";
            $data['from_address'] = env('MAIL_FROM_ADDRESS');//Sender Email
            Mail::to($send_to)->send(new SendEmail($data));


            return view('professional_form_submit');

        }catch (\Exception $exception){
            return view('professional_form_submit');

        }
//        return back()->with('success','Form Submitted Successfully!');
    }
    public function professionals(){
        $professionals=Customer::where('tag',"Professional")->paginate(50);
        return view('professionals',compact('professionals'));
    }
    public function professionals_check(){
        $professionals=Professional::where('status',0)->orderBy('created_at','desc')->paginate(50);
        return view('professionals_check',compact('professionals'));
    }
    public function professional_detail($id)
    {
        $professional = Professional::findorfail($id);
        return view('professionaldetail')->with(
            'professional', $professional
        );

    }
    public function professional_approve($id)
    {
        $professional = Professional::findorfail($id);
        $professional->status=1;
        $professional->save();
        $discount=50;
        $shop = Auth::user();
        $customer=Customer::where('email',$professional->email)->first();
        if($customer==null){
            $customers = $shop->api()->rest('post', '/admin/customers.json', [
                'customer' => [
                    'first_name' => $professional->name,
                    'last_name' => $professional->name,
                    'email' => $professional->email,
                    'addresses' => [0 => [
                        'zip' => '',
                        'address1' => $professional->address,
                    ]
                    ],
                    "password" => $professional->password,
                    "password_confirmation" => $professional->password,
                    "tags" => "professional",
                    "metafields" =>
                        array(
                            0 =>
                                array(
                                    "key" => 'is_professional',
                                    "value" => 1,
                                    "value_type" => "boolean",
                                    "namespace" => "customers",
                                ),
                            1 =>
                                array(
                                    "key" => 'discount',
                                    "value" => $discount,
                                    "value_type" => "float",
                                    "namespace" => "customers",
                                ),
                        ),
                ]
            ]);

            $customers = json_decode(json_encode($customers['body']['container']['customer']));
            list($couponCode, $price_rule_id,$discount_id) = $this->createDiscount($discount, $shop,$customers);

            $c = Customer::create([
                'first_name' => $professional->name,
                'last_name' => $professional->name,
                'email' => $professional->email,
                'shopify_id' => $customers->id,
                'seller_area' => $professional->address,
                'phone_no' => $professional->phone_no,
                'shop_id' => $shop->id,
                'tag' => "professional",
                'coupon_code' => $couponCode,
                'discount' => $discount,
                'price_rule_id' => $price_rule_id,
                'discount_id' => $discount_id,
            ]);
        }else{
            $customer->tag="professional";
            $customer->save();
        }
        try{

            $data['subject'] = "ProAddiction";
            $data['message'] = "You just got approved to PROFESSIONAL. Please visit Store.";
            $send_to = $c->email;
            $data['from_address'] = env('MAIL_FROM_ADDRESS');//Sender Email
            Mail::to($send_to)->send(new SendEmail($data));


            return redirect(route('professionals.check'));

        }catch (\Exception $exception){
            return redirect(route('professionals.check'));

        }
    }
    public function professional_disapprove($id)
    {
        $professional = Professional::findorfail($id);
        $professional->status=2;
        $professional->save();

        return redirect(route('professionals.check'));

    }
}


