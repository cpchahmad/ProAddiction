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
        {{ Session::get( 'warning' ) }}
        <br>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <br>
    @endif
    <div>

        <div class="d-flex justify-content-between mb-3">
            <h5>Professionals</h5>
            <div>
{{--                <a type="button" class="btn btn-info btn-lg" href="{{route('sync-customer')}}">Sync Customers</a>--}}
            </div>
        </div>
    @if(count($professionals)> 0)
        <div class="row">
            <div class="col-md-12 ">
                <table class="table {{--table_wrapper--}} table-striped table-hover">
                    <thead class="border-0">
                    <tr>
                        <th scope="col"><h6>Name</h6></th>
                        <th scope="col"><h6>Email</h6></th>
                        <th scope="col"><h6>Phone Number</h6> </th>
                        <th scope="col"><h6>Address</h6> </th>
                        <th scope="col"><h6>Tag</h6> </th>
{{--                        <th scope="col"><h6>Action</h6></th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($professionals as $professional)
                    <tr>
    {{--                    <th scope="row">1</th>--}}
                        <td>{{$professional->first_name}}&nbsp;{{$professional->last_name}}</td>

                        <td>{{$professional->email}}</td>
                        <td>@if($professional->phone_no){{$professional->phone_no}} @else None @endif</td>
                        <td>{{$professional->seller_area}}</td>
                        <td>{{$professional->tag}}</td>


                        {{--<td>
                            <div class="text-end">
                                <a href="{{route('professional-view',($professional->id))}}" class="btn btn-sm btn-primary" type="button"> view</a>
                            </div>
                        </td>--}}
                    </tr>
                    @endforeach

                    </tbody>
                </table>

                <div style="float: right">
                    {{ $professionals->links() }}
                </div>
            </div>

            </div>
        </div>
        @else
        <p class="text-center">No Data Available.</p>
    @endif
    </div>
@endsection


