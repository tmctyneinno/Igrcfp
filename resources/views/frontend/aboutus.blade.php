@extends('layouts.app')

@section('content')

<!--======  Start Page Hero Section  ======-->
<section 
    class="page-hero bg_cover p-r z-1" 
    style="background-image: url('{{ asset('assets/images/innerpage/bg/page-bg.png') }}');"
>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="page-content text-center">
                    <h1 style="color: black;">Welcome to IGRCFP</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<!--======  End Page Hero Section  ======-->

 <!--======  Start Company Section  ======-->
<section class="bizzen-company-sec pt-120 pb-120">
    <div class="container-fluid">
        <!--=== Clients Slider ===-->
        <div class="clients-slider" data-aos="fade-up" data-aos-duration="1000">
            <!--=== Bizzen Client Item ===-->
            <div class="bizzen-client-item">
                <div class="client-img">
                    <img src="{{ asset ('assets/images/innerpage/company/client-img1.png')}}" alt="Client Logo">
                </div>
            </div>
            <!--=== Bizzen Client Item ===-->
            <div class="bizzen-client-item">
                <div class="client-img">
                    <img src="{{ asset ('assets/images/innerpage/company/client-img2.png')}}" alt="Client Logo">
                </div>
            </div>
            <!--=== Bizzen Client Item ===-->
            <div class="bizzen-client-item">
                <div class="client-img">
                    <img src="{{ asset('assets/images/innerpage/company/client-img3.png')}}" alt="Client Logo">
                </div>
            </div>
            <!--=== Bizzen Client Item ===-->
            <div class="bizzen-client-item">
                <div class="client-img">
                    <img src="{{ asset ('assets/images/innerpage/company/client-img4.png')}}" alt="Client Logo">
                </div>
            </div>
            <!--=== Bizzen Client Item ===-->
            <div class="bizzen-client-item">
                <div class="client-img">
                    <img src="{{ asset('assets/images/innerpage/company/client-img5.png')}}" alt="Client Logo">
                </div>
            </div>
            <!--=== Bizzen Client Item ===-->
            <div class="bizzen-client-item">
                <div class="client-img">
                    <img src="{{ asset('assets/images/innerpage/company/client-img1.png')}}" alt="Client Logo">
                </div>
            </div>
            <!--=== Bizzen Client Item ===-->
            <div class="bizzen-client-item">
                <div class="client-img">
                    <img src="{{ asset('assets/images/innerpage/company/client-img2.png')}}" alt="Client Logo">
                </div>
            </div>
        </div>
    </div>
</section>
<!--======  End Company Section  ======-->

 <!--======  Start Who We Section  ======-->
<section class="bizzen-we_two pt-120 pb-20">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-10">
                <!--=== Bizzen Content Box ===-->
                <div class="bizzen-content-box">
                    <!--=== Section Title ===-->
                    <div class="section-title mb-30">
                        <span class="sub-title" data-aos="fade-down" data-aos-duration="1000">Who we are</span>
                        <h2 class="text-anm">Know More About Us</h2>
                    </div>
                    
                    
                </div>
            </div>
            <div class="col-xl-7 col-lg-10">
                <!--=== Bizzen Image Box ===-->
                <div class="bizzen-image-box mb-5 mb-xl-0 " >
                    <p class="mb-3 " data-aos="fade-up" data-aos-duration="1200">
                        The Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP) is a global professional body dedicated to raising standards in governance, risk management, compliance, and financial crime prevention.
                    </p>
                    
                    <p class="mb-3" data-aos="fade-up" data-aos-duration="1600">
                        We equip professionals and organizations with world-class training, certifications, and resources to stay ahead in a fast-changing regulatory environment.
                    </p>
                    <p data-aos="fade-up" data-aos-duration="1800">
                        With a presence across Africa, Europe, Asia, the Middle East, and the Americas, IGRCFP connects experts, regulators, and industry leaders to share knowledge, drive innovation, and build stronger institutions worldwide.
                    </p>
                    <div class="bizzen-button mt-30"  data-aos="fade-up" data-aos-duration="1800">
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
<!--======  End Who We Section  ======-->

<section
    class="bizzen-intro_one pt-250 pb-250 bg_cover"
    style="background-image: url('{{ asset('assets/images/home-three/bg/intro-bg.png') }}');"
    data-aos="fade-up"
    data-aos-duration="1000"
>
    <div class="container"></div>
</section>

 <!--======  Start Who We Section  ======-->
<section class="bizzen-we_two pt-120 pb-20">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-1 col-lg-10"></div>
            <div class="col-xl-10 col-lg-10">
                <!--=== Bizzen Content Box ===-->
                <div class="bizzen-content-box">
                    <!--=== Section Title ===-->
                    <div class="section-title mb-30 text-center">
                        <div class="title-with-lines" data-aos="fade-down" data-aos-duration="1000">
                            <span class=" text-black">OUR VISION</span>
                        </div>
                        <p class=" text-black">To be the foremost international institute advancing governance, compliance, and  
                            financial crime prevention through knowledge, innovation, and collaboration.</p>
                    </div>
                    
                    
                </div>
            </div>
            <div class="col-xl-1 col-lg-10"></div>
            
        </div>
    </div>
</section>
<!--======  End Who We Section  ======-->

<!--======  Start Growth Section  ======-->
<section class="bizzen-growth-sec pt-115 pb-120 bg_cover" >
    <div class="row justify-content-center pb-20">
        <div class="col-xl-8">
            <!--=== Section Title ===-->
            <div class="section-title text-center mb-60 text-black">
                <h3 class="text-anm" data-aos="fade-down" data-aos-duration="1000">Our Values</h3>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-10">
                <!--=== Bizzen Features List ===-->
                <div class="bizzen-features-list">
                    <div class="row">
                        <div class="col-md-6">
                            <!--=== Bizzen Features Item ===-->
                            <div class="bizzen-features-item style-two mb-35" data-aos="fade-up" data-aos-duration="1000">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/home-three/icon/cube.svg')}}" alt="check">
                                </div>
                                <div class="content">
                                    <h4 class="text-black">Integrity </h4>
                                    <p>The current investment landscape requires a robust approach to the investments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!--=== Bizzen Features Item ===-->
                            <div class="bizzen-features-item style-two mb-35" data-aos="fade-up" data-aos-duration="1200">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/home-three/icon/cube.svg')}}" alt="check">
                                </div>
                                <div class="content">
                                    <h4 class="text-black">>Global Reach</h4>
                                    <p>The current investment landscape requires a robust approach to the investments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!--=== Bizzen Features Item ===-->
                            <div class="bizzen-features-item style-two mb-35" data-aos="fade-up" data-aos-duration="1400">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/home-three/icon/cube.svg')}}" alt="check">
                                </div>
                                <div class="content">
                                    <h4>Advantage</h4>
                                    <p>The current investment landscape requires a robust approach to the investments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!--=== Bizzen Features Item ===-->
                            <div class="bizzen-features-item style-two mb-35" data-aos="fade-up" data-aos-duration="1800">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/home-three/icon/cube.svg')}}" alt="check">
                                </div>
                                <div class="content">
                                    <h4>Strategy</h4>
                                    <p>The current investment landscape requires a robust approach to the investments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="col-xl-6 col-lg-10">
                <div class="play-bg bg_cover mr-2" style="background-image: url({{ asset('assets/images/innerpage/bg/about-bg.png')}});">
                    <!--=== Play Button ===-->
                    <div class="play-button" data-aos="fade-up" data-aos-duration="1000">
                        <a href="https://www.youtube.com/watch?v=H7jpB4WnAH8" class="video-popup"><i class="fas fa-play"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--======  End Growth Section  ======-->


@endsection