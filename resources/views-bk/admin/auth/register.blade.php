@extends('admin.auth.master')
@section('content')
    <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">
        <div class="kt-login__head">
            <span class="kt-login__signup-label">Already have account?</span>
            <a href="{{ url('login') }}" class="kt-link kt-login__signup-link">Sign in!</a>
        </div>
        <div class="kt-login__body">
            <div class="kt-login__form">
                <div class="kt-login__title">
                    <h3>Sign In</h3>
                </div>
                <form class="kt-form" action="{{ url('registration') }}" novalidate="novalidate" id="kt_login_form"
                    method="post">
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="Name" name="name" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            <span class="error invalid-feedback d-block">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="Email" name="email" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span class="error invalid-feedback d-block">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" placeholder="Password" name="password">
                        @if ($errors->has('password'))
                            <span class="error invalid-feedback d-block">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="kt-login__actions">
                        {{-- <a href="{{ url('forgot-password') }}"
                            class="kt-link kt-login__link-forgot">
                            Forgot Password ?
                        </a> --}}
                        <button type="submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
