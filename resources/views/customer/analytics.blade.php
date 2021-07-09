@extends('layouts.agent_main')
@section('content')
    <div class="row ">
        <div class="col-md-12 pl-3 pt-2">
            <div class="row pl-3">
                <div class="col-md-6 col-lg-4 col-12 mb-2 col-sm-6">
                    <div class="media shadow-sm p-0 bg-white rounded text-primary ">
                        <span class="oi top-0 rounded-left bg-primary text-light h-100 p-4 oi-badge fs-5"></span>
                        <div class="media-body p-2">
                            <h6 class="media-title m-0">Total Orders</h6>
                            <div class="media-text">
                                <h3>{{$totalOrdersCount}}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 col-12 mb-2 col-sm-6">
                    <div class="media shadow-sm p-0 bg-info-lighter text-light rounded ">
                        <span class="oi top-0 rounded-left bg-white text-info h-100 p-4 oi-tag fs-5"></span>
                        <div class="media-body p-2">
                            <h6 class="media-title m-0">Total Products</h6>
                            <div class="media-text">
                                <h3>
                                    {{--@php
                                        $shop = \App\User::getRole('admin');
                                            $totalProductCount = 0;
                                            foreach($authUser->has_order as $order)
                                                {
                                                    $shopifyOrder = $shop->api()->rest('GET', '/admin/orders/'.$order->order_id.'.json');
                                                    $shopifyOrder = json_decode(json_encode($shopifyOrder));
                                                    $totalProductCount += count($shopifyOrder->body->order->line_items);
                                                }
                                    @endphp
                                    {{$totalProductCount}}--}}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-12 mb-2 col-sm-6">
                    <div class="media shadow-sm p-0 bg-warning-lighter text-primary-darker rounded ">
                        <span class="oi top-0 rounded-left bg-white text-warning h-100 p-4 oi-cart fs-5"></span>
                        <div class="media-body p-2">
                            <h6 class="media-title m-0">Total Refunds</h6>
                            <div class="media-text">
                                <h3>
{{--                                    {{$totalRefundCount}}--}}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                    <div class="row" style="color: black;">
                        <div class="col-lg-12 pl-3">
                            <table class="table table-striped table-hover">
                                <thead class="border-0">
                                <tr>
                                    <th scope="col">Order Id</th>
                                    <th scope="col">Date</th>
                                    {{--<th scope="col">Total Order</th>--}}
                                    <th scope="col">Total Price</th>
                                    <th scope="col">Agent Name</th>

                                    <th scope="col">Seller Area</th>
                                    <th scope="col">Agent Color & Zip Code</th>
                                    <th scope="col">Commission Rate %</th>
                                    <th scope="col">Total Commission</th>
                                    <th scope="col">Discount %</th>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Shipping Address</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
        <tr>
            @if($order->agent != null && $order->agent->email ==  $authUser->email)
                <td>{{$order->order_name}}</td>
                <td>{{$order->created_at->toDateString()}}</td>
                {{--<td>{{$order->total_order}}</td>--}}
                <td>{{$order->total_price}}</td>

                <td>
                    {{$order->agent->where('email', $authUser->email)->first()->first_name}}&nbsp;{{$order->agent->where('email', $authUser->email)->first()->last_name}}
                </td>
                <td>
                    {{$order->agent->where('email', $authUser->email)->first()->seller_area}}
                </td>
                <td>
                        <div style="height: 30px;background-color: {{$order->agent->seller_color}};width: 30px;"></div>
                        {{$order->agent->where('email', $authUser->email)->first()->seller_code}}
                </td>
                <td>
                    {{$order->agent->where('email', $authUser->email)->first()->commission}}
                </td>
                <td>
                    {{ ($order->agent->where('email', $authUser->email)->first()->commission / 100) * $order->total_price }}
                </td>
                <td>
                    {{$order->agent->where('email', $authUser->email)->first()->discount}}
                </td>

                <td>{{$order->customer_name}}</td>
                <td>
                    @php
                        $shiping = json_decode($order->shiping_address)
                    @endphp
                    <div class="card-body">
                        @if($shiping != null)
                            <p style="font-size: 14px">Full Name: {{$shiping->first_name}} {{$shiping->last_name}}
                                @if(isset($shiping->company))
                                    <br>Company: {{$shiping->company}}
                                @endif
                                <br>Address1: {{$shiping->address1}}
                                <br>Address2: {{$shiping->address2}}
                                <br>City: {{$shiping->city}}
                                <br>Province: {{$shiping->province}}
                                <br>Zip Code: {{$shiping->zip}}
                                <br>Country: {{$shiping->country}}
                                @if(isset($shiping->phone))
                                    <br>Phone: {{$shiping->phone}}
                                @endif
                            </p>
                        @endif
                    </div>
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
<div>
{{--                                {{ $orders->links() }}--}}
</div>

</div>
</div>
</div>
@endsection
