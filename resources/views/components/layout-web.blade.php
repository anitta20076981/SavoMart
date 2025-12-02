<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AMC</title>
    <meta content="" name="description">

    <meta content="" name="keywords">


    <link href="{{ asset('images/web/fav.png') }}" rel="icon">

    <link rel="stylesheet" href="{{ mix('css/web/layout/app.css') }}">


    @stack('styles')

</head>

<div>
    <div class="col-12 header-contr">
        @include('web.blocks.topbar')
        @include('web.blocks.navbar')
    </div>

    {{ $slot }}

    @include('web.blocks.footer')

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <div class="general-notification col-md-6" style="display:none">
        @if (session('success'))
        <div id="sessionSuccess">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div id="sessionError">{{ session('error') }}</div>
        @endif
    </div>


    @stack('scripts')

    </body>

</html>