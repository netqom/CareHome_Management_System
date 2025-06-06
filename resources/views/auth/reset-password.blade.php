@extends('layouts.guest')

@section('content')
	<div class="middle-box text-center loginscreen animated fadeInDown">
		<div>
			<div><a href="{{ route('home') }}">
				{{-- <img src="{{ asset('login-page-logo.png') }}" width="100px;" alt="logo-img"> --}}
				<img src="{{ asset('logo.svg') }}" width="100px;" alt="logo-img" style="width:70px;height:70px">
			</a></div>
			<h2 class="font-bold">Reset password</h2>
			<form method="POST" action="{{ route('password.store') }}" id="reset_password">
				@csrf
				<!-- Password Reset Token -->
				<input type="hidden" name="token" value="{{ $request->route('token') }}">
				<div class="form-group @error('email') has-error @enderror">
					<input id="email" class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
					@error('email')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<div class="form-group @error('password') has-error @enderror">
					<input id="password" class="form-control" type="password" placeholder="Password" name="password" required autocomplete="new-password">
					@error('password')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<div class="form-group @error('password_confirmation') has-error @enderror">
					<input id="password_confirmation" class="form-control" placeholder="Confirm Password" type="password" name="password_confirmation" required autocomplete="new-password">
					@error('password_confirmation')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b" id="resetPassword"> {{ __('Reset Password') }}</button>
			</form>
			<p class="m-t"> <!--small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small--> </p>
		</div>
	</div>
@endsection