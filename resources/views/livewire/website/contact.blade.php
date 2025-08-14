<div>
    <!--Page Title-->
    <section class="page-title" style="background-image:url(template/images/background/4.jpg);">
        <div class="auto-container">
            <h1>{{ __('messages.nav.contact') }}</h1>
        </div>
    </section>
    <!--End Page Title-->
    
    <!--Page Info-->
    <div class="page-info">
        <div class="auto-container">
            <div class="inner-container clearfix">
                <ul class="bread-crumb pull-left">
                    <li><a href="{{ route('home', ['locale' => app()->getLocale()]) }}">{{ __('messages.nav.home') }}</a></li>
                    <li>{{ __('messages.nav.contact') }}</li>
                </ul>
                <div class="text pull-right">{{ __('messages.footer.tagline') }}</div>
            </div>
        </div>
    </div>
    <!--End Page Info-->
    
    <!--Map Section-->
    <section class="map-section">
        <!--Map Outer-->
        <div class="map-outer">
            <!--Map Canvas-->
            <div class="map-canvas"
                data-zoom="12"
                data-lat="-19.8447"
                data-lng="34.8397"
                data-type="roadmap"
                data-hue="#0066cc"
                data-title="MainGDream"
                data-icon-path="template/images/icons/map-marker.png"
                data-content="{{ __('messages.contact.full_address') }}<br><a href='mailto:{{ __('messages.contact.email') }}'>{{ __('messages.contact.email') }}</a>">
            </div>
        </div>
    </section>
    <!--End Map Section-->
    
    <!--Contact Section-->
    <section class="contact-section">
        <div class="auto-container">
            <div class="contact-title">
                <h2>{{ __('messages.contact_page.form_title') }}</h2>
                <div class="text">{{ __('messages.contact_page.form_subtitle') }}</div>
            </div>
            <div class="row clearfix">
                
                <!--Form Column-->
                <div class="form-column col-md-8 col-sm-12 col-xs-12">
                    <div class="inner-column">
                        <!--Contact Form-->
                        <div class="contact-form">
                            
                            @if (session()->has('success'))
                                <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            @if (session()->has('error'))
                                <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                                    {{ session('error') }}
                                </div>
                            @endif
                            
                            <form wire:submit.prevent="submitForm">
                                <div class="row clearfix">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" wire:model="name" placeholder="{{ __('messages.contact_form.name') }}" required>
                                        @error('name') 
                                            <span class="error" style="color: #dc3545; font-size: 12px;">{{ $message }}</span> 
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <input type="email" wire:model="email" placeholder="{{ __('messages.contact_form.email') }}" required>
                                        @error('email') 
                                            <span class="error" style="color: #dc3545; font-size: 12px;">{{ $message }}</span> 
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <input type="tel" wire:model="phone" placeholder="{{ __('messages.contact_form.phone') }}" required>
                                        @error('phone') 
                                            <span class="error" style="color: #dc3545; font-size: 12px;">{{ $message }}</span> 
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <select wire:model="service_type" required>
                                            <option value="">{{ __('messages.contact_form.service_type') }}</option>
                                            <option value="engineering">{{ __('messages.contact_form.service_options.engineering') }}</option>
                                            <option value="maintenance">{{ __('messages.contact_form.service_options.maintenance') }}</option>
                                            <option value="technology">{{ __('messages.contact_form.service_options.technology') }}</option>
                                            <option value="spare_parts">{{ __('messages.contact_form.service_options.spare_parts') }}</option>
                                            <option value="consultation">{{ __('messages.contact_form.service_options.consultation') }}</option>
                                            <option value="other">{{ __('messages.contact_form.service_options.other') }}</option>
                                        </select>
                                        @error('service_type') 
                                            <span class="error" style="color: #dc3545; font-size: 12px;">{{ $message }}</span> 
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" wire:model="subject" placeholder="{{ __('messages.contact_form.subject') }}" required>
                                        @error('subject') 
                                            <span class="error" style="color: #dc3545; font-size: 12px;">{{ $message }}</span> 
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <textarea wire:model="message" placeholder="{{ __('messages.contact_form.message') }}" rows="5" required></textarea>
                                        @error('message') 
                                            <span class="error" style="color: #dc3545; font-size: 12px;">{{ $message }}</span> 
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <button type="submit" class="theme-btn btn-style-one" wire:loading.attr="disabled">
                                            <span wire:loading.remove>{{ __('messages.contact_form.submit') }}</span>
                                            <span wire:loading>{{ __('messages.common.loading') }}</span>
                                        </button>
                                    </div>                                        
                                </div>
                            </form>
                        </div>
                        <!--End Contact Form-->
                    </div>
                </div>
                
                <!--Info Column-->
                <div class="info-column col-md-4 col-sm-12 col-xs-12">
                    <div class="inner-column">
                        <h3>{{ __('messages.footer.get_in_touch') }}</h3>
                        <ul>
                            <li><span>{{ __('messages.contact_page.address_label') }}:</span>{{ __('messages.contact.full_address') }}</li>
                            <li><span>{{ __('messages.contact_page.email_label') }}:</span>{{ __('messages.contact.email') }}</li>
                            <li><span>{{ __('messages.contact_page.phone_label') }}:</span>{{ __('messages.contact.phone') }}</li>
                        </ul>
                        
                        <!--Business Hours-->
                        <div class="business-hours" style="margin-top: 30px;">
                            <h4>{{ __('messages.contact_page.business_hours') }}</h4>
                            <ul style="list-style: none; padding: 0;">
                                <li style="padding: 5px 0; border-bottom: 1px solid #eee;">
                                    <span style="font-weight: bold;">{{ __('messages.contact_page.weekdays') }}:</span> 
                                    {{ __('messages.contact_page.weekdays_hours') }}
                                </li>
                                <li style="padding: 5px 0; border-bottom: 1px solid #eee;">
                                    <span style="font-weight: bold;">{{ __('messages.contact_page.saturday') }}:</span> 
                                    {{ __('messages.contact_page.saturday_hours') }}
                                </li>
                                <li style="padding: 5px 0;">
                                    <span style="font-weight: bold;">{{ __('messages.contact_page.sunday') }}:</span> 
                                    {{ __('messages.contact_page.sunday_hours') }}
                                </li>
                            </ul>
                        </div>
                        
                        <!--Social Links-->
                        <div class="social-links" style="margin-top: 30px;">
                            <h4>{{ __('messages.contact_page.follow_us') }}</h4>
                            <div style="margin-top: 15px;">
                                <a href="#" style="margin-right: 10px; color: #0066cc; font-size: 20px;"><i class="fa fa-facebook"></i></a>
                                <a href="#" style="margin-right: 10px; color: #0066cc; font-size: 20px;"><i class="fa fa-linkedin"></i></a>
                                <a href="#" style="margin-right: 10px; color: #0066cc; font-size: 20px;"><i class="fa fa-twitter"></i></a>
                                <a href="mailto:{{ __('messages.contact.email') }}" style="color: #0066cc; font-size: 20px;"><i class="fa fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    <!--End Contact Section-->
    
    <!--Call to Action Section-->
    <section class="call-to-action-section" style="background: #003366; color: white; padding: 60px 0;">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <h3>{{ __('messages.contact_page.cta_title') }}</h3>
                    <div class="text">{{ __('messages.contact_page.cta_subtitle') }}</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 text-right">
                    <a href="tel:{{ __('messages.contact.phone') }}" class="theme-btn btn-style-four">{{ __('messages.contact_page.call_now') }}</a>
                </div>
            </div>
        </div>
    </section>
    <!--End Call to Action Section-->
</div>