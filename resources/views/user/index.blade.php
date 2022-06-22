@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar'));
@endphp
@push('script-page')
@endpush
@section('page-title')
    {{__('User')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('User')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('User')}}</li>
@endsection
@section('action-btn')
    <a href="#" data-url="{{ route('user.create') }}" data-size="md" data-bs-whatever="{{__('Create New User')}}"
    class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal" data-bs-target="#exampleModal"  data-bs-whatever="{{__('Create New User')}}" >
        <i class="ti ti-plus" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}"></i>
    </a>
@endsection
@section('content')
    <div class="row">
        @foreach($users as $user)
            <div class="col-lg-3 col-sm-6">
                <div class="card hover-shadow-lg">
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="text-end">
                                <div class="actions">   
                                        <div class="dropdown action-item">
                                            <a href="#" class="action-item " data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">

                                                <a href="#" data-url="{{ route('user.edit',$user->id) }}"  class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal" data-bs-whatever="{{__('Edit User')}}">
                                                <i class="ti ti-edit">  </i> {{ __('Edit') }}</a>


                                                <a href="#" class="dropdown-item" data-url="{{ route('plan.upgrade',$user->id) }}" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal" data-bs-whatever="{{__('View Plan')}}">
                                                    <i class="ti ti-eye"></i> {{ __('View') }}</a>


                                                <a href="#" data-url="{{route('user.reset',\Crypt::encrypt($user->id))}}" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal"
                                                    class="dropdown-item"  data-bs-whatever="{{__('Reset Password')}}">
                                                <i class="ti ti-lock"> </i> {{__('Reset Password')}}
                                                </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id]]) !!}
                                                    <a href="#!" class=" show_confirm dropdown-item">
                                                        <i class="ti ti-trash"></i>{{ __('Delete') }}
                                                    </a>
                                                    {!! Form::close() !!}
                                                    
                                            </div>
                                        </div>
                                    
                                </div>
                            </div>

                            <div class="avatar-parent-child">
                                <img alt="" @if(!empty($user->avatar)) src="{{$profile.'/'.$user->avatar}}" @else  avatar="{{$user->name}}"   @endif class="avatar  rounded-circle avatar-lg">
                            </div>
                        </div>
 

                        <h5 class="h6 mt-4 mb-2"> {{$user->name}}</h5>
                        <a href="#" class="d-block text-sm text-muted "> {{$user->email}}</a>
                       
                    </div>
                    <div class="card-body border-top">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-6 text-center">
                                <span class="d-block h4 mb-0">{{$user->countEmployees($user->id)}}</span>
                                <span class="d-block text-sm text-muted">{{__('Employees')}}</span>
                            </div>
                            <div class="col-6 text-center">
                                <span class="d-block h4 mb-0">{{$user->countClients($user->id)}}</span>
                                <span class="d-block text-sm text-muted">{{__('Clients')}}</span>
                            </div>
                            <div class="col-6 text-center pt-3">
                                <span class="d-block h5 mb-0">{{!empty($user->currentPlan)?$user->currentPlan->name:__('Free')}}</span>
                                <span class="d-block text-sm text-muted">{{__('Plan')}}</span>
                            </div>
                            <div class="col-6 text-center pt-3">
                                <span class="d-block h5 mb-0">{{!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date):'Unlimited'}}</span>
                                <span class="d-block text-sm text-muted">{{__('Plan Expired')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

