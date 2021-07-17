@extends('layouts.main')
@section('content')
    <style>
        .table_wrapper{
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    </style>

    @if( Session::has( 'success' ))
        {{ Session::get( 'success' ) }}
        <br>
    @elseif( Session::has( 'errors' ))
        {{ Session::get( 'errors' ) }}
        <br>
    @endif
    <div class="d-flex justify-content-between mb-3">
        <h5>Orders</h5>
        <div>
    <a href="{{route('sync_orders')}}"><button type="button" class="btn btn-info btn-lg">Sync orders</button></a>
    </div>
    </div>
        @if(count($orders)> 0)
            <div class="row" style="color: black;">
                <div class="col-lg-12 pl-3">
                    <table class="table table_wrapper table-striped table-hover">
                        <thead class="border-0">
                        <tr>
                            <th scope="col"><h6>Order Id</h6></th>
                            <th scope="col"><h6>Date</h6></th>
                            {{--<th scope="col">Total Order</th>--}}
                            <th scope="col"><h6>Total Price</h6></th>
                            <th scope="col"><h6>Agent Name</h6></th>
                            <th scope="col"><h6>Seller Area</h6></th>
                            <th scope="col"><h6>Agent Color & Code</h6></th>
                            <th scope="col"><h6>Commission Rate %</h6></th>
                            <th scope="col"><h6>Total Commission</h6></th>
                            <th scope="col"><h6>Discount %</h6></th>
                            <th scope="col"><h6>Customer Name</h6></th>
                            <th scope="col"><h6>Shipping Address</h6></th>
                            <th></th>

                        </tr>

                        </thead>
                        <tbody>

                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->order_name}}</td>
                                <td>{{$order->created_at->toDateString()}}</td>
                                {{--<td>{{$order->total_order}}</td>--}}
                                <td>{{$order->total_price}}</td>

                                <td>
                                   {{-- @if($order->customer && $order->customer->where('is_agent', 1)->first() != null)
                                        {{$order->customer->where('is_agent', 1)->first()->first_name}}
                                        &nbsp;
                                        {{$order->customer->where('is_agent', 1)->first()->last_name}}
                                    @endif--}}
                                    @if($order->agent != null)
                                        {{$order->agent->first_name}}&nbsp;{{$order->agent->last_name}}
                                    @else
                                        none
                                    @endif
                                </td>
                                <td>
                                    @if($order->agent != null)
                                        {{$order->agent->seller_area}}
                                    @else
                                        none
                                    @endif
                                </td>
                                <td>
                                    @if($order->agent != null)

                                        <div style="height: 30px;background-color: {{$order->agent->seller_color}};width: 30px;"></div>
                                        {{$order->agent->seller_code}}
                                    @else
                                        none

                                    @endif
                                </td>
                                <td>
                                    @if($order->agent != null)
                                        {{$order->agent->commission}}
                                    @else
                                        none
                                    @endif
                                </td>
                                <td>
                                    @if($order->status == 1 && $order->refund == 0 && $order->agent != null)
                                        {{
                                            ($order->agent->commission / 100) * $order->total_price}}
                                    @else
                                        0
                                    @endif
                                </td>
                                <td>
                                    @if($order->agent != null)
                                        {{$order->agent->discount}}
                                    @else
                                        none
                                    @endif
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
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirm_refund{{$order->id}}">
                                            Refund
                                        </button>
                                        <div class="modal fade" id="confirm_refund{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Are You Sure!</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" style="display: flex; justify-content: center;">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 100px;">No</button>
                                                        <form method="post" action="{{route('make_refund')}}">
                                                            @csrf
                                                            <input type="hidden" name="order_id" value="{{$order->order_id}}">
                                                            <button type="submit" class="btn btn-primary" style="width: 100px;">Yes</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $orders->links() }}
                    </div>

            </div>
    @else
            <p class="text-center">No Order Available.</p>
        @endif

@endsection
