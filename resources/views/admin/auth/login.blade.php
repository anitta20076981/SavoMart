@section('title', 'Login')

@push('style')
    <link rel="stylesheet" href="{{ mix('css/admin/auth/login.css') }}">
@endpush

@push('script')
    <script src="{{ mix('js/admin/auth/login.js') }}"></script>
@endpush

<x-admin-guest-layout>
    <div class="d-flex flex-column flex-column-fluid flex-lg-row">
        <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
            <div class="d-flex flex-center flex-lg-start flex-column">
                <a href="{{ route('admin_dashboard') }}" class="mb-7">
                    <img alt="Logo" src="{{ asset('images/admin/logos/logo111.jpeg') }}" />
                </a>
            </div>
        </div>
        <div class="d-flex flex-center w-lg-50 p-10">
            <div class="card rounded-3 w-md-550px">
                <div class="card-body p-10 p-lg-20">
                    <form novalidate="novalidate" class="form w-100" id="loginForm" method="post" action="{{ route('admin_login') }}">
                        @csrf
                        <div class="text-center mb-11">
                            <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                        </div>
                        <div class="fv-row mb-8">
                            <input type="text" class="form-control bg-transparent" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
                            @error('email')
                                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                            @enderror
                        </div>
                        <div class="fv-row mb-8">

                            <div class="mb-1">

                                <div class="position-relative mb-3">
                                    <input class="form-control bg-transparent" type="password" placeholder="Password" name="password" autocomplete="off" />
                                    <!-- <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2">
                                        <i class="bi bi-eye-slash fs-2"></i>
                                        <i class="bi bi-eye fs-2 d-none"></i>
                                    </span> -->
                                </div>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                            <div>
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                                    <span class="fw-semibold ps-2 fs-6 form-check-label">
                                        Remember Me
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="d-grid mb-10">
                            <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                                <span class="indicator-label">Sign In</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-guest-layout>
