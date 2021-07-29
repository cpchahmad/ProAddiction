@extends('layouts.main')
@section('content')
    <style>
        .daterangepicker .right{
            color: inherit !important;
        }
        .daterangepicker {
            width: 341px !important;
        }
    </style>


        <div class="row ">
            <div class="col-md-4 pl-3 pt-2">
                <div class="pl-3">
                    <h3>Dashboard</h3>
                </div>
            </div>

        </div>

    <div class="row">
        <div class="col-md-3">
            <select name="products" id="products" style="background: #fff; margin-left: 25px; cursor: pointer; padding: 12px 10px; border: 1px solid #ccc; width: 100%">
                                     <option selected disabled>Select Product</option>
                                        @foreach($sold_products as $sold_product)
                                            <option value="{{$sold_product->shopify_product_id}}">{{$sold_product->title}}</option>
                                        @endforeach
            </select>

        </div>

        <div class="col-md-3">
            <select name="agent_names" id="agent_names" style="background: #fff; margin-left: 25px; cursor: pointer; padding: 12px 10px; border: 1px solid #ccc; width: 100%">
                <option selected disabled>Select Agent Name</option>
            @foreach($agent_names as $agent_name)
                    <option value="{{$agent_name->email}}">{{$agent_name->first_name}} {{$agent_name->last_name}}   {!! "&nbsp;" !!} {!! "&nbsp;" !!} {!! "&nbsp;" !!} {!! "&nbsp;" !!}  {!! $agent_name->seller_code !!} </option>
                @endforeach
            </select>

        </div>

        <div class="col-md-2">
            <select id="sell_area" name="sell_area" style="background: #fff; margin-left: 25px; cursor: pointer; padding: 12px 10px; border: 1px solid #ccc; width: 100%">
                <option selected disabled>Select Area</option>
            @foreach($sell_areas as $sell_area)
                    <option value="{{$sell_area->city}}">{{$sell_area->city}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-4 ms-auto d-inline-flex">

            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                <i class="fa fa-calendar"></i>&nbsp;
                <span>{{$date_range}}</span> <i class="fa fa-caret-down"></i>
            </div>
            <button class="btn btn-primary filter_by_date" data-url="{{route('home')}}" style="margin-left: 10px"> Filter </button>
            <button class="btn btn-secondary clear_filter_data"> Clear </button>

        </div>
    </div>
        <!-- start info box -->
        <div class="row ">
            <div class="col-md-12 pl-3 pt-2">
                <div class="row pl-3">

                    <div class="col-md-6 col-lg-3 col-12 mb-2 col-sm-6">
                        <div class="media shadow-sm p-0 bg-white rounded text-primary ">
                            <span class="oi top-0 rounded-left bg-primary text-light h-100 p-4 oi-badge fs-5"></span>
                            <div class="media-body p-2">
                                <h6 class="media-title m-0">Total Orders</h6>
                                <div class="media-text">
                                    <h3>{{$total_orders->count()}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 col-12 mb-2 col-sm-6">
                        <div class="media shadow-sm p-0 bg-success-lighter text-light rounded">
                            <span class="oi top-0 rounded-left bg-white text-success h-100 p-4 oi-people fs-5"></span>
                            <div class="media-body p-2">
                                <h6 class="media-title m-0">Total Sales</h6>
                                <div class="media-text">
                                    <h3>{{$total_sales}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 col-12 mb-2 col-sm-6">
                        <div class="media shadow-sm p-0 bg-info-lighter text-light rounded ">
                            <span class="oi top-0 rounded-left bg-white text-info h-100 p-4 oi-tag fs-5"></span>
                            <div class="media-body p-2">
                                <h6 class="media-title m-0">Total Products</h6>
                                <div class="media-text">
                                    <h3>{{$total_products}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 col-12 mb-2 col-sm-6">
                        <div class="media shadow-sm p-0 bg-info-lighter text-light rounded ">
                            <span class="oi top-0 rounded-left bg-white text-info h-100 p-4 oi-tag fs-5"></span>
                            <div class="media-body p-2">
                                <h6 class="media-title m-0">Total Refunds</h6>
                                <div class="media-text">
                                    <h3>{{$total_refund_orders->count()}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6 col-lg-3 col-12 mb-2 col-sm-6">
                        <div class="media shadow-sm p-0 bg-warning-lighter text-primary-darker rounded ">
                            <span class="oi top-0 rounded-left bg-white text-warning h-100 p-4 oi-cart fs-5"></span>
                            <div class="media-body p-2">
                                <h6 class="media-title m-0">Total Commission</h6>
                                <div class="media-text">
                                    <h3>{{number_format($total_commission,2)}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end info box -->

        <!-- start second boxes -->
        <div class="row pl-3 mt-4 mb-5">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header p-0 bg-white">
                        <h6 class="border-bottom p-2"> Monthly Recap</h6>
                        <div class="row pb-1">
                            <div class="col-sm-3 col-6 mb-2">
                                <div class="text-center">
                                    <div class="fs-smaller">
                                        <span class="oi oi-caret-top fs-smallest mr-1 text-primary"></span>@if($total_orders->count() != 0){{number_format((count($agent_orders) / $total_orders->count()) * 100,2)}}% @endif</div>
                                    <div class="fw-bold">{{number_format($total_orders->count(),2)}}</div>
                                    <div>Total Orders</div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6 mb-2">
                                <div class="text-center">
                                    <div class="fs-smaller">

                                        <span class="oi oi-caret-top fs-smallest mr-1 text-success"></span>@if($total_sales != 0){{number_format(($agentorders_sale / $total_sales) * 100 ,2)}}% @endif</div>
                                    <div class="fw-bold">{{number_format($total_sales,2)}}</div>
                                    <div>Total Sales</div>
                                </div>
                            </div>

                            <div class="col-sm-3 col-6 mb-2">
                                <div class="text-center">
                                    <div class="fs-smaller">
                                        <span class="oi oi-caret-top  fs-smallest mr-1 text-warning"></span>@if($total_commission != 0){{number_format($total_commission / $total_sales * 100 ,2)}}% @endif</div>
                                    <div class="fw-bold">{{number_format($total_commission,2)}}</div>
                                    <div>Total Commission</div>
                                </div>
                            </div>
                            {{--                            <div class="col-sm-3 col-6 mb-2">--}}
                            {{--                                <div class="text-center">--}}
                            {{--                                    <div class="fs-smaller">--}}
                            {{--                                        <span class="oi oi-caret-bottom fs-smallest mr-1 text-danger"></span>3%</div>--}}
                            {{--                                    <div class="fw-bold">$24,591.00</div>--}}
                            {{--                                    <div>Total Profits</div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <h6>Orders <small>@if($total_orders->count() != 0)){{number_format((count($agent_orders) / $total_orders->count()) * 100,2)}}% @endif</small></h6>
                                <div class="progress">
                                    <div style="width: @if($total_orders->count() != 0){{(count($agent_orders) / $total_orders->count()) * 100}}%" @endif class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <br>
                                <h6>Sales <small>@if($total_sales != 0){{number_format(($agentorders_sale / $total_sales) * 100 ,2)}}% @endif</small></h6>
                                <div class="progress">
                                    <div style="width: @if($total_sales != 0){{($agentorders_sale / $total_sales) * 100 }}%" @endif class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <br>
                                <h6>Commission <small>@if($total_commission != 0){{number_format(($total_commission / $total_sales) * 100 ,2)}}% @endif</small></h6>
                                <div class="progress">
                                    <div style="width: @if($total_commission != 0){{($total_commission / $total_sales) * 100 }}%" @endif class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                            </div>
                            <div class="col-lg-8">

                                <canvas id="orders"  data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_one_values)}}" data-order-values = "{{json_encode($graph_two_values)}}" style="display: block; width: 670px; height: 335px;" width="670" height="335" class="chartjs-render-monitor"></canvas>
                                <canvas id="sales"  data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_two_values)}}" data-order-values = "{{json_encode($graph_two_values)}}" style="display: block; width: 670px; height: 335px;" width="670" height="335" class="chartjs-render-monitor"></canvas>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end second boxes -->
@endsection

@section('scripts')

    <script>
        $(document).ready(function() {
            if($('body').find('#orders').length > 0){
                var config = {
                    type: 'bar',
                    data: {
                        labels: JSON.parse($('#orders').attr('data-labels')),
                        datasets: [{
                            label: 'Order Count',
                            backgroundColor: '#5C6AC4',
                            borderColor: '#00e2ff',
                            data: JSON.parse($('#orders').attr('data-values')),
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Summary Orders Count'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Date'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 1
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Value'
                                }
                            }]
                        }
                    }
                };

                var ctx = document.getElementById('orders').getContext('2d');
                window.myBar = new Chart(ctx, config);
            }
            if($('body').find('#sales').length > 0){
                console.log('ok');
                var config = {
                    type: 'line',
                    data: {
                        labels: JSON.parse($('#sales').attr('data-labels')),
                        datasets: [{
                            label: 'Orders Sales',
                            backgroundColor: '#5C6AC4',
                            borderColor: '#6ECA5D',
                            data: JSON.parse($('#sales').attr('data-values')),
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Summary  Sales'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Date'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                ticks: {
                                    beginAtZero: true
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Sales'
                                }
                            }]
                        }
                    }
                };

                var ctx_2 = document.getElementById('sales').getContext('2d');
                window.myLine = new Chart(ctx_2, config);
            }
            if($('body').find('#reportrange').length > 0){
                var start = moment().subtract(29, 'days');
                var end = moment();

                function cb(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
                if($('#reportrange span').text() === ''){
                    $('#reportrange span').html('Select Date Range');
                }


                $('#reportrange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, cb);

            }
            $('body').on('click','.filter_by_date', function() {
                let daterange_string = $('#reportrange').find('span').text();
                var selected_area_value = $("#sell_area option:selected").val();
                var selected_agent_name = $("#agent_names option:selected").val() ;
                var selected_product = $("#products option:selected").val();
                window.location.href = $(this).data('url')+'?date-range='+daterange_string+'&sell_area='+selected_area_value+'&agent_names='+selected_agent_name+'&products='+selected_product;

            });

            $('body').on('click','.clear_filter_data', function() {

                window.location.href = '/';

            });


        });
    </script>

@endsection



