<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'MainGDream') - {{ __('messages.tagline') }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', __('messages.meta_description'))">
    <meta name="keywords" content="@yield('keywords', __('messages.meta_keywords'))">
    
    <!-- Template CSS -->
    <link href="{{ asset('template/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/revolution/css/settings.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/revolution/css/layers.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/revolution/css/navigation.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/responsive.css') }}" rel="stylesheet">
    
    <!-- Color Theme -->
    <link id="theme-color-file" href="{{ asset('template/css/color-themes/default-theme.css') }}" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('template/images/favicon.png') }}" type="image/x-icon">
    
    <!-- Responsive -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    
    @livewireStyles
    @stack('styles')
</head>

<body>
<div class="page-wrapper">
    <!-- Preloader -->
    <div class="preloader"></div>
    
    <!-- Header -->
    @include('layouts.header')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('layouts.footer')
</div>

<!-- Scroll to top -->
<div class="scroll-to-top scroll-to-target" data-target="html">
    <span class="icon fa fa-angle-double-up"></span>
</div>

<!-- Template JS -->
<script src="{{ asset('template/js/jquery.js') }}"></script>
<script src="{{ asset('template/plugins/revolution/js/jquery.themepunch.revolution.min.js') }}"></script>
<script src="{{ asset('template/plugins/revolution/js/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{ asset('template/js/main-slider-script.js') }}"></script>
<script src="{{ asset('template/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('template/js/script.js') }}"></script>

@livewireScripts
@stack('scripts')
</body>
</html>