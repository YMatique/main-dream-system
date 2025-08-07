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
                    @livewire('language-switcher')
                    
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
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/logo-maingdream.png') }}" alt="MainGDream">
                        </a>
                    </div>
                </div>
                
                <!-- Navigation -->
                <div class="nav-outer clearfix">
                    <nav class="main-menu">
                        <div class="navbar-collapse collapse clearfix">
                            <ul class="navigation clearfix">
                                <li class="{{ request()->routeIs('home') ? 'current' : '' }}">
                                    <a href="{{ route('home') }}">{{ __('messages.nav.home') }}</a>
                                </li>
                                <li class="dropdown {{ request()->routeIs('about*') ? 'current' : '' }}">
                                    <a href="{{ route('about') }}">{{ __('messages.nav.about') }}</a>
                                    <ul>
                                        <li><a href="{{ route('about') }}">{{ __('messages.nav.about_us') }}</a></li>
                                        <li><a href="{{ route('mission') }}">{{ __('messages.nav.mission') }}</a></li>
                                        <li><a href="{{ route('team') }}">{{ __('messages.nav.team') }}</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown {{ request()->routeIs('services*') ? 'current' : '' }}">
                                    <a href="{{ route('services') }}">{{ __('messages.nav.services') }}</a>
                                    <ul>
                                        <li><a href="{{ route('services') }}">{{ __('messages.nav.all_services') }}</a></li>
                                        <li><a href="{{ route('services.engineering') }}">{{ __('messages.nav.engineering') }}</a></li>
                                        <li><a href="{{ route('services.maintenance') }}">{{ __('messages.nav.maintenance') }}</a></li>
                                        <li><a href="{{ route('services.technology') }}">{{ __('messages.nav.technology') }}</a></li>
                                        <li><a href="{{ route('services.spare_parts') }}">{{ __('messages.nav.spare_parts') }}</a></li>
                                    </ul>
                                </li>
                                <li class="{{ request()->routeIs('projects*') ? 'current' : '' }}">
                                    <a href="{{ route('projects') }}">{{ __('messages.nav.projects') }}</a>
                                </li>
                                <li class="{{ request()->routeIs('contact') ? 'current' : '' }}">
                                    <a href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>