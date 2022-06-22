@extends('layouts.admin')
@push('script-page')
    <script>

(function () {
        var options = {
            chart: {
                height: 150,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [{
                name: "{{__('Order')}}",
                data: {!! json_encode($chartData['data']) !!}
            },],
            xaxis: {
                categories: {!! json_encode($chartData['label']) !!},
            },
            colors: ['#ffa21d', '#FF3A6E'],

            grid: {
                strokeDashArray: 4,
            },
            legend: {
                show: false,
            },
            markers: {
                size: 4,
                colors: ['#ffa21d', '#FF3A6E'],
                opacity: 0.9,
                strokeWidth: 2,
                hover: {
                    size: 7,
                }
            },
            yaxis: {
                tickAmount: 3,
                min: 10,
                max: 70,
            }
        };
        var chart = new ApexCharts(document.querySelector("#traffic-chart"), options);
        chart.render();
    })();


       
       
    </script>
@endpush
@section('page-title')
    {{__('Dashboard')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Dashboard')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Dashboard')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-shopping-cart"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted">{{__('Total Users')}}</small>
                                    <h6 class="m-0">{{$user->total_user}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0">{{$user['total_paid_user']}}</h4>
                            <small class="text-muted"> {{__('Paid Users')}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-shopping-cart"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted">{{__('Total Orders')}}</small>
                                    <h6 class="m-0">{{$user->total_orders}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0">{{env('CURRENCY_SYMBOL').$user['total_orders_price']}}</h4>
                            <small class="text-muted"> {{__('Total Order Amount')}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-shopping-cart"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted">{{__('Total Plans')}}</small>
                                    <h6 class="m-0">{{env('CURRENCY_SYMBOL').$user['total_orders_price']}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0">{{$user['most_purchese_plan']}}</h4>
                            <small class="text-muted"> {{__('Most Purchase Plan')}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-xl-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Recent Order')}}</h5>
            </div>
            <div class="card-body">
                <div id="traffic-chart"></div>
            </div>
        </div>
    </div>
@endsection

