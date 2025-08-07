<!--Main Footer-->
<footer class="main-footer">
    <div class="auto-container">
        <!--Widgets Section-->
        <div class="widgets-section">
            <div class="row clearfix">
                
                <!--big column-->
                <div class="big-column col-md-6 col-sm-12 col-xs-12">
                    <div class="row clearfix">
                    
                        <!--Footer Column-->
                        <div class="footer-column col-md-7 col-sm-6 col-xs-12">
                            <div class="footer-widget logo-widget">
                                <div class="logo">
                                    <a href="{{ route('home') }}">
                                        <img src="{{ asset('images/footer-logo-maingdream.png') }}" alt="MainGDream" />
                                    </a>
                                </div>
                                <div class="text">{{ __('messages.footer.description') }}</div>
                                
                                <!-- Mission Statement -->
                                <div class="mission-text">
                                    <strong>{{ __('messages.footer.mission_title') }}:</strong><br>
                                    <small>{{ __('messages.company.mission') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <!--Footer Column-->
                        <div class="footer-column col-md-5 col-sm-6 col-xs-12">
                            <div class="footer-widget links-widget">
                                <h2>{{ __('messages.footer.quick_links') }}</h2>
                                <div class="widget-content">
                                    <ul class="list">
                                        <li><a href="{{ route('home') }}">{{ __('messages.nav.home') }}</a></li>
                                        <li><a href="{{ route('about') }}">{{ __('messages.nav.about') }}</a></li>
                                        <li><a href="{{ route('services') }}">{{ __('messages.nav.services') }}</a></li>
                                        <li><a href="{{ route('projects') }}">{{ __('messages.nav.projects') }}</a></li>
                                        <li><a href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!--big column-->
                <div class="big-column col-md-6 col-sm-12 col-xs-12">
                    <div class="row clearfix">
                    
                        <!--Footer Column-->
                        <div class="footer-column col-md-6 col-sm-6 col-xs-12">
                            <div class="footer-widget links-widget">
                                <h2>{{ __('messages.footer.our_services') }}</h2>
                                <div class="widget-content">
                                    <ul class="list">
                                        <li><a href="{{ route('services.engineering') }}">{{ __('messages.nav.engineering') }}</a></li>
                                        <li><a href="{{ route('services.maintenance') }}">{{ __('messages.nav.maintenance') }}</a></li>
                                        <li><a href="{{ route('services.technology') }}">{{ __('messages.nav.technology') }}</a></li>
                                        <li><a href="{{ route('services.spare_parts') }}">{{ __('messages.nav.spare_parts') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!--Footer Column-->
                        <div class="footer-column col-md-6 col-sm-6 col-xs-12">
                            <div class="footer-widget info-widget">
                                <h2>{{ __('messages.footer.get_in_touch') }}</h2>
                                <div class="widget-content">
                                    <div class="contact-info">
                                        <!-- Phone -->
                                        <div class="info-item">
                                            <span class="icon flaticon-phone-call"></span>
                                            <div class="content">
                                                <div class="number">
                                                    <a href="tel:{{ config('app.phone') }}">{{ config('app.phone') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Email -->
                                        <div class="info-item">
                                            <span class="icon flaticon-note-1"></span>
                                            <div class="content">
                                                <a href="mailto:{{ config('app.email') }}">{{ config('app.email') }}</a>
                                            </div>
                                        </div>
                                        
                                        <!-- Address -->
                                        <div class="info-item">
                                            <span class="icon flaticon-pin"></span>
                                            <div class="content">
                                                {{ __('messages.footer.full_address') }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Social Media -->
                                    <div class="social-links">
                                        <ul class="social-icon-one">
                                            <li><a href="{{ config('app.social.facebook') }}" target="_blank"><span class="fa fa-facebook"></span></a></li>
                                            <li><a href="{{ config('app.social.linkedin') }}" target="_blank"><span class="fa fa-linkedin"></span></a></li>
                                            <li><a href="{{ config('app.social.twitter') }}" target="_blank"><span class="fa fa-twitter"></span></a></li>
                                            <li><a href="{{ config('app.social.instagram') }}" target="_blank"><span class="fa fa-instagram"></span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
        
        <!--Footer Bottom-->
        <div class="footer-bottom">
            <div class="clearfix">
                <div class="pull-left">
                    <div class="copyright">
                        &copy; {{ date('Y') }} <a href="{{ route('home') }}">MainGDream</a>. {{ __('messages.footer.copyright') }}
                    </div>
                </div>
                <div class="pull-right">
                    <div class="created">
                        {{ __('messages.footer.tagline') }}
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</footer>

<!-- Newsletter Popup (Optional) -->
{{-- @livewire('newsletter-popup') --}}