<?php

namespace App\Http\Controllers;

use App\Agent_City;
use App\Customer;
use App\Order;
use App\Order_line_Item;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
//        dd($request->all());
        if (isset(Auth::user()->customer_id)) {
            $agent = Customer::find(Auth::user()->customer_id);

            $agent_coupen = $agent->coupon_code;

            $agent_orders = Order::where('coupon_code', $agent_coupen)->where('refund', 0)->newQuery();
            $agent_products = Order_line_Item::whereIn('order_id', $agent_orders->pluck('id')->toArray());
            $agent_total_products = $agent_products->sum('quantity');
            $agent_refunds_orders = Order::where('coupon_code', $agent_coupen)->where('refund', 1)->newQuery();
            $total_sales = $agent_orders->sum('total_price');

            $total_commission = ($agent->commission / 100) * $total_sales;
            $store_total_orders = Order::get()->count();
            $store_total_sales = Order::get()->sum('total_price');
            $agent_sellareas = Agent_City::where('agent_code', $agent_coupen)->get();
            $ordersQ = Order::query()
                ->where('coupon_code', $agent_coupen)
                ->where('refund', 0)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                ->groupBy('date')
                ->newQuery();

            if ($request->filled('date-range') || $request->filled('sell_area')) {

                if ($request->input('date-range') != 'Select Date Range') {
                    $date_range = explode('-', $request->input('date-range'));
                    $start_date = $date_range[0];
                    $end_date = $date_range[1];
                    $comparing_start_date = Carbon::parse($start_date)->format('Y-m-d') . ' 00:00:00';
                    $comparing_end_date = Carbon::parse($end_date)->format('Y-m-d') . ' 23:59:59';
                    $orders = $agent_orders->whereBetween('created_at', [$comparing_start_date, $comparing_end_date]);
                    $agent_orders = $orders
                        ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                        ->groupBy('date')
                        ->get();

                    $agent_products = Order_line_Item::whereIn('order_id', $agent_orders->pluck('id')->toArray());
                    $agent_total_products = $agent_products->sum('quantity');
                    $refund_orders = $agent_refunds_orders->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->orWhere('agent_sellarea', $request->sell_area);

                    $total_sales = $agent_orders->sum('total_price');
                    $total_commission = ($agent->commission / 100) * $total_sales;

                }
                if ($request->input('sell_area') != 'Select Sell Area') {
                    $orders = $agent_orders->Where('agent_sellarea', $request->sell_area);

                    if ($request->input('date-range') != 'Select Date Range')
                        $orders = $agent_orders->Where('agent_sellarea', $request->sell_area);
                    else {
                        $agent_orders = $orders
                            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                            ->groupBy('date')
                            ->get();
                    }

                    $refund_orders = $agent_refunds_orders->Where('agent_sellarea', $request->sell_area);
                    $total_sales = $agent_orders->sum('total_sum');

                    $total_commission = ($agent->commission / 100) * $total_sales;
                }


                $graph_one_order_dates = $agent_orders->pluck('date')->toArray();
                $graph_one_order_values = $agent_orders->pluck('total')->toArray();
                $graph_two_order_values = $agent_orders->pluck('total_sum')->toArray();


                return view('agenthome')->with([
                    'agent_orders' => $agent_orders,
                    'total_sales' => $total_sales,
                    'total_commission' => $total_commission,
                    'store_total_orders' => $store_total_orders,
                    'store_total_sales' => $store_total_sales,
                    'graph_one_labels' => $graph_one_order_dates,
                    'graph_one_values' => $graph_one_order_values,
                    'graph_two_values' => $graph_two_order_values,
                    'agent_sellareas' => $agent_sellareas,
                    'agent_total_products' => $agent_total_products,
                    'refund_orders' => $refund_orders,
                    'date_range' => $request->input('date-range'),
                    'auto_selection_sellarea' => $request->input('sell_area'),

                ]);

            }

            $agent_sellareas = Agent_City::where('agent_code', $agent_coupen)->get();

            $ordersQ->get();
            $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
            $graph_one_order_values = $ordersQ->pluck('total')->toArray();
            $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();
            return view('agenthome')->with([
                'agent_orders' => $agent_orders,
                'total_sales' => $total_sales,
                'total_commission' => $total_commission,
                'store_total_orders' => $store_total_orders,
                'store_total_sales' => $store_total_sales,
                'graph_one_labels' => $graph_one_order_dates,
                'graph_one_values' => $graph_one_order_values,
                'graph_two_values' => $graph_two_order_values,
                'agent_sellareas' => $agent_sellareas,
                'agent_total_products' => $agent_total_products,
                'refund_orders' => $agent_refunds_orders,
                'date_range' => $request->input('date-range'),
                'auto_selection_sellarea' => $request->input('sell_area'),
            ]);
        } else {
            return view('home');
        }

    }

}

