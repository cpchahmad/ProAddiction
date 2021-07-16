<?php

namespace App\Http\Controllers;

use App\Commission;
use App\Customer;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function showAll()
    {
        $orders = Order::latest()->paginate(20);
        return view('orders', compact('orders'));
    }

    public function syncOrders($next = null){
        $shop = Auth::user();

        $orders = $shop->api()->rest('GET', '/admin/orders.json', [
            'limit' => 250,
            'page_info' => $next,
            'status' => 'any',
        ]);


        $orders = json_decode(json_encode($orders));
        foreach ($orders->body->orders as $order){
            $this->createShopifyOrders($order, $shop);
        }
        if (isset($orders->link->next))
            $this->syncOrders($orders->link->next);
        return redirect()->back()->with('success', 'Orders synced successfully');
    }

    public function createShopifyOrders($order, $shop){
        $o = Order::where('order_id', $order->id)->first();
        if($o === null)
            $o = new Order();
        $o->order_id = $order->id;
        $o->created_at = $order->created_at;
        $o->order_name = $order->name;
        $o->total_price = $order->total_price;
        if ( $order->cancelled_at != null)
            $o->status = 0;
        else
            $o->status = 1;
        if ( empty($order->refunds))
            $o->refund = 0;
        else
            $o->refund = 1;
        if (isset($order->customer))
        {
            $o->customer_name = $order->customer->first_name. ' ' .$order->customer->last_name;
            $o->shopify_id = $order->customer->id;
            $o->total_order = $order->customer->orders_count;
            $c = Customer::where('shopify_id', $order->customer->id)->first();
            if($c === null)
                $c = new Customer();
            $c->shopify_id = $order->customer->id;
            if($c->email)
                $c->email = $order->customer->email;
            else
                $c->email = 'none';
            $c->first_name = $order->customer->first_name;
            $c->last_name = $order->customer->last_name;
            $c->email = $order->customer->email;
            $c->seller_color = 'test';
            $c->phone_no = $order->customer->phone;
            if(isset($order->customer->addresses[0])) {
                $c->seller_code = $order->customer->addresses[0]->zip;
                $c->seller_area = $order->customer->addresses[0]->address1 . ' ' . $order->customer->addresses[0]->address2;
            }
            $c->shop_id = $shop->id;
            $c->save();
        }
        else
            $o->customer_name = 'none';
        if (isset($order->shipping_address))
            $o->shiping_address = json_encode($order->shipping_address);
        else
            $o->shiping_address = 'none';
        if($order->discount_codes)
            $o->coupon_code = $order->discount_codes[0]->code;
        else
            $o->coupon_code = 'none';
        $o->shop_id = $shop->id;
        $o->save();
        if($o->agent != null) {
            $commission = Commission::where('order_id', $order->id)->first();
            if($commission === null)
                $commission = new Commission();
            $commission->customer_id = $o->agent->id;
            $commission->order_id = $o->order_id;
            $commission->commission = $this->calculateCommission($order->total_price, $o->agent->commission);
            $commission->save();
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
        $shop =Auth::user();
        $order = $shop->api()->rest('GET', '/admin/orders/'.$request->order_id.'.json');
        $order = json_decode(json_encode($order));
        $location = $shop->api()->rest('GET', '/admin/locations.json');
        $location = json_decode(json_encode($location));
        $refundData =
            [
                'refund' =>
                [
                    'currency' => $order->body->order->currency,
                    'notify' => true,
                    'shipping' =>
                    [
                        'full_refund' => true,
                    ],
                    'refund_line_items' =>
                    [

                    ]
                ]
            ];
        foreach ($order->body->order->line_items as $lineItem) {
            array_push($refundData['refund']['refund_line_items'], [
                    "line_item_id" => $lineItem->id,
                    "quantity" => $lineItem->quantity,
                    'restock_type' => 'return',
                    "location_id" => $location->body->locations[0]->id,
            ]);
        }

        $refund = $shop->api()->rest('POST', '/admin/orders/'.$request->order_id.'/refunds.json', $refundData);
        $refund = json_decode(json_encode($refund));
        if (!$refund->errors) {
            return redirect()->back()->with('success', 'Order Refunded Successfully');
        }
        else {
            return redirect()->back();
        }
    }
}
