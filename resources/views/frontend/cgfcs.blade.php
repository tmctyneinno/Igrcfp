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
                    <h2 style="color: black;">Certified GRC & Financial Crime Specialist (CGFCS)</h2>
                        <ul >
                            <li ><a class="text-black" href="{{ route('index') }}">Home /</a></li>
                            <li ><a class="text-black" href="{{ route('certification') }}">Certification & Training /</a></li>
                            <li class="text-black">Certified GRC & Financial Crime Specialist (CGFCS)</li>
                        </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!--======  End Page Hero Section  ======-->


 <!--======  Start Who We Section  ======-->
<section class="bizzen-we_two pt-20 pb-20">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-10" >
                <!--=== Bizzen Content Box ===-->
                <div  class="bizzen-content-box">
                        <!--=== Section Title ===-->
                        <div class="section-title mb-30">
                            {{-- <span class="sub-title" data-aos="fade-down" data-aos-duration="1000">Who we are</span> --}}
                            <h2 class="text-anm">Overview</h2>
                            <div class="bizzen-image-box mb-5 mb-xl-0 " >
                            <p class="mb-3 " data-aos="fade-up" data-aos-duration="1200">
                                The Certified GRC & Financial Crime Specialist (CGFCS) program is the Institute’s  flagship professional 
                                certification. It equips participants with comprehensive  knowledge and practical tools to address the challenges 
                                of governance, risk  management, compliance, and financial crime prevention in today’s complex global  environment.  
                            </p>
                            
                            <p data-aos="fade-up" data-aos-duration="1800">
                                This program is ideal for professionals in banking, insurance, fintech, regulatory  agencies, and corporate governance, 
                                and is benchmarked to international best  practices
                            </p>
                            <div class="bizzen-button mt-30"  data-aos="fade-up" data-aos-duration="1800">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-10">
                <!--=== Bizzen Image Box ===-->
                <div style="background-color: #F4F8FE; padding:20px; border-radius:8px" class="bizzen-image-box mb-5 mb-xl-0 " >
                    <h6 class="mb-15">Benefits:</h6>
                    <ul class="check-list style-three mb-40" >
                        <li>Duration: 8–10 weeks (part-time) or 5-day intensive bootcamp</li>
                        <li class="pt-10">Mode: Online/ Hybrid/ In-person  masterclass options</li>
                        <li class="pt-10">Accreditation: CPD certified</li>
                        <li class="pt-10">Faculty: Global compliance practitioners, regulators, and industry experts</li>
                        <li class="pt-10">Fee: £950 (Individual Fees) Corporate Package: Discounts available for teams of 5+ participants</li>
                    </ul>
                    <div class="bizzen-button mt-30"  data-aos="fade-up" data-aos-duration="1800">
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
<!--======  End Who We Section  ======-->

 <!--======  Start Who We Section  ======-->
<section class="bizzen-we_two pt-120 pb-50">
    <div class="container">
        
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-10">
                <!--=== Bizzen Content Box ===-->
                <div class="bizzen-content-box">
                    <!--=== Section Title ===-->
                    <div class="section-title mb-30">
                        <span class="sub-title" data-aos="fade-down" data-aos-duration="1000">Objectives</span>
                        <h2 class="text-anm">Learning Objectives</h2>
                    </div>
                     <div class="member-info">
                    <div class="bizzen-content-box" data-aos="fade-up" data-aos-duration="1600">
                        <h6>By the end of this certification, participants will be able to:</h6>
                        <ul class="check-list style-one mb-40 mt-20">
                            <li><i class="far fa-check"></i>
                                Understand and apply core GRC frameworks in financial and non-financial  institutions.
                            </li>
                            <li><i class="far fa-check mt-10"></i>
                                Identify, assess, and mitigate financial crime risks, including AML/CTF, fraud,  bribery, and cyber-enabled threats.
                            </li>
                            <li><i class="far fa-check mt-10"></i>
                                Implement effective compliance and governance programs aligned with  regulatory expectations.
                            </li>
                            <li><i class="far fa-check mt-10"></i>
                                Apply risk-based approaches to due diligence, monitoring, and reporting  obligations.
                            </li>
                             <li><i class="far fa-check mt-10"></i>
                                Integrate ESG compliance, AI/RegTech solutions, and digital transformation  into risk and compliance strategies.
                            </li>
                        </ul>
                    </div>
                     </div>
                    
                </div>
            </div>
             <div class="col-xl-2 col-lg-10"></div>
            
        </div>
    </div>
</section>
<!--======  End Who We Section  ======-->

<!--======  Start Become Our Membership ======-->
<section class="bizzen-service_one pt-115  bg_cover pb-50" style="background-image: url(assets/images/home-one/bg/service-bg.png);">
    <div class="container">
        <div class="row ">
            <div class="col-xl-6 col-lg-8">
                <!--=== Section Title ===-->
                <div class="section-title mb-60">
                    <span class="sub-title" data-aos="fade-down" data-aos-duration="1000"> Enrollment</span>
                    <h2 class="text-anm">Who Can Enroll?</h2>
                </div>
            </div>
        </div>
        <div class="row ">
           <div class="col-xl-3 col-md-6">
            <div class="bizzen-service-item style-one mb-30" style="background-color: #0097A7 !important;">
                <div class="service-inner-content" >
                    <div class="icon"><img src="{{ asset('assets/images/home-three/icon/icon8.svg')}}" alt="icon" style="width:50px"></div>
                    <div class="">
                        <p class="title">
                            <a href="#"  style=" font-weight: 400; color: #F0F0F0;">Compliance Officers & AML Specialists</a>
                        </p>
                    </div>
                </div>
            </div>
            </div>

            <div class="col-xl-3 col-md-6">
            <div class="bizzen-service-item style-one mb-30" style="background-color: #0097A7;">
                <div class="service-inner-content">
                <div class="icon"><img src="{{ asset('assets/images/home-three/icon/icon8.svg')}}" alt="icon" style="width:50px"></div>
                <div class="">
                    <p class="title">
                    <a href="#"  style=" font-weight: 400; color: #F0F0F0;">
                        Risk & Internal Audit Professionals</a>
                    </p>
                </div>
                </div>
            </div>
            </div>

           <div class="col-xl-3 col-md-6">
            <div class="bizzen-service-item style-one mb-30" style="background-color: #0097A7;">
                <div class="service-inner-content">
                <div class="icon"><img src="{{ asset('assets/images/home-three/icon/icon8.svg')}}" alt="icon" style="width:50px"></div>
                <div class=""><p class="title">
                    <a href="#" style="font-weight: 400; color: #F0F0F0;">
                        Regulators & Supervisory Authorities</a></p>
                    </div>
                </div>
            </div>
            </div>

           <div class="col-xl-3 col-md-6">
            <div class="bizzen-service-item style-one mb-30" style="background-color: #0097A7;">
                <div class="service-inner-content" style="padding-bottom: 0px;">
                <div class="icon"><img src="{{ asset('assets/images/home-three/icon/icon8.svg')}}" alt="icon" style="width:50px"></div>
                <div class="">
                    <p class="title">
                        <a href="#" style="font-weight: 400; color: #F0F0F0;">
                        Governance & ESG Leaders
                        </a>
                    </p>
                    <br/> <br/>
                </div>
                </div>
            </div>
            </div>

        </div>
    </div>
</section>
<!--======  End Become Our Membership ======-->

 <section class="bizzen-blog-grid-sec pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-md-6 col-sm-12" >
                <!--=== Bizzen Blog Post ===-->
                <div style="background-color: #E8EDF7; border-radius:8px" class="bizzen-blog-post-item style-two mb-35" data-aos="fade-up" data-aos-duration="1000">
                   
                    <div class="post-content">
                        <h4 class="title"><a href="blog-details.html">Module 1:  Foundations of GRC</a></h4>
                        <ul class="check-list style-two" data-aos="fade-up" data-aos-duration="1200">
                            <li>Principles of Governance, Risk, and Compliance</li>
                            <li>International frameworks and standards (ISO, COSO, Basel, FATF)</li>
                            <li>Ethics, integrity, and accountability in leadership</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12" >
                <!--=== Bizzen Blog Post ===-->
                <div class="bizzen-blog-post-item style-two mb-35" data-aos="fade-up" data-aos-duration="1200" style="background-color: #E8EDF7; border-radius:8px">
                    
                    <div class="post-content">
                        <h4 class="title"><a href="blog-details.html">Module 2:  Financial Crime Fundamentals</a></h4>
                        <ul>
                            <li>Money laundering, terrorist financing, and proliferation financing</li>
                            <li>Fraud, bribery, corruption, and insider trading</li>
                            <li>Case studies: African & global perspectives</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <!--=== Bizzen Blog Post ===-->
                <div class="bizzen-blog-post-item style-two mb-35" data-aos="fade-up" data-aos-duration="1400">
                    <div class="post-thumbnail">
                        <img src="assets/images/innerpage/blog/blog-grid3.jpg" alt="Blog Grid">
                        <div class="date">21 <span>july</span></div>
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span><i class="far fa-user"></i> By <a href="#">Admin</a></span>
                            <span><i class="far fa-comment"></i><a href="#">Comments (03)</a></span>
                        </div>
                        <h4 class="title"><a href="blog-details.html">Why every entrepreneur needs solid digital marketing</a></h4>
                        <p>Our mission is to empowers businesses off our all size too thrive in an businesses ever changing </p>
                        <a href="blog-details.html" class="read-more style-one">Read More <i class="far fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <!--=== Bizzen Blog Post ===-->
                <div class="bizzen-blog-post-item style-two mb-35" data-aos="fade-up" data-aos-duration="1600">
                    <div class="post-thumbnail">
                        <img src="assets/images/innerpage/blog/blog-grid4.jpg" alt="Blog Grid">
                        <div class="date">21 <span>july</span></div>
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span><i class="far fa-user"></i> By <a href="#">Admin</a></span>
                            <span><i class="far fa-comment"></i><a href="#">Comments (03)</a></span>
                        </div>
                        <h4 class="title"><a href="blog-details.html">What consultants should know about working with nonprofits</a></h4>
                        <p>Our mission is to empowers businesses off our all size too thrive in an businesses ever changing </p>
                        <a href="blog-details.html" class="read-more style-one">Read More <i class="far fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <!--=== Bizzen Blog Post ===-->
                <div class="bizzen-blog-post-item style-two mb-35" data-aos="fade-up" data-aos-duration="1800">
                    <div class="post-thumbnail">
                        <img src="assets/images/innerpage/blog/blog-grid5.jpg" alt="Blog Grid">
                        <div class="date">21 <span>july</span></div>
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span><i class="far fa-user"></i> By <a href="#">Admin</a></span>
                            <span><i class="far fa-comment"></i><a href="#">Comments (03)</a></span>
                        </div>
                        <h4 class="title"><a href="blog-details.html">Innovative solutions for business success dynamic from today</a></h4>
                        <p>Our mission is to empowers businesses off our all size too thrive in an businesses ever changing </p>
                        <a href="blog-details.html" class="read-more style-one">Read More <i class="far fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <!--=== Bizzen Blog Post ===-->
                <div class="bizzen-blog-post-item style-two mb-35" data-aos="fade-up" data-aos-duration="2000">
                    <div class="post-thumbnail">
                        <img src="assets/images/innerpage/blog/blog-grid6.jpg" alt="Blog Grid">
                        <div class="date">21 <span>july</span></div>
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span><i class="far fa-user"></i> By <a href="#">Admin</a></span>
                            <span><i class="far fa-comment"></i><a href="#">Comments (03)</a></span>
                        </div>
                        <h4 class="title"><a href="blog-details.html">Spending Habits, 13 Tips for grow Your Money.</a></h4>
                        <p>Our mission is to empowers businesses off our all size too thrive in an businesses ever changing </p>
                        <a href="blog-details.html" class="read-more style-one">Read More <i class="far fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="theme-pagination text-center mt-30" data-aos="fade-up" data-aos-duration="2200">
                    <ul>
                        <li><a href="#"><i class="far fa-arrow-left"></i></a></li>
                        <li><a href="#">01</a></li>
                        <li><a href="#">02</a></li>
                        <li><a href="#">03</a></li>
                        <li><a href="#"><i class="far fa-arrow-right"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection