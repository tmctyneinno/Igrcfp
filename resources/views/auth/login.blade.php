@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" id="login-form">
                        @csrf
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- reCAPTCHA v2 with explicit rendering --}}
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div id="recaptcha-container"></div>
                                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                                @error('g-recaptcha-response')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit" async defer></script>
<script>
    let recaptchaWidget;
    
    window.onRecaptchaLoad = function() {
        recaptchaWidget = grecaptcha.render('recaptcha-container', {
            'sitekey': '{{ config('services.recaptcha.site_key') }}',
            'callback': function(response) {
                document.getElementById('g-recaptcha-response').value = response;
            },
            'expired-callback': function() {
                document.getElementById('g-recaptcha-response').value = '';
            }
        });
        console.log('reCAPTCHA widget rendered');
    };
    
    // Form submission handler to ensure reCAPTCHA is completed
    document.getElementById('login-form').addEventListener('submit', function(e) {
        const recaptchaResponse = document.getElementById('g-recaptcha-response').value;
        if (!recaptchaResponse) {
            e.preventDefault();
            alert('Please complete the reCAPTCHA verification.');
            return false;
        }
    });
</script>
@endpush