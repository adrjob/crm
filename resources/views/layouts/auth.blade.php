@php
$logos = asset(Storage::url('uploads/logo/'));
$company_favicon = Utility::getValByName('favicon');
$setting = App\Models\Utility::colorset();
$color = 'theme-3';
if (!empty($setting['color'])) {
    $color = $setting['color'];
}

$logo = Utility::get_superadmin_logo();
@endphp

<!DOCTYPE html>

<html dir="{{ env('SITE_RTL') == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CRMGo SaaS - Projects, Accounting, Leads, Deals & HRM Tool">
    <meta name="author" content="Rajodiya Infotech">

    <link rel="icon" href="{{ asset(Storage::url('uploads/logo/favicon.png')) }}" type="image" sizes="16x16">
    <title>
        {{ Utility::getValByName('header_text') ? Utility::getValByName('header_text') : config('app.name', 'CRMGo') }}
    </title>
    <link rel="icon"
        href="{{ $logos . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
        type="image/x-icon" />
    {{-- <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon" /> --}}

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!-- vendor css -->
    @if (env('SITE_RTL') == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
    @else
        @if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
            <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
        @else
            <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
        @endif
    @endif

    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}" id="main-style-link"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('public/custom_assets/css/custom.css') }}">

    <style type="text/css">
        img.navbar-brand-img {
            width: 245px;
            height: 61px;
        }

    </style>


</head>

<body class=" {{ $color }} ">

    <div class="auth-wrapper auth-v3">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">
            <nav class="navbar navbar-expand-md navbar-light default">
                <div class="container-fluid pe-2">
                    <a class="navbar-brand" href="#">
                        <img src="{{ asset(Storage::url('uploads/logo/' . $logo)) }}" class="auth-logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                        <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active"
                                    href="{{ !empty(Utility::getValByName('footer_value_1')) ? Utility::getValByName('footer_value_1') : Utility::getValByName('footer_value_1') }}">{{ !empty(Utility::getValByName('footer_link_1')) ? Utility::getValByName('footer_link_1') : Utility::getValByName('footer_link_1') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ !empty(Utility::getValByName('footer_value_2')) ? Utility::getValByName('footer_value_2') : Utility::getValByName('footer_value_2') }}">{{ !empty(Utility::getValByName('footer_link_2')) ? Utility::getValByName('footer_link_2') : Utility::getValByName('footer_link_2') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ !empty(Utility::getValByName('footer_value_3')) ? Utility::getValByName('footer_value_3') : Utility::getValByName('footer_value_3') }}">{{ !empty(Utility::getValByName('footer_link_3')) ? Utility::getValByName('footer_link_3') : Utility::getValByName('footer_link_3') }}</a>
                            </li>
                            <li class="nav-item ">
                                <select name="language" id="language" class=" btn btn-primary my-1 me-2 lang-dropdown option"
                                    onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                    @foreach (Utility::languages() as $language)
                                        <option @if ($lang == $language) selected @endif
                                            value="{{ route('login', $language) }}">{{ Str::upper($language) }}
                                        </option>
                                    @endforeach
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            @yield('content')
        </div>
    </div>

    @stack('custom-scripts')


    <script src="{{ asset('assets/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

    <script>
        feather.replace();
    </script>
    <div class="pct-customizer">
        <script>
            feather.replace();
            var pctoggle = document.querySelector("#pct-toggler");
            if (pctoggle) {
                pctoggle.addEventListener("click", function() {
                    if (
                        !document.querySelector(".pct-customizer").classList.contains("active")
                    ) {
                        document.querySelector(".pct-customizer").classList.add("active");
                    } else {
                        document.querySelector(".pct-customizer").classList.remove("active");
                    }
                });
            }

            var themescolors = document.querySelectorAll(".themes-color > a");
            for (var h = 0; h < themescolors.length; h++) {
                var c = themescolors[h];

                c.addEventListener("click", function(event) {
                    var targetElement = event.target;
                    if (targetElement.tagName == "SPAN") {
                        targetElement = targetElement.parentNode;
                    }
                    var temp = targetElement.getAttribute("data-value");
                    removeClassByPrefix(document.querySelector("body"), "theme-");
                    document.querySelector("body").classList.add(temp);
                });
            }

            function removeClassByPrefix(node, prefix) {
                for (let i = 0; i < node.classList.length; i++) {
                    let value = node.classList[i];
                    if (value.startsWith(prefix)) {
                        node.classList.remove(value);
                    }
                }
            }
        </script>
</body>

</html>
