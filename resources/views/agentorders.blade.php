@extends('layouts.agent_main')
@section('content')

    <style>
        .table_wrapper{
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    </style>

    <div class="col-lg-10 col-md-9 p-4">

        <div class="d-flex justify-content-between mb-3">
            <h5>Orders</h5>

        </div>
        <div class="row">
            <div class="col-lg-12 pl-3">

                <table class="table table_wrapper table-striped table-hover">
                    <thead class="border-0">
                    <tr>
                        <th scope="col"><h6>Order Id</h6></th>
                        <th scope="col"><h6>Date</h6></th>
                        {{--<th scope="col">Total Order</th>--}}
                        <th scope="col"><h6>Total Price</h6> </th>
                        <th scope="col"><h6>Name</h6></th>
                        <th scope="col"><h6>Seller Area</h6> </th>
                        <th scope="col"><h6> Color & Zip Code</h6>  </th>
                        <th scope="col"><h6>Commission Rate %</h6>  </th>
                        <th scope="col"><h6>Total Commission</h6> </th>
                        <th scope="col"><h6>Discount %</h6> </th>
                        <th scope="col"><h6>Customer Name</h6> </th>
                        <th scope="col"><h6>Shipping Address</h6> </th>
                        <th scope="col"><h6>Action</h6></th>

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
