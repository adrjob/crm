@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar/'));
@endphp
@push('css-page')
@endpush
@push('script-page')
<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })
</script>
@endpush
@section('page-title')
    {{__('Client Edit')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0"> {{\Auth::user()->clientIdFormat($client->client_id)}} {{__('Edit')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('client.index')}}">{{__('Client')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$user->name}}</li>
@endsection
@section('action-btn')
@endsection
@section('content')
{{-- 
    <div class="row">
        <div class="col-lg-4 order-lg-2">
            <div class="card">
                <div class="list-group list-group-flush" id="tabs">
                    <div data-href="#personal-info" class="list-group-item custom-list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-cog pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Personal Info')}}</a>
                                <p class="mb-0 text-sm">{{__('Edit details about your personal information')}}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#company-info" class="list-group-item custom-list-group-item">
                        <div class="media">
                            <i class="fas fa-home pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Company Info')}}</a>
                                <p class="mb-0 text-sm">{{__('Edit details about your company information')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 order-lg-1">
            <div id="personal-info" class="tabs-card">
                {{ Form::model($client, array('route' => array('client.personal.update', $client->user_id), 'method' => 'post', 'enctype' => "multipart/form-data")) }}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('name',__('Name'))}}
                                    {{Form::text('name',$user->name,array('class'=>'form-control font-style'))}}
                                    @error('name')
                                    <span class="invalid-name" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('mobile',__('Mobile'))}}
                                    {{Form::text('mobile',$client->mobile,array('class'=>'form-control'))}}
                                    @error('mobile')
                                    <span class="invalid-mobile" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('address_1',__('Address 1'))}}
                                    {{Form::textarea('address_1', $client->address_1, ['class'=>'form-control','rows'=>'4'])}}
                                    @error('address_1')
                                    <span class="invalid-address_1" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('address_2',__('Address 2'))}}
                                    {{Form::textarea('address_2', $client->address_2, ['class'=>'form-control','rows'=>'4'])}}
                                    @error('address_2')
                                    <span class="invalid-address_2" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('city',__('City'))}}
                                    {{Form::text('city',$client->city,array('class'=>'form-control'))}}
                                    @error('city')
                                    <span class="invalid-city" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('state',__('State'))}}
                                    {{Form::text('state',$client->state,array('class'=>'form-control'))}}
                                    @error('state')
                                    <span class="invalid-state" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('country',__('Country'))}}
                                    {{Form::text('country',$client->country,array('class'=>'form-control'))}}
                                    @error('country')
                                    <span class="invalid-country" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('zip_code',__('Zip Code'))}}
                                    {{Form::text('zip_code',$client->zip_code,array('class'=>'form-control'))}}
                                    @error('zip_code')
                                    <span class="invalid-zip_code" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-gradient-primary hover-shadow-lg border-0">
                                    <div class="card-body py-3">
                                        <div class="row row-grid align-items-center">
                                            <div class="col-lg-8">
                                                <div class="media align-items-center">
                                                    <a href="#" class="avatar avatar-lg rounded-circle mr-3">
                                                        <img @if(!empty($user->avatar)) src="{{$profile.'/'.$user->avatar}}" @else avatar="{{$user->name}}" @endif class="avatar  rounded-circle avatar-lg">
                                                    </a>
                                                    <div class="media-body">
                                                        <h5 class="text-white mb-0">{{$user->name}}</h5>
                                                        <div>
                                                            <input type="file" name="profile" id="file-1" class="custom-input-file custom-input-file-link" data-multiple-caption="{count} files selected" multiple/>
                                                            <label for="file-1">
                                                                <span class="text-white">{{__('Change avatar')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                    </div>
                </div>
                {{Form::close()}}
            </div>
            <div id="company-info" class="tabs-card d-none">
                {{ Form::model($client, array('route' => array('client.update.company', $client->user_id), 'method' => 'post')) }}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{Form::label('company_name',__('Company Name'))}}
                                    {{Form::text('company_name',$client->company_name,array('class'=>'form-control'))}}
                                    @error('company_name')
                                    <span class="invalid-company_name" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{Form::label('website',__('Website'))}}
                                    {{Form::text('website',$client->website,array('class'=>'form-control'))}}
                                    @error('website')
                                    <span class="invalid-website" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{Form::label('tax_number',__('Tax Number'))}}
                                    {{Form::text('tax_number',$client->tax_number,array('class'=>'form-control'))}}
                                    @error('tax_number')
                                    <span class="invalid-tax_number" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    {{Form::label('notes',__('Notes'))}}
                                    {{Form::textarea('notes', $client->notes, ['class'=>'form-control','rows'=>'3'])}}
                                    @error('notes')
                                    <span class="invalid-notes" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                    </div>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div> --}}

    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#useradd-1" class="list-group-item list-group-item-action border-0">{{ __('Personal Info') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-2" class="list-group-item list-group-item-action border-0">{{__('Company Info')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="useradd-1" class="card">
                        {{ Form::model($client, array('route' => array('client.personal.update', $client->user_id), 'method' => 'post', 'enctype' => "multipart/form-data")) }}
                        <div class="card-header">
                            <h5>{{ __('Personal Info') }}</h5>
                            <small class="text-muted">{{__('Edit details about your personal information')}}</small>
                        </div>
            
                        <div class="card-body">
                            <form>
                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{ Form::label('name', __('Name'),['class' => "form-label"]) }}
                                            {{Form::text('name',$user->name,array('class'=>'form-control font-style'))}}
                                            @error('name')
                                            <span class="invalid-name" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror     
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('mobile',__('Mobile'),['class' => "form-label"])}}
                                            {{Form::text('mobile',$client->mobile,array('class'=>'form-control'))}}
                                            @error('mobile')
                                            <span class="invalid-mobile" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('address_1',__('Address 1'),['class' => "form-label"])}}
                                            {{Form::textarea('address_1', $client->address_1, ['class'=>'form-control','rows'=>'4'])}}
                                            @error('address_1')
                                            <span class="invalid-address_1" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('address_2',__('Address 2'),['class' => "form-label"])}}
                                            {{Form::textarea('address_2', $client->address_2, ['class'=>'form-control','rows'=>'4'])}}
                                            @error('address_2')
                                            <span class="invalid-address_2" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            {{Form::label('city',__('City'),['class' => "form-label"])}}
                                            {{Form::text('city',$client->city,array('class'=>'form-control'))}}
                                            @error('city')
                                            <span class="invalid-city" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {{Form::label('state',__('State'),['class' => "form-label"])}}
                                        {{Form::text('state',$client->state,array('class'=>'form-control'))}}
                                        @error('state')
                                        <span class="invalid-state" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        {{Form::label('country',__('Country'),['class' => "form-label"])}}
                                        {{Form::text('country',$client->country,array('class'=>'form-control'))}}
                                        @error('country')
                                        <span class="invalid-country" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        {{Form::label('zip_code',__('Zip Code'),['class' => "form-label"])}}
                                        {{Form::text('zip_code',$client->zip_code,array('class'=>'form-control'))}}
                                        @error('zip_code')
                                        <span class="invalid-zip_code" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        {{Form::label('zip_code',__('Avatar'),['class' => "form-label"])}}
                                        <div class="card bg-gradient-primary hover-shadow-lg border-0">
                                            <div class="card-body py-3">
                                                <div class="row row-grid align-items-center">
                                                    <div class="col-lg-8">
                                                        <div class="media align-items-center">
                                                            <a href="#" class="avatar avatar-lg rounded-circle mr-3">
                                                                <img @if(!empty($user->avatar)) src="{{$profile.'/'.$user->avatar}}" @else avatar="{{$user->name}}" @endif class="avatar  rounded-circle avatar-lg">
                                                            </a>
                                                            <div class="media-body ms-3">
                                                                <h5 class="text-dark mb-2">{{$user->name}}</h5>
                                                                <div>
                                                                    <div class="input-group">
                                                                        <input type="file" class="form-control" id="file-1" name="profile"
                                                                            aria-describedby="inputGroupFileAddon04" aria-label="Upload" data-multiple-caption="{count} files selected" multiple/>
                                                                    </div>

                                                                    {{-- <input type="file" name="profile" id="file-1" class="custom-input-file custom-input-file-link" data-multiple-caption="{count} files selected" multiple/>
                                                                    <label for="file-1">
                                                                        <span class="text-white">{{__('Change avatar')}}</span>
                                                                    </label> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        {{Form::submit(__('Save Changes'),array('class'=>'btn btn-primary d-flex align-items-center'))}}
                                    </div>

                                    
                                </div>
                            </form>
                        </div>
                        {{Form::close()}}
                    </div>
                    <div id="useradd-2" class="card">
                        {{ Form::model($client, array('route' => array('client.update.company', $client->user_id), 'method' => 'post')) }}
                        <div class="card-header">
                            <h5>{{__('Company Info')}}</h5>
                            <small class="text-muted">{{__('Edit details about your company information')}}</small>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            {!! Form::label('clt_id', __('Client ID'),['class' => "form-label"]) !!}
                                            {!! Form::text('clt_id', \Auth::user()->clientIdFormat($client->client_id), ['class' => 'form-control','readonly']) !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('company_name',__('Company Name'),['class' => "form-label"])}}
                                            {{Form::text('company_name',$client->company_name,array('class'=>'form-control'))}}
                                            @error('company_name')
                                            <span class="invalid-company_name" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('website',__('Website'),['class' => "form-label"])}}
                                            {{Form::text('website',$client->website,array('class'=>'form-control'))}}
                                            @error('website')
                                            <span class="invalid-website" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('tax_number',__('Tax Number'),['class' => "form-label"])}}
                                            {{Form::text('tax_number',$client->tax_number,array('class'=>'form-control'))}}
                                            @error('tax_number')
                                            <span class="invalid-tax_number" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('notes',__('Notes'),['class' => "form-label"])}}
                                            {{Form::textarea('notes', $client->notes, ['class'=>'form-control','rows'=>'3'])}}
                                            @error('notes')
                                            <span class="invalid-notes" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        {{Form::submit(__('Save Changes'),array('class'=>'btn btn-primary d-flex align-items-center'))}}
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{ Form::close() }}
                    </div>
                  
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>

@endsection

