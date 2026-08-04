<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- CSRF Token --> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>IGRCFP Admin</title>
  <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/png">      
  <!-- remix icon font css  -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/remixicon.css')}}">
  <!-- BootStrap css -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/lib/bootstrap.min.css')}}">
  <!-- Apex Chart css -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/lib/apexcharts.css')}}">
  <!-- Data Table css -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/lib/dataTables.min.css')}}">
  <!-- Text Editor css --> 
  <link rel="stylesheet" href="{{ asset('assets/admin/css/lib/editor-katex.min.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/lib/editor.atom-one-dark.min.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/lib/editor.quill.snow.css')}}">
  <!-- Date picker css -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/lib/flatpickr.min.css')}}">
  <!-- Calendar css -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/lib/full-calendar.css')}}">
  <!-- Vector Map css -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/lib/jquery-jvectormap-2.0.5.css')}}">
  <!-- Popup css -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/lib/magnific-popup.css')}}">
  <!-- Slick Slider css -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/lib/slick.css')}}">
  <!-- main css -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css')}}">
  <!-- In the <head> section of resources/views/admin/layouts/app.blade.php -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body> 
    @include('admin.layouts.sidebar')
   

    <main class="dashboard-main">
        @include('admin.layouts.navbar')
    
        @yield('content')
        @include('admin.layouts.footer')

    </main>
  
    <!-- jQuery library js -->
    <script src="{{ asset('assets/admin/js/lib/jquery-3.7.1.min.js')}}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('assets/admin/js/lib/bootstrap.bundle.min.js')}}"></script>
    <!-- Apex Chart js -->
    <script src="{{ asset('assets/admin/js/lib/apexcharts.min.js')}}"></script>
    <!-- Data Table js -->
    <script src="{{ asset('assets/admin/js/lib/dataTables.min.js')}}"></script>
    <!-- Iconify Font js -->
    <script src="{{ asset('assets/admin/js/lib/iconify-icon.min.js')}}"></script>
    <!-- jQuery UI js -->
    <script src="{{ asset('assets/admin/js/lib/jquery-ui.min.js')}}"></script>
    <!-- Vector Map js -->
    <script src="{{ asset('assets/admin/js/lib/jquery-jvectormap-2.0.5.min.js')}}"></script>
    <script src="{{ asset('assets/admin/js/lib/jquery-jvectormap-world-mill-en.js')}}"></script>
    <!-- Popup js -->
    <script src="{{ asset('assets/admin/js/lib/magnifc-popup.min.js')}}"></script>
    <!-- Slick Slider js -->
    <script src="{{ asset('assets/admin/js/lib/slick.min.js')}}"></script>
    <!-- main js -->
    <script src="{{ asset('assets/admin/js/app.js')}}"></script>
    <script src="{{ asset('assets/admin/js/homeOneChart.js')}}"></script>
    <script>
        window.adminNotificationRoutes = {
            recent: @json(route('admin.notifications.recent')),
            markReadTemplate: @json(route('admin.notifications.mark-read', ['notification' => '__ID__'])),
            markAllRead: @json(route('admin.notifications.mark-all-read')),
        };
    </script>
    <script src="{{ asset('assets/admin/js/admin-notifications.js')}}"></script>
    @stack('scripts')
    

</body>
</html>
