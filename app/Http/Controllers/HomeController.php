<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {
        if (isset(Auth::user()->customer_id)) {
            $agent = Customer::find(Auth::user()->customer_id);
            $agent_coupen = $agent->coupon_code;
            $agent_orders = Order::where('coupon_code', $agent_coupen)->get();
            $total_sales = $agent_orders->sum('total_price');
            $total_commission = (($agent->commission / 100) * $total_sales );
            return view('agenthome')->with([
                'agent_orders' => $agent_orders,
                'agent' => $agent,
                'total_sales' => $total_sales,
                'total_commission' => $total_commission,
            ]);
        }else{
            return view('home');
        }

    }
}
