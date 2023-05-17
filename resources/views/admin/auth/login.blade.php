@extends('layouts.admin.auth')

@section('auth-content')
    <div class="container">
        <div class="account-box col-md-6">
            <div class="account-wrapper">
                <div class="account-logo">
                    <a href="/"><img src="{{ asset('user/img/dash/Softlogo.png') }}" alt="SchoolAdmin"></a>
                </div>
                <p class="text-center text-primary my-0" style="font-size: 20px;">Admin Login</p>
                <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf

                    <div class="form-group my-2">
                        <label>Username or Email</label>
                        <input type="text" id="username" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ old('login', 'admin') }}" required autocomplete="login" autofocus placeholder="Type Here">
                        @error('login')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <label>Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="inputChoosePassword" value="admin" required autocomplete="current-password" placeholder="Type your password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                    <div class="form-group text-center custom-mt-form-group mt-2">
                        <button class="btn btn-primary btn-block account-btn" type="submit">Login</button>
                    </div>
                    <div class="text-center">
                        @if (Route::has('password.request'))
                            <a class="" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
