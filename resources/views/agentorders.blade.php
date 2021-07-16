@extends('layouts.agent_main')
@section('content')

    <div class="col-lg-10 col-md-9 p-4">

        <div class="d-flex justify-content-between mb-3">
            <h5>Orders</h5>

        </div>
        <div class="row">
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
                        <th scope="col">Action</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($agent_orders as $order)
                        <tr>

                            <td><a href="{{route('agent-order-view',($order->id))}}">{{$order->order_name}}</a></td>
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
                                    style="height: 30px;background-color: {{$agent->seller_color}};width: 30px;"></div>{{$agent->seller_code}}
                                &nbsp;
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

                            <td>
                                <div class="text-end">
                                    <a href="{{route('agent-order-view',($order->id))}}" class="btn btn-sm btn-primary" type="button"> view</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div style="float: right">

                </div>
            </div>

        </div>

    </div>
@endsection
