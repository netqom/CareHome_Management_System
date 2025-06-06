@extends('layouts.guest')

@section('content')
	<div class="middle-box text-center loginscreen animated fadeInDown">
		<div>
			<div><a href="{{ route('home') }}">
				{{-- <img src="{{ asset('login-page-logo.png') }}" width="100px;" alt="logo-img"> --}}
				<img src="{{ asset('logo.svg') }}" width="100px;" alt="logo-img" style="width:70px;height:70px">
			</a></div>
			<p>
				{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
			</p>
			<form method="POST" action="{{ route('password.confirm') }}">
				@csrf
				<div class="form-group @error('email') has-error @enderror">
					<input id="password" class="form-control" type="password" name="password" required autocomplete="current-password"  >
					@error('password')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b">  {{ __('Confirm') }}</button>
			</form>
			<p class="m-t"> <!--small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small--> </p>
		</div>
	</div>
@endsection
