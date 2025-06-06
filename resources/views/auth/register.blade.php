@extends('layouts.guest')

@section('content')
	<div class="middle-box text-center loginscreen animated fadeInDown">
		<div>
			<div><a href="{{ route('home') }}">
				{{-- <img src="{{ asset('login-page-logo.png') }}" width="100px;" alt="logo-img"> --}}
				<img src="{{ asset('logo.svg') }}" width="100px;" alt="logo-img" style="width:70px;height:70px">
			</a></div>
			<h3>Register to docryt</h3>
			@if(Session::has('message'))
				@if(Session::get('type') == 'success')
					<div class="alert alert-success">
						{{ Session::get('message') }}
					</div>
				@else
					<div class="alert alert-danger">
						{{ Session::get('message') }}
					</div>
				@endif
			@endif
			<form method="POST" class="m-t" role="form"  action="{{ route('register') }}" id="register_form">
				@csrf
				<div class="form-group @error('email') has-error @enderror">
					<input id="email" class="form-control validate_email" type="email" name="email" value="{{ old('email') }}"   placeholder="Email"  required autofocus autocomplete="Email" >
					@error('email')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<div class="form-group @error('name') has-error @enderror">
					<input id="name" class="form-control" type="text" name="name"   placeholder="Name"  value="{{ old('name') }}" required autocomplete="name" >
					@error('name')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<div class="form-group @error('phone_number') has-error @enderror">
					<input id="phone_number" class="form-control validate_phone" type="text" name="phone_number"   placeholder="Phone Number"  value="{{ old('phone_number') }}" required autofocus autocomplete="phone_number" >
					@error('phone_number')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<div class="form-group position-relative @error('password') has-error @enderror">
					<input id="password" class="form-control pwcheck" placeholder="Password" type="password" name="password" id="password" required autocomplete="password" >
					<i class="fa fa-info-circle position-absolute" aria-hidden="true" data-toggle="tooltip" title="Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character." style="right: 10px; top: 50%; transform: translateY(-50%);"></i>
					@error('password')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<div class="form-group @error('password_confirmation') has-error @enderror">
					<input id="password_confirmation" class="form-control" placeholder="Password confirmation"
								type="password"
								name="password_confirmation" id="password_confirmation" required autocomplete="new-password" >
					@error('password_confirmation')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<!-- Remember Me -->
				<div class="form-group @error('term_conditions') has-error @enderror">
					<div class="text-left checkbox i-checks">
						<label><input type="checkbox" name="term_conditions" required><i></i> Agree the <a target="_blank" href="{{ route('term-conditions') }}" class="">Terms & Conditions</a> </label>
					</div>
					@error('term_conditions')
						<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
					@enderror
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b" id="registerForm"> {{ __('Register') }}</button>
				 <p class="text-muted text-center"><small>   {{ __('Already have an account?') }}</small></p>
				 <a class="btn btn-sm btn-white btn-block" href="{{ route('login')}}">Login</a>
			</form>
				<p class="m-t"> <!--small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small--> </p>
		</div>
	</div>
@endsection

