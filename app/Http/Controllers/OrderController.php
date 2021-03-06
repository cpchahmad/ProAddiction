<?php

namespace App\Http\Controllers;

use App\Agent_City;
use App\AgentStore;
use App\Commission;
use App\Customer;
use App\ErrorLog;
use App\Order;
use App\Order_line_Item;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function showAll(Request $request)
    {
//        dd($request->all());
        $ordersQ = Order::query();
        $agents = Customer::where('is_agent',1)->get();

        if ($request->has('date-range') && $request->input('date-range') != 'Select Date Range') {


            $date_range = explode('-', $request->input('date-range'));
            $start_date = $date_range[0];
            $end_date = $date_range[1];
            $comparing_start_date = Carbon::parse($start_date)->format('Y-m-d') . ' 00:00:00';
            $comparing_end_date = Carbon::parse($end_date)->format('Y-m-d') . ' 23:59:59';

            $ordersQ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date]);

        }
        if ($request->has('agent_name') && $request->input('agent_name') != 'Select Agent Name') {
            $agent_name = User::where('email', $request->agent_name)->first();
            $a_stores=$agent_name->has_stores->pluck('email')->toArray();
            array_push($a_stores,$agent_name->email);

            $ordersQ->whereIn('email',$a_stores);
        }

        $orders = $ordersQ->paginate(10);
        $auto_selection_name = $request->input('agent_name');
        return view('orders', compact('orders','agents','auto_selection_name'));
    }

    public function syncOrders($next = null)
    {
        $shop = Auth::user();

        $orders = $shop->api()->rest('GET', '/admin/orders.json', [
            'limit' => 250,
            'page_info' => $next,
            'status' => 'any',
        ]);


        $orders = json_decode(json_encode($orders));
        foreach ($orders->body->orders as $order) {
            $this->createShopifyOrders($order, $shop);
        }
        if (isset($orders->link->next))
            $this->syncOrders($orders->link->next);
        return redirect()->back()->with('success', 'Orders synced successfully');
    }

    public function createShopifyOrders($order, $shop)
    {

        $o = Order::where('order_id', $order->id)->first();
        if ($o === null)
            $o = new Order();
        $o->order_id = $order->id;
        $o->created_at = $order->created_at;
        $o->order_name = $order->name;
        $o->total_price = $order->total_price;
        if ($order->cancelled_at != null)
            $o->status = 0;
        else
            $o->status = 1;
        if (empty($order->refunds))
            $o->refund = 0;
        else
            $o->refund = 1;
        if (isset($order->customer)) {
            $o->customer_name = $order->customer->first_name . ' ' . $order->customer->last_name;
            $o->shopify_id = $order->customer->id;
            $o->total_order = $order->customer->orders_count;
            $c = Customer::where('shopify_id', $order->customer->id)->first();
            if ($c === null)
                $c = new Customer();
            $c->shopify_id = $order->customer->id;
            if ($c->email)
                $c->email = $order->customer->email;
            else
                $c->email = 'none';
            $c->first_name = $order->customer->first_name;
            $c->last_name = $order->customer->last_name;
            $c->email = $order->customer->email;
            $c->seller_color = 'test';
            $c->phone_no = $order->customer->phone;
            if (isset($order->customer->addresses[0])) {
                $c->seller_code = $order->customer->addresses[0]->zip;
                $c->seller_area = $order->customer->addresses[0]->address1 . ' ' . $order->customer->addresses[0]->address2;
            }
            $c->shop_id = $shop->id;
            $c->save();
            $o->email = $order->customer->email;

        } else
            $o->customer_name = 'none';

        if (isset($order->shipping_address)) {
            $shipping_address = json_encode($order->shipping_address);
            $agent_sellares = json_decode(json_encode($order->shipping_address));
            $o->shiping_address = $shipping_address;
            $o->agent_sellarea = $agent_sellares->city;
        } else
            $o->shiping_address = 'none';

        if (isset($order->discount_codes[0]))
            $o->coupon_code = $order->discount_codes[0]->code;
        else
            $o->coupon_code = 'none';
        $o->shop_id = $shop->id;
        $o->save();
        $agent_stores=AgentStore::all;

        if($o->email != null) {
            $agent=User::Role('agent')->where('email',$o->email)->first();
            if($agent===null){
                foreach ($agent_stores as $a_store) {
                    if ($o->email == $a_store->email) {
                        $commission = Commission::where('order_id', $order->id)->first();
                        if ($commission === null)
                            $commission = new Commission();

                        $commission->customer_id = $a_store->agent_id;
                        $commission->order_id = $o->order_id;
                        $commission->commission = $this->calculateCommission($order->total_price, $a_store->agent->agentDetail->commission);
                        $commission->save();
                        $o->agent_id = $a_store->agent_id;
                        $o->save();
                    }
                }
            }else{
                $commission = Commission::where('order_id', $order->id)->first();
                if ($commission === null)
                    $commission = new Commission();

                $commission->customer_id = $agent->customer_id;
                $commission->order_id = $o->order_id;
                $commission->commission = $this->calculateCommission($order->total_price, $agent->agentDetail->commission);
                $commission->save();
                $o->agent_id = $agent->customer_id;
                $o->save();
            }


        }

        /*if ($o->agent_order != null) {
            $commission = Commission::where('order_id', $order->id)->first();
            if ($commission === null)
                $commission = new Commission();
            $commission->customer_id = $o->agent_order->customer_id;
            $commission->order_id = $o->order_id;
            $commission->commission = $this->calculateCommission($order->total_price, $o->agent_order->commission);
            $commission->save();
        }*/

        $agent_city = new Agent_City();
        if ($order->discount_codes) {
            $agent_city->agent_code = $order->discount_codes[0]->code;
        }
        if (isset($order->shipping_address)) {
            $shipping_address = json_decode(json_encode($order->shipping_address));
            $agent_city->city = $shipping_address->city;
        }
        $agent_city->save();

        foreach ($order->line_items as $item) {
            $line_item = Order_line_Item::where('shopify_order_id', $item->id)->first();
            if ($line_item == null) {
                $line_item = new Order_line_Item();
            }
            $line_item->order_id = $o->id;
            $line_item->shopify_order_id = $item->id;
            $line_item->shopify_product_id = $item->product_id;
            $line_item->sku = $item->sku;
            $line_item->title = $item->title;
            $line_item->price = $item->price;
            $line_item->quantity = $item->quantity;
            $line_item->item_src = (!empty($item->image)) ? $item->image : '';
            $line_item->save();

        }

    }

    public function createCommissionOfAgent($agentId, $orderId, $totalPrice, $commission)
    {
        Commission::create([
            'customer_id' => $agentId,
            'order_id' => $orderId,
            'commission' => $this->calculateCommission($totalPrice, $commission)
        ]);
    }

    public function calculateCommission($totalPrice, $commission)
    {
        $totalCommission = ($commission / 100) * $totalPrice;
        return $totalCommission;
    }

    public function makeRefund(Request $request)
    {

        $shop = Auth::user();
        $order = $shop->api()->rest('GET', '/admin/orders/' . $request->order_id . '.json');
        $order = json_decode(json_encode($order));
        $location = $shop->api()->rest('GET', '/admin/locations.json');
        $location = json_decode(json_encode($location));

        $calculaterefund =
            [
                "refund" => [
                    "shipping" => [
                        "full_refund" => true
                    ],
                    "refund_line_items" => [

                    ]
                ]
            ];

        foreach ($order->body->order->line_items as $lineItem) {
            array_push($calculaterefund['refund']['refund_line_items'], [
                "line_item_id" => $lineItem->id,
                "quantity" => $lineItem->quantity,
                "restock_type" => 'return',
            ]);
        }

        $calculatedrefund = $shop->api()->rest('POST', '/admin/orders/' . $request->order_id . '/refunds/calculate.json', $calculaterefund);
        $calculatedrefund = json_decode(json_encode($calculatedrefund));
//                dd($calculatedrefund);
        if ($calculatedrefund->errors == 'true') {

            Session::flash('info', 'Order has already been Refunded');
            return back();
        }
        $refundData =
            [
                "refund" => [
                    "currency" => $order->body->order->currency,
                    "notify" => true,
                    "note" => "wrong size",
                    "shipping" => [
                        "full_refund" => true
                    ],
                    "refund_line_items" => [


                    ],
                    "transactions" => [
                        [
                            "parent_id" => $calculatedrefund->body->refund->transactions[0]->parent_id,
                            "amount" => $calculatedrefund->body->refund->transactions[0]->amount,
                            "kind" => 'refund',
                            "gateway" => $calculatedrefund->body->refund->transactions[0]->gateway
                        ]
                    ],

                ]
            ];

        foreach ($order->body->order->line_items as $lineItem) {
            array_push($refundData['refund']['refund_line_items'], [
                "line_item_id" => $lineItem->id,
                "quantity" => $lineItem->quantity,
                'restock_type' => 'return',
                'location_id' => $location->body->locations[0]->id,
            ]);
        }

        $refund = $shop->api()->rest('POST', '/admin/orders/' . $request->order_id . '/refunds.json', $refundData);

        $order = Order::where('order_id', $request->order_id)->first();
        $order->status = 0;
        $order->refund = 1;
        $order->save();
        $refund = json_decode(json_encode($refund));
        if (!$refund->errors) {
            return redirect()->back()->with('success', 'Order Refunded Successfully');
        } else {

            return redirect()->back();
        }
    }
}
