@extends('layouts.app')

@section('content')

<!--======  Start Who We Section  ======-->
<section class="bizzen-we_one pb-100 pt-110">
    <div class="container">
        <div class="row">
           
            <div class="col-xl-6">
                <!--=== Bizzen Content Box ===-->
                <div class="bizzen-content-box text-justify-content">
                    <div class="section-title">
                        <br/> <br/>
                        <h2 class="text-anm">Knowledgeable And Trustworthy Experts</h2>
                    </div>
                    <p class="mb-30" data-aos="fade-up" data-aos-duration="1200">
                        Join a trusted professional community advancing excellence in governance, compliance, and financial crime prevention. Membership at IGRCFP connects you with peers across the world, gives you access to exclusive resources, and provides professional recognition that sets you apart.
                    </p>
                    <div class="bizzen-button">
                        <a style="border-radius: 8px" href="{{  route('login') }}" class="theme-btn style-one">Become a Member Today </a>
                    </div>
                </div>
            </div>
             <div class="col-xl-6">
                <!--=== Bizzen Image ===-->
                <div class="bizzen-image mb-5 mb-xl-0" data-aos="fade-up" data-aos-duration="1200">
                    <img src="assets/images/home-three/gallery/membership.png" alt="who we">
                </div>
            </div>
        </div>
    </div>
</section>
<!--======  End Who We Section  ======-->
 <!--======  Start Who We Section  ======-->
<section class="bizzen-we_two pt-120 pb-20">
    <div class="container">
        
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-10">
                <!--=== Bizzen Content Box ===-->
                <div class="bizzen-content-box">
                    <!--=== Section Title ===-->
                    <div class="section-title mb-30">
                        <span class="sub-title" data-aos="fade-down" data-aos-duration="1000">Why Join?</span>
                        <h2 class="text-anm">Why Join IGRCFP?</h2>
                    </div>
                    
                    
                </div>
            </div>
            <div class="col-xl-7 col-lg-10">
                <!--=== Bizzen Image Box ===-->
                <div class="bizzen-image-box mb-5 mb-xl-0 " >
                    <p class="mb-3 " data-aos="fade-up" data-aos-duration="1200">
                        At IGRCFP, we go beyond certification—we build careers and shape leaders. By joining us, you become part of a global network of governance, risk, compliance, and financial crime professionals who are driving change across industries. Membership gives you access to exclusive insights, research, and training, alongside opportunities for professional recognition, CPD credits, and the use of respected post-nominals. Whether you are in banking, fintech, insurance, or regulation, IGRCFP equips you with the knowledge, tools, and connections to stay ahead, stay compliant, and stand out as a trusted expert.
                    </p>
                    
                </div>
            </div>
            
        </div>
    </div>
</section>
<!--======  End Who We Section  ======-->


<!--======  Start Why Join  ======-->
<section class="bizzen-we_two pt-120 pb-120">
    
    <div class="container ">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5">
            <div class="col" >
            <div class="bizzen-service-item style-one mb-30" style=" padding: 0px 0px 0px 0px">
                <div class="service-inner-content" >
                <div class="icon text-center" >
                    <img src="{{ asset('assets/images/home-three/icon/icon3.png')}}" alt="icon" style="width:50px"></div>
                <div class="text-center">
                    <p class="title">
                        <a href="service-details.html"  style="font-size: 20px; font-weight: 400;">Network Opportunities</a>
                    </p>
                    <p style="color: #666666;" class="mt-10">
                        Connect with a global network of compliance professionals.
                    </p>
                    </div>
                </div>
            </div>
            </div>
            <div class="col" >
            <div class="bizzen-service-item style-one mb-30" style=" padding: 0px 0px 0px 0px">
                <div class="service-inner-content" >
                <div class="icon text-center" >
                    <img src="{{ asset('assets/images/home-three/icon/icon4.png')}}" alt="icon" style="width:50px"></div>
                <div class="text-center">
                    <p class="title">
                        <a href="service-details.html"  style="font-size: 20px; font-weight: 400;">Get Relevant Updates</a>
                    </p>
                    <p style="color: #666666;" class="mt-10">
                        Access exclusive research, insights, and regulatory updates.
                    </p>
                    </div>
                </div>
            </div>
            </div>

            <div class="col" >
            <div class="bizzen-service-item style-one mb-30" style=" padding: 0px 0px 0px 0px">
                <div class="service-inner-content" >
                <div class="icon text-center" >
                    <img src="{{ asset('assets/images/home-three/icon/icon5.png')}}" alt="icon" style="width:50px"></div>
                <div class="text-center">
                    <p class="title">
                        <a href="service-details.html"  style="font-size: 20px; font-weight: 400;">
                        Member Benefits
                        </a>
                    </p>
                    <p style="color: #666666;" class="mt-10">
                        Gain access to IGRCFP’s mentor–mentee programme, connecting experienced ..
                    </p>
                    </div>
                </div>
            </div>
            </div>

            <div class="col" >
            <div class="bizzen-service-item style-one mb-30" style=" padding: 0px 0px 0px 0px">
                <div class="service-inner-content" >
                <div class="icon text-center" >
                    <img src="{{ asset('assets/images/home-three/icon/icon6.png')}}" alt="icon" style="width:50px"></div>
                <div class="text-center">
                    <p class="title">
                        <a href="service-details.html"  style="font-size: 20px; font-weight: 400;">Exclusive Discount</a>
                    </p>
                    <p style="color: #666666;" class="mt-10">
                        Receive discounts on certifications, events, and publications.
                    </p>
                    <br/>
                    </div>
                </div>
            </div>
            </div>

            <div class="col" >
            <div class="bizzen-service-item style-one mb-30" style=" padding: 0px 0px 0px 0px">
                <div class="service-inner-content" >
                <div class="icon text-center" >
                    <img src="{{ asset('assets/images/home-three/icon/icon7.png')}}" alt="icon" style="width:50px"></div>
                <div class="text-center">
                    <p class="title">
                        <a href="service-details.html"  style="font-size: 20px; font-weight: 400;">Recognition</a>
                    </p>
                    <p style="color: #666666;" class="mt-10">
                        Gain recognition with post-nominals (e.g., A.IGRCFP, F.IGRCFP).      
                    </p>
                    <br/>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
<!--======  End Why Join  ======-->

<!--======  Start Project Section  ======-->
<section class="bizzen-service_one pt-80  bg_cover pb-50">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <!--=== Section Title ===-->
                <div class="section-title text-center text-xl-start mb-50">
                    <span class="sub-title" data-aos="fade-down" data-aos-duration="1000">Become our Membership</span>
                    <h2 class="text-anm">Our Membership Categories</h2>
                </div>
            </div>
            <div class="col-lg-4">
                <!--=== Bizzen Button ===-->
                <div class="bizzen-button mb-60 text-center text-lg-end" data-aos="fade-up" data-aos-duration="1000">
                    <a href="{{  route('login') }}" class="theme-btn style-one">Become a Member Today<i class="far fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <!--=== Project Slide ===-->
    </div>

     <div class="container">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5">
            <div class="col" >
            <div class="bizzen-service-item style-one mb-30" style="background-color: #0A1F44 !important;">
                <div class="service-inner-content" >
                <div class="icon"><img src="{{ asset('assets/images/home-three/icon/icon1.png')}}" alt="icon" style="width:50px"></div>
                <div class="">
                    <p class="title">
                        <a href="service-details.html"  style="font-size: 20px; font-weight: 400; color: #F0F0F0;">Student Member</a>
                    </p>
                    </div>
                </div>
            </div>
            </div>

            <div class="col">
            <div class="bizzen-service-item style-one mb-30" style="background-color: #F4F4F4;">
                <div class="service-inner-content">
                <div class="icon"><img src="{{ asset('assets/images/home-three/icon/icon2.png')}}" alt="icon" style="width:50px"></div>
                <div class=""><p class="title">
                    <a href="service-details.html"  style="font-size: 18px; font-weight: 400; color: #000000;">Associate Member</a></p></div>
                </div>
            </div>
            </div>

            <div class="col">
            <div class="bizzen-service-item style-one mb-30" style="background-color: #007B6E;">
                <div class="service-inner-content">
                <div class="icon"><img src="{{ asset('assets/images/home-three/icon/icon1.png')}}" alt="icon" style="width:50px"></div>
                <div class=""><h4 class="title">
                    <a href="service-details.html" style="font-size: 18px; font-weight: 400; color: #F0F0F0;">Invest Process</a></h4></div>
                </div>
            </div>
            </div>

            <div class="col">
            <div class="bizzen-service-item style-one mb-30" style="background-color: #0097A7;">
                <div class="service-inner-content" style="padding-bottom: 0px;">
                <div class="icon"><img src="{{ asset('assets/images/home-three/icon/icon1.png')}}" alt="icon" style="width:50px"></div>
                <div class=""><h4 class="title">
                    <a href="service-details.html" style="font-size: 16px; font-weight: 400; color: #F0F0F0;">
                    Fellow (Senior Executive & Experts)
                    </a></h4></div>
                </div>
            </div>
            </div>

            <div class="col">
            <div class="bizzen-service-item style-one mb-30" style="background-color: #13346D;">
                <div class="service-inner-content" style="padding-bottom: 0px;">
                <div class="icon"><img src="{{ asset('assets/images/home-three/icon/icon1.png')}}" alt="icon" style="width:50px"></div>
                <div class=""><h4 class="title">
                    <a href="service-details.html" style="font-size: 16px; font-weight: 400; color: #F0F0F0;">
                    Cooperate Member (for Organizations)
                </a></h4></div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="container">
        
        <div class="features-wrapper" >
            <div class="row "> 
                <div class="col-xl-4 col-md-6 col-sm-12 item-column ">
                    <!--=== Bizzen Features Item ===-->
                    <div style="text-align: left"  class=" bizzen-features-item style-one" data-aos="fade-up" data-aos-duration="1200">
                        <div class="content" >
                            <h6>Category</h6>
                            <div class="mt-50">
                            </div>
                            <p>Student Affiliate</p>
                            <div class="mt-50">
                            </div>
                            <p>Associate Member (A.IGRCFP)</p>
                            <div class="mt-50">
                            </div>
                            <p>Professional Member</p>
                            <div class="mt-50">
                            </div>
                            <p>Fellow (F.IGRCFP)</p>
                            <div class="mt-50">
                            </div>
                            <p>Corporate Membership</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-12 item-column">
                    <!--=== Bizzen Features Item ===-->
                    <div style="text-align: left"  class="bizzen-features-item style-one" data-aos="fade-up" data-aos-duration="1400">
                        <div class="content">
                            <h4>Annual Fee</h4>
                            <div class="mt-50">
                            </div>
                            <p>$50</p>
                            <div class="mt-50">
                            </div>
                            <p>$150</p>
                            <div class="mt-50">
                            </div>
                            <p>$250</p>
                            <div class="mt-50">
                            </div>
                            <p>$350</p>
                            <div class="mt-50">
                            </div>
                            <p>$1500</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-md-6 col-sm-12 item-column">
                    <!--=== Bizzen Features Item ===-->
                    <div style="text-align: left" class="bizzen-features-item style-one" data-aos="fade-up" data-aos-duration="1600">
                        <div class="content">
                            <h4>Benefits</h4>
                            <div class="mt-50">
                            </div>
                            <p>Access to online community, selected resources, and discounts on training.</p>
                            <div class="mt-20">
                            </div>
                            <p>Full access to resources, participation in events, and certification discounts.</p>
                            <div class="mt-20">
                            </div>
                            <p>Full access to resources, participation in events, and certification discounts.</p>
                            <div class="mt-20">
                            </div>
                            <p>Leadership recognition, eligibility for governance roles, and priority speaker slots.</p>
                            <div class="mt-20">
                            </div>
                            <p>Multi-user access for teams, brand recognition, advisory engagement, and event sponsorship discounts.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--======  End Project Section  ======-->


<!--======  Start Service Details Section  ======-->
<section class="service-details-sec pt-80 pb-95">
    <div class="container">
        <div class="row justify-content-left">
            <div class="col-xl-12">
                <!--=== Section Title ===-->
                <div class="section-title text-left mb-60">
                    <span class="sub-title" data-aos="fade-up" data-aos-duration="1000">Mentorship programmme</span>
                    <h2 class="text-anm">Know More About Us</h2>
                    <div class="content-wrap">
                        <p data-aos="fade-up" data-aos-duration="1200">
                            At IGRCFP, we believe learning goes beyond the classroom. Our mentorship programme pairs senior professionals with aspiring practitioners to provide guidance, career support, and industry insights. Whether you are a mentor sharing your expertise or a mentee growing your skills, this programme helps you build meaningful professional relationships.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!--=== Service Details Wrapper ===-->
        <div class="service-details-wrapper">
           
            <!--=== Process Wrapper ===-->
            <div class="process-wrapper">
               
                <div class="row">
                    <div class="col-xl-4 col-md-6 col-sm-12">
                        <!--=== Bizzen Process Item ===-->
                        <div class="bizzen-process-item style-three mb-40" data-aos="fade-up" data-aos-duration="800">
                            <div class="line"></div>
                            <div class="number">01</div>
                            <div class="content">
                                <h4>Online Application</h4>
                                <p>Our consultancy excels providing quick solutions tailored </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-12">
                        <!--=== Bizzen Process Item ===-->
                        <div class="bizzen-process-item style-three mb-40" data-aos="fade-up" data-aos-duration="1200">
                            <div class="line"></div>
                            <div class="number">03</div>
                            <div class="content">
                                <h4>Compare Quotes</h4>
                                <p>Our consultancy excels providing quick solutions tailored </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-12">
                        <!--=== Bizzen Process Item ===-->
                        <div class="bizzen-process-item style-three mb-40" data-aos="fade-up" data-aos-duration="1400">
                            <div class="line"></div>
                            <div class="number">04</div>
                            <div class="content">
                                <h4>Solutions tailored</h4>
                                <p>Our consultancy excels providing quick solutions tailored </p>
                            </div>
                        </div>
                    </div>
                </div>
              
            </div>
            <!--=== Intro Wrapper ===-->
            <div class="intro-wrapper mb-80" data-aos="fade-up" data-aos-duration="1600">
                <h3 class="mb-20">Kye features</h3>
                <p class="mb-25">Our service guides you through the entire strategic planning process, from initial goal formulation to precise execution. Start with a thorough assessment of your current position and market landscape, then help you define clear, actionable objectives aligned with your vision. Our approach includes developing detailed action plans.</p>
                <p class="mb-25">Formulating and implementing business goals. We begin with an in-depth analysis of your business and market to identify opportunities and challenges. From there, we work with you to define clear, actionable.</p>
                <div class="bizzen-image-box">
                    <img src="assets/images/innerpage/service/intro-img1.jpg" alt="intro image">
                    <div class="play-button">
                        <a href="https://www.youtube.com/watch?v=H7jpB4WnAH8" class="video-popup"><img src="assets/images/innerpage/service/play-btn.png" alt="play button"></a>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</section>
<!--======  End Service Details Section  ======-->
                

@endsection