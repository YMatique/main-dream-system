<header class="main-header header-style-two">
    <!-- Header Top -->
    <div class="header-top">
        <div class="auto-container">
            <div class="inner-container clearfix">
                <!-- Top Left -->
                <div class="top-left">
                    <ul class="links clearfix">
                        <li><a href="tel:{{ config('app.phone') }}">{{ config('app.phone') }}</a></li>
                        <li><a href="mailto:{{ config('app.email') }}">
                                <span class="icon flaticon-note-1"></span>{{ config('app.email') }}
                            </a></li>
                        <li><a href="#">
                                <span class="icon flaticon-pin"></span>{{ __('messages.address') }}
                            </a></li>
                    </ul>
                </div>

                <!-- Top Right -->
                <div class="top-right clearfix">
                    <!-- Language Switcher -->
                    @livewire('website.language-switcher')

                    <!-- Social Icons -->
                    <div class="social-icon">
                        <ul class="clearfix">
                            <li><a href="#"><span class="fa fa-facebook"></span></a></li>
                            <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                            <li><a href="#"><span class="fa fa-linkedin"></span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <div class="main-box">
        <div class="auto-container">
            <div class="outer-container clearfix">
                <!-- Logo -->
                <div class="logo-box">
                    <div class="logo">
                        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ asset('images/logo-maingdream.png') }}" alt="MainGDream">
                        </a>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="nav-outer clearfix">
                    <!-- resources/views/layouts/header.blade.php -->
                    <nav class="main-menu">
                        <div class="navbar-collapse collapse clearfix">
                            <ul class="navigation clearfix">
                                <li class="{{ request()->routeIs('home') ? 'current' : '' }}">
                                    <a href="{{ route('home', ['locale' => app()->getLocale()]) }}">
                                        {{ __('messages.nav.home') }}
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('about*') ? 'current' : '' }}">
                                    <a href="{{ route('about', ['locale' => app()->getLocale()]) }}">
                                        {{ __('messages.nav.about') }}
                                    </a>
                                </li>
                                <li class=" {{ request()->routeIs('services*') ? 'current' : '' }}">
                                    <a href="{{ route('services', ['locale' => app()->getLocale()]) }}">
                                        {{ __('messages.nav.services') }}
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('projects*') ? 'current' : '' }}">
                                    <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">
                                        {{ __('messages.nav.projects') }}
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('contact') ? 'current' : '' }}">
                                    <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}">
                                        {{ __('messages.nav.contact') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
