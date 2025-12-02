<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-template="vertical-menu-template">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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


<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            @include('admin.layouts.topbar')
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('admin.layouts.navigation')
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                    @include('admin.layouts.footer')
                </div>
            </div>
        </div>
    </div>

    <div class="general-notification d-none col-md-6">
        @if (session('success'))
            <div id="sessionSuccess" class="hide">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div id="sessionError" class="hide">{{ session('error') }}</div>
        @endif
    </div>

    <div id="model-area"></div>
    <div id="drawer-area"></div>

    @stack('script')
</body>

</html>