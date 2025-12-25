<div id="kt_app_header" class="app-header">
    <div class="app-container container-fluid d-flex align-items-stretch flex-stack" id="kt_app_header_container">
        <div class="d-flex align-items-center d-block d-lg-none ms-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px me-2" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
            <a href="{{ route('admin_dashboard') }}">
                <img alt="Logo" src="{{ asset('images/admin/logos/demo38-small.svg') }}" class="h-30px" />
            </a>
        </div>
        <div class="app-navbar flex-lg-grow-1" id="kt_app_header_navbar">
            <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1">
                <div id="kt_header_search" class="header-search d-flex align-items-center w-lg-200px" data-kt-search-keypress="true" data-kt-search-min-length="2" data-kt-search-enter="enter" data-kt-search-layout="menu" data-kt-search-responsive="true" data-kt-menu-trigger="auto" data-kt-menu-permanent="true" data-kt-menu-placement="bottom-start">
                    <div data-kt-search-element="toggle" class="search-toggle-mobile d-flex d-lg-none align-items-center">
                        <div class="d-flex">
                            <i class="ki-outline ki-magnifier fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
                <div class="cursor-pointer symbol symbol-circle symbol-35px symbol-md-45px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <img src="{{ auth()->user()->profile_picture && Storage::disk('savomart')->exists(auth()->user()->profile_picture) ? Storage::disk('savomart')->url(auth()->user()->profile_picture) : asset('images/admin/avatars/blank.png') }}" alt="user" />
                </div>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="{{ auth()->user()->profile_picture && Storage::disk('savomart')->exists(auth()->user()->profile_picture) ? Storage::disk('savomart')->url(auth()->user()->profile_picture) : asset('images/admin/avatars/blank.png') }}" />
                            </div>
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">{{ auth()->user()->name }}
                                </div>
                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->email }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="separator my-2"></div>
                    @can('user_update')
                        <div class="menu-item px-5">
                            <a href="{{ route('admin_user_edit', ['id' => auth()->user()->id]) }}" class="menu-link px-5">My Profile</a>
                        </div>
                    @endcan
                    <div class="separator my-2"></div>
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title position-relative">Mode
                                <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                    <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                    <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                                </span>
                            </span>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-night-day fs-2"></i>
                                    </span>
                                    <span class="menu-title">Light</span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-moon fs-2"></i>
                                    </span>
                                    <span class="menu-title">Dark</span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-screen fs-2"></i>
                                    </span>
                                    <span class="menu-title">System</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item px-5">
                        <a href="javascript:void(0)" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="menu-link px-5">Sign Out</a>
                        <form id="logout-form" action="{{ route('admin_logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-navbar-separator separator d-none d-lg-flex"></div>
    </div>
</div>
