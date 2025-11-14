@extends('layouts.app')

@section('content')

<!--======  Smooth Wrapper  ======-->
<div id="smooth-wrapper">
    <div id="smooth-content">
        <main>
            <!--======  Start Page Hero Section  ======-->
            <section class="page-hero bg_cover p-r z-1" style="background-image: url('{{ asset('assets/images/innerpage/bg/page-bg.png') }}')">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="page-content text-center text-black">
                                <h1 class="text-black">Contact us for inquiries</h1>
                                <ul class="">
                                    <li class="text-black"><a href="{{  route('index') }}">Home</a></li>
                                    <li class="text-black">Contact Us</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section><!--======  End Page Hero Section  ======-->
            <!--======  Start Contact Info Section  ======-->
            <section class="bizzen-contact-info-sec pt-105">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!--=== Section Title ===-->
                            <div class="section-title text-center mb-50">
                                <h2>Our Contact Information</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-xl-4 col-md-6 col-sm-12">
                            <!--=== Bizzen Info Box ===-->
                            <div class="bizzen-info-left-box mb-40">
                                <div class="icon">
                                    <i class="far fa-map-marker-alt"></i>
                                </div>
                                <div class="content">
                                    <h5>Our Address</h5>
                                    <p>374 William S Canning Blvd, Fall River MA 2721, USA</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12">
                            <!--=== Bizzen Info Box ===-->
                            <div class="bizzen-info-left-box mb-40">
                                <div class="icon">
                                    <i class="far fa-phone-alt"></i>
                                </div>
                                <div class="content">
                                    <h5>Contact Number</h5>
                                    <p><span>Mobile: <a href="tel:+13217322978">+13217322978</a></span></p>
                                    <p><span>Email: <a href="mailto:">saorhelp@gmail.com</a></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12">
                            <!--=== Bizzen Info Box ===-->
                            <div class="bizzen-info-left-box mb-40">
                                <div class="icon">
                                    <i class="far fa-clock"></i>
                                </div>
                                <div class="content">
                                    <h5>Our Address</h5>
                                    <p>Mon - Sat: 9:00 - 18:00</p>
                                    <p>Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section><!--======  End Contact Info Section  ======-->
            <!--======  Start Contact Section  ======-->
            <section class="bizzen-contact_two pt-80 pb-120">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-6 col-lg-10">
                            <!--=== Map Box ===-->
                            <div class="map-box mb-5 mb-xl-0" data-aos="fade-up" data-aos-duration="1300">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d96777.16150026117!2d-74.00840582560909!3d40.71171357405996!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1706508986625!5m2!1sen!2sbd" loading="lazy"></iframe>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-10">
                            <!--=== Contact Wrapper ===-->
                            <div class="contact-wrapper" data-aos="fade-left" data-aos-duration="1400">
                                <h2>Get In Touch</h2>
                                <form id="contact-form" class="contact-form" action="contact.php" method="POST">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="text" class="form_control" placeholder="Your Name" name="name" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="email" class="form_control" placeholder="Email Address" name="email" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="text" class="form_control" placeholder="Phone Number" name="phone" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea class="form_control" placeholder="Message" name="message" rows="5" cols="8"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <button class="theme-btn style-one">Send Message Us <i class="far fa-arrow-right"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section><!--======  End Contact Section  ======-->
        </main>
        <!--======  Start Footer  ======-->
        <footer class="main-footer">
            <div class="footer-shape"><img src="assets/images/footer/footer-shape.png" alt="footer shape"></div>
            <!--=== Footer Widget Wrapper ===-->
            <div class="footer-widget-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            <!--=== Footer Widget ===-->
                            <div class="footer-widget footer-about-widget pt-100" data-aos="fade-up" data-aos-duration="800">
                                <div class="widget-content">
                                    <div class="footer-logo mb-20">
                                        <a href="index.html"><img src="assets/images/innerpage/logo/logo-white.png" alt="logo white"></a>
                                    </div>
                                    <p class="mb-20">Sign up to searing weekly newsletter to get the latest updates</p>
                                    <form>
                                        <div class="form-group">
                                            <input type="email" class="form_control" placeholder="Enter Email Address" name="email" required>
                                            <button class="submit-btn"><i class="far fa-paper-plane"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <!--=== Footer Widget Wrap ===-->
                            <div class="footer-widget-inner">
                                <!--=== Footer Top ===-->
                                <div class="footer-top" data-aos="fade-up" data-aos-duration="1000">
                                    <div class="big-text">Let’s talk Business?</div>
                                </div>
                                <div class="footer-widget-area">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <!--=== Footer Widget ===-->
                                            <div class="footer-widget footer-contact-info-widget mb-40" data-aos="fade-up" data-aos-duration="1200">
                                                <div class="widget-content">
                                                    <h6>Headquarters - USA</h6>
                                                    <ul>
                                                        <li>
                                                            90 Washington. mg Manchester, USA
                                                        </li>
                                                        <li>
                                                            <a href="tel:+1(980)3605-6302">+1 (980) 3605 - 6302</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <!--=== Footer Widget ===-->
                                            <div class="footer-widget footer-contact-info-widget mb-40" data-aos="fade-up" data-aos-duration="1400">
                                                <div class="widget-content">
                                                    <h6>Headquarters - UK</h6>
                                                    <ul>
                                                        <li>
                                                            993 Renner Burg, West Rond, MT 94251-030
                                                        </li>
                                                        <li>
                                                            <a href="mailto:company@gmail.com">company@gmail.com</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <!--=== Footer Widget ===-->
                                            <div class="footer-widget footer-social-widget mb-40" data-aos="fade-up" data-aos-duration="1600">
                                                <h4 class="widget-title">Follow Us:</h4>
                                                <div class="widget-content">
                                                    <div class="social-box">
                                                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                                                        <a href="#"><i class="fab fa-twitter"></i></a>
                                                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                                        <a href="#"><i class="fab fa-pinterest-p"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--=== Copyright Area ===-->
            <div class="copyright-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <!--=== Copyright Text ===-->
                            <div class="copyright-text text-md-start text-center">
                                <p>&copy; All copyright 2025 by Bizzen</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!--=== Copyright link ===-->
                            <div class="copyright-link text-md-end text-center">
                                <a href="index.html">Terms & Conditions</a>
                                <a href="index.html">Privacy Policy</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

@endsection