<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
       
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
       
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
        <!--====== Favicon Icon ======-->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/png">
        <!--====== Google Fonts ======--> 
        <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
        <!--====== FontAwesome css ======-->
        <link rel="stylesheet" href="{{asset('assets/fonts/fontawesome/css/all.min.css')}}">
        <!--====== Bootstrap css ======-->
        <link rel="stylesheet" href="{{asset('assets/css/plugins/bootstrap.min.css')}}">
        <!--====== Slick-popup css ======-->
        <link rel="stylesheet" href="{{asset('assets/css/plugins/slick.css')}}">
        <!--====== Magnific-popup css ======-->
        <link rel="stylesheet" href="{{asset('assets/css/plugins/magnific-popup.css')}}">
        <!--====== Aos css ======-->
        <link rel="stylesheet" href="{{asset('assets/css/plugins/aos.css')}}">
        <!--====== Default css ======-->
        <link rel="stylesheet" href="{{asset('assets/css/spacings.css')}}">
        <!--====== Default css ======-->
        <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
         {{-- @include('layouts.footer') --}}
    </body>
</html>
