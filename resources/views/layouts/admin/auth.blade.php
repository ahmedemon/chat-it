@php
    $admin = App\Models\Admin::where('id', $_GET['panel'] ?? 1)->first();
    if ($admin != null) {
        $admin_id = $admin->id;
    } else {
        $admin_id = 1;
    }
    
    $setting = App\Models\Setting::where('admin_id', $admin_id)->first();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} - {{ $setting->link_title ?? 'Dashboard' }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ $setting->site_logo ? asset('storage/admin/' . $setting->site_logo) : asset('assets/chat-head.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('user/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/plugins/morris/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/style.css') }}">
</head>

<body>
    <div class="main-wrapper">
        <div class="account-page">
            @yield('auth-content')
        </div>
    </div>
    <script src="{{ asset('user/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('user/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('user/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('user/js/app.js') }}"></script>
</body>

</html>
