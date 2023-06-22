<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>


{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">--}}
    {{--    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app_mobile.css') }}" media="screen and (max-width: 450px)">
    <link rel="stylesheet" href="{{ asset('css/app_tablet.css') }}" media="screen and (max-width: 820px) and (min-width: 460px)">

{{--        <script defer src="{{ asset('js/script.js')}}"></script>--}}

</head>
<body class="antialiased">
@include('inc.header')
<div class="main-cont">
    @yield('content')
</div>
@include('inc.footer')
@stack('script')
</body>
</html>
