<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!--====== SEO / Social Sharing Meta ======-->
        <meta name="description" content="IGRCFP — The Institute of Governance, Risk, Compliance & Financial Crime Prevention. Building professionals. Strengthening institutions. Advancing a safer, more resilient world.">

        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ config('app.name', 'IGRCFP') }}">
        <meta property="og:title" content="{{ config('app.name', 'IGRCFP') }} — Institute of Governance, Risk, Compliance & Financial Crime Prevention">
        <meta property="og:description" content="Building professionals. Strengthening institutions. Advancing a safer, more resilient world.">
        <meta property="og:image" content="{{ asset('assets/images/logo-main.png') }}">
        <meta property="og:image:width" content="512">
        <meta property="og:image:height" content="512">
        <meta property="og:url" content="{{ url()->current() }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ config('app.name', 'IGRCFP') }} — Institute of Governance, Risk, Compliance & Financial Crime Prevention">
        <meta name="twitter:description" content="Building professionals. Strengthening institutions. Advancing a safer, more resilient world.">
        <meta name="twitter:image" content="{{ asset('assets/images/logo-main.png') }}">

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
        {{-- Admin --}}
         <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
    <!-- Tawk.to Chat Widget -->
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6a2133802e923e1c2967e150/1jq8r2d4j';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->
</html>