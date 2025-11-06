 <!--======  Start Footer  ======-->
<footer class="main-footer footer-v2 pt-100">
<div class="footer-shape"><img src="assets/images/footer/footer-shape.png" alt="footer shape"></div>
<div class="footer-widget-area pb-60">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-md-6 col-sm-12">
                <!--=== Footer Widget ===-->
                <div class="footer-widget footer-about-widget mb-40" data-aos="fade-up" data-aos-duration="800">
                    <div class="widget-content">
                        <div class="footer-logo mb-30">
                            <img class="img-fluid w-25 d-block"  src="{{ asset('assets/images/home-three/logo/logo-main.png')}}" alt="Logo White">
                        </div>
                        <p>Welcome to our best Business Consulting Agency, because it is pain, but occasionally circumstances.</p>
                        <div class="social-box">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-pinterest-p"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-lg-6">
                        <!--=== Footer Widget ===-->
                        <div class="footer-widget footer-nav-widget mb-40" data-aos="fade-up" data-aos-duration="1000">
                            <div class="widget-content">
                                <h4 class="widget-title">Explore</h4>
                                <ul class="widget-nav">
                                    <li><a href="{{ route('about-us') }}">About</a></li>
                                    <li><a href="{{ route('services') }}">Services</a></li>
                                    <li><a href="{{ route('structure') }}">Our Structure</a></li>

                                    <li><a href="{{ route('certification') }}">Cerification</a></li>
                                    <li><a href="{{ route('contact') }}">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!--=== Footer Widget ===-->
                        <div class="footer-widget footer-contact-info-widget mb-40" data-aos="fade-up" data-aos-duration="1200">
                            <div class="widget-content">
                                <h4 class="widget-title">Contact</h4>
                                <ul>
                                    <li>
                                        85, Great Portland Street First Floor W1W 7LT, London, United Kingdom
                                    </li>
                                    <li>
                                        <a href="mailto:info@igrcfp.org">info@igrcfp.org</a>
                                        <a href="tel:+353877123968">+353877123968, +442078560149</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <!--=== Footer Widget ===-->
                <div class="footer-widget footer-newsletter-widget" data-aos="fade-up" data-aos-duration="1400">
                    <div class="widget-content">
                        <h4 class="widget-title">Newsletter</h4>
                        <p>Sign up to searing weekly newsletter to get the latest updates</p>
                        <form>
                            <div class="form-group">
                                <input type="email" class="form_control" placeholder="Enter Email Address" name="email" required>
                                <button class="submit-btn"><i class="far fa-paper-plane"></i></button>
                            </div>
                        </form>
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
                    <p>&copy; {{ date('Y') }} IGRCFP. All rights reserved.</p>
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

<!--====== Jquery js ======-->
<script src="{{ asset('assets/js/plugins/jquery-3.7.1.min.js')}}"></script>
<!--====== Bootstrap js ======-->
<script src="{{ asset('assets/js/plugins/popper.min.js')}}"></script>
<!--====== Bootstrap js ======-->
<script src="{{ asset('assets/js/plugins/bootstrap.min.js')}}"></script>
<!--====== Gsap Js ======-->
<script src="{{ asset('assets/js/plugins/gsap/gsap.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/gsap/SplitText.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/gsap/ScrollSmoother.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/gsap/ScrollTrigger.min.js')}}"></script>
<!--====== Slick js ======-->
<script src="{{ asset('assets/js/plugins/slick.min.js')}}"></script>
<!--====== Magnific js ======-->
<script src="{{ asset('assets/js/plugins/jquery.magnific-popup.min.js')}}"></script>
<!--====== Waypoint js ======-->
<script src="{{ asset('assets/js/plugins/jquery.waypoints.js')}}"></script>
<!--====== CounterUp js ======-->
<script src="{{ asset('assets/js/plugins/jquery.counterup.min.js')}}"></script>
<!--====== Aos js ======-->
<script src="{{ asset('assets/js/plugins/aos.js')}}"></script>
<!--====== Common js ======-->
<script src="{{ asset('assets/js/theme.js')}}"></script>