@extends('layouts.app')

@section('content')
<!--====== Start Contact Section ======-->
<section class="bizzen-contact_two pt-100 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <!--=== Section Title ===-->
                <div class="section-title text-center mb-50">
                    <div class="site-branding">
                        <a href="{{ route('index') }}" class="brand-logo">
                            <img src="{{ asset('assets/images/home-three/logo/logo-main.png')}}" style="width:60px" alt="Brand Logo">
                        </a>
                    </div>
                    <h2>Join Us!</h2>
                    <p>Please provide your details</p>
                    
                    <!-- Google Sign Up Button -->
                    <a href="{{ route('certification') }}" class="theme-btn style-two w-100" style="border-radius: 8px">
                        <img src="https://www.gstatic.com/images/branding/product/1x/gsa_48dp.png" 
                            alt="Google Logo" 
                            style="width: 20px; height: 20px; margin-right: 8px; vertical-align: middle;">
                        Continue with Google
                    </a>
                </div>

                <!-- Divider -->
                <div class="row align-items-center my-4">
                    <div class="col">
                        <hr>
                    </div>
                    <div class="col-auto">
                        <p class="mb-0 text-muted">Or With Email</p>
                    </div>
                    <div class="col">
                        <hr>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-6">
                <!--=== Contact Wrapper ===-->
                <div class="contact-wrapper" data-aos="fade-left" data-aos-duration="1400">
                    
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="contact-form" class="contact-form" action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-lg-12 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name"
                                           placeholder="Your Full Name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-lg-12 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email"
                                           placeholder="Enter Email Address" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- LinkedIn URL -->
                            <div class="col-lg-12 mb-3">
                                <label for="linkedin_url" class="form-label">LinkedIn</label>
                                <div class="input-group">
                                    <input type="url" 
                                           class="form-control @error('linkedin_url') is-invalid @enderror" 
                                           id="linkedin_url"
                                           placeholder="Enter your LinkedIn URL" 
                                           name="linkedin_url" 
                                           value="{{ old('linkedin_url') }}" 
                                           required>
                                    @error('linkedin_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="col-lg-12 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" 
                                            stroke-linecap="round" stroke-linejoin="round" 
                                            viewBox="0 0 24 24">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password"
                                           placeholder="Enter Password" 
                                           name="password" 
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-lg-12 mb-4">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" 
                                            stroke-linecap="round" stroke-linejoin="round" 
                                            viewBox="0 0 24 24">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation"
                                           placeholder="Confirm Password" 
                                           name="password_confirmation" 
                                           required>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-lg-12">
                                <button type="submit" class="theme-btn style-one w-100" style="border-radius: 8px">
                                    Register 
                                    <i class="far fa-arrow-right ms-12"></i>
                                </button>
                            </div>

                            <!-- Login Link -->
                            <div class="col-lg-12 text-center mt-3">
                                <p class="text-muted">
                                    Already have an account? 
                                    <a href="{{ route('login') }}" class="text-primary text-decoration-underline">Sign In</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section><!--====== End Contact Section ======-->
@endsection