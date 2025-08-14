<div>
    <!--Page Title-->
    <section class="page-title" style="background-image:url({{ asset('template/images/background/4.jpg') }});">
        <div class="auto-container">
            <h1>{{ __('messages.nav.about') }}</h1>
        </div>
    </section>
    <!--End Page Title-->
    
    <!--Page Info-->
    <div class="page-info">
        <div class="auto-container">
            <div class="inner-container clearfix">
                <ul class="bread-crumb pull-left">
                    <li><a href="{{ route('home', ['locale' => app()->getLocale()]) }}">{{ __('messages.nav.home') }}</a></li>
                    <li>{{ __('messages.nav.about') }}</li>
                </ul>
                <div class="text pull-right">{{ __('messages.footer.tagline') }}</div>
            </div>
        </div>
    </div>
    <!--End Page Info-->
    

    <section class="history-page-section">
        <div class="auto-container">
            
            <!--About Section Three-->
            <div class="about-section-three">
                <div class="sec-title">
                    <h2>{{ __('messages.nav.about_us') }}</h2>
                </div>
                <div class="row clearfix">
                    <!--Content Column-->
                    <div class="content-column col-md-8 col-sm-12 col-xs-12">
                        <div class="inner-column">
                            <div class="bold-text">{{ __('messages.about.intro') }}</div>
                            <div class="text">
                                <p>{{ __('messages.about.focus') }}</p>
                                <p>{{ __('messages.about.clients_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <!--Image Column-->
                    <div class="image-column col-md-4 col-sm-12 col-xs-12">
                        <div class="image">
                            <img src="{{ asset('template/images/resource/about-3.jpg') }}" alt="{{ __('messages.home.company.image_alt') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End History Page Section-->

    <!--Company Section-->
    <section class="company-section">
        <div class="auto-container">
            <!--Content Column-->
            <div class="content-column">
                <div class="inner-column">
                    <div class="sec-title">
                        <h2>{{ __('messages.home.company.vision_title') }}</h2>
                        <div class="title">{{ __('messages.company.vision') }}</div>
                        <div class="text">
                            <strong>{{ __('messages.footer.mission_title') }}:</strong> {{ __('messages.company.mission') }}
                            <br><br>
                            {{ __('messages.company.foundation_text') }} {{ __('messages.company.dedication') }}
                        </div>
                        <div class="signature"><img src="template/images/resource/signature.jpg" alt="{{ __('messages.home.company.signature_alt') }}" /></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Company Section-->
    
    <!--Fluid Section One-->
    <section class="fluid-section-one alternate">
        <div class="outer-container clearfix">
            <!--Image Column-->
            <div class="image-column" style="background-image:url({{ asset('template/images/resource/image-1.jpg') }});">
                <figure class="image-box"><img src="{{ asset('template/images/resource/image-1.jpg') }}" alt="{{ __('messages.home.approach.image_alt') }}"></figure>
            </div>
            <!--Content Column-->
            <div class="content-column">
                <div class="inner-column">
                    <div class="sec-title light">
                        <h2>{{ __('messages.home.approach.title') }}</h2>
                    </div>
                    <div class="text">
                        <p>{{ __('messages.home.approach.paragraph1') }}</p>
                        <p>{{ __('messages.home.approach.paragraph2') }}</p>
                    </div>
                    <ul class="icons-list">
                        <li><span class="icon flaticon-target"></span>{!! __('messages.home.approach.dedicated_team') !!}</li>
                        <li><span class="icon flaticon-group"></span>{!! __('messages.home.approach.best_engineers') !!}</li>
                        <li><span class="icon flaticon-technology-2"></span>{!! __('messages.home.approach.support_24_7') !!}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--End Fluid Section One-->
    
    <!--History Section-->
    <section class="history-section">
        <div class="auto-container">
            <div class="row clearfix">
                
                <!--Title Column-->
                <div class="title-column col-md-4 col-sm-12 col-xs-12">
                    <div class="inner-column">
                        <h2>{{ __('messages.company.history.title') }}</h2>
                    </div>
                </div>
                <!--Content Column-->
                <div class="content-column col-md-8 col-sm-12 col-xs-12">
                    <div class="inner-column">
                        <div class="bold-text">{{ __('messages.company.history.intro') }}</div>
                        <div class="text">
                            <p>{{ __('messages.company.history.beginning') }}</p>
                            <p>{{ __('messages.company.history.growth') }} {{ __('messages.company.history.partnerships') }}</p>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="image">
                                    <img src="{{ asset('template/images/resource/history-1.jpg') }}" alt="MainGDream História 1" />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="image">
                                    <img src="{{ asset('template/images/resource/history-2.jpg') }}" alt="MainGDream História 2" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    <!--End History Section-->
    
    <!--Values Section-->
    <section class="history-section">
        <div class="auto-container">
            <div class="row clearfix">
                
                <!--Title Column-->
                <div class="title-column col-md-4 col-sm-12 col-xs-12">
                    <div class="inner-column">
                        <h2>{{ __('messages.company.values.title') }}</h2>
                    </div>
                </div>
                <!--Content Column-->
                <div class="content-column col-md-8 col-sm-12 col-xs-12">
                    <div class="inner-column">
                        <div class="bold-text">{{ __('messages.company.values.intro') }}</div>
                        
                        <div class="row clearfix" style="margin-top: 30px;">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div style="margin-bottom: 20px;">
                                    <h4>{{ __('messages.company.values.commitment') }}</h4>
                                    <p>{{ __('messages.company.values.commitment_desc') }}</p>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <h4>{{ __('messages.company.values.excellence') }}</h4>
                                    <p>{{ __('messages.company.values.excellence_desc') }}</p>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <h4>{{ __('messages.company.values.innovation') }}</h4>
                                    <p>{{ __('messages.company.values.innovation_desc') }}</p>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <h4>{{ __('messages.company.values.ethics') }}</h4>
                                    <p>{{ __('messages.company.values.ethics_desc') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div style="margin-bottom: 20px;">
                                    <h4>{{ __('messages.company.values.safety') }}</h4>
                                    <p>{{ __('messages.company.values.safety_desc') }}</p>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <h4>{{ __('messages.company.values.sustainability') }}</h4>
                                    <p>{{ __('messages.company.values.sustainability_desc') }}</p>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <h4>{{ __('messages.company.values.partnership') }}</h4>
                                    <p>{{ __('messages.company.values.partnership_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    <!--End Values Section-->
    
    <!--CEO Section-->
    <section class="testimonial-section">
        <div class="auto-container">
            <div class="single-item-carousel owl-carousel owl-theme">
                
                <div class="testimonial-block">
                    <div class="inner-box">
                        
                        <div class="upper-box">
                            <div class="image">
                                <img src="{{ asset('template/images/resource/author.jpg') }}" alt="{{ __('messages.home.ceo.image_alt') }}" />
                            </div>
                            <div class="quote-icon">
                                <span class="icon flaticon-left-quote-1"></span>
                            </div>
                        </div>
                        <div class="text">{{ __('messages.home.ceo.message_intro') }} {{ __('messages.home.ceo.message_full') }}</div>
                        <div class="author">CEO MainGDream</div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    <!--End CEO Section-->
    
    <!--Clients Section-->
    <section class="clients-section">
        <div class="auto-container">
            
            <div class="sponsors-outer">
                <!--Sponsors Carousel-->
                <ul class="sponsors-carousel owl-carousel owl-theme">
                    <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/1.jpg') }}" alt="{{ __('messages.home.clients.client1') }}"></a></figure></li>
                    <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/2.jpg') }}" alt="{{ __('messages.home.clients.client2') }}"></a></figure></li>
                    <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/3.jpg') }}" alt="{{ __('messages.home.clients.client3') }}"></a></figure></li>
                    <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/4.jpg') }}" alt="{{ __('messages.home.clients.client4') }}"></a></figure></li>
                    <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/5.jpg') }}" alt="{{ __('messages.home.clients.client5') }}"></a></figure></li>
                    <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/6.jpg') }}" alt="{{ __('messages.home.clients.client6') }}"></a></figure></li>
                </ul>
            </div>
            
        </div>
    </section>
    <!--End Clients Section-->
    
    <!--Team Section-->
    <section class="team-section style-two">
        <div class="auto-container">
            <div class="row clearfix">
                
                <!--Title Column-->
                <div class="title-column col-md-3 col-sm-12 col-xs-12">
                    <h2>{{ __('messages.nav.team') }}</h2>
                </div>
                
                <!--Team Column-->
                <div class="team-column col-md-9 col-sm-12 col-xs-12">
                    <div class="inner-column">
                        <div class="text">{{ __('messages.about.team_desc') }}</div>
                        <div class="row clearfix">
                            
                            <!--Team Block-->
                            <div class="team-block col-md-4 col-sm-6 col-xs-12">
                                <div class="inner-box">
                                    <div class="image">
                                        <a href="#"><img src="{{ asset('template/images/resource/team-1.jpg') }}" alt="Engenheiro MainGDream" /></a>
                                        <div class="overlay-box">
                                            <ul class="social-icons">
                                                <li><a href="#"><span class="fa fa-linkedin-square"></span></a></li>
                                                <li><a href="#"><span class="fa fa-facebook-square"></span></a></li>
                                                <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="lower-box">
                                        <h3><a href="#">Engenheiro Sénior</a></h3>
                                        <div class="title">{{ __('messages.services.engineering') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!--Team Block-->
                            <div class="team-block col-md-4 col-sm-6 col-xs-12">
                                <div class="inner-box">
                                    <div class="image">
                                        <a href="#"><img src="{{ asset('template/images/resource/team-2.jpg') }}" alt="Técnico Manutenção MainGDream" /></a>
                                        <div class="overlay-box">
                                            <ul class="social-icons">
                                                <li><a href="#"><span class="fa fa-linkedin-square"></span></a></li>
                                                <li><a href="#"><span class="fa fa-facebook-square"></span></a></li>
                                                <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="lower-box">
                                        <h3><a href="#">Chefe de Manutenção</a></h3>
                                        <div class="title">{{ __('messages.services.maintenance') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!--Team Block-->
                            <div class="team-block col-md-4 col-sm-6 col-xs-12">
                                <div class="inner-box">
                                    <div class="image">
                                        <a href="#"><img src="{{ asset('template/images/resource/team-3.jpg') }}" alt="Especialista Tecnologia MainGDream" /></a>
                                        <div class="overlay-box">
                                            <ul class="social-icons">
                                                <li><a href="#"><span class="fa fa-linkedin-square"></span></a></li>
                                                <li><a href="#"><span class="fa fa-facebook-square"></span></a></li>
                                                <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="lower-box">
                                        <h3><a href="#">Especialista em Tecnologia</a></h3>
                                        <div class="title">{{ __('messages.services.technology') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    <!--End Team Section-->
</div>