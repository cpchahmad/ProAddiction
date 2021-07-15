<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    public function showAnalytics()
    {
        $authUser = Auth::user();
        $orders = Order::latest()->get();
        $totalOrdersCount = 0;
        foreach ($orders as $order)
        {
            if ($order->agent !=  null && $order->agent->email == $authUser->email)
            {
                //$totalOrdersCount += $order->agent->where('email', $authUser->email)->count();
                $totalOrdersCount += 1;
            }
        }
       // dd($totalOrdersCount);
       // $totalRefundCount = $orders->where('refund', 1)->where($orders->agent->email, $authUser->email)->get()->count();
        return view('customer.analytics', compact('orders', 'authUser', 'totalOrdersCount', 'totalRefundCount'));
    }
    public function agent_dashboard(Request $request){

        $user = User::where('email',$request->email)->first();

        if ($user != null){
            Auth::login($user);

        }else{
            return back();
        }
    }
}
