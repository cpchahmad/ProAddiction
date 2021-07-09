@extends('layouts.main')
@section('content')
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
                                            ($order->agent->commission / 100) * $order->total_price
}}
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
                                <td>
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
