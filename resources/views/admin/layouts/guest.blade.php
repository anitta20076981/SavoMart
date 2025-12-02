<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-template="vertical-menu-template">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="ThemeSelect">
    <title>@yield('title', 'Blank') | {{ config('settings.company_name') ?? 'SavoMart Cms' }}</title>
    <link rel="apple-touch-icon" href="{{ asset('images/admin/logos/logo111.jpeg') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/admin/logos/logo111.jpeg') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

    <link href="{{ mix('css/admin/layouts/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ mix('css/admin/layouts/app.css') }}" rel="stylesheet" type="text/css" />
    @stack('style')
</head>


<body id="kt_body" class="app-blank app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
    <div class="d-flex flex-column flex-root">
        {{ $slot }}
    </div>

    @stack('script')
</body>

</html>