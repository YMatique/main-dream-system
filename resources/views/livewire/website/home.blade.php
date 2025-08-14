{{-- resources/views/pages/home.blade.php --}}
{{-- @extends('layouts.app') --}}

@section('title', __('messages.home.title'))
@section('description', __('messages.home.description'))

@section('content')

<!--Main Slider-->
<section class="main-slider">
    <div class="rev_slider_wrapper fullwidthbanner-container" id="rev_slider_two_wrapper" data-source="gallery">
        <div class="rev_slider fullwidthabanner" id="rev_slider_two" data-version="5.4.1">
            <ul>
                
                {{-- Slide 1 --}}
                <li data-description="Slide Description" data-easein="default" data-easeout="default" data-fsmasterspeed="1500" data-fsslotamount="7" data-fstransition="fade" data-hideafterloop="0" data-hideslideonmobile="off" data-index="rs-1688" data-masterspeed="default" data-param1="" data-param10="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-rotate="0" data-saveperformance="off" data-slotamount="default" data-thumb="{{ asset('template/images/main-slider/image-4.jpg') }}" data-title="Slide Title" data-transition="parallaxvertical">
                <img alt="{{ __('messages.home.slider.slide1.alt') }}" class="rev-slidebg" data-bgfit="cover" data-bgparallax="10" data-bgposition="center center" data-bgrepeat="no-repeat" data-no-retina="" src="{{ asset('template/images/main-slider/image-4.jpg') }}"> 
                
                <div class="tp-caption" 
                data-paddingbottom="[0,0,0,0]"
                data-paddingleft="[0,0,0,0]"
                data-paddingright="[0,0,0,0]"
                data-paddingtop="[0,0,0,0]"
                data-responsive_offset="on"
                data-type="text"
                data-height="none"
                data-width="['700','700','700','550']"
                data-whitespace="normal"
                data-hoffset="['15','15','15','15']"
                data-voffset="['-70','-120','-120','-105']"
                data-x="['left','left','left','left']"
                data-y="['middle','middle','middle','middle']"
                data-textalign="['top','top','top','top']"
                data-frames='[{"from":"y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
                    <h2>{!! __('messages.home.slider.slide1.title') !!}</h2>
                </div>
                
                <div class="tp-caption" 
                data-paddingbottom="[0,0,0,0]"
                data-paddingleft="[0,0,0,0]"
                data-paddingright="[0,0,0,0]"
                data-paddingtop="[0,0,0,0]"
                data-responsive_offset="on"
                data-type="text"
                data-height="none"
                data-width="['680','700','700','450']"
                data-whitespace="normal"
                data-hoffset="['15','15','15','15']"
                data-voffset="['50','40','20','0']"
                data-x="['left','left','left','left']"
                data-y="['middle','middle','middle','middle']"
                data-textalign="['top','top','top','top']"
                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
                    <div class="text">{{ __('messages.home.slider.slide1.description') }}</div>
                </div>
                
                <div class="tp-caption tp-resizeme" 
                data-paddingbottom="[0,0,0,0]"
                data-paddingleft="[0,0,0,0]"
                data-paddingright="[0,0,0,0]"
                data-paddingtop="[0,0,0,0]"
                data-responsive_offset="on"
                data-type="text"
                data-height="none"
                data-width="['560','700','700','550']"
                data-whitespace="normal"
                data-hoffset="['15','15','15','15']"
                data-voffset="['140','150','150','115']"
                data-x="['left','left','left','left']"
                data-y="['middle','middle','middle','middle']"
                data-textalign="['top','top','top','top']"
                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
                    <div class="btns-box">
                        <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="theme-btn btn-style-one">{{ __('messages.home.slider.btn_quote') }}</a>
                        <a href="{{ route('about', ['locale' => app()->getLocale()]) }}" class="theme-btn btn-style-two">{{ __('messages.home.slider.btn_learn') }}</a>
                    </div>
                </div>
                
                </li>
                
                {{-- Slide 2 --}}
                <li data-description="Slide Description" data-easein="default" data-easeout="default" data-fsmasterspeed="1500" data-fsslotamount="7" data-fstransition="fade" data-hideafterloop="0" data-hideslideonmobile="off" data-index="rs-1689" data-masterspeed="default" data-param1="" data-param10="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-rotate="0" data-saveperformance="off" data-slotamount="default" data-thumb="{{ asset('template/images/main-slider/image-5.jpg') }}" data-title="Slide Title" data-transition="parallaxvertical">
                <img alt="{{ __('messages.home.slider.slide2.alt') }}" class="rev-slidebg" data-bgfit="cover" data-bgparallax="10" data-bgposition="center center" data-bgrepeat="no-repeat" data-no-retina="" src="{{ asset('template/images/main-slider/image-5.jpg') }}"> 
                
                <div class="tp-caption" 
                data-paddingbottom="[0,0,0,0]"
                data-paddingleft="[0,0,0,0]"
                data-paddingright="[0,0,0,0]"
                data-paddingtop="[0,0,0,0]"
                data-responsive_offset="on"
                data-type="text"
                data-height="none"
                data-width="['650','700','700','550']"
                data-whitespace="normal"
                data-hoffset="['15','15','15','15']"
                data-voffset="['-70','-120','-120','-105']"
                data-x="['left','left','left','left']"
                data-y="['middle','middle','middle','middle']"
                data-textalign="['top','top','top','top']"
                data-frames='[{"from":"y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
                    <h2>{!! __('messages.home.slider.slide2.title') !!}</h2>
                </div>
                
                <div class="tp-caption" 
                data-paddingbottom="[0,0,0,0]"
                data-paddingleft="[0,0,0,0]"
                data-paddingright="[0,0,0,0]"
                data-paddingtop="[0,0,0,0]"
                data-responsive_offset="on"
                data-type="text"
                data-height="none"
                data-width="['700','700','700','450']"
                data-whitespace="normal"
                data-hoffset="['15','15','15','15']"
                data-voffset="['50','40','20','0']"
                data-x="['left','left','left','left']"
                data-y="['middle','middle','middle','middle']"
                data-textalign="['top','top','top','top']"
                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
                    <div class="text">{{ __('messages.home.slider.slide2.description') }}</div>
                </div>
                
                <div class="tp-caption tp-resizeme" 
                data-paddingbottom="[0,0,0,0]"
                data-paddingleft="[0,0,0,0]"
                data-paddingright="[0,0,0,0]"
                data-paddingtop="[0,0,0,0]"
                data-responsive_offset="on"
                data-type="text"
                data-height="none"
                data-width="['560','700','700','550']"
                data-whitespace="normal"
                data-hoffset="['15','15','15','15']"
                data-voffset="['140','150','150','115']"
                data-x="['left','left','left','left']"
                data-y="['middle','middle','middle','middle']"
                data-textalign="['top','top','top','top']"
                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
                    <div class="btns-box">
                        <a href="{{ route('services.maintenance', ['locale' => app()->getLocale()]) }}" class="theme-btn btn-style-one">{{ __('messages.home.slider.btn_services') }}</a>
                        <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="theme-btn btn-style-two">{{ __('messages.home.slider.btn_contact') }}</a>
                    </div>
                </div>
                
                </li>
                
                {{-- Slide 3 --}}
                <li data-description="Slide Description" data-easein="default" data-easeout="default" data-fsmasterspeed="1500" data-fsslotamount="7" data-fstransition="fade" data-hideafterloop="0" data-hideslideonmobile="off" data-index="rs-1690" data-masterspeed="default" data-param1="" data-param10="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-rotate="0" data-saveperformance="off" data-slotamount="default" data-thumb="{{ asset('template/images/main-slider/image-6.jpg') }}" data-title="Slide Title" data-transition="parallaxvertical">
                <img alt="{{ __('messages.home.slider.slide3.alt') }}" class="rev-slidebg" data-bgfit="cover" data-bgparallax="10" data-bgposition="center center" data-bgrepeat="no-repeat" data-no-retina="" src="{{ asset('template/images/main-slider/image-6.jpg') }}"> 
                
                <div class="tp-caption" 
                data-paddingbottom="[0,0,0,0]"
                data-paddingleft="[0,0,0,0]"
                data-paddingright="[0,0,0,0]"
                data-paddingtop="[0,0,0,0]"
                data-responsive_offset="on"
                data-type="text"
                data-height="none"
                data-width="['650','700','700','550']"
                data-whitespace="normal"
                data-hoffset="['15','15','15','15']"
                data-voffset="['-70','-120','-120','-105']"
                data-x="['left','left','left','left']"
                data-y="['middle','middle','middle','middle']"
                data-textalign="['top','top','top','top']"
                data-frames='[{"from":"y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
                    <h2>{!! __('messages.home.slider.slide3.title') !!}</h2>
                </div>
                
                <div class="tp-caption" 
                data-paddingbottom="[0,0,0,0]"
                data-paddingleft="[0,0,0,0]"
                data-paddingright="[0,0,0,0]"
                data-paddingtop="[0,0,0,0]"
                data-responsive_offset="on"
                data-type="text"
                data-height="none"
                data-width="['700','700','700','450']"
                data-whitespace="normal"
                data-hoffset="['15','15','15','15']"
                data-voffset="['50','40','20','0']"
                data-x="['left','left','left','left']"
                data-y="['middle','middle','middle','middle']"
                data-textalign="['top','top','top','top']"
                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
                    <div class="text">{{ __('messages.home.slider.slide3.description') }}</div>
                </div>
                
                <div class="tp-caption tp-resizeme" 
                data-paddingbottom="[0,0,0,0]"
                data-paddingleft="[0,0,0,0]"
                data-paddingright="[0,0,0,0]"
                data-paddingtop="[0,0,0,0]"
                data-responsive_offset="on"
                data-type="text"
                data-height="none"
                data-width="['560','700','700','550']"
                data-whitespace="normal"
                data-hoffset="['15','15','15','15']"
                data-voffset="['140','150','150','115']"
                data-x="['left','left','left','left']"
                data-y="['middle','middle','middle','middle']"
                data-textalign="['top','top','top','top']"
                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
                    <div class="btns-box">
                        <a href="{{ route('services.technology', ['locale' => app()->getLocale()]) }}" class="theme-btn btn-style-one">{{ __('messages.home.slider.btn_technology') }}</a>
                        <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}" class="theme-btn btn-style-two">{{ __('messages.home.slider.btn_projects') }}</a>
                    </div>
                </div>
                
                </li>
                
            </ul>
        </div>
    </div>
</section>
<!--End Main Slider-->

<!--Company Section-->
<section class="company-section">
    <div class="auto-container">
        <div class="row clearfix">
            <!--Content Column-->
            <div class="content-column col-md-8 col-sm-12 col-xs-12">
                <div class="inner-column">
                    <div class="sec-title">
                        <h2>{{ __('messages.home.company.vision_title') }}</h2>
                    </div>
                    <div class="bold-text">{{ __('messages.company.vision') }}</div>
                    <div class="text">{{ __('messages.home.company.description') }}</div>
                    <div class="signature">
                        <img src="{{ asset('template/images/resource/signature.jpg') }}" alt="{{ __('messages.home.company.signature_alt') }}" />
                    </div>
                </div>
            </div>
            <!--Image Column-->
            <div class="image-column col-md-4 col-sm-12 col-xs-12">
                <div class="inner-column">
                    <div class="image">
                        <img src="{{ asset('template/images/resource/company.jpg') }}" alt="{{ __('messages.home.company.image_alt') }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--End Company Section-->

<!--Services Section-->
<section class="services-section">
    <div class="auto-container">
        <div class="sec-title">
            <h2>{{ __('messages.home.services.title') }}</h2>
            <div class="title">{{ __('messages.home.services.subtitle') }}</div>
        </div>
        
        <div class="four-item-carousel owl-carousel owl-theme">
            
            <!--Services Block 1-->
            <div class="services-block-three">
                <div class="inner-box">
                    <div class="image">
                        <a href="{{ route('services.engineering', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ asset('template/images/resource/service-7.jpg') }}" alt="{{ __('messages.services.engineering') }}" />
                        </a>
                    </div>
                    <div class="lower-content">
                        <h3><a href="{{ route('services.engineering', ['locale' => app()->getLocale()]) }}">{{ __('messages.services.engineering') }}</a></h3>
                        <div class="text">{{ __('messages.home.services.engineering_desc') }}</div>
                    </div>
                </div>
            </div>
            
            <!--Services Block 2-->
            <div class="services-block-three">
                <div class="inner-box">
                    <div class="image">
                        <a href="{{ route('services.maintenance', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ asset('template/images/resource/service-8.jpg') }}" alt="{{ __('messages.services.maintenance') }}" />
                        </a>
                    </div>
                    <div class="lower-content">
                        <h3><a href="{{ route('services.maintenance', ['locale' => app()->getLocale()]) }}">{{ __('messages.services.maintenance') }}</a></h3>
                        <div class="text">{{ __('messages.home.services.maintenance_desc') }}</div>
                    </div>
                </div>
            </div>
            
            <!--Services Block 3-->
            <div class="services-block-three">
                <div class="inner-box">
                    <div class="image">
                        <a href="{{ route('services.technology', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ asset('template/images/resource/service-9.jpg') }}" alt="{{ __('messages.services.technology') }}" />
                        </a>
                    </div>
                    <div class="lower-content">
                        <h3><a href="{{ route('services.technology', ['locale' => app()->getLocale()]) }}">{{ __('messages.services.technology') }}</a></h3>
                        <div class="text">{{ __('messages.home.services.technology_desc') }}</div>
                    </div>
                </div>
            </div>
            
            <!--Services Block 4-->
            <div class="services-block-three">
                <div class="inner-box">
                    <div class="image">
                        <a href="{{ route('services.spare_parts', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ asset('template/images/resource/service-10.jpg') }}" alt="{{ __('messages.services.spare_parts') }}" />
                        </a>
                    </div>
                    <div class="lower-content">
                        <h3><a href="{{ route('services.spare_parts', ['locale' => app()->getLocale()]) }}">{{ __('messages.services.spare_parts') }}</a></h3>
                        <div class="text">{{ __('messages.home.services.spare_parts_desc') }}</div>
                    </div>
                </div>
            </div>
            
            <!--Services Block 5 (Repeat for carousel)-->
            <div class="services-block-three">
                <div class="inner-box">
                    <div class="image">
                        <a href="{{ route('services.engineering', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ asset('template/images/resource/service-7.jpg') }}" alt="{{ __('messages.services.engineering') }}" />
                        </a>
                    </div>
                    <div class="lower-content">
                        <h3><a href="{{ route('services.engineering', ['locale' => app()->getLocale()]) }}">{{ __('messages.services.engineering') }}</a></h3>
                        <div class="text">{{ __('messages.home.services.engineering_desc') }}</div>
                    </div>
                </div>
            </div>
            
            <!--Services Block 6 (Repeat for carousel)-->
            <div class="services-block-three">
                <div class="inner-box">
                    <div class="image">
                        <a href="{{ route('services.maintenance', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ asset('template/images/resource/service-8.jpg') }}" alt="{{ __('messages.services.maintenance') }}" />
                        </a>
                    </div>
                    <div class="lower-content">
                        <h3><a href="{{ route('services.maintenance', ['locale' => app()->getLocale()]) }}">{{ __('messages.services.maintenance') }}</a></h3>
                        <div class="text">{{ __('messages.home.services.maintenance_desc') }}</div>
                    </div>
                </div>
            </div>
            
            <!--Services Block 7 (Repeat for carousel)-->
            <div class="services-block-three">
                <div class="inner-box">
                    <div class="image">
                        <a href="{{ route('services.technology', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ asset('template/images/resource/service-9.jpg') }}" alt="{{ __('messages.services.technology') }}" />
                        </a>
                    </div>
                    <div class="lower-content">
                        <h3><a href="{{ route('services.technology', ['locale' => app()->getLocale()]) }}">{{ __('messages.services.technology') }}</a></h3>
                        <div class="text">{{ __('messages.home.services.technology_desc') }}</div>
                    </div>
                </div>
            </div>
            
            <!--Services Block 8 (Repeat for carousel)-->
            <div class="services-block-three">
                <div class="inner-box">
                    <div class="image">
                        <a href="{{ route('services.spare_parts', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ asset('template/images/resource/service-10.jpg') }}" alt="{{ __('messages.services.spare_parts') }}" />
                        </a>
                    </div>
                    <div class="lower-content">
                        <h3><a href="{{ route('services.spare_parts', ['locale' => app()->getLocale()]) }}">{{ __('messages.services.spare_parts') }}</a></h3>
                        <div class="text">{{ __('messages.home.services.spare_parts_desc') }}</div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</section>
<!--End Services Section-->

<!--Fluid Section One-->
<section class="fluid-section-one">
    <div class="outer-container clearfix">
        <!--Image Column-->
        <div class="image-column" style="background-image:url({{ asset('template/images/resource/image-1.jpg') }});">
            <figure class="image-box"><img src="{{ asset('template/images/resource/image-1.jpg') }}" alt="{{ __('messages.home.approach.image_alt') }}"></figure>
        </div>
        <!--Content Column-->
        <div class="content-column">
            <div class="inner-column">
                <div class="sec-title">
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

<!--Project Section-->
<section class="project-section">
    <div class="auto-container">
        
        <!--Sec Title-->
        <div class="sec-title light">
            <div class="clearfix">
                <div class="pull-left">
                    <h2>{{ __('messages.home.projects.title') }}</h2>
                </div>
                <div class="pull-right">
                    <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}" class="projects">{{ __('messages.home.projects.view_all') }}</a>
                </div>
            </div>
        </div>
        
        <div class="row clearfix">
            
            <!--Services Block Two-->
            <div class="services-block-two col-md-3 col-sm-6 col-xs-12">
                <div class="inner-box">
                    <div class="image">
                        <img src="{{ asset('template/images/gallery/1.jpg') }}" alt="{{ __('messages.home.projects.project1_alt') }}" />
                        <div class="content-overlay">
                            <div class="overlay-inner">
                                <div class="content-box">
                                    <h4><a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">{{ __('messages.home.projects.project1_title') }}</a></h4>
                                    <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}" class="detail">{{ __('messages.home.projects.details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--Services Block Two-->
            <div class="services-block-two col-md-3 col-sm-6 col-xs-12">
                <div class="inner-box">
                    <div class="image">
                        <img src="{{ asset('template/images/gallery/2.jpg') }}" alt="{{ __('messages.home.projects.project2_alt') }}" />
                        <div class="content-overlay">
                            <div class="overlay-inner">
                                <div class="content-box">
                                    <h4><a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">{{ __('messages.home.projects.project2_title') }}</a></h4>
                                    <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}" class="detail">{{ __('messages.home.projects.details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--Services Block Two-->
            <div class="services-block-two col-md-3 col-sm-6 col-xs-12">
                <div class="inner-box">
                    <div class="image">
                        <img src="{{ asset('template/images/gallery/3.jpg') }}" alt="{{ __('messages.home.projects.project3_alt') }}" />
                        <div class="content-overlay">
                            <div class="overlay-inner">
                                <div class="content-box">
                                    <h4><a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">{{ __('messages.home.projects.project3_title') }}</a></h4>
                                    <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}" class="detail">{{ __('messages.home.projects.details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--Services Block Two-->
            <div class="services-block-two col-md-3 col-sm-6 col-xs-12">
                <div class="inner-box">
                    <div class="image">
                        <img src="{{ asset('template/images/gallery/4.jpg') }}" alt="{{ __('messages.home.projects.project4_alt') }}" />
                        <div class="content-overlay">
                            <div class="overlay-inner">
                                <div class="content-box">
                                    <h4><a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">{{ __('messages.home.projects.project4_title') }}</a></h4>
                                    <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}" class="detail">{{ __('messages.home.projects.details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
<!--End Project Section-->

<!--Ceo Section-->
<section class="ceo-section">
    <div class="auto-container">
        <div class="row clearfix">
            
            <!--Image Column-->
            <div class="image-column col-md-5 col-sm-12 col-xs-12">
                <div class="image">
                    <img src="{{ asset('template/images/resource/ceo.png') }}" alt="{{ __('messages.home.ceo.image_alt') }}" />
                </div>
            </div>
            
            <!--Content Column-->
            <div class="content-column col-md-7 col-sm-12 col-xs-12">
                <div class="inner-column">
                    <h2>{{ __('messages.home.ceo.title') }}</h2>
                    <div class="bold-text">{{ __('messages.home.ceo.message_intro') }}</div>
                    <div class="text">{{ __('messages.home.ceo.message_full') }}</div>
                    <div class="signature">
                        <img src="{{ asset('template/images/resource/signature-1.jpg') }}" alt="{{ __('messages.home.ceo.signature_alt') }}" />
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
<!--End Ceo Section-->

<!--News Section-->
<section class="news-section alternate">
    <div class="auto-container">

        <div class="sec-title">
            <h2>{{ __('messages.home.news.title') }}</h2>
            <div class="title">{{ __('messages.home.news.subtitle') }}</div>
        </div>
        
        <div class="row clearfix">
        
            <!--Column-->
            <div class="column col-md-8 col-sm-12 col-xs-12">
                <div class="row clearfix">
                    
                    <!--News Block-->
                    <div class="news-block col-md-6 col-sm-6 col-xs-12">
                        <div class="inner-box">
                            <div class="image">
                                <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">
                                    <img src="{{ asset('template/images/resource/news-1.jpg') }}" alt="{{ __('messages.home.news.news1_alt') }}" />
                                </a>
                            </div>
                            <div class="lower-box">
                                <div class="post-info">{{ __('messages.home.news.recent_date') }}</div>
                                <h3><a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">{{ __('messages.home.news.news1_title') }}</a></h3>
                                <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}" class="theme-btn read-more">{{ __('messages.buttons.read_more') }}</a>
                            </div>
                        </div>
                    </div>
                    
                    <!--News Block-->
                    <div class="news-block col-md-6 col-sm-6 col-xs-12">
                        <div class="inner-box">
                            <div class="image">
                                <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">
                                    <img src="{{ asset('template/images/resource/news-2.jpg') }}" alt="{{ __('messages.home.news.news2_alt') }}" />
                                </a>
                            </div>
                            <div class="lower-box">
                                <div class="post-info">{{ __('messages.home.news.recent_date') }}</div>
                                <h3><a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">{{ __('messages.home.news.news2_title') }}</a></h3>
                                <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}" class="theme-btn read-more">{{ __('messages.buttons.read_more') }}</a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <!--Column-->
            <div class="column col-md-4 col-sm-12 col-xs-12">
                <div class="sidebar-news">
                    
                    <!--News Block Two-->
                    <div class="news-block-two">
                        <div class="inner-box">
                            <h3><a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">{{ __('messages.home.news.sidebar1_title') }}</a></h3>
                            <div class="post-info">{{ __('messages.home.news.recent_date') }}</div>
                        </div>
                    </div>
                    
                    <!--News Block Two-->
                    <div class="news-block-two">
                        <div class="inner-box">
                            <h3><a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">{{ __('messages.home.news.sidebar2_title') }}</a></h3>
                            <div class="post-info">{{ __('messages.home.news.recent_date') }}</div>
                        </div>
                    </div>
                    
                    <!--News Block Two-->
                    <div class="news-block-two">
                        <div class="inner-box">
                            <h3><a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">{{ __('messages.home.news.sidebar3_title') }}</a></h3>
                            <div class="post-info">{{ __('messages.home.news.recent_date') }}</div>
                        </div>
                    </div>
                    
                    <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}" class="read-more">{{ __('messages.buttons.read_more') }}</a>
                    
                </div>
            </div>
            
        </div>
        
    </div>
</section>
<!--End News Section-->

<!--Clients Section-->
<section class="clients-section alternate">
    <div class="auto-container">
        
        <div class="sponsors-outer">
            <!--Sponsors Carousel-->
            <ul class="sponsors-carousel owl-carousel owl-theme">
                <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/7.png') }}" alt="{{ __('messages.home.clients.client1') }}"></a></figure></li>
                <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/8.png') }}" alt="{{ __('messages.home.clients.client2') }}"></a></figure></li>
                <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/9.png') }}" alt="{{ __('messages.home.clients.client3') }}"></a></figure></li>
                <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/10.png') }}" alt="{{ __('messages.home.clients.client4') }}"></a></figure></li>
                <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/11.png') }}" alt="{{ __('messages.home.clients.client5') }}"></a></figure></li>
                <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/12.png') }}" alt="{{ __('messages.home.clients.client6') }}"></a></figure></li>
                <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/7.png') }}" alt="{{ __('messages.home.clients.client1') }}"></a></figure></li>
                <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/8.png') }}" alt="{{ __('messages.home.clients.client2') }}"></a></figure></li>
                <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/9.png') }}" alt="{{ __('messages.home.clients.client3') }}"></a></figure></li>
                <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ asset('template/images/clients/10.png') }}" alt="{{ __('messages.home.clients.client4') }}"></a></figure></li>
            </ul>
        </div>
        
    </div>
</section>
<!--End Clients Section-->

<!--Call Back Section-->
<section class="call-back-section" style="background-image:url({{ asset('template/images/background/2.jpg') }})">
    <div class="auto-container">
        
        <!--Sec Title-->
        <div class="sec-title">
            <h2>{{ __('messages.home.callback.title') }}</h2>
        </div>
        
        <!-- Call Back Form -->
        {{-- @livewire('contact-form') --}}
        
    </div>
</section>
<!--End Call Back Section-->

@endsection