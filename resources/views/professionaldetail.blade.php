@extends('layouts.main')
@section('content')
    <div>


        <div class="d-flex justify-content-between mb-3">
            <h5>Professional Detail</h5>
            @if($professional->status==1)
                <div>
                    <a type="button" class="btn btn-info btn-lg" href="{{route('professionals')}}">Back</a>
                </div>
            @else
            <div>
               <a type="button" class="btn btn-info btn-lg" href="{{route('professional.approve',($professional->id))}}">Approve</a>
               <a type="button" class="btn btn-danger btn-lg" href="{{route('professional.disapprove',($professional->id))}}">Disapprove</a>
            </div>
            @endif

        </div>

        <div class="row">

            <div class="col-sm-6">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header bg-white border-light">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="text-indigo m-0">{{ucfirst($professional->name)}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6> Salon Address:</h6>

                        <div>{{$professional->address}}</div>

                    </div>

                </div>

            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="card border-light border-0 text-indigo shadow-sm">
                    <div class="card-header bg-white">
                        <h6>Customer Overview</h6>
                    </div>

                    <div class="card-body bg-white">
                        <div class="mb-2"><span><a href="#">{{$professional->email}}</a></span></div>
                        <div><span>{{$professional->phone_no}}</span></div>
                    </div>

                </div>
                <div class="mt-2">
                    <div class="card border-light border-0 text-indigo shadow-sm">
                        <div class="card-header bg-white">
                            <h6>Attach Documents</h6>
                        </div>

                        <div class="card-body bg-white">
                            <div>
                                @if($professional->file_name)
                                    <a href="{{$professional->file_name}} " target="_blank">
                                        <i class="fa fa-file"></i> Documents
                                    </a>

                                @else None @endif
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
