@extends('layouts.auth')

@php
$footer_text = isset(\App\Models\Utility::settings()['footer_text']) ? \App\Models\Utility::settings()['footer_text'] : '';
@endphp

@section('content')
    <div class="card">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="row align-items-center text-start">
            <div class="col-xl-6">
                <div class="card-body">
                    <div class="">
                        <h2 class="mb-3 f-w-600">{{__('Password reset')}}</h2>
                    </div>
                    {{Form::open(array('route'=>'password.email','method'=>'post','id'=>'loginForm'))}}
                    <div class="">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Email') }}</label>
                            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
                            @error('email')
                            <span class="error invalid-email text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                        <div class="d-grid">
                            {{Form::submit(__('Reset Password'),array('class'=>'btn btn-primary btn-block mt-2','id'=>'saveBtn'))}}
                        </div>
                        {{ Form::close() }}
                        @if(Utility::getValByName('SIGNUP') == 'on')
                            <p class="my-4 text-center">{{ __('Not registered?') }}
                                    <a href="{{ route('register',$lang) }}" class="my-4 text-primary">{{ __('Create account') }}</a>
                            </p>
                        @endif
                        <p class="my-4 text-center">{{__('Back to ')}}
                            <a href="{{route('login',$lang)}}" class="my-4 text-primary">{{ __('Login') }}</a>
                        </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-xl-6 img-card-side">
                <div class="auth-img-content">
                    <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt="" class="img-fluid">
                    <h3 class="text-white mb-4 mt-5"> {{ __('“Attention is the new currency”') }}</h3>
                    <p class="text-white"> {{__('The more effortless the writing looks, the more effort the writer
                        actually put into the process.')}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="auth-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <p class="text-dark">{{ $footer_text }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
