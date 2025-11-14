@extends('layouts.app')

@section('content')
<section class="bizzen-contact_two pt-100 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z" fill="#4CAF50"/>
                        </svg>
                    </div>

                    <div class="section-title text-center mb-50">
                        <h2 class="text-success mb-3">Welcome Aboard!</h2>
                        <p class="lead mb-4">Your registration was successful. We're excited to have you on board.</p>
                        
                        @if(session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="mt-5">
                            <a href="{{ route('login') }}" class="theme-btn style-one me-3" style="border-radius: 8px">
                                Proceed to Sign In
                            </a>
                            <a href="{{ route('index') }}" class="theme-btn style-two" style="border-radius: 8px">
                                Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection