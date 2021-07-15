<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
//    public function showAnalytics()
//    {
//        $authUser = Auth::user();
//        $orders = Order::latest()->get();
//        $totalOrdersCount = 0;
//        foreach ($orders as $order)
//        {
//            if ($order->agent !=  null && $order->agent->email == $authUser->email)
//            {
//                //$totalOrdersCount += $order->agent->where('email', $authUser->email)->count();
//                $totalOrdersCount += 1;
//            }
//        }
//       // dd($totalOrdersCount);
//       // $totalRefundCount = $orders->where('refund', 1)->where($orders->agent->email, $authUser->email)->get()->count();
//        return view('customer.analytics', compact('orders', 'authUser', 'totalOrdersCount'));
//    }


    public function index()
    {
        if (isset(Auth::user()->customer_id)) {
            $agent = Customer::find(Auth::user()->customer_id);
            $agent_coupen = $agent->coupon_code;
            $agent_orders = Order::where('coupon_code', $agent_coupen)->get();
            $total_sales = $agent_orders->sum('total_price');
            $total_commission = (($agent->commission / 100) * $total_sales );
            return view('analytics')->with([
                'agent_orders' => $agent_orders,
                'agent' => $agent,
                'total_sales' => $total_sales,
                'total_commission' => $total_commission,
            ]);
        }else{
            return view('home');
        }

    }

    public function agent_dashboard(Request $request){

        $user = User::where('email',$request->email)->first();

        if ($user != null){
            Auth::login($user);
            return redirect('agenthome');
        }else{
            return back();
        }
    }
}
