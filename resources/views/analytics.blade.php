@extends('layouts.main')
@section('content')
    <div class="row ">
        <div class="col-md-12 pl-3 pt-2">
            <div class="row pl-3">
                <div class="col-md-6 col-lg-4 col-12 mb-2 col-sm-6">
                    <div class="media shadow-sm p-0 bg-white rounded text-primary ">
                        <span class="oi top-0 rounded-left bg-primary text-light h-100 p-4 oi-badge fs-5"></span>
                        <div class="media-body p-2">
                            <h6 class="media-title m-0">Total Orders</h6>
                            <div class="media-text">
                                <h3>{{$totalOrdersCount}}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 col-12 mb-2 col-sm-6">
                    <div class="media shadow-sm p-0 bg-info-lighter text-light rounded ">
                        <span class="oi top-0 rounded-left bg-white text-info h-100 p-4 oi-tag fs-5"></span>
                        <div class="media-body p-2">
                            <h6 class="media-title m-0">Total Products</h6>
                            <div class="media-text">
                                <h3>
                                    @php
                                        $shop = \Illuminate\Support\Facades\Auth::user();
                                            $totalProductCount = 0;
                                            foreach ($agents as $agent){
                                                if ($agent->has_order != null){
                                                    foreach($agent->has_order as $order)
                                                        {
                                                            $shopifyOrder = $shop->api()->rest('GET', '/admin/orders/'.$order->order_id.'.json');
                                                            $shopifyOrder = json_decode(json_encode($shopifyOrder));
                                                            $totalProductCount += count($shopifyOrder->body->order->line_items);
                                                        }
                                                }
                                            }
                                    @endphp
                                    {{$totalProductCount}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-12 mb-2 col-sm-6">
                    <div class="media shadow-sm p-0 bg-warning-lighter text-primary-darker rounded ">
                        <span class="oi top-0 rounded-left bg-white text-warning h-100 p-4 oi-cart fs-5"></span>
                        <div class="media-body p-2">
                            <h6 class="media-title m-0">Total Refunds</h6>
                            <div class="media-text">
                                <h3>
                                    {{$totalRefundCount}}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row" style="color: black;">
        <div class="col-lg-12 pl-3"><br>
            <table class="table table-striped table-hover">
                <thead class="border-0">
                <tr>
                    <th scope="col">Agent Name</th>
                    <th scope="col">Total Orders</th>
                    <th scope="col">Total Products</th>
                    <th scope="col">Seller Area</th>
                    <th scope="col">Agent Color & Zip Code</th>
                    <th scope="col">Total Commission</th>
                    {{--<th scope="col">Average order value</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($agents as $agent)
                    <tr>
                        <td>{{$agent->first_name}} {{$agent->last_name}}</td>
                        <td>
                            @if($agent->has_order != null)
                                {{$agent->has_order->count()}}
                            @else
                                0
                            @endif

                        </td>
                        <td>
                            @php
                            $shop = \Illuminate\Support\Facades\Auth::user();
                            if ($agent->has_order != null){
                                $productCount = 0;
                                foreach($agent->has_order as $order)
                                    {
                                        $shopifyOrder = $shop->api()->rest('GET', '/admin/orders/'.$order->order_id.'.json');
                                        $shopifyOrder = json_decode(json_encode($shopifyOrder));
                                        $productCount += count($shopifyOrder->body->order->line_items);
                                    }
}
                            @endphp
                            {{$productCount}}
                        </td>
                        <td>
                            {{$agent->seller_area}}
                        </td>
                        <td>
                            <div style="height: 30px;background-color: {{$agent->seller_color}};width: 30px;"></div>
                            {{$agent->seller_code}}
                        </td>
                        <td>
                            {{($agent->commission / 100) * $agent->has_order->sum('total_price')}}
                        </td>
                    </tr>


                @endforeach

                </tbody>
            </table>
            <div>
                {{--{{ $orders->links() }}--}}
            </div>

        </div>
@endsection
