@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_favicon=Utility::getValByName('company_favicon');
    $setting = App\Models\Utility::colorset();
    $mode_setting = \App\Models\Utility::mode_layout();
    $color = 'theme-3';
    if (!empty($setting['color'])) {
        $color = $setting['color'];
    }


 

@endphp

<head>
  <title> @yield('page-title') - {{(Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'CRMGo SaaS')}}</title>

    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"
    />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Dashboard Template Description" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="Rajodiya Infotech" />

    <!-- Favicon icon -->
    <link rel="icon" href="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" type="image" sizes="16x16">

    {{-- <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon" /> --}}
    <!--Calendar --> 
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css')}}">
    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
   
    @stack('pre-purpose-css-page')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    <!-- vendor css -->
    @if (env('SITE_RTL') == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
       
    @else
        @if( isset($mode_setting['cust_darklayout']) && $mode_setting['cust_darklayout'] == 'on')
            <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
        @else
            <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
        @endif
    @endif

    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('public/custom_assets/css/custom.css') }}">

    {{-- <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css')}}"> --}}
    <!-- date --> 
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}">

    <!-- Dragulla -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">

    <!--bootstrap switch-->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">

    <!-- fileupload-custom css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">

    <meta name="url" content="{{ url('').'/'.config('chatify.routes.prefix') }}" data-user="{{ Auth::user()->id }}">
   
    <link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/>

@stack('css-page')
</head>


