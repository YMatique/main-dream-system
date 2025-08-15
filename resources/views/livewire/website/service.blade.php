<div>
    <!--Page Title-->
    <section class="page-title" style="background-image:url({{ asset('template/images/background/4.jpg') }});">
        <div class="auto-container">
            <h1>{{ __('messages.services.title') }}</h1>
        </div>
    </section>
    <!--End Page Title-->
    
    <!--Page Info-->
    <div class="page-info">
        <div class="auto-container">
            <div class="inner-container clearfix">
                <ul class="bread-crumb pull-left">
                    <li><a href="{{ route('home', ['locale' => app()->getLocale()]) }}">{{ __('messages.nav.home') }}</a></li>
                    <li>{{ __('messages.nav.services') }}</li>
                </ul>
                <div class="text pull-right">{{ __('messages.footer.tagline') }}</div>
            </div>
        </div>
    </div>
    <!--End Page Info-->
    
    <!--Sidebar Page Container-->
    <div class="sidebar-page-container">
        <div class="auto-container">
            <div class="row clearfix">
                
                <!--Content Side-->
                <div class="content-side pull-right col-lg-9 col-md-8 col-sm-12 col-xs-12">
                    <div class="services-single">
                        <div class="inner-box">
                            
                            @if($selectedService === 'engineering')
                                <div class="big-image">
                                    <img src="template/images/resource/service-engineering.jpg" alt="{{ __('messages.services.engineering') }}" />
                                </div>
                                <h2>{{ __('messages.services.engineering') }}</h2>
                                <div class="text">
                                    <p>{{ __('messages.home.services.engineering_desc') }}</p>
                                    <p>{{ __('messages.services.engineering_detail.description') }}</p>
                                    
                                    <div class="two-column">
                                        <div class="row clearfix">
                                            <div class="content-column col-md-6 col-sm-6 col-xs-12">
                                                <div class="inner-column right-padd">
                                                    <h3>{{ __('messages.services.engineering_detail.benefits_title') }}</h3>
                                                    <p>{{ __('messages.services.engineering_detail.benefits_desc') }}</p>
                                                    <ul class="list-style-four">
                                                        <li>{{ __('messages.services.engineering_detail.benefit1') }}</li>
                                                        <li>{{ __('messages.services.engineering_detail.benefit2') }}</li>
                                                        <li>{{ __('messages.services.engineering_detail.benefit3') }}</li>
                                                        <li>{{ __('messages.services.engineering_detail.benefit4') }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="image-column col-md-6 col-sm-6 col-xs-12">
                                                <div class="image">
                                                    <img src="template/images/resource/engineering-detail.jpg" alt="{{ __('messages.services.engineering') }} MainGDream" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($selectedService === 'maintenance')
                                <div class="big-image">
                                    <img src="template/images/resource/service-maintenance.jpg" alt="{{ __('messages.services.maintenance') }}" />
                                </div>
                                <h2>{{ __('messages.services.maintenance_detail.title') }}</h2>
                                <div class="text">
                                    <p>{{ __('messages.home.services.maintenance_desc') }}</p>
                                    <p>{{ __('messages.services.maintenance_detail.desc') }}</p>
                                    
                                    <div class="two-column">
                                        <div class="row clearfix">
                                            <div class="content-column col-md-6 col-sm-6 col-xs-12">
                                                <div class="inner-column right-padd">
                                                    <h3>{{ __('messages.services.maintenance_detail.services_title') }}</h3>
                                                    <p>{{ __('messages.services.maintenance_detail.services_desc') }}</p>
                                                    <ul class="list-style-four">
                                                        <li>{{ __('messages.services.maintenance_detail.service1') }}</li>
                                                        <li>{{ __('messages.services.maintenance_detail.service2') }}</li>
                                                        <li>{{ __('messages.services.maintenance_detail.service3') }}</li>
                                                        <li>{{ __('messages.services.maintenance_detail.service4') }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="image-column col-md-6 col-sm-6 col-xs-12">
                                                <div class="image">
                                                    <img src="template/images/resource/maintenance-detail.jpg" alt="{{ __('messages.services.maintenance') }} MainGDream" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($selectedService === 'technology')
                                <div class="big-image">
                                    <img src="template/images/resource/service-technology.jpg" alt="{{ __('messages.services.technology') }}" />
                                </div>
                                <h2>{{ __('messages.services.technology_detail.title') }}</h2>
                                <div class="text">
                                    <p>{{ __('messages.home.services.technology_desc') }}</p>
                                    <p>{{ __('messages.services.technology_detail.desc') }}</p>
                                    
                                    <div class="two-column">
                                        <div class="row clearfix">
                                            <div class="content-column col-md-6 col-sm-6 col-xs-12">
                                                <div class="inner-column right-padd">
                                                    <h3>{{ __('messages.services.technology_detail.solutions_title') }}</h3>
                                                    <p>{{ __('messages.services.technology_detail.solutions_desc') }}</p>
                                                    <ul class="list-style-four">
                                                        <li>{{ __('messages.services.technology_detail.solution1') }}</li>
                                                        <li>{{ __('messages.services.technology_detail.solution2') }}</li>
                                                        <li>{{ __('messages.services.technology_detail.solution3') }}</li>
                                                        <li>{{ __('messages.services.technology_detail.solution4') }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="image-column col-md-6 col-sm-6 col-xs-12">
                                                <div class="image">
                                                    <img src="template/images/resource/technology-detail.jpg" alt="{{ __('messages.services.technology') }} MainGDream" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($selectedService === 'spare_parts')
                                <div class="big-image">
                                    <img src="template/images/resource/service-spares.jpg" alt="{{ __('messages.services.spare_parts') }}" />
                                </div>
                                <h2>{{ __('messages.services.spare_parts_detail.title') }}</h2>
                                <div class="text">
                                    <p>{{ __('messages.home.services.spare_parts_desc') }}</p>
                                    <p>{{ __('messages.services.spare_parts_detail.desc') }}</p>
                                    
                                    <div class="two-column">
                                        <div class="row clearfix">
                                            <div class="content-column col-md-6 col-sm-6 col-xs-12">
                                                <div class="inner-column right-padd">
                                                    <h3>{{ __('messages.services.spare_parts_detail.training_title') }}</h3>
                                                    <p>{{ __('messages.services.spare_parts_detail.training_desc') }}</p>
                                                    <ul class="list-style-four">
                                                        <li>{{ __('messages.services.spare_parts_detail.feature1') }}</li>
                                                        <li>{{ __('messages.services.spare_parts_detail.feature2') }}</li>
                                                        <li>{{ __('messages.services.spare_parts_detail.feature3') }}</li>
                                                        <li>{{ __('messages.services.spare_parts_detail.feature4') }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="image-column col-md-6 col-sm-6 col-xs-12">
                                                <div class="image">
                                                    <img src="template/images/resource/spares-detail.jpg" alt="{{ __('messages.services.spare_parts') }} MainGDream" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="featured-blocks">
                                <div class="clearfix">
                                    
                                    <!--Featured Block-->
                                    <div class="featured-block col-md-6 col-sm-6 col-xs-12">
                                        <div class="featured-inner">
                                            <div class="content">
                                                <div class="icon-box">
                                                    <span class="icon flaticon-worker"></span>
                                                </div>
                                                <h3><a href="#">{!! __('messages.home.approach.best_engineers') !!}</a></h3>
                                                <div class="featured-text">{{ __('messages.about.team_desc') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--Featured Block-->
                                    <div class="featured-block col-md-6 col-sm-6 col-xs-12">
                                        <div class="featured-inner">
                                            <div class="content">
                                                <div class="icon-box">
                                                    <span class="icon flaticon-clock-1"></span>
                                                </div>
                                                <h3><a href="#">{!! __('messages.home.approach.support_24_7') !!}</a></h3>
                                                <div class="featured-text">{{ __('messages.company.values.commitment_desc') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--Featured Block-->
                                    <div class="featured-block col-md-6 col-sm-6 col-xs-12">
                                        <div class="featured-inner">
                                            <div class="content">
                                                <div class="icon-box">
                                                    <span class="icon flaticon-medal"></span>
                                                </div>
                                                <h3><a href="#">{{ __('messages.company.values.excellence') }}</a></h3>
                                                <div class="featured-text">{{ __('messages.company.values.excellence_desc') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--Featured Block-->
                                    <div class="featured-block col-md-6 col-sm-6 col-xs-12">
                                        <div class="featured-inner">
                                            <div class="content">
                                                <div class="icon-box">
                                                    <span class="icon flaticon-gear"></span>
                                                </div>
                                                <h3><a href="#">{{ __('messages.company.values.innovation') }}</a></h3>
                                                <div class="featured-text">{{ __('messages.company.values.innovation_desc') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <!--Accordian Boxed-->
                            <div class="accordian-boxed">
                                <h3>{{ __('messages.services.faq.title') }}</h3>
                                <!--Accordian Box-->
                                <ul class="accordion-box style-three">
                                    
                                    <!--Block-->
                                    <li class="accordion block">
                                        <div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>{{ __('messages.services.faq.question1') }}</div>
                                        <div class="acc-content">
                                            <div class="content">
                                                <div class="text">{{ __('messages.services.faq.answer1') }}</div>
                                            </div>
                                        </div>
                                    </li>

                                    <!--Block-->
                                    <li class="accordion block">
                                        <div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>{{ __('messages.services.faq.question2') }}</div>
                                        <div class="acc-content">
                                            <div class="content">
                                                <div class="text">{{ __('messages.services.faq.answer2') }}</div>
                                            </div>
                                        </div>
                                    </li>
                                    
                                    <!--Block-->
                                    <li class="accordion block active-block">
                                        <div class="acc-btn active"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>{{ __('messages.services.faq.question3') }}</div>
                                        <div class="acc-content current">
                                            <div class="content">
                                                <div class="text">{{ __('messages.services.faq.answer3') }}</div>
                                            </div>
                                        </div>
                                    </li>
                                    
                                    <!--Block-->
                                    <li class="accordion block">
                                        <div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>{{ __('messages.services.faq.question4') }}</div>
                                        <div class="acc-content">
                                            <div class="content">
                                                <div class="text">{{ __('messages.services.faq.answer4') }}</div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Sidebar Side-->
                <div class="sidebar-side col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <aside class="sidebar">
                        
                        <!--Blog Category Widget-->
                        <div class="sidebar-widget sidebar-blog-category">
                            <ul class="blog-cat">
                                <li class="{{ $selectedService === 'engineering' ? 'active' : '' }}">
                                    <a href="#" wire:click.prevent="selectService('engineering')">{{ __('messages.services.engineering') }}</a>
                                </li>
                                <li class="{{ $selectedService === 'maintenance' ? 'active' : '' }}">
                                    <a href="#" wire:click.prevent="selectService('maintenance')">{{ __('messages.services.maintenance') }}</a>
                                </li>
                                <li class="{{ $selectedService === 'technology' ? 'active' : '' }}">
                                    <a href="#" wire:click.prevent="selectService('technology')">{{ __('messages.services.technology') }}</a>
                                </li>
                                <li class="{{ $selectedService === 'spare_parts' ? 'active' : '' }}">
                                    <a href="#" wire:click.prevent="selectService('spare_parts')">{{ __('messages.services.spare_parts') }}</a>
                                </li>
                            </ul>
                        </div>
                        
                        <!--Brochure-->
                        <div class="sidebar-widget brochure-widget">
                            
                            <div class="brochure-box">
                                <div class="inner">
                                    <span class="icon fa fa-file-pdf-o"></span>
                                    <div class="text">PDF. {{ __('messages.buttons.download') }}</div>
                                </div>
                                <a href="#" class="overlay-link"></a>
                            </div>
                            
                            <div class="brochure-box">
                                <div class="inner">
                                    <span class="icon flaticon-file"></span>
                                    <div class="text">DOC. {{ __('messages.buttons.download') }}</div>
                                </div>
                                <a href="#" class="overlay-link"></a>
                            </div>
                            
                        </div>

                        <!--Contact Widget-->
                        <div class="sidebar-widget contact-info-widget">
                            <div class="sidebar-title style-two">
                                <h2>{{ __('messages.footer.get_in_touch') }}</h2>
                            </div>
                            <div class="inner-box">
                                <ul>
                                    <li><span class="icon fa fa-phone"></span>{{ __('messages.contact.phone') }}</li>
                                    <li><span class="icon fa fa-send"></span>{{ __('messages.contact.email') }}</li>
                                </ul>
                            </div>
                        </div>
                        
                    </aside>
                </div>
                
            </div>
        </div>
    </div>
    
</div>