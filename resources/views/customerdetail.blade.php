@extends('layouts.main')
@section('content')
    <div>


        <div class="d-flex justify-content-between mb-3">
            <h5>Customer Detail</h5>

        </div>

        <div class="row">

            <div class="col-sm-6">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header bg-white border-light">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="text-indigo m-0">{{ucfirst($customer->first_name)}} {{ucfirst($customer->last_name)}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6> Default Address:</h6>

                        <div>{{$customer->seller_area}}</div>
                        <div>{{$customer->seller_code}}</div>
                        <br><br>
                        <h6> Coupon Code:</h6>
                        <div><a href="#">{{$customer->coupon_code}}</a></div>
                    </div>

                </div>

            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="card border-light border-0 text-indigo shadow-sm">
                    <div class="card-header bg-white">
                        <h6>Customer Overview</h6>
                    </div>

                    <div class="card-body bg-white">
                        <div class="mb-2"><span><a href="#">{{$customer->email}}</a></span></div>
                        <div><span>{{$customer->phone_no}}</span></div>
                    </div>

                </div>
                <div class="mt-2">
                    <div class="card border-light border-0 text-indigo shadow-sm">
                        <div class="card-header bg-white">
                            <h6>Money Detail</h6>
                        </div>

                        <div class="card-body bg-white">
                            <h6> Commission:</h6>

                            <div>{{number_format($customer->commission,2)}}</div>
                            <br>
                            <h6>Discount:</h6>
                            <div>{{number_format($customer->discount,2)}}</div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
