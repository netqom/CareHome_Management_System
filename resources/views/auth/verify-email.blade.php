@extends('layouts.guest')

@section('content')
	<div class="middle-box text-center loginscreen animated fadeInDown">
		<div>
			<div><a href="{{ route('home') }}">
				{{-- <img src="{{ asset('login-page-logo.png') }}" width="100px;" alt="logo-img"> --}}
				<img src="{{ asset('logo.svg') }}" width="100px;" alt="logo-img" style="width:70px;height:70px">
			</a></div>
			 <div class="mb-4 text-sm text-gray-600">
				{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
			</div>
			@if (session('status') == 'verification-link-sent')
				<div class="mb-4 font-medium text-sm text-green-600">
					{{ __('A new verification link has been sent to the email address you provided during registration.') }}
				</div>
			@endif
			<form method="POST" action="{{ route('verification.send') }}">
				@csrf
				<button type="submit" class="btn btn-primary block full-width m-b"> {{ __('Resend Verification Email') }}</button>
			</form>
			<form method="POST" action="{{ route('logout') }}">
				@csrf

				<button type="submit" class="btn btn-primary block full-width m-b">
					{{ __('Log Out') }}
				</button>
			</form>
			<p class="m-t"> <!--small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small--> </p>
		</div>
	</div>
@endsection
