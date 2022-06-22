@php
    $currantLang = $users->currentLanguage();
    $languages=\App\Models\Utility::languages();
    $footer_text=isset(\App\Models\Utility::settings()['footer_text']) ? \App\Models\Utility::settings()['footer_text'] : '';
    $header_text = (!empty(\App\Models\Utility::settings()['company_name'])) ? \App\Models\Utility::settings()['company_name'] : env('APP_NAME');
	$SITE_RTL = Cookie::get('SITE_RTL');
    
    if($SITE_RTL != 'on'){
        $SITE_RTL = 'off';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{$SITE_RTL == 'on'?'rtl':''}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.admin.head')
<body class="application application-offset">

<div class="container">
<div class="main-content position-relative">
    <nav class="navbar navbar-main navbar-expand-lg navbar-border n-top-header">
    <div class="container align-items-lg-center">
       <h4>{{$header_text}}</h4>
    </div>
    </nav>
    <div class="page-content">
        @include('partials.admin.content')
    </div>
</div>
</div>
@include('partials.admin.footer')
</body>
</html>