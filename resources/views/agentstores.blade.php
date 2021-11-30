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
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Store</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('agent-add-store')}}" method="POST">
                        @csrf

                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <label for="material-error">Email </label>
                                    <input  class="form-control" type="text"  name="email"
                                            value=""   placeholder="Enter Your Email Address">
                                </div>
                            </div>
                        </div>


                </div>
                        <div class="modal-footer">
                            <div class="block-content block-content-full text-right border-top">
                                <button type="submit" class="btn btn-sm btn-primary" >Add</button>
                            </div>
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
        <div class="d-flex justify-content-between mb-3">
            <h5>My Stores</h5>
            <div>
{{--                <a type="button" class="btn btn-info btn-lg" href="{{route('sync-customer')}}">Sync Customers</a>--}}
                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Add New Store</button>
            </div>
        </div>
    @if(count($stores)> 0)
        <div class="row">
            <div class="col-lg-12 pl-3">


                <table class="table  table-striped table-hover">
                    <thead class="border-0">
                    <tr>
                        <th scope="col"><h6>Store</h6></th>

                        <th scope="col"><h6>Action</h6></th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stores as $store)
                    <tr>
                        <td>{{$store->email}}</td>
                        <td>
                            <div class="text-end">
                                <a href="{{route('agent-delete-store',$store->id)}}" class="btn btn-sm btn-danger" type="button"> Delete</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                <div style="float: right">
                    {{ $stores->links() }}
                </div>
            </div>

            </div>
        </div>
        @else
        <p class="text-center">No Stores Available.</p>
    @endif
    </div>
@endsection


