<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function showAnalytics()
    {
        //$agents = Customer::where('is_agent', 1)->latest()->paginate(20);
        $agents = Customer::where('is_agent', 1)->get();
        /*foreach ($agents as $agent)
        {
            if ($agent->has_order != null)
            {
                dd($agent->has_order);
            }
        }*/
        $totalOrders = Order::where('coupon_code', '!=', 'none');
        $totalOrdersCount = $totalOrders->get()->count();
        $totalRefundCount = $totalOrders->where('refund', 1)->get()->count();
        return view('analytics', compact('agents', 'totalOrdersCount', 'totalRefundCount'));
    }
}
