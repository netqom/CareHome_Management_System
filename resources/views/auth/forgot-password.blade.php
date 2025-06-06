@extends('layouts.guest')

@section('content')
	<div class="middle-box text-center loginscreen animated fadeInDown">
		<div>
			<div><a href="{{ route('home') }}">
				{{-- <img src="{{ asset('login-page-logo.png') }}" width="100px;" alt="logo-img"> --}}
				<img src="{{ asset('logo.svg') }}" width="100px;" alt="logo-img" style="width:70px;height:70px">
			</a></div>
			<h2 class="font-bold">Forgot password</h2>
			<p>
				Enter your email address and your password reset link will be emailed to you.
			</p>
			@if (session('status'))
				<div class="alert alert-success" role="alert">
					{{ session('status') }}
				</div>
			@endif
			@if (session('info'))
				<div class="alert alert-danger">
					{{ session('info') }}
				</div>
			@endif	
			<form method="POST" class="m-t"   action="{{ route('password.email') }}" id="forget_password">
				@csrf
				<div class="form-group @error('email') has-error @enderror">
					<input id="email"  class="form-control" placeholder="{{__('Email')}}"  type="email" name="email" value="{{ old('email') }}" required autofocus >
					@error('email')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b" id="forgetPassword"> {{ __('Send Password Reset Link') }}</button>
				<p class="text-muted text-center"><small>Remembered Password </small></p>
				 <a class="btn btn-sm btn-white btn-block" href="{{ route('login')}}">Login</a>
			</form>
			<p class="m-t"> <!--small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small--> </p>
		</div>
	</div>
@endsection

