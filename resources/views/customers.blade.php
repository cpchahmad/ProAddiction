@extends('layouts.main')
@section('content')
    <style>
        .table_wrapper{
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    </style>

    {{--@if( Session::has( 'success' ))
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
    @endif--}}
    <div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Agent</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('add_agent')}}" method="POST">
                        @csrf
                        <div class="block-content font-size-sm">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">First Name</label>
                                        <input  class="form-control" type="text"  name="first_name"
                                                value=""   placeholder="Enter Your First Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <label for="material-error">Last Name </label>
                                    <input  class="form-control" type="text"  name="last_name"
                                            value=""   placeholder="Enter Your Last Name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <label for="material-error">Email </label>
                                    <input  class="form-control" type="text"  name="email"
                                            value=""   placeholder="Enter Your Email Address">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <label for="material-error">Password </label>
                                    <input  class="form-control" type="password"  name="password"
                                            value=""   placeholder="Enter Your Password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <label for="material-error">Phone Number </label>
                                    <input  class="form-control" type="text"  name="phone_no"
                                            value=""   placeholder="Enter Your Phone Number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <label for="material-error">Country </label>
                                    <select id="country" name="country" class="form-control" onchange="showStates(this.value);">
                                        @foreach($countries as $country)
                                            <option name="country" value="{{$country->name}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="show_state"></div>
                        <div id="show_cities"></div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <label for="material-error">Agent Code </label>
                                    <input  class="form-control" type="text"  name="seller_code"
                                            value=""   placeholder="Enter Your Seller Code">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <label for="ColorInput" class="form-label">Seller Color</label>
                                    <input type="color" id="" name="seller_color" value="#563d7c" title="Choose your color">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <label for="material-error">Discount in percentage</label>
                                    <input  class="form-control" type="text"  name="discount"
                                            value="50"   placeholder="Enter discount">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <label for="material-error">Commission Rate in percentage</label>
                                    <input  class="form-control" type="text"  name="commission_rate"
                                            value=""   placeholder="Enter Commission Rate On Each Sell">
                                </div>
                            </div>
                        </div>

                </div>
                        <div class="modal-footer">
                            <div class="block-content block-content-full text-right border-top">
                                <button type="submit" class="btn btn-sm btn-primary" >Approve</button>
                            </div>
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
        <div class="d-flex justify-content-between mb-3">
            <h5>Agents</h5>
            <div>
{{--                <a type="button" class="btn btn-info btn-lg" href="{{route('sync-customer')}}">Sync Customers</a>--}}
                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Add New Agent</button>
            </div>
        </div>
    @if(count($customers)> 0)
        <div class="row">
            <div class="col-lg-12 pl-3">


                <table class="table table_wrapper table-striped table-hover">
                    <thead class="border-0">
                    <tr>
                        <th scope="col"><h6>Name</h6></th>
                        <th scope="col"><h6>Created At</h6> </th>
                        <th scope="col"><h6>Phone Number</h6> </th>
                        <th scope="col"><h6>Email</h6></th>
                        <th scope="col"><h6>Seller Area</h6> </th>
                        <th scope="col"><h6>Agent Code</h6> </th>
                        <th scope="col"><h6>Seller Color</h6> </th>
                        <th scope="col"><h6>Discount in percentage</h6>  </th>
                        <th scope="col"><h6>Commission</h6></th>
                        <th scope="col"><h6>Action</h6></th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $customer)
                    <tr>
    {{--                    <th scope="row">1</th>--}}
                        <td><a href="{{route('customer-view',($customer->id))}}">{{$customer->first_name}}&nbsp;{{$customer->last_name}}</a></td>

                        <td>{{\Carbon\Carbon::parse($customer->created_at)->format('d/m/Y')}}</td>

                        <td>@if($customer->phone_no){{$customer->phone_no}} @else None @endif</td>
                        <td>{{$customer->email}}</td>
                        <td>
                            @if($customer->seller_area){{$customer->seller_area}} @else None @endif
                        </td>
                        <td>@if($customer->seller_code){{$customer->seller_code}} @else None @endif </td>
                        <td>
                            @if($customer->seller_color)
                            <div style="height: 30px;background-color: {{$customer->seller_color}};width: 30px;"></div>
                            @else
                            None
                            @endif
                        </td>
                        <td>
                            @if($customer->discount)
                            {{$customer->discount}}
                            @else
                                None
                            @endif
                        </td>
                        <td>
                            @if($customer->commission)
                            {{$customer->commission}}
                            @else
                            None
                            @endif
                        </td>
                        <td>
                            <div class="text-end">
                                <a href="{{route('customer-view',($customer->id))}}" class="btn btn-sm btn-primary" type="button"> view</a>
                                <a href="{{route('customer-delete',($customer->id))}}" class="btn btn-sm btn-danger" type="button"> Delete</a>

                            </div>
                        </td>
                    </tr>
                    @endforeach

                    </tbody>
                </table>

                <div style="float: right">
                    {{ $customers->links() }}
                </div>
            </div>

            </div>
        </div>
        @else
        <p class="text-center">No Agents Available.</p>
    @endif
    </div>
@endsection


