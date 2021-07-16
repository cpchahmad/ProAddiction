@extends('layouts.agent_main')
@section('content')
    <div class="col-lg-10 col-md-9 p-4">

        <div class="d-flex justify-content-between mb-3">
            <h5>Agent Order</h5>

        </div>
        <div class="row">

            <div class="col-sm-6">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header bg-white border-light">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="text-indigo m-0"><a href="#">{{$order->order_name}}</a></h6>
                            </div>
                        </div>
                    </div>
                    @php $shipping_address = json_decode($order->shiping_address) @endphp

                    <div class="card-body">
                        <h6> Shipping Address:</h6>

                        <div class="row">
                            <div class="col-6">
                             <div><strong>Name:</strong></div>
                                <div><strong>Phone:</strong></div>
                                <div><strong>Address:</strong></div>
                                <div><strong>City:</strong></div>
                                <div><strong>Zip Code:</strong></div>
                                <div><strong>Province:</strong></div>
                                <div><strong>Country:</strong></div>

                            </div>
                            <div class="col-6">
                                <div>@if($shipping_address->name){{$shipping_address->name}}@else No Name @endif</div>
                                <div>@if($shipping_address->phone){{$shipping_address->phone}}@else No Phone @endif</div>
                                <div>@if($shipping_address->address1){{$shipping_address->address1}} @else No Address @endif </div>
                                <div>@if($shipping_address->city){{$shipping_address->city}}@else No City @endif</div>
                                <div>@if($shipping_address->zip){{$shipping_address->zip}}@else No zip code @endif</div>
                                <div>@if($shipping_address->province){{$shipping_address->province}}@else No Province @endif</div>
                                <div>@if($shipping_address->country){{$shipping_address->country}}@else No country @endif</div>
                            </div>

                        </div>


                        <br><br>
                        <h6> Coupon Code:</h6>
                        <div><a href="#">{{$order->coupon_code}}</a></div>
                    </div>

                </div>

            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="card border-light border-0 text-indigo shadow-sm">
                    <div class="card-header bg-white">
                        <h6> Overview</h6>
                    </div>

                    <div class="card-body bg-white">
                        <h6>Customer Name:</h6>
                        <div class="mb-2"><span><a href="#">{{$order->customer_name}}</a></span></div>
                    </div>

                </div>
                <div class="mt-2">
                    <div class="card border-light border-0 text-indigo shadow-sm">
                        <div class="card-header bg-white">
                            <h6>Money Detail</h6>
                        </div>

                        <div class="card-body bg-white">
                            <h6> Total Price:</h6>

                            <div>{{number_format($order->total_price,2)}}</div>
                            <br>
                            <h6>Refund Status:</h6>
                            <div>@if($order->refund == 0) False @else True @endif</div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
