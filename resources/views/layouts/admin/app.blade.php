<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

    <meta name="title" content="Travel Agency Management Software Bangladesh || Travel & Tours Management System | Dhaka | Bangladesh">
    <meta name="description" content="Travel Agency Management Software Bangladesh ||
    Travel & Tours Management System | Dhaka | Bangladesh , bangladesh travel agency list ,
    travel agency management system project, travel agency system, online travel agency software, Best Travel Agency Software BD,
    Travel Agency Software: Booking, Management and Tour">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? 'Dashboard' }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('user/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('user/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/plugins/morris/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/sidebar-left.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('user/css/header-menu.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('user/css/sidebar-right.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('user/css/style.css') }}">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
</head>

<body>
    <div class="main-wrapper">
        {{-- @include('sweetalert::alert') --}}
        @include('layouts.admin.sidebar')


        <div class="page-wrapper">
            <div class="content container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('user/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('user/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('user/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('user/js/select2.min.js') }}"></script>
    <script src="{{ asset('user/js/moment.min.js') }}"></script>
    <script src="{{ asset('user/js/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('user/js/jquery.fullcalendar.js') }}"></script>
    <script src="{{ asset('user/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('user/plugins/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('user/js/apexcharts.js') }}"></script>
    <script src="{{ asset('user/js/chart-data.js') }}"></script>
    <script src="{{ asset('user/js/app.js') }}"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    {{-- {!! Toastr::message() !!} --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function comingSoon() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Please be patient',
                text: 'This feature is coming soon!',
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>

    @stack('js')
</body>

</html>
