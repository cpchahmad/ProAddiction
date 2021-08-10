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
                                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#{{$order->id}}">View</button>
                                    <div class="modal fade" id="{{$order->id}}" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Shipping Address</h4>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($shiping != null)
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div><b>Full Name : </b> </div>
                                                                <div><b>Company : </b></div>
                                                                <div><b>Address1 :</b> </div>
                                                                <div><b>Address2 :</b></div>
                                                                <div><b>City :</b></div>
                                                                <div><b>Province :</b> </div>
                                                                <div><b>Zip Code : </b></div>
                                                                <div><b>Country : </b></div>
                                                                @if(isset($shiping->phone))
                                                                    <div><b>Phone : </b></div>
                                                                @endif

                                                            </div>
                                                            <div class="col-md-6">
                                                                <div>@if($shiping->first_name){{$shiping->first_name}} {{$shiping->last_name}}@else None @endif</div>
                                                                <div>@if($shiping->company){{$shiping->company}} @else None @endif</div>
                                                                <div>@if($shiping->address1){{$shiping->address1}} @else None @endif</div>
                                                                <div>@if($shiping->address2){{$shiping->address2}} @else None @endif</div>
                                                                <div>@if($shiping->city){{$shiping->city}} @else None @endif</div>
                                                                <div>@if($shiping->province){{$shiping->province}} @else None @endif </div>
                                                                <div>@if($shiping->zip){{$shiping->zip}} @else None @endif </div>
                                                                <div>@if($shiping->country){{$shiping->country}} @else None @endif</div>
                                                                <div>@if($shiping->phone){{$shiping->phone}}@else None @endif</div>
                                                            </div>

                                                        </div>
                                                    @endif
                                                    <div class="mt-2"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <td>
                                <div class="card-body">
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
