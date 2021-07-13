@extends('layouts.agent_main')
@section('content')
    <div class="col-lg-10 col-md-9 p-4">
        <div class="row ">
            <div class="col-md-12 pl-3 pt-2">
                <div class="row pl-3">
                    <div class="col-md-6 col-lg-4 col-12 mb-2 col-sm-6">
                        <div class="media shadow-sm p-0 bg-white rounded text-primary ">
                            <span class="oi top-0 rounded-left bg-primary text-light h-100 p-4 oi-badge fs-5"></span>
                            <div class="media-body p-2">
                                <h6 class="media-title m-0">Total Orders</h6>
                                <div class="media-text">
                                    <h3>{{count($agent_orders)}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-12 mb-2 col-sm-6">
                        <div class="media shadow-sm p-0 bg-info-lighter text-light rounded ">
                            <span class="oi top-0 rounded-left bg-white text-info h-100 p-4 oi-tag fs-5"></span>
                            <div class="media-body p-2">
                                <h6 class="media-title m-0">Total Sales</h6>
                                <div class="media-text">
                                    <h3>{{$total_sales}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-12 mb-2 col-sm-6">
                        <div class="media shadow-sm p-0 bg-warning-lighter text-primary-darker rounded ">
                            <span class="oi top-0 rounded-left bg-white text-warning h-100 p-4 oi-cart fs-5"></span>
                            <div class="media-body p-2">
                                <h6 class="media-title m-0">Total Commission</h6>
                                <div class="media-text">
                                    <h3>{{$total_commission}}</h3>
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
                                <th scope="col">Name</th>
                                <th scope="col">Seller Area</th>
                                <th scope="col"> Color & Zip Code</th>
                                <th scope="col">Commission Rate %</th>
                                <th scope="col">Total Commission</th>
                                <th scope="col">Discount %</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Shipping Address</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($agent_orders as $order)
                                <tr>

                                    <td>{{$order->order_name}}</td>
                                    <td>{{$order->created_at->toDateString()}}</td>
                                    <td>{{$order->total_price}}</td>

                                    <td>
                                        {{$agent->first_name}}&nbsp;{{$agent->last_name}}
                                    </td>
                                    <td>
                                        {{$agent->seller_area}}&nbsp;
                                    </td>
                                    <td>
                                        <div
                                            style="height: 30px;background-color: {{$agent->seller_color}};width: 30px;"></div>{{$agent->seller_code}}&nbsp;
                                    </td>
                                    <td>
                                        {{$agent->commission}}&nbsp;
                                    </td>
                                    <td>
                                        {{ ($agent->commission / 100) * $order->total_price }}
                                    </td>
                                    <td>
                                        {{$agent->discount}}&nbsp;
                                    </td>

                                    <td>{{$order->customer_name}}</td>
                                    <td>
                                        @php
                                            $shiping = json_decode($order->shiping_address)
                                        @endphp
                                        <div class="card-body">
                                            @if($shiping != null)
                                                <p style="font-size: 14px">Full
                                                    Name: {{$shiping->first_name}} {{$shiping->last_name}}
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
        </div>
@endsection
