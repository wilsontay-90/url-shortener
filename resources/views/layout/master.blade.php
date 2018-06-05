<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URL Shortener</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('before_styles')

    <link rel="stylesheet" href="{{ asset(mix('/css/app.css')) }}">

    @yield('after_styles')
</head>

<body>

<div class="container">

    <div class="row">
        @yield('contents')
    </div>

</div>

@yield('before_scripts')
<script src="{{ secure_asset('js/jquery.min.js') }}"></script>
<script src="{{ secure_asset('js/vendor.js') }}"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@yield('after_scripts')


</body>

</html>