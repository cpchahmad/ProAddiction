<?php

namespace App\Http\Controllers;

use App\Agent_City;
use App\Customer;
use App\Order;
use App\Order_line_Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {

//        dd($request->all());
            $total_orders = Order::Query();
            $total_sales = $total_orders->where('refund',0)->sum('total_price');
            $agents_commission = Customer::sum('commission');
            $total_commission = ($agents_commission / 100 ) * $total_sales;
            $agent_orders = Order::whereNotNull('coupon_code')->where('refund',0)->get();
            $agentorders_sale = $agent_orders->sum('total_price');
            $total_products = Order_line_Item::sum('quantity');
            $total_refunds_orders = Order::where('refund', 1)->newQuery();
        $sell_areas = Agent_City::whereNotNull('city')->get();
        $agent_names = Customer::get();
        $all_products = Order_line_Item::get();
        $sold_products =  $all_products->unique('shopify_product_id');    //now products are not duplicate

        $ordersQ = Order::query()
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                ->groupBy('date')
                ->newQuery();

            if($request->filled('date-range') || $request->filled('sell_area') || $request->filled('agent_names') || $request->filled('products')) {
                if ($request->input('date-range') != 'Select Date Range' || $request->input('sell_area') != 'Select Area' || $request->input('agent_names') != 'Select Agent Name' || $request->input('products') != 'Select Product') {
                    if ($request->input('date-range') != 'Select Date Range') {

                        $date_range = explode('-', $request->input('date-range'));
                        $start_date = $date_range[0];
                        $end_date = $date_range[1];
                        $comparing_start_date = Carbon::parse($start_date)->format('Y-m-d') . ' 00:00:00';
                        $comparing_end_date = Carbon::parse($end_date)->format('Y-m-d') . ' 23:59:59';

                        $orders = $total_orders->whereBetween('created_at', [$comparing_start_date, $comparing_end_date]);
                        $total_orders = $orders
                            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                            ->groupBy('date')
                            ->get();

                        $total_sales = $total_orders->sum('total_price');
                        $agent_orders = $agent_orders->whereBetween('created_at', [$comparing_start_date, $comparing_end_date]);
                        $agentorders_sale = $agent_orders->sum('total_price');
                        $total_commission = ($agents_commission / 100) * $total_sales;
                        $products = Order_line_Item::whereIn('order_id', $total_orders->pluck('id')->toArray());
                        $total_products = $products->sum('quantity');
                        $total_refunds_orders = $total_refunds_orders->whereBetween('created_at', [$comparing_start_date, $comparing_end_date]);

                        $graph_one_order_dates = $total_orders->pluck('date')->toArray();
                        $graph_one_order_values = $total_orders->pluck('total')->toArray();
                        $graph_two_order_values = $total_orders->pluck('total_sum')->toArray();
                    }

                    if ($request->input('sell_area') != 'Select Area') {
                        if ($request->input('date-range') != 'Select Date Range') {
                            $orders = $orders->Where('agent_sellarea', $request->sell_area);

                        }else{
                            $orders = $total_orders->Where('agent_sellarea', $request->sell_area);

                        }

                        $products = Order_line_Item::whereIn('order_id', $orders->get()->pluck('id')->toArray());
                        $total_products = $products->sum('quantity');
                        $total_refund_orders = $total_refunds_orders->Where('agent_sellarea', $request->sell_area);
                        $total_orders = $orders
                            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                            ->groupBy('date')
                            ->get();
                        $total_sales = $total_orders->sum('total_sum');
                        $total_commission = ($agents_commission / 100) * $total_sales;

                        $graph_one_order_dates = $total_orders->pluck('date')->toArray();
                        $graph_one_order_values = $total_orders->pluck('total')->toArray();
                        $graph_two_order_values = $total_orders->pluck('total_sum')->toArray();
                    }

                    if ($request->input('agent_names') != 'Select Agent Name') {
                        $agent_name = Customer::where('email', $request->agent_names)->first();
                        if ($request->input('date-range') != 'Select Date Range') {
                            $orders = $orders->Where('coupon_code', $agent_name->coupon_code);

                        }elseif ($request->input('sell_area') != 'Select Area') {
                            $orders = $orders->Where('coupon_code', $agent_name->coupon_code);
                        }
                        else{
                            $orders = $total_orders->Where('coupon_code', $agent_name->coupon_code);

                        }
                        if (isset($orders)) {
                            $products = Order_line_Item::whereIn('order_id', $orders->get()->pluck('id')->toArray());
                            $total_products = $products->sum('quantity');
                            $total_orders = $orders
                                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                                ->groupBy('date')
                                ->get();
                            $total_sales = $total_orders->sum('total_sum');
                            $total_commission = ($agents_commission / 100) * $total_sales;
                            $total_refund_orders = $total_refunds_orders->Where('coupon_code', $agent_name->coupon_code);

                            $graph_one_order_dates = $total_orders->pluck('date')->toArray();
                            $graph_one_order_values = $total_orders->pluck('total')->toArray();
                            $graph_two_order_values = $total_orders->pluck('total_sum')->toArray();
                        }
                    }

                    if ($request->input('products') != 'Select Product') {

                        $products = $all_products->Where('shopify_product_id', $request->products);

                        if ($request->input('date-range') != 'Select Date Range')
                            $orders = $orders->whereIn('id', $products->pluck('order_id')->toArray());
                        elseif ($request->input('sell_area') != 'Select Area')
                            $orders = $orders->whereIn('id', $products->pluck('order_id')->toArray());

                        elseif ($request->input('agent_names') != 'Select Agent Name')
                            $orders = $orders->whereIn('id', $products->pluck('order_id')->toArray());

                        else{
                            $orders = $total_orders->whereIn('id', $products->pluck('order_id')->toArray());

                        }
                        $total_products = $products->count();
                        $total_orders = $orders
                            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                            ->groupBy('date')
                            ->get();
                        $total_sales = $total_orders->sum('total_sum');
                        $total_commission = ($agents_commission / 100) * $total_sales;
                        $total_refund_orders = $total_refunds_orders->whereIn('id', $products->pluck('order_id')->toArray());

                        $graph_one_order_dates = $total_orders->pluck('date')->toArray();
                        $graph_one_order_values = $total_orders->pluck('total')->toArray();
                        $graph_two_order_values = $total_orders->pluck('total_sum')->toArray();

                    }

                    return view('dashboard')->with([
                        'total_orders' => $total_orders,
                        'total_sales' => $total_sales,
                        'total_commission' => $total_commission,
                        'total_products' => $total_products,
                        'total_refund_orders' => $total_refunds_orders,
                        'agent_orders' => $agent_orders,
                        'agentorders_sale' => $agentorders_sale,
                        'agent_names' => $agent_names,
                        'sell_areas' => $sell_areas,
                        'sold_products' => $sold_products,
                        'graph_one_labels' => $graph_one_order_dates,
                        'graph_one_values' => $graph_one_order_values,
                        'graph_two_values' => $graph_two_order_values,
                        'date_range' => $request->input('date-range'),
                        'auto_selection_sellarea'=>$request->input('sell_area'),
                        'auto_selection_agentname'=>$request->input('agent_names'),
                        'auto_selection_product'=>$request->input('products'),

                    ]);
                }

            }


            $ordersQ->get();
            $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
            $graph_one_order_values = $ordersQ->pluck('total')->toArray();
            $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();

            return view('dashboard')->with([
                'total_orders' => $total_orders,
                'total_sales' => $total_sales,
                'total_commission' => $total_commission,
                'total_products' => $total_products,
                'total_refund_orders' => $total_refunds_orders,
                'agent_orders' => $agent_orders,
                'agentorders_sale' => $agentorders_sale,
                'agent_names' => $agent_names,
                'sell_areas' => $sell_areas,
                'sold_products' => $sold_products,
                'graph_one_labels' => $graph_one_order_dates,
                'graph_one_values' => $graph_one_order_values,
                'graph_two_values' => $graph_two_order_values,
                'date_range' => $request->input('date-range'),
                'auto_selection_sellarea'=>$request->input('sell_area'),
                'auto_selection_agentname'=>$request->input('agent_names'),
                'auto_selection_product'=>$request->input('products'),
            ]);

    }
}
