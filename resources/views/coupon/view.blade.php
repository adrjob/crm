@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
    {{__('Coupon Detail')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Coupon')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('coupon.index')}}">{{__('Coupon')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$coupon->name}}</li>
@endsection
@section('action-btn')

@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0">{{$coupon->name}}</h6>
                </div>

            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead>
                <tr>
                    <th scope="col" class="sort" data-sort="name"> {{__('User')}}</th>
                    <th scope="col" class="sort" data-sort="name"> {{__('Date')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($userCoupons as $userCoupon)
                    <tr class="font-style">
                        <td>{{ !empty($userCoupon->userDetail)?$userCoupon->userDetail->name:'' }}</td>
                        <td>{{ $userCoupon->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

