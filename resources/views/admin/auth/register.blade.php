@extends('layouts.admin.auth')

@section('auth-content')
    <div class="container">
        <div class="account-box col-md-8">
            <div class="account-wrapper">
                <div class="account-logo">
                    <a href="{{ route('admin.register.index') }}"><img src="{{ asset('user/img/dash/Softlogo.png') }}" alt="SchoolAdmin"></a>
                </div>
                <p class="text-center text-primary my-0" style="font-size: 20px;">Register</p>
                <form method="POST" action="{{ route('admin.register.register') }}">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="form-group my-2">
                                <label>Name</label>
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autofocus placeholder="Type Here">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group my-2">
                                <label>Username</label>
                                <input type="text" id="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Type Here">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group my-2">
                                <label>Phone</label>
                                <input type="number" id="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="Type Here">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group my-2">
                                <label>Email</label>
                                <input type="text" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Type Here">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group my-2">
                                <label>Address</label>
                                <input type="text" id="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" placeholder="Type Here">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group my-2">
                                <label>Your Domain</label>
                                <input type="text" id="domain" class="form-control @error('domain') is-invalid @enderror" name="domain" value="{{ old('domain') }}" placeholder="https://example.com">
                                @error('domain')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group my-2">
                                <label>Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="inputChoosePassword" value="admin" required autocomplete="current-password" placeholder="Type your password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            <div class="form-group text-center custom-mt-form-group mt-2">
                                <button class="btn btn-primary btn-block account-btn" type="submit">Sign Up</button>
                            </div>
                            <div class="text-center">
                                @if (Route::has('admin.login.view'))
                                    <a class="" href="{{ route('admin.login.view') }}">
                                        {{ __('Already Have An Account?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
