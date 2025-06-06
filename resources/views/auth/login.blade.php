@extends('layouts.guest')

@section('content')
	<div class="middle-box text-center loginscreen animated fadeInDown">
		<div>
			<div><a href="{{ route('home') }}">
				{{-- <img src="{{ asset('logo-dark.png') }}" width="100px;" alt="logo-img"> --}}
				{{-- <img src="{{ asset('login-page-logo.png') }}" width="100px;" alt="logo-img"> --}}
				<img src="{{ asset('logo.svg') }}" width="100px;" alt="logo-img" style="width:70px;height:70px">
				</a>
			</div>
			<h3>Welcome to docryt</h3>
			@error('info')
				<div class="alert alert-danger">
					{{ $message }}
				</div>
			@enderror	
			@if (session('info'))
			<div class="alert alert-success">
				{{ session('info') }}
			</div>
			@endif
			@if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
			 <form method="POST" class="m-t" role="form" action="{{ route('login') }}" id="login_form">
				@csrf
				<div class="form-group @error('email') has-error @enderror">
					<input type="email" class="form-control" placeholder="Email"  type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" >
					@error('email')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<div class="form-group @error('password') has-error @enderror">
					<input class="form-control" placeholder="Password" type="password" name="password" required autocomplete="password">
					@error('password')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<!-- Remember Me -->
				<div class="form-group">
					<label for="remember_me" class="inline-flex items-center">
						<div class="checkbox i-checks"><label> <input id="remember_me" name="remember" type="checkbox" type="checkbox"><i></i> {{ __('Remember me') }} </label></div>
					</label>
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b" id="loginForm">{{ __('Login') }}</button>
				<div class="d-flex justify-content-between">
					@if (Route::has('password.request'))
					<a class="" href="{{ route('password.request') }}">
						{{ __('Forgot password?') }}
					</a>
					@endif
					<!--p class="text-muted text-center">
						<small>Do not have an account?</small>
					</p-->
					<a class="" href="{{ route('register') }}">{{ __('Create an Account') }}</a>
				</div>
			</form>
			<p class="m-t"> <!--small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small--> </p>
		</div>
	</div>
@endsection