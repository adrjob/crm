@extends('layouts.admin')
@php
$dir = asset(Storage::url('uploads/plan'));
@endphp
@push('script-page')
@endpush
@section('page-title')
    {{ __('Plan') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{ __('Plan') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Plan') }}</li>
@endsection
@section('action-btn')
    @if (\Auth::user()->type == 'super admin' && !empty($admin_payment_setting) && ($admin_payment_setting['is_stripe_enabled'] == 'on' || $admin_payment_setting['is_paypal_enabled'] == 'on' || $admin_payment_setting['is_paystack_enabled'] == 'on' || $admin_payment_setting['is_flutterwave_enabled'] == 'on' || $admin_payment_setting['is_razorpay_enabled'] == 'on' || $admin_payment_setting['is_mercado_enabled'] == 'on' || $admin_payment_setting['is_paytm_enabled'] == 'on' || $admin_payment_setting['is_mollie_enabled'] == 'on' || $admin_payment_setting['is_paypal_enabled'] == 'on' || $admin_payment_setting['is_skrill_enabled'] == 'on' || $admin_payment_setting['is_coingate_enabled'] == 'on' || $admin_payment_setting['is_paymentwall_enabled'] == 'on'))
        <a href="#" data-url="{{ route('plan.create') }}" data-bs-toggle="modal" data-bs-target="#exampleModal"
            data-bs-whatever="{{ __('Create New Plan') }}" data-size="lg" class="btn btn-sm btn-primary btn-icon m-1"
            data-bs-toggle="tooltip" title="{{ __('Create New Plan') }}">
            <span class="btn-inner--icon"><i class="ti ti-plus"></i></span>
        </a>
    @endif
@endsection
@section('content')

    @foreach ($plans as $plan)
        <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6">
            <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s" style="
                    visibility: visible;
                    animation-delay: 0.2s;
                    animation-name: fadeInUp;
                ">
                <div class="card-body">
                    <span class="price-badge bg-primary">{{ $plan->name }}</span>
                    @if (\Auth::user()->type == 'super admin')
                        <div class="d-flex flex-row-reverse m-0 p-0">
                            <div class="action-btn bg-primary ms-2">
                            <a title="Edit Plan" data-size="lg" href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                data-url="{{ route('plan.edit', $plan->id) }}" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                data-bs-whatever="{{ __('Edit Plan') }}" data-size="lg"
                                
                                data-original-title="{{ __('Edit') }}"><i class="ti ti-edit text-white" data-bs-title="{{ __('Edit Plan') }}" data-bs-toggle="tooltip"></i></a>
                            </div>
                            </div>
                    @endif
                    @if (\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id)
                        <div class="d-flex flex-row-reverse m-0 p-0 ">
                            <span class="d-flex align-items-center ">
                                <i class="f-10 lh-1 fas fa-circle text-success"></i>
                                <span class="ms-2">{{ __('Active') }}</span>
                            </span>
                        </div>
                    @endif

                    <h3 class="mb-4 f-w-600 ">{{ env('CURRENCY_SYMBOL') . $plan->price . ' / ' . $plan->duration }}</h3>
                        
                    @if ($plan->description)
                        <p class="mb-0">
                            {{ $plan->description }}<br />
                        </p>
                    @endif

                    <ul class="list-unstyled my-3">
                        <li>
                            <span class="theme-avtar">
                                <i class="text-primary ti ti-circle-plus"></i></span>
                            {{ $plan->max_employee == '-1' ? __('Unlimited') : $plan->max_employee }} {{ __('Employee') }}
                        </li>
                        <li>
                            <span class="theme-avtar">
                                <i class="text-primary ti ti-circle-plus"></i></span>
                            {{ $plan->max_client == '-1' ? __('Unlimited') : $plan->max_client }} {{ __('Clients') }}
                        </li>
                    </ul>
                    <div class="row">
                        @if (\Auth::user()->plan == $plan->id && date('Y-m-d') < \Auth::user()->plan_expire_date && \Auth::user()->is_trial_done != 1)
                            <div class="col-12">
                                <p class="server-plan font-weight-bold text-center mx-sm-5">
                                    {{ __('Expire on ') }}
                                    {{ date('d M Y', strtotime(\Auth::user()->plan_expire_date)) }}
                                </p>
                            </div>
                        @elseif(\Auth::user()->plan == $plan->id && !empty(\Auth::user()->plan_expire_date) && \Auth::user()->plan_expire_date < date('Y-m-d'))
                            <div class="col-12">
                                <p class="server-plan font-weight-bold text-center mx-sm-5">
                                    {{ __('Expired') }}
                                </p>
                            </div>
                        @elseif(\Auth::user()->plan == $plan->id && !empty(\Auth::user()->plan_expire_date) && \Auth::user()->is_trial_done == 1)
                            <div class="col-12">
                                <p class="server-plan font-weight-bold text-center mx-sm-5">
                                    {{ __('Current Trial Expire on ') . date('d M Y', strtotime(\Auth::user()->plan_expire_date)) }}
                                </p>
                            </div>
                        @else
                            @if ($plan->id != \Auth::user()->plan && \Auth::user()->type != 'super admin')
                                @if ($plan->price > 0)
                                    <div class="{{ $plan->id == 1 ? 'col-12' : 'col-8' }}">
                                        <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                            class="btn  btn-primary d-flex justify-content-center align-items-center ">{{ __('Subscribe') }}
                                            <i class="ti ti-shopping-cart m-1"></i></a>
                                        <p></p>
                                    </div>
                                @endif
                            @endif
                        @endif

                        @if($plan->id != 1 && \Auth::user()->type == 'company')
                        <div class="col-auto mb-2">
                            @if(\Auth::user()->requested_plan != $plan->id)
                            <div class="col-8">
                                <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                    class="btn btn-primary btn-icon m-1" data-title="{{ __('Send Request') }}"
                                    data-toggle="tooltip">
                                    <span class="btn-inner--icon"><i class="ti ti-arrow-forward-up"></i></span>
                                </a>
                            </div>
                            @else
                            <div class="col-4">
                                <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                    class="btn btn-icon m-1 btn-danger" data-title="{{ __('Cancle Request') }}"
                                    data-toggle="tooltip">
                                    <span class="btn-inner--icon"><i class="ti ti-trash"></i></span>
                                </a>
                            </div>
                            @endif
                        </div>
                        @endif
                    
                        
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
