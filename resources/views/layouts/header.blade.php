<header class="main-header">

    <!-- Header Top -->
    <div class="header-top">
        <div class="auto-container">
            <div class="inner-container clearfix">

                <!--Top Left-->
                <div class="top-left">
                    <ul class="links clearfix">
                        <li><a href="#">{{ __('messages.contact.phone') }}</a></li>
                        <li><a href="#"><span class="icon flaticon-note-1"></span>{{ __('messages.contact.email') }}</a></li>
                        <li><a href="#"><span class="icon flaticon-pin"></span>{{ __('messages.contact.address') }}</a></li>
                    </ul>
                </div>

                <!--Top Right-->
                <div class="top-right clearfix">
                    @livewire('website.language-switcher')
                    <!--social-icon-->
                    {{-- <div class="social-icon">
                        	<ul class="clearfix">
                            	<li><a href="#"><span class="fa fa-facebook"></span></a></li>
                                <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                                <li><a href="#"><span class="fa fa-google-plus"></span></a></li>
                                <li><a href="#"><span class="fa fa-linkedin"></span></a></li>
                            </ul>
                        </div> --}}
                </div>

            </div>

        </div>
    </div>
    <!-- Header Top End -->

    <!-- Main Box -->
    <div class="main-box">
        <div class="auto-container">
            <div class="outer-container clearfix">
                <!--Logo Box-->
                <div class="logo-box">
                    <div class="logo"><a href="{{ route('home', ['locale' => app()->getLocale()]) }}"><img
                                src="{{ asset('template/images/logo-letters.png') }}" alt="MainGDream"></a></div>
                </div>

                <!--Nav Outer-->
                <div class="nav-outer clearfix">

                    <!-- Main Menu -->
                    <nav class="main-menu">
                        <div class="navbar-header">
                            <!-- Toggle Button -->
                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>

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
                    <!-- Main Menu End-->
                    {{-- <div class="outer-box">
                            <!--Search Box-->
                            <div class="search-box-outer">
                                <div class="dropdown">
                                    <button class="search-box-btn dropdown-toggle" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-search"></span></button>
                                    <ul class="dropdown-menu pull-right search-panel" aria-labelledby="dropdownMenu3">
                                        <li class="panel-outer">
                                            <div class="form-container">
                                                <form method="post" action="blog.html">
                                                    <div class="form-group">
                                                        <input type="search" name="field-name" value="" placeholder="Search Here" required>
                                                        <button type="submit" class="search-btn"><span class="fa fa-search"></span></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}
                </div>
                <!--Nav Outer End-->

            </div>
        </div>
    </div>

    <!--Sticky Header-->
    <div class="sticky-header">
        <div class="auto-container">
            <div class="sticky-inner-container clearfix">
                <!--Logo-->
                <div class="logo pull-left">
                    <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="img-responsive"><img
                            src="{{ asset('template/images/logo-w-letters.png') }}" alt="" title=""></a>
                </div>

                <!--Right Col-->
                <div class="right-col pull-right">
                    <!-- Main Menu -->
                    <nav class="main-menu">
                        <div class="navbar-header">
                            <!-- Toggle Button -->
                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>

                        <div class="navbar-collapse collapse clearfix">
                            <ul class="navigation clearfix">
                                <li class="dropdown">
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
                    <!-- Main Menu End-->

                    <!--Outer Box-->
                    {{-- <div class="outer-box">
                            <!--Search Box-->
                            <div class="search-box-outer">
                                <div class="dropdown">
                                    <button class="search-box-btn dropdown-toggle" type="button" id="dropdownMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-search"></span></button>
                                    <ul class="dropdown-menu pull-right search-panel" aria-labelledby="dropdownMenu4">
                                        <li class="panel-outer">
                                            <div class="form-container">
                                                <form method="post" action="blog.html">
                                                    <div class="form-group">
                                                        <input type="search" name="field-name" value="" placeholder="Search Here" required>
                                                        <button type="submit" class="search-btn"><span class="fa fa-search"></span></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}

                </div>

            </div>
        </div>
    </div>
    <!--End Sticky Header-->

</header>
<!--End Main Header -->
