 <!--=== Start  Header Area  ===-->
<header class="header-area header-three">
    <!--====  Header Navigation  ===-->
    <div class="header-navigation">
        <div class="container-fluid">
            <!--====  Primary Menu  ===-->
            <div class="primary-menu">
                <!--====  Site Branding  ===-->
                <div class="site-branding">
                    <a href="{{ route('index') }}" class="brand-logo">
                        <img src="{{ asset('assets/images/home-three/logo/logo-main.png')}}" style="width:60px" alt="Brand Logo"></a>
                </div>
                <!--=== Main Menu ===-->
                <div class="theme-nav-menu">
                    <!--=== Menu Top ===-->
                    <div class="theme-menu-top d-block d-xl-none">
                        <div class="site-branding">
                            <a href="{{ route('index') }}" class="brand-logo">
                                <img src="{{ asset('assets/images/home-three/logo/logo-main.png')}}" style="width:60px" alt="Brand Logo"></a>
                        </div>
                    </div>
                    <!--=== Main Menu ===-->
                    <nav class="main-menu">
                        <ul>
                            <li class="menu-item"><a href="{{ route('index') }}">Home</a></li>

                            <li class="menu-item has-children">
                                <a href="{{ route('about-us') }}">About Us +</a>
                                <ul class="sub-menu">
                                    <li><a href="index.html">Welcome to IGRCFP</a></li>
                                    <li><a href="index-2.html">Our Structure</a></li>
                                </ul>
                            </li>
                            <li class="menu-item"><a href="{{ route('membership') }}">Membership</a></li>
                            <li class="menu-item"><a href="{{ route('certification') }}">Certifications & Training</a></li>
                            <li class="menu-item"><a href="{{ route('event') }}">Event</a></li>
                            <li class="menu-item"><a href="{{ route('blog') }}">Blog</a></li>

                            <li class="menu-item"><a href="contact.html">Contact</a></li>
                        </ul>
                    </nav>
                    <!--=== Nav Button ===-->
                    <div class="theme-nav-button mt-20 d-block d-md-none">
                        <a href="contact.html" class="theme-btn style-one">Get Involved<i class="far fa-arrow-right"></i></a>
                    </div>
                    <!--===  Menu Bottom ===-->
                    <div class="theme-menu-bottom mt-50 d-block d-xl-none">
                        <h5>Follow Us</h5>
                        <ul class="social-link">
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
                <!--=== Header Nav Right ===-->
                <div class="nav-right-item">
                    <div class="nav-button d-none d-md-block">
                        <a href="contact.html" class="theme-btn style-one">Get A Quote<i class="far fa-arrow-right"></i></a>
                    </div>
                    <div class="navbar-toggler">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</header>
<!--=== End  Header Area  ===-->
