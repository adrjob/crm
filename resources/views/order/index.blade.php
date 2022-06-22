@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
    {{__('Order')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Order')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Order')}}</li>
@endsection
@section('action-btn')
@endsection
@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th scope="col" class="sort" data-sort="name"> {{__('Order Id')}}</th>
                                <th scope="col" class="sort" data-sort="budget">{{__('Date')}}</th>
                                <th scope="col" class="sort" data-sort="status">{{__('Name')}}</th>
                                <th scope="col">{{__('Plan Name')}}</th>
                                <th scope="col" class="sort" data-sort="completion"> {{__('Price')}}</th>
                                <th scope="col" class="sort" data-sort="completion"> {{__('Payment Type')}}</th>
                                <th scope="col" class="sort" data-sort="completion"> {{__('Status')}}</th>
                                <th scope="col" class="sort" data-sort="completion"> {{__('Coupon')}}</th>
                                <th scope="col" class="sort" data-sort="completion"> {{__('Invoice')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{$order->order_id}}</td>
                                <td>{{$order->created_at->format('d M Y')}}</td>
                                <td>{{$order->user_name}}</td>
                                <td>{{$order->plan_name}}</td>
                                <td>{{env('CURRENCY_SYMBOL').$order->price}}</td>
                                <td>{{$order->payment_type}}</td>
                                <td>
                                    @if($order->payment_status == 'succeeded')
                                        <i class="mdi mdi-circle text-success"></i> {{ucfirst($order->payment_status)}}
                                    @else
                                        <i class="mdi mdi-circle text-danger"></i> {{ucfirst($order->payment_status)}}
                                    @endif
                                </td>
        
                                <td>{{!empty($order->total_coupon_used)? !empty($order->total_coupon_used->coupon_detail)?$order->total_coupon_used->coupon_detail->code:'-':'-'}}</td>
        
                                <td class="text-center">
                                   
                                        @if($order->receipt != 'free coupon' && $order->payment_type == 'STRIPE')
                                            <a href="{{$order->receipt}}" title="Invoice" target="_blank" class=""><i class="ti ti-file"></i> </a>
                                        @elseif($order->receipt =='free coupon')
                                            <p>{{__('Used 100 % discount coupon code.')}}</p>
                                        @elseif($order->payment_type == 'Manually')
                                            <p>{{__('Manually plan upgraded by super admin')}}</p>
                                        @else
                                            -
                                        @endif
                                    
                                </td>
                            </tr>
                        @endforeach

                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

