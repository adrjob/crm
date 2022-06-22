@extends('layouts.admin')
@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo=Utility::getValByName('company_logo');
    $company_small_logo=Utility::getValByName('company_small_logo');
    $company_favicon=Utility::getValByName('company_favicon');
    $lang=\App\Models\Utility::getValByName('default_language');
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

    <script>
        $(document).ready(function () {
            $('.list-group-item').on('click', function () {
                var href = $(this).attr('data-href');
                $('.tabs-card').addClass('d-none');
                $(href).removeClass('d-none');
                $('#tabs .list-group-item').removeClass('text');
                $(this).addClass('text');
            });
        });

    </script>
    <script>
        $(document).on("change", "select[name='estimate_template'], input[name='estimate_color']", function () {
            var template = $("select[name='estimate_template']").val();
            var color = $("input[name='estimate_color']:checked").val();
            $('#estimate_frame').attr('src', '{{url('/estimate/preview')}}/' + template + '/' + color);
        });
        $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function () {
            var template = $("select[name='invoice_template']").val();
            var color = $("input[name='invoice_color']:checked").val();
            $('#invoice_frame').attr('src', '{{url('/invoice/preview')}}/' + template + '/' + color);
        });

        function check_theme(color_val) {
            $('#theme_color').prop('checked', false);
            $('input[value="'+color_val+'"]').prop('checked', true);
        }

    </script>

      <script>
    $(document).ready(function () {
            if ($('.gdpr_fulltime').is(':checked') ) {

                $('.fulltime').show();
            } else {

                $('.fulltime').hide();
            }

        $('#gdpr_cookie').on('change', function() {
            if ($('.gdpr_fulltime').is(':checked') ) {

                $('.fulltime').show();
            } else {

                $('.fulltime').hide();
            }
        });
    });

</script>
@endpush
@section('page-title')
    {{__('Settings')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 ">   {{__('Settings')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Settings')}}</li>
@endsection
@section('action-btn')
@endsection
@section('content')


    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        @if(\Auth::user()->type == "company")
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                <a href="#useradd-1" class="list-group-item list-group-item-action border-0">{{ __('Site Setting') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-2" class="list-group-item list-group-item-action border-0">{{ __('Company Setting') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-3" class="list-group-item list-group-item-action border-0">{{ __('System Setting') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-4" class="list-group-item list-group-item-action border-0">{{ __('Estimate Setting') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-5" class="list-group-item list-group-item-action border-0">{{ __('Invoice Setting') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-6" class="list-group-item list-group-item-action border-0">{{ __('Payment Setting') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-7" class="list-group-item list-group-item-action border-0">{{ __('Zoom Setting') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-8" class="list-group-item list-group-item-action border-0">{{ __('Slack Setting') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-9" class="list-group-item list-group-item-action border-0">{{ __('Telegram Setting') }}
                                                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-10" class="list-group-item list-group-item-action border-0">{{ __('Twillio Setting') }}
                                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-12" class="list-group-item list-group-item-action border-0">{{ __('Mailer Setting') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-13" class="list-group-item list-group-item-action border-0">{{ __('Pusher Setting') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-15" class="list-group-item list-group-item-action border-0">{{ __('ReCaptcha Setting') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            </div>
                        @endif

                        <!--Super Admin-->
                        @if(\Auth::user()->type=='super admin')
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#useradd-11" class="list-group-item list-group-item-action border-0">{{ __('Site Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-12" class="list-group-item list-group-item-action border-0">{{ __('Mailer Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-13" class="list-group-item list-group-item-action border-0">{{ __('Pusher Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-14" class="list-group-item list-group-item-action border-0">{{ __('Payment Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-15" class="list-group-item list-group-item-action border-0">{{ __('ReCaptcha Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-xl-9">
                    @if(\Auth::user()->type == "company")
                        <div id="useradd-1" class="card">
                            {{Form::model($settings,array('route'=>'business.setting','method'=>'POST','enctype' => "multipart/form-data"))}}
                            <div class="card-header">
                                <h5>{{ __('Site Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company') }}</small>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="small-title">{{ __('Favicon') }}</h5>
                                        </div>
                                        <div class="card-body setting-card setting-logo-box p-3">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="logo-content text-center py-2">
                                                        <img src="{{ asset(Storage::url('uploads/logo/favicon.png')) }}"
                                                            class="small-logo" alt="" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="choose-file">
                                                        <label for="favicon" class="form-label text-dark">
                                                            <div>{{ __('Choose file here') }}</div>
                                                            <input type="file" class="form-control" name="favicon"
                                                                id="small-favicon" data-filename="edit-favicon">
                                                        </label>
                                                        <p class="edit-favicon"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="small-title">{{ __('Dark Logo') }}</h5>
                                        </div>
                                        <div class="card-body setting-card setting-logo-box p-3">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="logo-content text-center py-2">
                                                        <img src="{{ asset(Storage::url('uploads/logo/logo-dark.png')) }}"
                                                            class="big-logo" alt="" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="choose-file">
                                                        <label for="logo" class="form-label text-dark">
                                                            <div>{{ __('Choose file here') }}</div>
                                                            <input type="file" class="form-control" name="logo" id="logo"
                                                                data-filename="edit-logo">
                                                        </label>
                                                        <p class="edit-logo"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="small-title">{{ __('Light Logo') }}</h5>
                                        </div>
                                        <div class="card-body setting-card setting-logo-box p-3">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="logo-content text-center py-2">
                                                        <img src="{{ asset(Storage::url('uploads/logo/logo-light.png')) }}"
                                                            class="big-logo" alt="" style="filter: drop-shadow(2px 3px 7px #011c4b);" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="choose-file">
                                                        <label for="logo" class="form-label text-dark">
                                                            <div>{{ __('Choose file here') }}</div>
                                                            <input type="file" class="form-control" name="white_logo"
                                                                id="white_logo" data-filename="edit-white_logo">
                                                        </label>
                                                        <p class="edit-white_logo"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{Form::label('header_text',__('Title Text'),['class'=>'col-form-label text-dark text-dark']) }}
                                            {{Form::text('header_text',Utility::getValByName('header_text'),array('class'=>'form-control','placeholder'=>__('Enter Header Title Text')))}}
                                            @error('header_text')
                                            <span class="invalid-header_text" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{Form::label('footer_text',__('Footer Text'),['class'=>'col-form-label text-dark text-dark']) }}
                                            {{Form::text('footer_text',Utility::getValByName('footer_text'),array('class'=>'form-control','placeholder'=>__('Enter Footer Text')))}}
                                            @error('footer_text')
                                            <span class="invalid-footer_text" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            {{Form::label('default_language',__('Default Language'),['class'=>'col-form-label text-dark text-dark']) }}
                                            <select name="default_language" id="default_language" class="form-control select2">
                                                @foreach(Utility::languages() as $language)
                                                    <option @if(Utility::getValByName('default_language') == $language) selected @endif value="{{$language}}">{{Str::upper($language)}}</option>
                                                @endforeach
                                            </select>
                                            @error('default_language')
                                            <span class="invalid-default_language" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col switch-width col-lg-4">
                                                <div class="form-group ml-2 mr-3 ">
                                                    <label class="form-label text-dark">{{ __('RTL') }}</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" data-toggle="switchbutton"
                                                            data-onstyle="primary" class=""
                                                            name="SITE_RTL" id="SITE_RTL"
                                                            {{ env('SITE_RTL') == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="SITE_RTL"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col switch-width">
                                                <div class="form-group mr-3">
                                                    <label class="form-label text-dark text-dark " for="display_landing_page">{{ __('Enable Landing Page') }}</label>
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input type="checkbox" name="display_landing_page" class="form-check-input" id="display_landing_page" data-toggle="switchbutton" {{ $settings['display_landing_page'] == 'on' ? 'checked="checked"' : '' }} data-onstyle="primary">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col switch-width">
                                                <!-- <div class="form-group mr-3">
                                                    <label class="form-label text-dark text-dark" for="SIGNUP">{{ __('Sign Up') }}</label>
                                                <div class="">
                                                    <input type="checkbox" name="SIGNUP" id="SIGNUP" data-toggle="switchbutton" {{ $settings['SIGNUP'] == 'on' ? 'checked="checked"' : '' }}  data-onstyle="primary">
                                                    <label class="form-check-labe" for="SIGNUP"></label>
                                                </div>
                                                </div> -->
                                            </div>
                                            <div class="col switch-width">
                                                <div class="form-group mr-3">
                                                    {{ Form::label('gdpr_cookie', 'GDPR Cookie', ['class' => 'form-label text-dark']) }}
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" data-toggle="switchbutton"
                                                            data-onstyle="primary"
                                                            class="custom-control-input gdpr_fulltime gdpr_type"
                                                            name="gdpr_cookie" id="gdpr_cookie"
                                                            {{ isset($settings['gdpr_cookie']) && $settings['gdpr_cookie'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-label text-dark"
                                                            for="gdpr_cookie"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                </div>

                                <div class="row">
                                    <div class="form-group col-12">
                                        {{Form::label('cookie_text',__('GDPR Cookie Text'),array('class'=>'fulltime col-form-label text-dark text-dark') )}}
                                        {!! Form::textarea('cookie_text',$settings['cookie_text'], ['class'=>'form-control fulltime','rows'=>'4']) !!}    
                                    </div>
                                </div>

                                <div class="row">
                                    <h4 class="small-title">{{ __('Theme Customizer') }}</h4>
                                    <div class="setting-card setting-logo-box p-3">
                                        <div class="row">
                                            <div class="pct-body">
                                                <div class="row">
                                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                                        <h6 class="mt-2">
                                                            <i data-feather="credit-card"
                                                                class="me-2"></i>{{ __('Primary color settings') }}
                                                        </h6>
                                                        <hr class="my-2" />
                                                        <div class="theme-color themes-color">
                                                            <a href="#!" class=""
                                                                data-value="theme-1"
                                                                onclick="check_theme('theme-1')"></a>
                                                            <input type="radio" class="theme_color"
                                                                name="color" value="theme-1"
                                                                style="display: none;">
                                                            <a href="#!" class=""
                                                                data-value="theme-2"
                                                                onclick="check_theme('theme-2')"></a>
                                                            <input type="radio" class="theme_color"
                                                                name="color" value="theme-2"
                                                                style="display: none;">
                                                            <a href="#!" class=""
                                                                data-value="theme-3"
                                                                onclick="check_theme('theme-3')"></a>
                                                            <input type="radio" class="theme_color"
                                                                name="color" value="theme-3"
                                                                style="display: none;">
                                                            <a href="#!" class=""
                                                                data-value="theme-4"
                                                                onclick="check_theme('theme-4')"></a>
                                                            <input type="radio" class="theme_color"
                                                                name="color" value="theme-4"
                                                                style="display: none;">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                                        <h6 class="mt-2  rtl-hide">
                                                            <i data-feather="layout"
                                                                class="me-2"></i>{{ __('Sidebar settings') }}
                                                        </h6>
                                                        <hr class="my-2 rtl-hide" />
                                                        <div class="form-check form-switch rtl-hide">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="cust-theme-bg" name="cust_theme_bg"
                                                                {{ !empty($settings['cust_theme_bg']) && $settings['cust_theme_bg'] == 'on' ? 'checked' : '' }} />
                                                            <label class="form-check-label f-w-600 pl-1"
                                                                for="cust-theme-bg">{{ __('Transparent layout') }}</label>
                                                            
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                                        <h6 class="mt-2 rtl-hide">
                                                            <i data-feather="sun"
                                                                class="me-2"></i>{{ __('Layout settings') }}
                                                        </h6>
                                                        <hr class="my-2 rtl-hide" />
                                                        <div class="form-check form-switch mt-2 rtl-hide">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="cust-darklayout" name="cust_darklayout"
                                                                {{ !empty($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on' ? 'checked' : '' }} />
                                                            <label class="form-check-label f-w-600 pl-1"
                                                                for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                

                                


                            <div class="card-footer text-end">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                </div>
                            </div>
                            {{ Form::close() }} 
                        </div>

                        <!--Company Setting-->
                        <div id="useradd-2" class="card">
                            <div class="card-header">
                                <h5>{{ __('Company Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company') }}</small>
                            </div>


                            {{Form::model($settings,array('route'=>'company.setting','method'=>'post'))}}
                                <div class="card-body">

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_name *',__('Company Name *'),array('class' => 'col-form-label text-dark')) }}
                                            {{Form::text('company_name',null,array('class'=>'form-control font-style'))}}
                                            @error('company_name')
                                            <span class="invalid-company_name" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_address',__('Address'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('company_address',null,array('class'=>'form-control font-style'))}}
                                            @error('company_address')
                                            <span class="invalid-company_address" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_city',__('City'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('company_city',null,array('class'=>'form-control font-style'))}}
                                            @error('company_city')
                                            <span class="invalid-company_city" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_state',__('State'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('company_state',null,array('class'=>'form-control font-style'))}}
                                            @error('company_state')
                                            <span class="invalid-company_state" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_zipcode',__('Zip/Post Code'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('company_zipcode',null,array('class'=>'form-control'))}}
                                            @error('company_zipcode')
                                            <span class="invalid-company_zipcode" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group  col-md-6">
                                            {{Form::label('company_country',__('Country'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('company_country',null,array('class'=>'form-control font-style'))}}
                                            @error('company_country')
                                            <span class="invalid-company_country" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_telephone',__('Telephone'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('company_telephone',null,array('class'=>'form-control'))}}
                                            @error('company_telephone')
                                            <span class="invalid-company_telephone" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_email',__('System Email *'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('company_email',null,array('class'=>'form-control'))}}
                                            @error('company_email')
                                            <span class="invalid-company_email" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_email_from_name',__('Email (From Name) *'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('company_email_from_name',null,array('class'=>'form-control font-style'))}}
                                            @error('company_email_from_name')
                                            <span class="invalid-company_email_from_name" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('registration_number',__('Company Registration Number *'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('registration_number',null,array('class'=>'form-control'))}}
                                            @error('registration_number')
                                            <span class="invalid-registration_number" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('vat_number',__('VAT Number *'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('vat_number',null,array('class'=>'form-control'))}}
                                            @error('vat_number')
                                            <span class="invalid-vat_number" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            {{Form::label('timezone',__('Timezone'),array('class' => 'form-label text-dark'))}}
                                            <select type="text" name="timezone" class="form-control custom-select" id="timezone">
                                                <option value="">{{__('Select Timezone')}}</option>
                                                @foreach($timezones as $k=>$timezone)
                                                    <option value="{{$k}}" {{(env('TIMEZONE')==$k)?'selected':''}}>{{$timezone}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_start_time',__('Company Start Time *'),array('class' => 'form-label text-dark')) }}
                                            {{Form::time('company_start_time',null,array('class'=>'form-control'))}}
                                            @error('company_start_time')
                                            <span class="invalid-company_start_time" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_end_time',__('Company End Time *'),array('class' => 'form-label text-dark')) }}
                                            {{Form::time('company_end_time',null,array('class'=>'form-control'))}}
                                            @error('company_end_time')
                                            <span class="invalid-company_end_time" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer text-end">
                                    <div class="form-group">
                                        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                    </div>
                                </div>
                                {{Form::close()}}

                        </div>

                        <!--System Setting-->
                        <div id="useradd-3" class="card">
                            <div class="card-header">
                                <h5>{{ __('System Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company') }}</small>
                            </div>

                            {{Form::model($settings,array('route'=>'system.setting','method'=>'post'))}}
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {{Form::label('site_currency',__('Currency *'),array('class' => 'form-label text-dark')) }}
                                        {{Form::text('site_currency',null,array('class'=>'form-control font-style'))}}
                                        @error('site_currency')
                                        <span class="invalid-site_currency" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{Form::label('site_currency_symbol',__('Currency Symbol *'),array('class' => 'form-label text-dark')) }}
                                        {{Form::text('site_currency_symbol',null,array('class'=>'form-control'))}}
                                        @error('site_currency_symbol')
                                        <span class="invalid-site_currency_symbol" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-dark" for="example3cols3Input">{{__('Currency Symbol Position')}}</label>
                                            <div class="row">
                                                <div class="form-check col-md-6">
                                                    <input class="form-check-input" type="radio" name="site_currency_symbol_position" value="pre" @if(@$settings['site_currency_symbol_position'] == 'pre') checked @endif
                                                        id="flexCheckDefault">
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        {{__('Pre')}}
                                                    </label>
                                                </div>
                                                <div class="form-check col-md-6">
                                                    <input class="form-check-input" type="radio" name="site_currency_symbol_position" value="post" @if(@$settings['site_currency_symbol_position'] == 'post') checked @endif
                                                        id="flexCheckChecked" checked>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        {{__('Post')}}
                                                    </label>
                                                </div>

                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="site_date_format" class="form-label text-dark">{{__('Date Format')}}</label>
                                        <select type="text" name="site_date_format" class="form-control selectric" id="site_date_format">
                                            <option value="M j, Y" @if(@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>Jan 1,2015</option>
                                            <option value="d-m-Y" @if(@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>d-m-y</option>
                                            <option value="m-d-Y" @if(@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>m-d-y</option>
                                            <option value="Y-m-d" @if(@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>y-m-d</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="site_time_format" class="form-label text-dark">{{__('Time Format')}}</label>
                                        <select type="text" name="site_time_format" class="form-control selectric" id="site_time_format">
                                            <option value="g:i A" @if(@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>10:30 PM</option>
                                            <option value="g:i a" @if(@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>10:30 pm</option>
                                            <option value="H:i" @if(@$settings['site_time_format'] == 'H:i') selected="selected" @endif>22:30</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{Form::label('client_prefix',__('Client Prefix'),array('class' => 'form-label text-dark')) }}
                                        {{Form::text('client_prefix',null,array('class'=>'form-control'))}}
                                        @error('client_prefix')
                                        <span class="invalid-client_prefix" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{Form::label('employee_prefix',__('Employee Prefix'),array('class' => 'form-label text-dark')) }}
                                        {{Form::text('employee_prefix',null,array('class'=>'form-control'))}}
                                        @error('employee_prefix')
                                        <span class="invalid-employee_prefix" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{Form::label('estimate_prefix',__('Estimate Prefix'),array('class' => 'form-label text-dark')) }}
                                        {{Form::text('estimate_prefix',null,array('class'=>'form-control'))}}
                                        @error('estimate_prefix')
                                        <span class="invalid-estimate_prefix" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{Form::label('invoice_prefix',__('Invoice Prefix'),array('class' => 'form-label text-dark')) }}
                                        {{Form::text('invoice_prefix',null,array('class'=>'form-control'))}}
                                        @error('invoice_prefix')
                                        <span class="invalid-invoice_prefix" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{Form::label('footer_title',__('Estimate/Invoice Footer Title'),array('class' => 'form-label text-dark')) }}
                                        {{Form::text('footer_title',null,array('class'=>'form-control'))}}
                                        @error('footer_title')
                                        <span class="invalid-footer_title" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12">
                                        {{Form::label('footer_notes',__('Estimate/Invoice Footer Notes'),array('class' => 'form-label text-dark')) }}
                                        {{Form::textarea('footer_notes', null, ['class'=>'form-control','rows'=>'3'])}}
                                        @error('footer_notes')
                                        <span class="invalid-footer_notes" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark mb-0">{{__('App Site URL')}}</label> <br>
                                        <small>{{__("App Site URL to login app.")}}</small>
                                        {{ Form::text('currency',URL::to('/'), ['class' => 'form-control', 'placeholder' => __('Enter Currency'),'disabled'=>'true']) }}                                            
                                    </div>
                                
                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark mb-0">{{__('Tracking Interval')}}</label> <br>
                                        <small>{{__("Image Screenshort Take Interval time ( 1 = 1 min)")}}</small>
                                        {{ Form::number('interval_time',isset($settings['interval_time'])?$settings['interval_time']:'10', ['class' => 'form-control', 'placeholder' => __('Enter Tracking Interval'),'required'=>'required']) }}                                            
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                </div>
                            </div>
                            {{Form::close()}}

                        </div>

                        <!--Estimate Setting-->
                        <div id="useradd-4" class="card">
                            <div class="card-header">
                                <h5>{{ __('Estimate Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company estimate') }}</small>
                            </div>
                            <div class="bg-none">
                                <div class="row company-setting">
                                    <div class="col-md-3">
                                        <div class="card-header card-body">
                                            <h5></h5>
                                            <form id="setting-form" method="post" action="{{route('estimate.template.setting')}}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="address" class="col-form-label text-dark">{{__('Estimation Template')}}</label>
                                                    <select class="form-control" name="estimate_template" data-toggle="select">
                                                        @foreach(Utility::templateData()['templates'] as $key => $template)
                                                            <option value="{{$key}}" {{(isset($settings['estimate_template']) && $settings['estimate_template'] == $key) ? 'selected' : ''}}> {{$template}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label text-dark">{{__('Color Input')}}</label>
                                                    <div class="row gutters-xs">
                                                        @foreach(Utility::templateData()['colors'] as $key => $color)
                                                            <div class="col-auto">
                                                                <label class="colorinput">
                                                                    <input name="estimate_color" type="radio" value="{{$color}}" class="colorinput-input" {{(isset($settings['estimate_color']) && $settings['estimate_color'] == $color) ? 'checked' : ''}}>
                                                                    <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="form-group mt-2 text-end">
                                                    <input type="submit" value="{{__('Save')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        @if(isset($settings['estimate_template']) && isset($settings['estimate_color']))
                                            <iframe id="estimate_frame" class="w-100 h-1220" frameborder="0" src="{{route('estimate.preview',[$settings['estimate_template'],$settings['estimate_color']])}}"></iframe>
                                        @else
                                            <iframe id="estimate_frame" class="w-100 h-1220" frameborder="0" src="{{route('estimate.preview',['template1','fffff'])}}"></iframe>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!--Invoice Setting-->
                        <div id="useradd-5" class="card">
                            <div class="card-header">
                                <h5>{{ __('Invoice Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company invoice') }}</small>
                            </div>
                            <div class="bg-none">
                                <div class="row company-setting">
                                    <div class="col-md-3">
                                        <div class="card-header card-body">
                                            <h5></h5>
                                            <form id="setting-form" method="post" action="{{route('invoice.template.setting')}}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="address" class="col-form-label text-dark">{{__('Invoice Template')}}</label>
                                                    <select class="form-control select2" name="invoice_template">
                                                        @foreach(Utility::templateData()['templates'] as $key => $template)
                                                            <option value="{{$key}}" {{(isset($settings['invoice_template']) && $settings['invoice_template'] == $key) ? 'selected' : ''}}>{{$template}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label text-dark">{{__('Color Input')}}</label>
                                                    <div class="row gutters-xs">
                                                        @foreach(Utility::templateData()['colors'] as $key => $color)
                                                        <div class="col-auto">
                                                            <label class="colorinput">
                                                                <input name="invoice_color" type="radio" value="{{$color}}" class="colorinput-input" {{(isset($settings['invoice_color']) && $settings['invoice_color'] == $color) ? 'checked' : ''}}>
                                                                <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                    </div>
                                                </div>
                                                <div class="form-group mt-2 text-end">
                                                    <input type="submit" value="{{__('Save')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </form>
                                        </div> 
                                    </div>
                                    <div class="col-md-9">
                                    @if(isset($settings['invoice_template']) && isset($settings['invoice_color']))
                                        <iframe id="invoice_frame" class="w-100 h-1220" frameborder="0" src="{{route('invoice.preview',[$settings['invoice_template'],$settings['invoice_color']])}}"></iframe>
                                    @else
                                        <iframe id="invoice_frame" class="w-100 h-1220" frameborder="0" src="{{route('invoice.preview',['template1','fffff'])}}"></iframe>
                                    @endif
                                    </div>
                                </div>
                            </div>


                        </div>

                        <!--Payment Setting-->
                        <div id="useradd-6" class="card">
                            <div class="card-header">
                                <h5>{{ __('Payment Setting') }}</h5>
                                <small class="text-muted">{{ __('This detail will use for collect payment on invoice from clients. On invoice client will find out pay now button based on your below configuration.') }}</small>
                            </div>
                            <div class="card-body">
                                <form id="setting-form" method="post" action="{{route('company.payment.setting')}}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <!-- <div class="card"> -->
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                            <label class="col-form-label text-dark">{{__('Currency')}} *</label>
                                                            <input type="text" name="currency" class="form-control" id="currency" value="{{(!isset($admin_payment_setting['currency']) || is_null($admin_payment_setting['currency'])) ? '' : $admin_payment_setting['currency']}}" >
                                                            <small class="text-xs">
                                                                {{ __('Note: Add currency code as per three-letter ISO code') }}.
                                                                <a href="https://stripe.com/docs/currencies" target="_blank">{{ __('you can find out here..') }}</a>
                                                            </small>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                            <label for="currency_symbol" class="col-form-label text-dark">{{__('Currency Symbol')}}</label>
                                                            <input type="text" name="currency_symbol" class="form-control" id="currency_symbol" value="{{(!isset($admin_payment_setting['currency_symbol']) || is_null($admin_payment_setting['currency_symbol'])) ? '' : $admin_payment_setting['currency_symbol']}}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                    <div class="faq justify-content-center">
                                        <div class="col-sm-12 col-md-10 col-xxl-12">
                                            <div class="accordion accordion-flush" id="accordionExample">
        
                                                <!-- Strip -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-2">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Stripe') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse1" class="accordion-collapse collapse"aria-labelledby="heading-2-2"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Stripe') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
        
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_stripe_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_stripe_enabled" id="is_stripe_enabled" {{(isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-label text-dark" for="is_stripe_enabled">{{__('Enable Stripe')}}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="stripe_key" class="col-form-label text-dark">{{__('Stripe Key')}}</label>
                                                                        <input class="form-control" placeholder="{{__('Stripe Key')}}" name="stripe_key" type="text" value="{{(!isset($admin_payment_setting['stripe_key']) || is_null($admin_payment_setting['stripe_key'])) ? '' : $admin_payment_setting['stripe_key']}}" id="stripe_key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="stripe_secret" class="col-form-label text-dark">{{__('Stripe Secret')}}</label>
                                                                        <input class="form-control " placeholder="{{ __('Stripe Secret') }}" name="stripe_secret" type="text" value="{{(!isset($admin_payment_setting['stripe_secret']) || is_null($admin_payment_setting['stripe_secret'])) ? '' : $admin_payment_setting['stripe_secret']}}" id="stripe_secret">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="stripe_secret" class="col-form-label text-dark">{{__('Stripe_Webhook_Secret')}}</label>
                                                                        <input class="form-control " placeholder="{{ __('Enter Stripe Webhook Secret') }}" name="stripe_webhook_secret" type="text" value="{{(!isset($admin_payment_setting['stripe_webhook_secret']) || is_null($admin_payment_setting['stripe_webhook_secret'])) ? '' : $admin_payment_setting['stripe_webhook_secret']}}" id="stripe_webhook_secret">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Paypal -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-3">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Paypal') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse2" class="accordion-collapse collapse"aria-labelledby="heading-2-3"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Paypal') }}</h5>
                                                                </div>
                                                                
        
                                                                
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_paypal_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_paypal_enabled" id="is_paypal_enabled" {{(isset($admin_payment_setting['is_paypal_enabled']) && $admin_payment_setting['is_paypal_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_paypal_enabled">{{__('Enable Paypal')}}</label>
                                                                    </div>
                                                                </div>
                                                            
                                                                <div class="col-md-12">
                                                                    <label class="paypal-label col-form-label text-dark" for="paypal_mode">{{__('Paypal Mode')}}</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="paypal_mode" value="sandbox" class="form-check-input" {{ !isset($admin_payment_setting['paypal_mode']) || $admin_payment_setting['paypal_mode'] == '' || $admin_payment_setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    
                                                                                        {{__('Sandbox')}}  
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="paypal_mode" value="live" class="form-check-input" {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    
                                                                                        {{__('Live')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label text-dark">{{ __('Client ID') }}</label>
                                                                        <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="{{(!isset($admin_payment_setting['paypal_client_id']) || is_null($admin_payment_setting['paypal_client_id'])) ? '' : $admin_payment_setting['paypal_client_id']}}" placeholder="{{ __('Client ID') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_secret_key" class="col-form-label text-dark">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="paypal_secret_key" id="paypal_secret_key" class="form-control" value="{{(!isset($admin_payment_setting['paypal_secret_key']) || is_null($admin_payment_setting['paypal_secret_key'])) ? '' : $admin_payment_setting['paypal_secret_key']}}" placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Paystack -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-4">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Paystack') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse3" class="accordion-collapse collapse"aria-labelledby="heading-2-4"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Paystack') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_paystack_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_paystack_enabled" id="is_paystack_enabled" {{(isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_paystack_enabled">{{__('Enable Paystack')}}</label>
                                                                    </div>
                                                                </div>
        
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label text-dark">{{ __('Public Key')}}</label>
                                                                        <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control" value="{{(!isset($admin_payment_setting['paystack_public_key']) || is_null($admin_payment_setting['paystack_public_key'])) ? '' : $admin_payment_setting['paystack_public_key']}}" placeholder="{{ __('Public Key')}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label text-dark">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="paystack_secret_key" id="paystack_secret_key" class="form-control" value="{{(!isset($admin_payment_setting['paystack_secret_key']) || is_null($admin_payment_setting['paystack_secret_key'])) ? '' : $admin_payment_setting['paystack_secret_key']}}" placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- FLUTTERWAVE -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-5">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="true" aria-controls="collapse4">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Flutterwave') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse4" class="accordion-collapse collapse"aria-labelledby="heading-2-5"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Flutterwave') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_flutterwave_enabled" id="is_flutterwave_enabled" {{(isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_flutterwave_enabled">{{__('Enable Flutterwave')}}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label text-dark">{{ __('Public Key')}}</label>
                                                                        <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" class="form-control" value="{{(!isset($admin_payment_setting['flutterwave_public_key']) || is_null($admin_payment_setting['flutterwave_public_key'])) ? '' : $admin_payment_setting['flutterwave_public_key']}}" placeholder="Public Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label text-dark">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="flutterwave_secret_key" id="flutterwave_secret_key" class="form-control" value="{{(!isset($admin_payment_setting['flutterwave_secret_key']) || is_null($admin_payment_setting['flutterwave_secret_key'])) ? '' : $admin_payment_setting['flutterwave_secret_key']}}" placeholder="Secret Key">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Razorpay -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-6">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="true" aria-controls="collapse5">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Razorpay') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse5" class="accordion-collapse collapse"aria-labelledby="heading-2-6"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Razorpay') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_razorpay_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_razorpay_enabled" id="is_razorpay_enabled" {{(isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_razorpay_enabled">{{__('Enable Razorpay')}}</label>
                                                                    </div>
                                                                </div>
        
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label text-dark">Public Key</label>
        
                                                                        <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control" value="{{(!isset($admin_payment_setting['razorpay_public_key']) || is_null($admin_payment_setting['razorpay_public_key'])) ? '' : $admin_payment_setting['razorpay_public_key']}}" placeholder="Public Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label text-dark">Secret Key</label>
                                                                        <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="{{(!isset($admin_payment_setting['razorpay_secret_key']) || is_null($admin_payment_setting['razorpay_secret_key'])) ? '' : $admin_payment_setting['razorpay_secret_key']}}" placeholder="Secret Key">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Paytm -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-7">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="true" aria-controls="collapse6">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Paytm') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse6" class="accordion-collapse collapse"aria-labelledby="heading-2-7"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Paytm') }}</h5>
                                                                </div>
        
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_paytm_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_paytm_enabled" id="is_paytm_enabled" {{(isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_paytm_enabled">{{__('Enable Paytm')}}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label class="paypal-label col-form-label text-dark" for="paypal_mode">Paytm Environment</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
        
                                                                                        <input type="radio" name="paytm_mode" value="local" class="form-check-input" {{ !isset($admin_payment_setting['paytm_mode']) || $admin_payment_setting['paytm_mode'] == '' || $admin_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>
                                                                                    
                                                                                        {{__('Local')}}  
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="paytm_mode" value="production" class="form-check-input" {{ isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                                    
                                                                                        {{__('Production')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_public_key" class="col-form-label text-dark">Merchant ID</label>
                                                                        <input type="text" name="paytm_merchant_id" id="paytm_merchant_id" class="form-control" value="{{(!isset($admin_payment_setting['paytm_merchant_id']) || is_null($admin_payment_setting['paytm_merchant_id'])) ? '' : $admin_payment_setting['paytm_merchant_id']}}" placeholder="Merchant ID">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_secret_key" class="col-form-label text-dark">Merchant Key</label>
                                                                        <input type="text" name="paytm_merchant_key" id="paytm_merchant_key" class="form-control" value="{{(!isset($admin_payment_setting['paytm_merchant_key']) || is_null($admin_payment_setting['paytm_merchant_key'])) ? '' : $admin_payment_setting['paytm_merchant_key']}}" placeholder="Merchant Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_industry_type" class="col-form-label text-dark">Industry Type</label>
                                                                        <input type="text" name="paytm_industry_type" id="paytm_industry_type" class="form-control" value="{{(!isset($admin_payment_setting['paytm_industry_type']) || is_null($admin_payment_setting['paytm_industry_type'])) ? '' : $admin_payment_setting['paytm_industry_type']}}" placeholder="Industry Type">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Mercado Pago-->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-8">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="true" aria-controls="collapse7">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Mercado Pago') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse7" class="accordion-collapse collapse"aria-labelledby="heading-2-8"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Mercado Pago') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_mercado_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_mercado_enabled" id="is_mercado_enabled" {{(isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_mercado_enabled">{{__('Enable Mercado Pago')}}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 ">
                                                                    <label class="coingate-label col-form-label text-dark" for="mercado_mode">{{__('Mercado Mode')}}</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="mercado_mode" value="sandbox" class="form-check-input" {{ isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == '' || isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                        {{__('Sandbox')}}  
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="mercado_mode" value="live" class="form-check-input" {{ isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                        {{__('Live')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="mercado_access_token" class="col-form-label text-dark">{{ __('Access Token') }}</label>
                                                                        <input type="text" name="mercado_access_token" id="mercado_access_token" class="form-control" value="{{isset($admin_payment_setting['mercado_access_token']) ? $admin_payment_setting['mercado_access_token']:''}}" placeholder="{{ __('Access Token') }}"/>                                                        
                                                                        @if ($errors->has('mercado_secret_key'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('mercado_access_token') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Mollie -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-9">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="true" aria-controls="collapse8">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Mollie') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse8" class="accordion-collapse collapse"aria-labelledby="heading-2-9"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Mollie') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_mollie_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_mollie_enabled" id="is_mollie_enabled" {{(isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_mollie_enabled">{{__('Enable Mollie')}}</label>
                                                                    </div>
                                                                </div>
        
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mollie_api_key" class="col-form-label text-dark">{{ __('Mollie Api Key') }}</label>
                                                                        <input type="text" name="mollie_api_key" id="mollie_api_key" class="form-control" value="{{(!isset($admin_payment_setting['mollie_api_key']) || is_null($admin_payment_setting['mollie_api_key'])) ? '' : $admin_payment_setting['mollie_api_key']}}" placeholder="Mollie Api Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mollie_profile_id" class="col-form-label text-dark">{{ __('Mollie Profile Id') }}</label>
                                                                        <input type="text" name="mollie_profile_id" id="mollie_profile_id" class="form-control" value="{{(!isset($admin_payment_setting['mollie_profile_id']) || is_null($admin_payment_setting['mollie_profile_id'])) ? '' : $admin_payment_setting['mollie_profile_id']}}" placeholder="Mollie Profile Id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mollie_partner_id" class="col-form-label text-dark">{{ __('Mollie Partner Id') }}</label>
                                                                        <input type="text" name="mollie_partner_id" id="mollie_partner_id" class="form-control" value="{{(!isset($admin_payment_setting['mollie_partner_id']) || is_null($admin_payment_setting['mollie_partner_id'])) ? '' : $admin_payment_setting['mollie_partner_id']}}" placeholder="Mollie Partner Id">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Skrill -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-10">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="true" aria-controls="collapse9">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Skrill') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse9" class="accordion-collapse collapse"aria-labelledby="heading-2-10"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Skrill') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_skrill_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_skrill_enabled" id="is_skrill_enabled" {{(isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_skrill_enabled">{{__('Enable Skrill')}}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="mollie_api_key" class="col-form-label text-dark">Skrill Email</label>
                                                                        <input type="text" name="skrill_email" id="skrill_email" class="form-control" value="{{(!isset($admin_payment_setting['skrill_email']) || is_null($admin_payment_setting['skrill_email'])) ? '' : $admin_payment_setting['skrill_email']}}" placeholder="Enter Skrill Email">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- CoinGate -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-11">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="true" aria-controls="collapse10">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('CoinGate') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse10" class="accordion-collapse collapse"aria-labelledby="heading-2-11"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('CoinGate') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_coingate_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_coingate_enabled" id="is_coingate_enabled" {{(isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_coingate_enabled">{{__('Enable CoinGate')}}</label>
                                                                    </div>
                                                                </div>
        
                                                                <div class="col-md-12">
                                                                    <label class="col-form-label text-dark" for="coingate_mode">CoinGate Mode</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
        
                                                                                        <input type="radio" name="coingate_mode" value="sandbox" class="form-check-input" {{ !isset($admin_payment_setting['coingate_mode']) || $admin_payment_setting['coingate_mode'] == '' || $admin_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
        
                                                                                        {{__('Sandbox')}}  
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="coingate_mode" value="live" class="form-check-input" {{ isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                        {{__('Live')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="coingate_auth_token" class="col-form-label text-dark">CoinGate Auth Token</label>
                                                                        <input type="text" name="coingate_auth_token" id="coingate_auth_token" class="form-control" value="{{(!isset($admin_payment_setting['coingate_auth_token']) || is_null($admin_payment_setting['coingate_auth_token'])) ? '' : $admin_payment_setting['coingate_auth_token']}}" placeholder="CoinGate Auth Token">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- PaymentWall -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-12">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse11" aria-expanded="true" aria-controls="collapse11">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('PaymentWall') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse11" class="accordion-collapse collapse"aria-labelledby="heading-2-12"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('PaymentWall') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_paymentwall_enabled" id="is_paymentwall_enabled" {{(isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_paymentwall_enabled">{{__('Enable PaymentWall')}}</label>
                                                                    </div>
                                                                </div>
        
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paymentwall_public_key" class="col-form-label text-dark">{{ __('Public Key')}}</label>
                                                                        <input type="text" name="paymentwall_public_key" id="paymentwall_public_key" class="form-control" value="{{(!isset($admin_payment_setting['paymentwall_public_key']) || is_null($admin_payment_setting['paymentwall_public_key'])) ? '' : $admin_payment_setting['paymentwall_public_key']}}" placeholder="{{ __('Public Key')}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paymentwall_private_key" class="col-form-label text-dark">{{ __('Private Key') }}</label>
                                                                        <input type="text" name="paymentwall_private_key" id="paymentwall_private_key" class="form-control" value="{{(!isset($admin_payment_setting['paymentwall_private_key']) || is_null($admin_payment_setting['paymentwall_private_key'])) ? '' : $admin_payment_setting['paymentwall_private_key']}}" placeholder="{{ __('Private Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <div class="form-group">
                                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            

                        </div>

                        <!--Zoom Setting-->
                        <div id="useradd-7" class="card">
                            <div class="card-header">
                                <h5>{{ __('Zoom Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company zoom meeting') }}</small>
                            </div>

                            {{ Form::open(['url' => route('setting.ZoomSettings'), 'enctype' => 'multipart/form-data']) }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-label text-dark">{{ __('Zoom API Key') }}</label>
                                            <input type="text" name="zoom_api_key" class="form-control"
                                                placeholder="Zoom API Key"
                                                value="{{ !empty($settings['zoom_api_key']) ? $settings['zoom_api_key'] : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-dark">{{ __('Zoom Secret Key') }}</label>
                                        <input type="text" name="zoom_secret_key" class="form-control"
                                            placeholder="Zoom Secret Key"
                                            value="{{ !empty($settings['zoom_secret_key']) ? $settings['zoom_secret_key'] : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                </div>
                            </div>
                            {{ Form::close() }}

                        </div>

                        <!--Slack Setting-->
                        <div id="useradd-8" class="card">
                            <div class="card-header">
                                <h5>{{ __('Slack Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company slack setting') }}</small>
                            </div>

                            <div class="card-body">
                                {{ Form::open(['route' => 'slack.setting','id'=>'slack-setting','method'=>'post' ,'class'=>'d-contents']) }}
                                <div class="row">
                                    <div class="col-md-12">
                                        {{Form::label('slack',__('Slack Webhook URL'),array('class'=>'form-label text-dark')) }}
                                    
                                        <div class="col-md-8">
                                            {{ Form::text('slack_webhook', isset($settings['slack_webhook']) ?$settings['slack_webhook'] :'', ['class' => 'form-control w-100', 'placeholder' => __('Enter Slack Webhook URL'), 'required' => 'required']) }}
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4 mb-2">
                                        <h5 class="small-title">{{__('Module Setting')}}</h5>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Holidays Create')}}</span> 
                                                    {{Form::checkbox('holiday_create_notification', '1',isset($settings['holiday_create_notification']) && $settings['holiday_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'holiday_create_notification'))}}
                                                    <label class="form-check-label" for="holiday_create_notification"></label>
                                                </div>

                                            </li>
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Meeting Create')}}</span> 
                                                    {{Form::checkbox('meeting_create_notification', '1',isset($settings['meeting_create_notification']) && $settings['meeting_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'meeting_create_notification'))}}
                                                    <label class="form-check-label" for="meeting_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Company Policy Create')}}</span> 
                                                    {{Form::checkbox('company_policy_create_notification', '1',isset($settings['company_policy_create_notification']) && $settings['company_policy_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'company_policy_create_notification'))}}
                                                    <label class="form-check-label" for="company_policy_create_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Award Create')}}</span> 
                                                    {{Form::checkbox('award_create_notification', '1',isset($settings['award_create_notification']) && $settings['award_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'award_create_notification'))}}
                                                    <label class="form-check-label" for="award_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Lead Create')}}</span> 
                                                    {{Form::checkbox('lead_create_notification', '1',isset($settings['lead_create_notification']) && $settings['lead_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'lead_create_notification'))}}
                                                    <label class="form-check-label" for="lead_create_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Deal Create')}}</span> 
                                                    {{Form::checkbox('deal_create_notification', '1',isset($settings['deal_create_notification']) && $settings['deal_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'deal_create_notification'))}}
                                                    <label class="form-check-label" for="deal_create_notification"></label>
                                                </div>
                                            </li>

                                            
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Convert Lead To Deal')}}</span> 
                                                    {{Form::checkbox('convert_lead_to_deal_notification', '1',isset($settings['convert_lead_to_deal_notification']) && $settings['convert_lead_to_deal_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'convert_lead_to_deal_notification'))}}
                                                    <label class="form-check-label" for="convert_lead_to_deal_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Estimation Create')}}</span> 
                                                    {{Form::checkbox('estimation_create_notification', '1',isset($settings['estimation_create_notification']) && $settings['estimation_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'estimation_create_notification'))}}
                                                    <label class="form-check-label" for="estimation_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Project Create')}}</span> 
                                                    {{Form::checkbox('project_create_notification', '1',isset($settings['project_create_notification']) && $settings['project_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'project_create_notification'))}}
                                                    <label class="form-check-label" for="project_create_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Project Status Create')}}</span> 
                                                    {{Form::checkbox('project_status_updated_notification', '1',isset($settings['project_status_updated_notification']) && $settings['project_status_updated_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'project_status_updated_notification'))}}
                                                    <label class="form-check-label" for="project_status_updated_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Task Create')}}</span> 
                                                    {{Form::checkbox('task_create_notification', '1',isset($settings['task_create_notification']) && $settings['task_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'task_create_notification'))}}
                                                    <label class="form-check-label" for="task_create_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Task Move Create')}}</span> 
                                                    {{Form::checkbox('task_move_notification', '1',isset($settings['task_move_notification']) && $settings['task_move_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'task_move_notification'))}}
                                                    <label class="form-check-label" for="task_move_notification"></label>
                                                </div>
                                            </li>

                                         
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Task Comment')}}</span> 
                                                    {{Form::checkbox('task_comment_notification', '1',isset($settings['task_comment_notification']) && $settings['task_comment_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'task_comment_notification'))}}
                                                    <label class="form-check-label" for="task_comment_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Milestone Create')}}</span> 
                                                    {{Form::checkbox('milestone_create_notification', '1',isset($settings['milestone_create_notification']) && $settings['milestone_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'milestone_create_notification'))}}
                                                    <label class="form-check-label" for="milestone_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Invoice Create')}}</span> 
                                                    {{Form::checkbox('invoice_create_notification', '1',isset($settings['invoice_create_notification']) && $settings['invoice_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'invoice_create_notification'))}}
                                                    <label class="form-check-label" for="invoice_create_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Invoice status Updated')}}</span> 
                                                    {{Form::checkbox('invoice_status_updated_notification', '1',isset($settings['invoice_status_updated_notification']) && $settings['invoice_status_updated_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'invoice_status_updated_notification'))}}
                                                    <label class="form-check-label" for="invoice_status_updated_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Payment Create')}}</span> 
                                                    {{Form::checkbox('payment_create_notification', '1',isset($settings['payment_create_notification']) && $settings['payment_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'payment_create_notification'))}}
                                                    <label class="form-check-label" for="payment_create_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Contract Create')}}</span> 
                                                    {{Form::checkbox('contract_create_notification', '1',isset($settings['contract_create_notification']) && $settings['contract_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'contract_create_notification'))}}
                                                    <label class="form-check-label" for="contract_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div> 
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Support Create')}}</span> 
                                                    {{Form::checkbox('support_create_notification', '1',isset($settings['support_create_notification']) && $settings['support_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'support_create_notification'))}}
                                                    <label class="form-check-label" for="support_create_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Event Create')}}</span> 
                                                    {{Form::checkbox('event_create_notification', '1',isset($settings['contract_create_notification']) && $settings['event_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'event_create_notification'))}}
                                                    <label class="form-check-label" for="event_create_notification"></label>
                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <div class="form-group">
                                        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>

                        </div>

                        <!--Telegram Setting-->
                        <div id="useradd-9" class="card">
                            <div class="card-header">
                                <h5>{{ __('Telegram Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company telegram setting') }}</small>
                            </div>

                            <div class="card-body">
                                {{ Form::open(['route' => 'telegram.setting','id'=>'telegram-setting','method'=>'post' ,'class'=>'d-contents']) }}
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('telegrambot',__('Telegram Access Token'),array('class'=>'form-label text-dark')) }}
                                            {{Form::text('telegrambot',isset($settings['telegrambot']) ?$settings['telegrambot'] :'',array('class'=>'form-control active telegrambot','placeholder'=>'1234567890:AAbbbbccccddddxvGENZCi8Hd4B15M8xHV0'))}}
                                            <p>{{__('Get Chat ID')}} : https://api.telegram.org/bot-TOKEN-/getUpdates</p>
                                            @if ($errors->has('telegrambot'))
                                                <span class="invalid-feedback d-block">
                                                    {{ $errors->first('telegrambot') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('telegramchatid',__('Telegram Chat Id'),array('class'=>'form-label text-dark')) }}
                                            {{Form::text('telegramchatid',isset($settings['telegramchatid']) ?$settings['telegramchatid'] :'',array('class'=>'form-control active telegramchatid','placeholder'=>'123456789'))}}
                                            @if ($errors->has('telegramchatid'))
                                                <span class="invalid-feedback d-block">
                                                    {{ $errors->first('telegramchatid') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4 mb-2">
                                        <h4 class="small-title">{{__('Module Setting')}}</h4>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Holidays Create')}}</span> 
                                                    {{Form::checkbox('telegram_holiday_create_notification', '1',isset($settings['telegram_holiday_create_notification']) && $settings['telegram_holiday_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_holiday_create_notification'))}}
                                                    <label class="form-check-label" for="telegram_holiday_create_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Meeting Create')}}</span> 
                                                    {{Form::checkbox('telegram_meeting_create_notification', '1',isset($settings['telegram_meeting_create_notification']) && $settings['telegram_meeting_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_meeting_create_notification'))}}
                                                    <label class="form-check-label" for="telegram_meeting_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Company Policy Create')}}</span> 
                                                    {{Form::checkbox('telegram_company_policy_create_notification', '1',isset($settings['telegram_company_policy_create_notification']) && $settings['telegram_company_policy_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_company_policy_create_notification'))}}
                                                    <label class="form-check-label" for="telegram_company_policy_create_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Award Create')}}</span> 
                                                    {{Form::checkbox('telegram_award_create_notification', '1',isset($settings['telegram_award_create_notification']) && $settings['telegram_award_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_award_create_notification'))}}
                                                    <label class="form-check-label" for="telegram_award_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Lead Create')}}</span> 
                                                    {{Form::checkbox('telegram_award_create_notification', '1',isset($settings['telegram_award_create_notification']) && $settings['telegram_award_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_award_create_notification'))}}
                                                    <label class="form-check-label" for="telegram_award_create_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item"> 
                                                <div class=" form-switch form-switch-right">
                                                    <span>{{__('Deal Create')}}</span> 
                                                    {{Form::checkbox('telegram_award_create_notification', '1',isset($settings['telegram_award_create_notification']) && $settings['telegram_award_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_award_create_notification'))}}
                                                    <label class="form-check-label" for="telegram_award_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Convert Lead To Deal')}}</span> 
                                                {{Form::checkbox('telegram_convert_lead_to_deal_notification', '1',isset($settings['telegram_convert_lead_to_deal_notification']) && $settings['telegram_convert_lead_to_deal_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_convert_lead_to_deal_notification'))}}
                                                    <label class="form-check-label" for="telegram_convert_lead_to_deal_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item"> 
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Estimation Create')}}</span>
                                                {{Form::checkbox('telegram_estimation_create_notification', '1',isset($settings['telegram_estimation_create_notification']) && $settings['telegram_estimation_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_estimation_create_notification'))}}  
                                                    <label class="form-check-label" for="telegram_estimation_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Project Create')}}</span> 
                                                {{Form::checkbox('telegram_project_create_notification', '1',isset($settings['telegram_project_create_notification']) && $settings['telegram_project_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_project_create_notification'))}}
                                                    <label class="form-check-label" for="telegram_project_create_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item"> 
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Project Status Updated')}}</span>
                                                {{Form::checkbox('telegram_project_status_updated_notification', '1',isset($settings['telegram_project_status_updated_notification']) && $settings['telegram_project_status_updated_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_project_status_updated_notification'))}}                                                
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Task Create')}}</span> 
                                                {{Form::checkbox('telegram_task_create_notification', '1',isset($settings['telegram_task_create_notification']) && $settings['telegram_task_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_task_create_notification'))}}                                                
                                                    <label class="form-check-label" for="telegram_task_create_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item"> 
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Task Move')}}</span>
                                                {{Form::checkbox('telegram_task_move_notification', '1',isset($settings['telegram_task_move_notification']) && $settings['telegram_task_move_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_task_move_notification'))}}                                                
                                                    <label class="form-check-label" for="telegram_task_move_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Task Comment')}}</span> 
                                                {{Form::checkbox('telegram_task_comment_notification', '1',isset($settings['telegram_task_comment_notification']) && $settings['telegram_task_comment_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_task_comment_notification'))}}                                                
                                                    <label class="form-check-label" for="telegram_task_comment_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item"> 
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Milestone Create')}}</span>
                                                {{Form::checkbox('telegram_milestone_create_notification', '1',isset($settings['telegram_milestone_create_notification']) && $settings['telegram_milestone_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_milestone_create_notification'))}}                                                
                                                    <label class="form-check-label" for="telegram_milestone_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Invoice Create')}}</span> 
                                                {{Form::checkbox('telegram_invoice_create_notification', '1',isset($settings['telegram_invoice_create_notification']) && $settings['telegram_invoice_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_invoice_create_notification'))}}                                                
                                                    <label class="form-check-label" for="telegram_invoice_create_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item"> 
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Invoice status Updated')}}</span>
                                                {{Form::checkbox('telegram_invoice_status_updated_notification', '1',isset($settings['telegram_invoice_status_updated_notification']) && $settings['telegram_invoice_status_updated_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_invoice_status_updated_notification'))}}                                                
                                                    <label class="form-check-label" for="telegram_invoice_status_updated_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Payment Create')}}</span> 
                                                {{Form::checkbox('telegram_payment_create_notification', '1',isset($settings['telegram_payment_create_notification']) && $settings['telegram_payment_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_payment_create_notification'))}}                                                
                                                    <label class="form-check-label" for="telegram_payment_create_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item"> 
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Contract Create')}}</span>
                                                {{Form::checkbox('telegram_contract_create_notification', '1',isset($settings['telegram_contract_create_notification']) && $settings['telegram_contract_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_contract_create_notification'))}}                                                
                                                    <label class="form-check-label" for="telegram_contract_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div> 
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Support Create')}}</span> 
                                                {{Form::checkbox('telegram_support_create_notification', '1',isset($settings['telegram_support_create_notification']) && $settings['telegram_support_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_support_create_notification'))}}                                                
                                                    <label class="form-check-label" for="telegram_support_create_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item"> 
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Event Create')}}</span>
                                                {{Form::checkbox('telegram_event_create_notification', '1',isset($settings['telegram_event_create_notification']) && $settings['telegram_event_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_event_create_notification'))}}                                                
                                                    <label class="form-check-label" for="telegram_event_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <div class="form-group">
                                        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>

                        </div>

                        <!--Twillio Setting-->
                        <div id="useradd-10" class="card">
                            <div class="card-header">
                                <h5>{{ __('Twillio Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company Twillio setting') }}</small>
                            </div>

                            <div class="card-body">
                                {{ Form::open(['route' => 'twilio.setting','id'=>'twilio-setting','method'=>'post' ,'class'=>'d-contents']) }}
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            {{Form::label('twilio_sid',__('Twilio SID '),array('class'=>'form-label text-dark')) }}
                                            {{ Form::text('twilio_sid', isset($settings['twilio_sid']) ?$settings['twilio_sid'] :'', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio SID'), 'required' => 'required']) }}
                                            @error('twilio_sid')
                                            <span class="invalid-twilio_sid" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            {{Form::label('twilio_token',__('Twilio Token'),array('class'=>'form-label text-dark')) }}
                                            {{ Form::text('twilio_token', isset($settings['twilio_token']) ?$settings['twilio_token'] :'', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio Token'), 'required' => 'required']) }}
                                            @error('twilio_token')
                                            <span class="invalid-twilio_token" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            {{Form::label('twilio_from',__('Twilio From'),array('class'=>'form-label text-dark')) }}
                                            {{ Form::text('twilio_from', isset($settings['twilio_from']) ?$settings['twilio_from'] :'', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio From'), 'required' => 'required']) }}
                                            @error('twilio_from')
                                            <span class="invalid-twilio_from" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4 mb-2">
                                        <h4 class="small-title">{{__('Module Setting')}}</h4>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Leave Approve/Reject')}}</span> 
                                                {{Form::checkbox('twilio_leave_approve_reject_notification', '1',isset($settings['twilio_leave_approve_reject_notification']) && $settings['twilio_leave_approve_reject_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'twilio_leave_approve_reject_notification'))}}                                               
                                                    <label class="form-check-label" for="twilio_leave_approve_reject_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Award Create')}}</span> 
                                                {{Form::checkbox('twilio_award_create_notification', '1',isset($settings['twilio_award_create_notification']) && $settings['twilio_award_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'twilio_award_create_notification'))}}                                            
                                                    <label class="form-check-label" for="twilio_award_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Trip Create')}}</span> 
                                                {{Form::checkbox('twilio_trip_create_notification', '1',isset($settings['twilio_trip_create_notification']) && $settings['twilio_trip_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'twilio_trip_create_notification'))}}                                                
                                                <label class="form-check-label" for="twilio_trip_create_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Ticket Create')}}</span> 
                                                {{Form::checkbox('twilio_ticket_create_notification', '1',isset($settings['twilio_ticket_create_notification']) && $settings['twilio_ticket_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'twilio_ticket_create_notification'))}}                                                
                                                <label class="form-check-label" for="twilio_ticket_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Event Create')}}</span> 
                                                {{Form::checkbox('twilio_event_create_notification', '1',isset($settings['twilio_event_create_notification']) && $settings['twilio_event_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'twilio_event_create_notification'))}}                                                
                                                <label class="form-check-label" for="twilio_event_create_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Project Create')}}</span> 
                                                {{Form::checkbox('twilio_project_create_notification', '1',isset($settings['twilio_project_create_notification']) && $settings['twilio_project_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'twilio_project_create_notification'))}}                                                
                                                <label class="form-check-label" for="twilio_project_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Task Create')}}</span> 
                                                {{Form::checkbox('twilio_task_create_notification', '1',isset($settings['twilio_task_create_notification']) && $settings['twilio_task_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'twilio_task_create_notification'))}}                                                
                                                <label class="form-check-label" for="twilio_task_create_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Contract Create')}}</span> 
                                                {{Form::checkbox('twilio_contract_create_notification', '1',isset($settings['twilio_contract_create_notification']) && $settings['twilio_contract_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'twilio_contract_create_notification'))}}                                               
                                                <label class="form-check-label" for="twilio_contract_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Invoice Create')}}</span> 
                                                {{Form::checkbox('twilio_invoice_create_notification', '1',isset($settings['twilio_invoice_create_notification']) && $settings['twilio_invoice_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'twilio_invoice_create_notification'))}}                                                
                                                <label class="form-check-label" for="twilio_invoice_create_notification"></label>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('Invoice Payment Create')}}</span> 
                                                {{Form::checkbox('twilio_invoice_payment_create_notification', '1',isset($settings['twilio_invoice_payment_create_notification']) && $settings['twilio_invoice_payment_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'twilio_invoice_payment_create_notification'))}}                                                
                                                <label class="form-check-label" for="twilio_invoice_payment_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-switch form-switch-right">
                                                <span>{{__('payment Create')}}</span> 
                                                {{Form::checkbox('twilio_payment_create_notification', '1',isset($settings['twilio_payment_create_notification']) && $settings['twilio_payment_create_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'twilio_payment_create_notification'))}}                                              
                                                <label class="form-check-label" for="twilio_payment_create_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                </div>
                                <div class="card-footer text-end">
                                    <div class="form-group">
                                        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                            
                            

                        </div>

                        <!--Mail Setting-->
                        <div id="useradd-12" class="card">
                            <div class="card-header">
                                <h5>{{ __('Mail Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company') }}</small>
                            </div>
                            {{Form::open(array('route'=>'email.setting','method'=>'post'))}}
                                <div class="card-body">

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_driver',__('Mail Driver'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_driver',env('MAIL_DRIVER'),array('class'=>'form-control','placeholder'=>__('Enter Mail Driver')))}}
                                            @error('mail_driver')
                                            <span class="invalid-mail_driver" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_host',__('Mail Host'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_host',env('MAIL_HOST'),array('class'=>'form-control ','placeholder'=>__('Enter Mail Host')))}}
                                            @error('mail_host')
                                            <span class="invalid-mail_driver" role="alert">
                                                     <strong class="text-danger">{{ $message }}</strong>
                                                     </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_port',__('Mail Port')) }}
                                            {{Form::text('mail_port',env('MAIL_PORT'),array('class'=>'form-control','placeholder'=>__('Enter Mail Port')))}}
                                            @error('mail_port')
                                            <span class="invalid-mail_port" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_username',__('Mail Username'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_username',env('MAIL_USERNAME'),array('class'=>'form-control','placeholder'=>__('Enter Mail Username')))}}
                                            @error('mail_username')
                                            <span class="invalid-mail_username" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_password',__('Mail Password'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_password',env('MAIL_PASSWORD'),array('class'=>'form-control','placeholder'=>__('Enter Mail Password')))}}
                                            @error('mail_password')
                                            <span class="invalid-mail_password" role="alert">
                                                     <strong class="text-danger">{{ $message }}</strong>
                                                     </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_encryption',__('Mail Encryption'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_encryption',env('MAIL_ENCRYPTION'),array('class'=>'form-control','placeholder'=>__('Enter Mail Encryption')))}}
                                            @error('mail_encryption')
                                            <span class="invalid-mail_encryption" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group  col-md-6">
                                            {{Form::label('mail_from_address',__('Mail From Address'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_from_address',env('MAIL_FROM_ADDRESS'),array('class'=>'form-control','placeholder'=>__('Enter Mail From Address')))}}
                                            @error('mail_from_address')
                                            <span class="invalid-mail_from_address" role="alert">
                                                     <strong class="text-danger">{{ $message }}</strong>
                                                     </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_from_name',__('Mail From Name'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_from_name',env('MAIL_FROM_NAME'),array('class'=>'form-control','placeholder'=>__('Enter Mail From Name')))}}
                                            @error('mail_from_name')
                                            <span class="invalid-mail_from_name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer ">
                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            <a href="#" data-url="{{route('test.mail' )}}" data-bs-toggle="modal" data-bs-target="#exampleModal"  data-bs-whatever="{{__('Send Test Mail')}}" class="btn btn-print-invoice btn-primary m-r-10">
                                                {{__('Send Test Mail')}}
                                            </a>
                                        </div>

                                        <div class="form-group col-md-6 text-end">
                                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                        </div>
                                    </div>
                                </div>
                                {{Form::close()}}

                        </div>

                         <!--Pusher Setting-->
                         <div id="useradd-13" class="card">
                            <div class="card-header">
                                <h5>{{ __('Pusher Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company Chats') }}</small>
                            </div>


                            {{Form::model($settings,array('route'=>'pusher.setting','method'=>'post'))}}
                                <div class="card-body">

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {{Form::label('pusher_app_id',__('Pusher App Id'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('pusher_app_id',env('PUSHER_APP_ID'),array('class'=>'form-control font-style'))}}
                                            @error('pusher_app_id')
                                            <span class="invalid-pusher_app_id" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('pusher_app_key',__('Pusher App Key'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('pusher_app_key',env('PUSHER_APP_KEY'),array('class'=>'form-control font-style'))}}
                                            @error('pusher_app_key')
                                            <span class="invalid-pusher_app_key" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('pusher_app_secret',__('Pusher App Secret'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('pusher_app_secret',env('PUSHER_APP_SECRET'),array('class'=>'form-control font-style'))}}
                                            @error('pusher_app_secret')
                                            <span class="invalid-pusher_app_secret" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('pusher_app_cluster',__('Pusher App Cluster'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('pusher_app_cluster',env('PUSHER_APP_CLUSTER'),array('class'=>'form-control font-style'))}}
                                            @error('pusher_app_cluster')
                                            <span class="invalid-pusher_app_cluster" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                       
                                    </div>

                                </div>
                                <div class="card-footer ">
                                    
                                        <div class="form-group text-end">
                                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                        </div>
                                
                                </div>
                            {{Form::close()}}

                        </div>

                        <!--Recaptcha Setting-->
                        <div id="useradd-15" class="card">
                            <form method="POST" action="{{ route('recaptcha.settings.store') }}" accept-charset="UTF-8"> 
                                @csrf
                                <div class="col-md-12">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-lg-8 col-md-8 col-sm-8">
                                                <h5>{{ __('ReCaptcha Setting') }}</h5>
                                                <small class="text-secondary font-weight-bold">
                                                <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/" target="_blank" class="text-blue">
                                                <small>({{__('How to Get Google reCaptcha Site and Secret key')}})</small>
                                                </a>
                                                </small>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                                <div class="col switch-width">
                                                    <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="recaptcha_module" id="recaptcha_module" data-toggle="switchbutton" value="yes" {{ env('RECAPTCHA_MODULE') == 'yes' ? 'checked="checked"' : '' }}  data-onstyle="primary">
                                                            <label class="form-check-labe" for="recaptcha_module"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label class="form-label">{{ __('Google Recaptcha Key') }}</label>
                                                <input class="form-control" placeholder="{{ __('Enter Google Recaptcha Key') }}" name="google_recaptcha_key" type="text" value="{{env('NOCAPTCHA_SITEKEY')}}" id="google_recaptcha_key">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label class="form-label">{{ __('Google Recaptcha Secret') }}</label>
                                                <input class="form-control " placeholder="{{ __('Enter Google Recaptcha Secret') }}" name="google_recaptcha_secret" type="text" value="{{env('NOCAPTCHA_SECRET')}}" id="google_recaptcha_secret">
                                            </div>
                                        </div>
                                        <div class="card-footer p-0">
                                            <div class="col-sm-12 mt-3 px-2">
                                                <div class="text-end">
                                                    <input class="btn btn-print-invoice  btn-primary " type="submit" value="{{__('Save Changes')}}">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form> 
                        </div>

                    @endif

                    @if(\Auth::user()->type == 'super admin')
                        <div id="useradd-11" class="card">
                            {{Form::model($settings,array('route'=>'business.setting','method'=>'POST','enctype' => "multipart/form-data"))}}
                            <div class="card-header">
                                <h5>{{ __('Site Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company') }}</small>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="small-title">{{ __('Favicon') }}</h5>
                                            </div>
                                            <div class="card-body setting-card setting-logo-box p-3">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="logo-content logo-set-bg text-center py-2">
                                                            <img src="{{ asset(Storage::url('uploads/logo/favicon.png')) }}"
                                                                class="small-logo" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="choose-file">
                                                            <label for="favicon" class="form-label text-dark">
                                                                <div>{{ __('Choose file here') }}</div>
                                                                <input type="file" class="form-control" name="favicon"
                                                                    id="small-favicon" data-filename="edit-favicon">
                                                            </label>
                                                            <p class="edit-favicon"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="small-title">{{ __('Dark Logo') }}</h5>
                                            </div>
                                            <div class="card-body setting-card setting-logo-box p-3">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="logo-content logo-set-bg  text-center py-2">
                                                            <img src="{{ asset(Storage::url('uploads/logo/logo-dark.png')) }}"
                                                                class="big-logo" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="choose-file">
                                                            <label for="logo" class="form-label text-dark">
                                                                <div>{{ __('Choose file here') }}</div>
                                                                <input type="file" class="form-control" name="logo" id="logo"
                                                                    data-filename="edit-logo">
                                                            </label>
                                                            <p class="edit-logo"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="small-title">{{ __('Light Logo') }}</h5>
                                            </div>
                                            <div class="card-body setting-card setting-logo-box p-3">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="logo-content  logo-set-bg text-center py-2">
                                                            <img src="{{ asset(Storage::url('uploads/logo/logo-light.png')) }}"
                                                                class="big-logo" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="choose-file">
                                                            <label for="logo" class="form-label text-dark">
                                                                <div>{{ __('Choose file here') }}</div>
                                                                <input type="file" class="form-control" name="white_logo"
                                                                    id="white_logo" data-filename="edit-white_logo">
                                                            </label>
                                                            <p class="edit-white_logo"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row mt-4"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{Form::label('header_text',__('Title Text'),['class'=>'col-form-label text-dark text-dark']) }}
                                            {{Form::text('header_text',Utility::getValByName('header_text'),array('class'=>'form-control','placeholder'=>__('Enter Header Title Text')))}}
                                            @error('header_text')
                                            <span class="invalid-header_text" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{Form::label('footer_text',__('Footer Text'),['class'=>'col-form-label text-dark text-dark']) }}
                                            {{Form::text('footer_text',Utility::getValByName('footer_text'),array('class'=>'form-control','placeholder'=>__('Enter Footer Text')))}}
                                            @error('footer_text')
                                            <span class="invalid-footer_text" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            {{Form::label('default_language',__('Default Language'),['class'=>'col-form-label text-dark text-dark']) }}
                                            <select name="default_language" id="default_language" class="form-control select2">
                                                @foreach(Utility::languages() as $language)
                                                    <option @if(Utility::getValByName('default_language') == $language) selected @endif value="{{$language}}">{{Str::upper($language)}}</option>
                                                @endforeach
                                            </select>
                                            @error('default_language')
                                            <span class="invalid-default_language" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col switch-width">
                                                <div class="form-group ml-2 mr-3 ">
                                                    <label class="form-label text-dark">{{ __('RTL') }}</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" data-toggle="switchbutton"
                                                            data-onstyle="primary" class=""
                                                            name="SITE_RTL" id="SITE_RTL"
                                                            {{ env('SITE_RTL') == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="SITE_RTL"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col switch-width">
                                                <div class="form-group mr-3">
                                                    <label class="form-label text-dark text-dark " for="display_landing_page">{{ __('Enable Landing Page') }}</label>
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input type="checkbox" name="display_landing_page" class="form-check-input" id="display_landing_page" data-toggle="switchbutton" {{ $settings['display_landing_page'] == 'on' ? 'checked="checked"' : '' }} data-onstyle="primary">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col switch-width">
                                                <div class="form-group mr-3">
                                                    <label class="form-label text-dark text-dark" for="SIGNUP">{{ __('Sign Up') }}</label>
                                                <div class="">
                                                    <input type="checkbox" name="SIGNUP" id="SIGNUP" data-toggle="switchbutton" {{ $settings['SIGNUP'] == 'on' ? 'checked="checked"' : '' }}  data-onstyle="primary">
                                                    <label class="form-check-labe" for="SIGNUP"></label>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col switch-width">
                                                <div class="form-group mr-3">
                                                    {{ Form::label('gdpr_cookie', 'GDPR Cookie', ['class' => 'form-label text-dark']) }}
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" data-toggle="switchbutton"
                                                            data-onstyle="primary"
                                                            class="custom-control-input gdpr_fulltime gdpr_type"
                                                            name="gdpr_cookie" id="gdpr_cookie"
                                                            {{ isset($settings['gdpr_cookie']) && $settings['gdpr_cookie'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-label text-dark"
                                                            for="gdpr_cookie"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                   
                                <div class="row">
                                    <div class="form-group col-12">
                                        {{Form::label('cookie_text',__('GDPR Cookie Text'),array('class'=>'fulltime') )}}
                                        {!! Form::textarea('cookie_text',$settings['cookie_text'], ['class'=>'form-control fulltime','rows'=>'4']) !!}    
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <h4 class="small-title">{{ __('Theme Customizer') }}</h4>
                                    <div class="setting-card setting-logo-box p-3">
                                        <div class="row">
                                            <div class="pct-body">
                                                <div class="row">
                                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                                        <h6 class="mt-2">
                                                            <i data-feather="credit-card"
                                                                class="me-2"></i>{{ __('Primary color settings') }}
                                                        </h6>
                                                        <hr class="my-2" />
                                                        <div class="theme-color themes-color">
                                                            <a href="#!" class=""
                                                                data-value="theme-1"
                                                                onclick="check_theme('theme-1')"></a>
                                                            <input type="radio" class="theme_color"
                                                                name="color" value="theme-1"
                                                            style="display: none;">
                                                            <a href="#!" class=""
                                                                data-value="theme-2"
                                                                onclick="check_theme('theme-2')"></a>
                                                            <input type="radio" class="theme_color"
                                                                name="color" value="theme-2"
                                                                style="display: none;">
                                                            <a href="#!" class=""
                                                                data-value="theme-3"
                                                                onclick="check_theme('theme-3')"></a>
                                                            <input type="radio" class="theme_color"
                                                                name="color" value="theme-3"
                                                                style="display: none;">
                                                            <a href="#!" class=""
                                                                data-value="theme-4"
                                                                onclick="check_theme('theme-4')"></a>
                                                            <input type="radio" class="theme_color"
                                                                name="color" value="theme-4"
                                                                style="display: none;">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                                        <h6 class="mt-2  rtl-hide">
                                                            <i data-feather="layout"
                                                                class="me-2"></i>{{ __('Sidebar settings') }}
                                                        </h6>
                                                        <hr class="my-2 rtl-hide" />
                                                        <div class="form-check form-switch rtl-hide">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="cust-theme-bg" name="cust_theme_bg"
                                                                {{ !empty($settings['cust_theme_bg']) && $settings['cust_theme_bg'] == 'on' ? 'checked' : '' }} />
                                                            <label class="form-check-label f-w-600 pl-1"
                                                                for="cust-theme-bg">{{ __('Transparent layout') }}</label>
                                                               
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                                        <h6 class="mt-2 rtl-hide">
                                                            <i data-feather="sun"
                                                                class="me-2"></i>{{ __('Layout settings') }}
                                                        </h6>
                                                        <hr class="my-2 rtl-hide" />
                                                        <div class="form-check form-switch mt-2 rtl-hide">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="cust-darklayout" name="cust_darklayout"
                                                                {{ !empty($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on' ? 'checked' : '' }} />
                                                            <label class="form-check-label f-w-600 pl-1"
                                                                for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-end">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                </div>
                            </div>
                            {{ Form::close() }} 
                        </div>

                        <!--Mail Setting-->
                        <div id="useradd-12" class="card">
                            <div class="card-header">
                                <h5>{{ __('Mail Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company') }}</small>
                            </div>


                            {{Form::open(array('route'=>'email.setting','method'=>'post'))}}
                                <div class="card-body">

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_driver',__('Mail Driver'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_driver',env('MAIL_DRIVER'),array('class'=>'form-control','placeholder'=>__('Enter Mail Driver')))}}
                                            @error('mail_driver')
                                            <span class="invalid-mail_driver" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_host',__('Mail Host'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_host',env('MAIL_HOST'),array('class'=>'form-control ','placeholder'=>__('Enter Mail Host')))}}
                                            @error('mail_host')
                                            <span class="invalid-mail_driver" role="alert">
                                                     <strong class="text-danger">{{ $message }}</strong>
                                                     </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_username',__('Mail Username'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_username',env('MAIL_USERNAME'),array('class'=>'form-control','placeholder'=>__('Enter Mail Username')))}}
                                            @error('mail_username')
                                            <span class="invalid-mail_username" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_password',__('Mail Password'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_password',env('MAIL_PASSWORD'),array('class'=>'form-control','placeholder'=>__('Enter Mail Password')))}}
                                            @error('mail_password')
                                            <span class="invalid-mail_password" role="alert">
                                                     <strong class="text-danger">{{ $message }}</strong>
                                                     </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_encryption',__('Mail Encryption'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_encryption',env('MAIL_ENCRYPTION'),array('class'=>'form-control','placeholder'=>__('Enter Mail Encryption')))}}
                                            @error('mail_encryption')
                                            <span class="invalid-mail_encryption" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group  col-md-6">
                                            {{Form::label('mail_from_address',__('Mail From Address'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_from_address',env('MAIL_FROM_ADDRESS'),array('class'=>'form-control','placeholder'=>__('Enter Mail From Address')))}}
                                            @error('mail_from_address')
                                            <span class="invalid-mail_from_address" role="alert">
                                                     <strong class="text-danger">{{ $message }}</strong>
                                                     </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('mail_from_name',__('Mail From Name'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('mail_from_name',env('MAIL_FROM_NAME'),array('class'=>'form-control','placeholder'=>__('Enter Mail From Name')))}}
                                            @error('mail_from_name')
                                            <span class="invalid-mail_from_name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer ">
                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            <a href="#" data-url="{{route('test.mail' )}}" data-bs-toggle="modal" data-bs-target="#exampleModal"  data-bs-whatever="{{__('Send Test Mail')}}" class="btn btn-print-invoice  btn-warning m-r-10">
                                                {{__('Send Test Mail')}}
                                            </a>
                                        </div>

                                        <div class="form-group col-md-6 text-end">
                                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                        </div>
                                    </div>
                                </div>
                                {{Form::close()}}

                        </div>

                        <!--Pusher Setting-->
                        <div id="useradd-13" class="card">
                            <div class="card-header">
                                <h5>{{ __('Pusher Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Company Chats') }}</small>
                            </div>


                            {{Form::model($settings,array('route'=>'pusher.setting','method'=>'post'))}}
                                <div class="card-body">

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {{Form::label('pusher_app_id',__('Pusher App Id'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('pusher_app_id',env('PUSHER_APP_ID'),array('class'=>'form-control font-style'))}}
                                            @error('pusher_app_id')
                                            <span class="invalid-pusher_app_id" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('pusher_app_key',__('Pusher App Key'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('pusher_app_key',env('PUSHER_APP_KEY'),array('class'=>'form-control font-style'))}}
                                            @error('pusher_app_key')
                                            <span class="invalid-pusher_app_key" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('pusher_app_secret',__('Pusher App Secret'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('pusher_app_secret',env('PUSHER_APP_SECRET'),array('class'=>'form-control font-style'))}}
                                            @error('pusher_app_secret')
                                            <span class="invalid-pusher_app_secret" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('pusher_app_cluster',__('Pusher App Cluster'),array('class' => 'form-label text-dark')) }}
                                            {{Form::text('pusher_app_cluster',env('PUSHER_APP_CLUSTER'),array('class'=>'form-control font-style'))}}
                                            @error('pusher_app_cluster')
                                            <span class="invalid-pusher_app_cluster" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                       
                                    </div>

                                </div>
                                <div class="card-footer ">
                                    
                                        <div class="form-group text-end">
                                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                        </div>
                                
                                </div>
                            {{Form::close()}}

                        </div>

                        <!--payment Setting-->
                        <div id="useradd-14" class="card">
                            <div class="card-header">
                                <h5>{{ __('Payment Setting') }}</h5>
                                <small class="text-muted">{{ __('This detail will use for collect payment on Plans from company. On plans company will find out pay now button based on your below configuration.') }}</small>
                            </div>
                            <div class="card-body">
                                {{Form::open(array('route'=>'payment.setting','method'=>'post'))}}
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                            <label class="col-form-label text-dark">{{__('Currency')}} *</label>
                                                            <input type="text" name="currency" class="form-control" id="currency" value="{{(!isset($admin_payment_setting['currency']) || is_null($admin_payment_setting['currency'])) ? '' : $admin_payment_setting['currency']}}" required>
                                                            <small class="text-xs">
                                                                {{ __('Note: Add currency code as per three-letter ISO code') }}.
                                                                <a href="https://stripe.com/docs/currencies" target="_blank">{{ __('you can find out here..') }}</a>
                                                            </small>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                            <label for="currency_symbol" class="col-form-label text-dark">{{__('Currency Symbol')}}</label>
                                                            <input type="text" name="currency_symbol" class="form-control" id="currency_symbol" value="{{(!isset($admin_payment_setting['currency_symbol']) || is_null($admin_payment_setting['currency_symbol'])) ? '' : $admin_payment_setting['currency_symbol']}}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="faq justify-content-center">
                                        <div class="col-sm-12 col-md-10 col-xxl-12">
                                            <div class="accordion accordion-flush" id="accordionExample">
        
                                                <!-- Strip -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-2">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Stripe') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse1" class="accordion-collapse collapse"aria-labelledby="heading-2-2"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Stripe') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
        
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_stripe_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_stripe_enabled" id="is_stripe_enabled" {{(isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-label text-dark" for="is_stripe_enabled">{{__('Enable Stripe')}}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="stripe_key" class="col-form-label text-dark">{{__('Stripe Key')}}</label>
                                                                        <input class="form-control" placeholder="{{__('Stripe Key')}}" name="stripe_key" type="text" value="{{(!isset($admin_payment_setting['stripe_key']) || is_null($admin_payment_setting['stripe_key'])) ? '' : $admin_payment_setting['stripe_key']}}" id="stripe_key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="stripe_secret" class="col-form-label text-dark">{{__('Stripe Secret')}}</label>
                                                                        <input class="form-control " placeholder="{{ __('Stripe Secret') }}" name="stripe_secret" type="text" value="{{(!isset($admin_payment_setting['stripe_secret']) || is_null($admin_payment_setting['stripe_secret'])) ? '' : $admin_payment_setting['stripe_secret']}}" id="stripe_secret">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="stripe_secret" class="col-form-label text-dark">{{__('Stripe_Webhook_Secret')}}</label>
                                                                        <input class="form-control " placeholder="{{ __('Enter Stripe Webhook Secret') }}" name="stripe_webhook_secret" type="text" value="{{(!isset($admin_payment_setting['stripe_webhook_secret']) || is_null($admin_payment_setting['stripe_webhook_secret'])) ? '' : $admin_payment_setting['stripe_webhook_secret']}}" id="stripe_webhook_secret">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Paypal -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-3">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Paypal') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse2" class="accordion-collapse collapse"aria-labelledby="heading-2-3"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Paypal') }}</h5>
                                                                </div>
                                                                
        
                                                                
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_paypal_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_paypal_enabled" id="is_paypal_enabled" {{(isset($admin_payment_setting['is_paypal_enabled']) && $admin_payment_setting['is_paypal_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_paypal_enabled">{{__('Enable Paypal')}}</label>
                                                                    </div>
                                                                </div>
                                                            
                                                                <div class="col-md-12">
                                                                    <label class="paypal-label col-form-label text-dark" for="paypal_mode">{{__('Paypal Mode')}}</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="paypal_mode" value="sandbox" class="form-check-input" {{ !isset($admin_payment_setting['paypal_mode']) || $admin_payment_setting['paypal_mode'] == '' || $admin_payment_setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    
                                                                                        {{__('Sandbox')}}  
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="paypal_mode" value="live" class="form-check-input" {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    
                                                                                        {{__('Live')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label text-dark">{{ __('Client ID') }}</label>
                                                                        <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="{{(!isset($admin_payment_setting['paypal_client_id']) || is_null($admin_payment_setting['paypal_client_id'])) ? '' : $admin_payment_setting['paypal_client_id']}}" placeholder="{{ __('Client ID') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_secret_key" class="col-form-label text-dark">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="paypal_secret_key" id="paypal_secret_key" class="form-control" value="{{(!isset($admin_payment_setting['paypal_secret_key']) || is_null($admin_payment_setting['paypal_secret_key'])) ? '' : $admin_payment_setting['paypal_secret_key']}}" placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Paystack -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-4">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Paystack') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse3" class="accordion-collapse collapse"aria-labelledby="heading-2-4"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Paystack') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_paystack_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_paystack_enabled" id="is_paystack_enabled" {{(isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_paystack_enabled">{{__('Enable Paystack')}}</label>
                                                                    </div>
                                                                </div>
        
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label text-dark">{{ __('Public Key')}}</label>
                                                                        <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control" value="{{(!isset($admin_payment_setting['paystack_public_key']) || is_null($admin_payment_setting['paystack_public_key'])) ? '' : $admin_payment_setting['paystack_public_key']}}" placeholder="{{ __('Public Key')}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label text-dark">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="paystack_secret_key" id="paystack_secret_key" class="form-control" value="{{(!isset($admin_payment_setting['paystack_secret_key']) || is_null($admin_payment_setting['paystack_secret_key'])) ? '' : $admin_payment_setting['paystack_secret_key']}}" placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- FLUTTERWAVE -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-5">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="true" aria-controls="collapse4">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Flutterwave') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse4" class="accordion-collapse collapse"aria-labelledby="heading-2-5"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Flutterwave') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_flutterwave_enabled" id="is_flutterwave_enabled" {{(isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_flutterwave_enabled">{{__('Enable Flutterwave')}}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label text-dark">{{ __('Public Key')}}</label>
                                                                        <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" class="form-control" value="{{(!isset($admin_payment_setting['flutterwave_public_key']) || is_null($admin_payment_setting['flutterwave_public_key'])) ? '' : $admin_payment_setting['flutterwave_public_key']}}" placeholder="Public Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label text-dark">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="flutterwave_secret_key" id="flutterwave_secret_key" class="form-control" value="{{(!isset($admin_payment_setting['flutterwave_secret_key']) || is_null($admin_payment_setting['flutterwave_secret_key'])) ? '' : $admin_payment_setting['flutterwave_secret_key']}}" placeholder="Secret Key">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Razorpay -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-6">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="true" aria-controls="collapse5">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Razorpay') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse5" class="accordion-collapse collapse"aria-labelledby="heading-2-6"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Razorpay') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_razorpay_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_razorpay_enabled" id="is_razorpay_enabled" {{(isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_razorpay_enabled">{{__('Enable Razorpay')}}</label>
                                                                    </div>
                                                                </div>
        
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label text-dark">Public Key</label>
        
                                                                        <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control" value="{{(!isset($admin_payment_setting['razorpay_public_key']) || is_null($admin_payment_setting['razorpay_public_key'])) ? '' : $admin_payment_setting['razorpay_public_key']}}" placeholder="Public Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label text-dark">Secret Key</label>
                                                                        <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="{{(!isset($admin_payment_setting['razorpay_secret_key']) || is_null($admin_payment_setting['razorpay_secret_key'])) ? '' : $admin_payment_setting['razorpay_secret_key']}}" placeholder="Secret Key">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Paytm -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-7">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="true" aria-controls="collapse6">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Paytm') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse6" class="accordion-collapse collapse"aria-labelledby="heading-2-7"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Paytm') }}</h5>
                                                                </div>
        
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_paytm_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_paytm_enabled" id="is_paytm_enabled" {{(isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_paytm_enabled">{{__('Enable Paytm')}}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label class="paypal-label col-form-label text-dark" for="paypal_mode">Paytm Environment</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
        
                                                                                        <input type="radio" name="paytm_mode" value="local" class="form-check-input" {{ !isset($admin_payment_setting['paytm_mode']) || $admin_payment_setting['paytm_mode'] == '' || $admin_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>
                                                                                    
                                                                                        {{__('Local')}}  
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="paytm_mode" value="production" class="form-check-input" {{ isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                                    
                                                                                        {{__('Production')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_public_key" class="col-form-label text-dark">Merchant ID</label>
                                                                        <input type="text" name="paytm_merchant_id" id="paytm_merchant_id" class="form-control" value="{{(!isset($admin_payment_setting['paytm_merchant_id']) || is_null($admin_payment_setting['paytm_merchant_id'])) ? '' : $admin_payment_setting['paytm_merchant_id']}}" placeholder="Merchant ID">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_secret_key" class="col-form-label text-dark">Merchant Key</label>
                                                                        <input type="text" name="paytm_merchant_key" id="paytm_merchant_key" class="form-control" value="{{(!isset($admin_payment_setting['paytm_merchant_key']) || is_null($admin_payment_setting['paytm_merchant_key'])) ? '' : $admin_payment_setting['paytm_merchant_key']}}" placeholder="Merchant Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_industry_type" class="col-form-label text-dark">Industry Type</label>
                                                                        <input type="text" name="paytm_industry_type" id="paytm_industry_type" class="form-control" value="{{(!isset($admin_payment_setting['paytm_industry_type']) || is_null($admin_payment_setting['paytm_industry_type'])) ? '' : $admin_payment_setting['paytm_industry_type']}}" placeholder="Industry Type">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Mercado Pago-->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-8">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="true" aria-controls="collapse7">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Mercado Pago') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse7" class="accordion-collapse collapse"aria-labelledby="heading-2-8"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Mercado Pago') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_mercado_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_mercado_enabled" id="is_mercado_enabled" {{(isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_mercado_enabled">{{__('Enable Mercado Pago')}}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 ">
                                                                    <label class="coingate-label col-form-label text-dark" for="mercado_mode">{{__('Mercado Mode')}}</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="mercado_mode" value="sandbox" class="form-check-input" {{ isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == '' || isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                        {{__('Sandbox')}}  
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="mercado_mode" value="live" class="form-check-input" {{ isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                        {{__('Live')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="mercado_access_token" class="col-form-label text-dark">{{ __('Access Token') }}</label>
                                                                        <input type="text" name="mercado_access_token" id="mercado_access_token" class="form-control" value="{{isset($admin_payment_setting['mercado_access_token']) ? $admin_payment_setting['mercado_access_token']:''}}" placeholder="{{ __('Access Token') }}"/>                                                        
                                                                        @if ($errors->has('mercado_secret_key'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('mercado_access_token') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Mollie -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-9">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="true" aria-controls="collapse8">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Mollie') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse8" class="accordion-collapse collapse"aria-labelledby="heading-2-9"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Mollie') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_mollie_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_mollie_enabled" id="is_mollie_enabled" {{(isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_mollie_enabled">{{__('Enable Mollie')}}</label>
                                                                    </div>
                                                                </div>
        
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mollie_api_key" class="col-form-label text-dark">{{ __('Mollie Api Key') }}</label>
                                                                        <input type="text" name="mollie_api_key" id="mollie_api_key" class="form-control" value="{{(!isset($admin_payment_setting['mollie_api_key']) || is_null($admin_payment_setting['mollie_api_key'])) ? '' : $admin_payment_setting['mollie_api_key']}}" placeholder="Mollie Api Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mollie_profile_id" class="col-form-label text-dark">{{ __('Mollie Profile Id') }}</label>
                                                                        <input type="text" name="mollie_profile_id" id="mollie_profile_id" class="form-control" value="{{(!isset($admin_payment_setting['mollie_profile_id']) || is_null($admin_payment_setting['mollie_profile_id'])) ? '' : $admin_payment_setting['mollie_profile_id']}}" placeholder="Mollie Profile Id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mollie_partner_id" class="col-form-label text-dark">{{ __('Mollie Partner Id') }}</label>
                                                                        <input type="text" name="mollie_partner_id" id="mollie_partner_id" class="form-control" value="{{(!isset($admin_payment_setting['mollie_partner_id']) || is_null($admin_payment_setting['mollie_partner_id'])) ? '' : $admin_payment_setting['mollie_partner_id']}}" placeholder="Mollie Partner Id">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Skrill -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-10">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="true" aria-controls="collapse9">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('Skrill') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse9" class="accordion-collapse collapse"aria-labelledby="heading-2-10"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('Skrill') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_skrill_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_skrill_enabled" id="is_skrill_enabled" {{(isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_skrill_enabled">{{__('Enable Skrill')}}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="mollie_api_key" class="col-form-label text-dark">Skrill Email</label>
                                                                        <input type="text" name="skrill_email" id="skrill_email" class="form-control" value="{{(!isset($admin_payment_setting['skrill_email']) || is_null($admin_payment_setting['skrill_email'])) ? '' : $admin_payment_setting['skrill_email']}}" placeholder="Enter Skrill Email">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- CoinGate -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-11">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="true" aria-controls="collapse10">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('CoinGate') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse10" class="accordion-collapse collapse"aria-labelledby="heading-2-11"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('CoinGate') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_coingate_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_coingate_enabled" id="is_coingate_enabled" {{(isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_coingate_enabled">{{__('Enable CoinGate')}}</label>
                                                                    </div>
                                                                </div>
        
                                                                <div class="col-md-12">
                                                                    <label class="col-form-label text-dark" for="coingate_mode">CoinGate Mode</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
        
                                                                                        <input type="radio" name="coingate_mode" value="sandbox" class="form-check-input" {{ !isset($admin_payment_setting['coingate_mode']) || $admin_payment_setting['coingate_mode'] == '' || $admin_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
        
                                                                                        {{__('Sandbox')}}  
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="coingate_mode" value="live" class="form-check-input" {{ isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                        {{__('Live')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="coingate_auth_token" class="col-form-label text-dark">CoinGate Auth Token</label>
                                                                        <input type="text" name="coingate_auth_token" id="coingate_auth_token" class="form-control" value="{{(!isset($admin_payment_setting['coingate_auth_token']) || is_null($admin_payment_setting['coingate_auth_token'])) ? '' : $admin_payment_setting['coingate_auth_token']}}" placeholder="CoinGate Auth Token">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- PaymentWall -->
                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-12">
                                                        <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse11" aria-expanded="true" aria-controls="collapse11">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i> {{ __('PaymentWall') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse11" class="accordion-collapse collapse"aria-labelledby="heading-2-12"data-bs-parent="#accordionExample" >
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-6 py-2">
                                                                    <h5 class="h5">{{ __('PaymentWall') }}</h5>
                                                                </div>
                                                                <div class="col-6 py-2 text-end">
                                                                    <div class="form-check form-switch d-inline-block">
                                                                        <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_paymentwall_enabled" id="is_paymentwall_enabled" {{(isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on') ? 'checked' : ''}}>
                                                                        <label class="custom-control-label form-control-label" for="is_paymentwall_enabled">{{__('Enable PaymentWall')}}</label>
                                                                    </div>
                                                                </div>
        
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paymentwall_public_key" class="col-form-label text-dark">{{ __('Public Key')}}</label>
                                                                        <input type="text" name="paymentwall_public_key" id="paymentwall_public_key" class="form-control" value="{{(!isset($admin_payment_setting['paymentwall_public_key']) || is_null($admin_payment_setting['paymentwall_public_key'])) ? '' : $admin_payment_setting['paymentwall_public_key']}}" placeholder="{{ __('Public Key')}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paymentwall_private_key" class="col-form-label text-dark">{{ __('Private Key') }}</label>
                                                                        <input type="text" name="paymentwall_private_key" id="paymentwall_private_key" class="form-control" value="{{(!isset($admin_payment_setting['paymentwall_private_key']) || is_null($admin_payment_setting['paymentwall_private_key'])) ? '' : $admin_payment_setting['paymentwall_private_key']}}" placeholder="{{ __('Private Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <div class="form-group">
                                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            

                        </div>

                        <!--Recaptcha Setting-->
                        <div id="useradd-15" class="card">
                            <div class="card-header">
                                <h5>{{ __('Zoom Setting') }}</h5>
                                <small class="text-muted">{{ __('Edit details about your Human resources') }}</small>
                            </div>

                            <form method="POST" action="{{ route('recaptcha.settings.store') }}" accept-charset="UTF-8">
                            @csrf
                            <div class="card-body">
                                <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                    <div class="form-switch form-switch-right">
                                        <input type="checkbox" class="form-check-input" name="recaptcha_module" id="recaptcha_module" value="yes" {{ env('RECAPTCHA_MODULE') == 'yes' ? 'checked="checked"' : '' }}>
                                        <label class="custom-control-label form-control-label" for="recaptcha_module">
                                            {{ __('Google Recaptcha') }}
                                            <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/" target="_blank" class="text-blue">
                                                <small>({{__('How to Get Google reCaptcha Site and Secret key')}})</small>
                                            </a>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-label text-dark">{{ __('Google Recaptcha Key') }}</label>
                                            <input class="form-control" placeholder="{{ __('Enter Google Recaptcha Key') }}" name="google_recaptcha_key" type="text" value="{{env('NOCAPTCHA_SITEKEY')}}" id="google_recaptcha_key">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-dark">{{ __('Google Recaptcha Secret') }}</label>
                                        <input class="form-control " placeholder="{{ __('Enter Google Recaptcha Secret') }}" name="google_recaptcha_secret" type="text" value="{{env('NOCAPTCHA_SECRET')}}" id="google_recaptcha_secret">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                </div>
                            </div>
                            </form>

                        </div>
                    @endif

                </div>
                
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>


@endsection

