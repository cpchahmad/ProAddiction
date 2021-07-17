<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {

            $total_orders = Order::get();
            $total_sales = $total_orders->sum('total_price');
            $agents_commission = Customer::sum('commission');
            $total_commission = ($agents_commission / 100 ) * $total_sales;
            $agent_orders = Order::whereNotNull('coupon_code')->get();
            $agentorders_sale = $agent_orders->sum('total_price');
            $ordersQ = Order::query()
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                ->groupBy('date')
                ->newQuery();

            if($request->filled('date-range')) {
                $date_range = explode('-', $request->input('date-range'));
                $start_date = $date_range[0];
                $end_date = $date_range[1];
                $comparing_start_date = Carbon::parse($start_date)->format('Y-m-d') . ' 00:00:00';
                $comparing_end_date = Carbon::parse($end_date)->format('Y-m-d') . ' 23:59:59';

                $orders = $total_orders->whereBetween('created_at', [$comparing_start_date, $comparing_end_date]);


                   $total_orders = $orders;
                   $total_sales = $total_orders->sum('total_price');
                   $agent_orders = $agent_orders->whereBetween('created_at', [$comparing_start_date, $comparing_end_date]);
                   $agentorders_sale = $agent_orders->sum('total_price');
                   $total_commission = ($agents_commission / 100) * $total_sales;
                   $ordersQ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date]);

                   $ordersQ->get();

                   $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
                   $graph_one_order_values = $ordersQ->pluck('total')->toArray();
                   $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();

                   return view('dashboard')->with([
                       'total_orders' => $total_orders,
                       'total_sales' => $total_sales,
                       'total_commission' => $total_commission,
                       'agent_orders' => $agent_orders,
                       'agentorders_sale' => $agentorders_sale,
                       'graph_one_labels' => $graph_one_order_dates,
                       'graph_one_values' => $graph_one_order_values,
                       'graph_two_values' => $graph_two_order_values,
                       'date_range' => $request->input('date-range')
                   ]);

            }

            $ordersQ->get();
            $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
            $graph_one_order_values = $ordersQ->pluck('total')->toArray();
            $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();
            return view('dashboard')->with([
                'total_orders' => $total_orders,
                'total_sales' => $total_sales,
                'total_commission' => $total_commission,
                'agent_orders' => $agent_orders,
                'agentorders_sale' => $agentorders_sale,
                'graph_one_labels' => $graph_one_order_dates,
                'graph_one_values' => $graph_one_order_values,
                'graph_two_values' => $graph_two_order_values,
                'date_range' => $request->input('date-range')
            ]);

    }
}
