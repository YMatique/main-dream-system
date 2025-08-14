<div>
    <!--Page Title-->
    <section class="page-title" style="background-image:url({{ asset('template/images/background/4.jpg') }});">
        <div class="auto-container">
            <h1>{{ __('messages.projects.detail.title') }}</h1>
        </div>
    </section>
    <!--End Page Title-->
    
    <!--Page Info-->
    <div class="page-info">
        <div class="auto-container">
            <div class="inner-container clearfix">
                <ul class="bread-crumb pull-left">
                    <li><a href="{{ route('home', ['locale' => app()->getLocale()]) }}">{{ __('messages.nav.home') }}</a></li>
                    <li><a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">{{ __('messages.nav.projects') }}</a></li>
                    <li>{{ __('messages.projects.automation.title') }}</li>
                </ul>
                <div class="text pull-right">{{ __('messages.footer.tagline') }}</div>
            </div>
        </div>
    </div>
    <!--End Page Info-->
    
    <!--Project Detail Section-->
    <section class="project-detail-section">
        
        <!--Description Section-->
        <div class="description-section">
            <div class="auto-container">
                <div class="row clearfix">
                    <!--Image Column-->
                    <div class="image-column col-md-8 col-sm-12 col-xs-12">
                        <div class="image">
                            <img src="{{ asset('template/images/resource/automation-system.jpg') }}" alt="{{ __('messages.projects.automation.image_alt') }}" />
                        </div>
                    </div>
                    <!--Info Column-->
                    <div class="info-column col-md-4 col-sm-12 col-xs-12">
                        <div class="inner-column">
                            <ul>
                                <li><span>{{ __('messages.projects.detail.client') }} :</span>{{ __('messages.projects.automation.client') }}</li>
                                <li><span>{{ __('messages.projects.detail.category') }} :</span>{{ __('messages.projects.automation.category') }}</li>
                                <li><span>{{ __('messages.projects.detail.date') }} :</span>{{ __('messages.projects.automation.date') }}</li>
                                <li><span>{{ __('messages.projects.detail.status') }} :</span>{{ __('messages.projects.automation.status') }}</li>
                                <li><span>{{ __('messages.projects.detail.duration') }} :</span>{{ __('messages.projects.automation.duration') }}</li>
                                <li><span>{{ __('messages.projects.detail.technologies') }} :</span>{{ __('messages.projects.automation.technologies') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <h2>{{ __('messages.projects.detail.description_title') }}</h2>
                <div class="text">
                    <p>{{ __('messages.projects.automation.description_1') }}</p>
                    <p>{{ __('messages.projects.automation.description_2') }}</p>
                </div>
            </div>
        </div>
            
        <!--We Did Section-->
        <div class="we-did-section">
            <div class="auto-container">
                <div class="row clearfix">
                    
                    <!--Content Column-->
                    <div class="content-column col-md-8 col-sm-12 col-xs-12">
                        <div class="inner-column">
                            <h2>{{ __('messages.projects.detail.what_we_did') }}</h2>
                            <div class="text">{{ __('messages.projects.automation.implementation_desc') }}</div>
                            <ul class="list-style-two">
                                <li><span>{{ __('messages.projects.automation.feature_1_title') }}</span> {{ __('messages.projects.automation.feature_1_desc') }}</li>
                                <li><span>{{ __('messages.projects.automation.feature_2_title') }}</span> {{ __('messages.projects.automation.feature_2_desc') }}</li>
                                <li><span>{{ __('messages.projects.automation.feature_3_title') }}</span> {{ __('messages.projects.automation.feature_3_desc') }}</li>
                            </ul>
                        </div>
                    </div>
                    <!--Image Column-->
                    <div class="image-column col-md-4 col-sm-12 col-xs-12">
                        <div class="image">
                            <img src="{{ asset('template/images/resource/automation-dashboard.jpg') }}" alt="{{ __('messages.projects.automation.dashboard_alt') }}" />
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!--End We Did Section-->
        
        <!--Technical Details Section-->
        <section class="technical-section" style="background: #f8f9fa; padding: 60px 0;">
            <div class="auto-container">
                <h2>{{ __('messages.projects.detail.technical_details') }}</h2>
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h4>{{ __('messages.projects.automation.frontend_title') }}</h4>
                        <div class="text">{{ __('messages.projects.automation.frontend_desc') }}</div>
                        <ul class="list-style-three">
                            <li>{{ __('messages.projects.automation.frontend_tech_1') }}</li>
                            <li>{{ __('messages.projects.automation.frontend_tech_2') }}</li>
                            <li>{{ __('messages.projects.automation.frontend_tech_3') }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h4>{{ __('messages.projects.automation.backend_title') }}</h4>
                        <div class="text">{{ __('messages.projects.automation.backend_desc') }}</div>
                        <ul class="list-style-three">
                            <li>{{ __('messages.projects.automation.backend_tech_1') }}</li>
                            <li>{{ __('messages.projects.automation.backend_tech_2') }}</li>
                            <li>{{ __('messages.projects.automation.backend_tech_3') }}</li>
                        </ul>
                    </div>
                </div>
                
                <!--Key Features Section-->
                <div class="features-section" style="margin-top: 50px;">
                    <h3>{{ __('messages.projects.automation.key_features_title') }}</h3>
                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="feature-block" style="padding: 20px; background: white; margin-bottom: 20px; border-radius: 8px;">
                                <h5 style="color: #0066cc; margin-bottom: 15px;">{{ __('messages.projects.automation.feature_block_1_title') }}</h5>
                                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                                    <li>{{ __('messages.projects.automation.feature_block_1_item_1') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_1_item_2') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_1_item_3') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_1_item_4') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_1_item_5') }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="feature-block" style="padding: 20px; background: white; margin-bottom: 20px; border-radius: 8px;">
                                <h5 style="color: #0066cc; margin-bottom: 15px;">{{ __('messages.projects.automation.feature_block_2_title') }}</h5>
                                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                                    <li>{{ __('messages.projects.automation.feature_block_2_item_1') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_2_item_2') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_2_item_3') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_2_item_4') }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="feature-block" style="padding: 20px; background: white; margin-bottom: 20px; border-radius: 8px;">
                                <h5 style="color: #0066cc; margin-bottom: 15px;">{{ __('messages.projects.automation.feature_block_3_title') }}</h5>
                                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                                    <li>{{ __('messages.projects.automation.feature_block_3_item_1') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_3_item_2') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_3_item_3') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_3_item_4') }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="feature-block" style="padding: 20px; background: white; margin-bottom: 20px; border-radius: 8px;">
                                <h5 style="color: #0066cc; margin-bottom: 15px;">{{ __('messages.projects.automation.feature_block_4_title') }}</h5>
                                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                                    <li>{{ __('messages.projects.automation.feature_block_4_item_1') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_4_item_2') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_4_item_3') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_4_item_4') }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="feature-block" style="padding: 20px; background: white; margin-bottom: 20px; border-radius: 8px;">
                                <h5 style="color: #0066cc; margin-bottom: 15px;">{{ __('messages.projects.automation.feature_block_5_title') }}</h5>
                                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                                    <li>{{ __('messages.projects.automation.feature_block_5_item_1') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_5_item_2') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_5_item_3') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_5_item_4') }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="feature-block" style="padding: 20px; background: white; margin-bottom: 20px; border-radius: 8px;">
                                <h5 style="color: #0066cc; margin-bottom: 15px;">{{ __('messages.projects.automation.feature_block_6_title') }}</h5>
                                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                                    <li>{{ __('messages.projects.automation.feature_block_6_item_1') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_6_item_2') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_6_item_3') }}</li>
                                    <li>{{ __('messages.projects.automation.feature_block_6_item_4') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Technical Details Section-->
        
        <!--Result Section-->
        <section class="result-section">
            <div class="auto-container">
                <h2>{{ __('messages.projects.detail.final_results') }}</h2>
                <div class="text">
                    <p>{{ __('messages.projects.automation.results_1') }}</p>
                    <p>{{ __('messages.projects.automation.results_2') }}</p>
                </div>
                <ul class="list-style-three">
                    <li>{{ __('messages.projects.automation.benefit_1') }}</li>
                    <li>{{ __('messages.projects.automation.benefit_2') }}</li>
                    <li>{{ __('messages.projects.automation.benefit_3') }}</li>
                    <li>{{ __('messages.projects.automation.benefit_4') }}</li>
                </ul>
                
                <div class="stats-section" style="margin-top: 50px;">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="stat-block text-center">
                                <div class="count" style="font-size: 48px; font-weight: bold; color: #0066cc;">{{ __('messages.projects.automation.stat_efficiency') }}</div>
                                <div class="text">{{ __('messages.projects.automation.stat_efficiency_label') }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="stat-block text-center">
                                <div class="count" style="font-size: 48px; font-weight: bold; color: #0066cc;">{{ __('messages.projects.automation.stat_time') }}</div>
                                <div class="text">{{ __('messages.projects.automation.stat_time_label') }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="stat-block text-center">
                                <div class="count" style="font-size: 48px; font-weight: bold; color: #0066cc;">{{ __('messages.projects.automation.stat_errors') }}</div>
                                <div class="text">{{ __('messages.projects.automation.stat_errors_label') }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="stat-block text-center">
                                <div class="count" style="font-size: 48px; font-weight: bold; color: #0066cc;">{{ __('messages.projects.automation.stat_satisfaction') }}</div>
                                <div class="text">{{ __('messages.projects.automation.stat_satisfaction_label') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Result Section-->
        
        <!--Next Project Section-->
        <section class="next-project-section" style="background: #003366; color: white; padding: 40px 0;">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <h3>{{ __('messages.projects.detail.interested_title') }}</h3>
                        <div class="text">{{ __('messages.projects.detail.interested_desc') }}</div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 text-right">
                        <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="theme-btn btn-style-four">{{ __('messages.buttons.contact_us') }}</a>
                    </div>
                </div>
            </div>
        </section>
        <!--End Next Project Section-->
        
    </section>
    <!--End Project Detail Section-->
</div>