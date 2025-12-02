@section('title', 'Change Password')

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/users/changeUserPassword.css') }}">
@endpush

@push('script')
    <script src="{{ asset('js/admin/users/changeUserPassword.js') }}"></script>
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <x-breadcrumbs :breadcrumbs="$breadcrumbs"></x-breadcrumbs>

    {{-- Page Content --}}
    <!-- Form Advance -->
    <div class="col s12 m12 l12">
        <div id="Form-advance" class="card card card-default scrollspy">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <form method="POST" id="userPasswordForm" action="{{ route('admin_user_update_password') }}">
                            <input type="hidden" value="{{ auth()->user()->id }}" name="id">
                            @csrf
                            <div class="row">
                                <div class="input-field col m4 s12">
                                    <input id="current" name="current" type="password">
                                    <label for="current">Current Password <span class="red-text">*</span></label>
                                    @error('current')
                                        <small class="form-text red-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="input-field col m4 s12">
                                    <input id="password" name="password" type="password">
                                    <label for="password">Password <span class="red-text">*</span></label>
                                    @error('password')
                                        <small class="form-text red-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="input-field col m4 s12">
                                    <input id="password_confirm" name="password_confirm" type="password">
                                    <label for="password_confirm">Confirm Password <span class="red-text">*</span></label>
                                    @error('password_confirm')
                                        <small class="form-text red-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12 display-flex justify-content-end mt-3">
                                    <a href="{{ route('admin_user_list') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn indigo submit-btn">Update Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
