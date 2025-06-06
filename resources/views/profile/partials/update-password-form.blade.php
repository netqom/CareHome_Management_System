<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6" id="updatePassword">
        @csrf
        @method('put')
        
		<div class="form-group row @error('current_password', 'updatePassword') has-error @enderror">
			<label class="col-lg-2 col-form-label">Current Password</label>
			<div class="col-lg-10">
				<input type="password" name="current_password" placeholder="Current Password" class="form-control" value="{{ old('current_password') }}" required autocomplete="off">
				@if(session()->has('current_password_error'))
					<span class="text-danger text-left d-block" role="alert">{{ session('current_password_error') }}</span>
				@elseif($errors->has('current_password'))
					<span class="text-danger text-left d-block" role="alert">{{ $errors->first('current_password') }}</span>
				@endif
				{{-- @error('current_password','updatePassword')
					<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
				@enderror --}}
			</div>
		</div>
		<div class="form-group row @error('password') has-error @enderror">
			<label class="col-lg-2 col-form-label">New Password</label>
			<div class="col-lg-10">
				<input type="password" name="password" id="update_password_password" placeholder="New Password" class="form-control" value="{{ old('password') }}" required autocomplete="off">
				@error('password')
					<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
				@enderror
			</div>
		</div>
		<div class="form-group row @error('password_confirmation') has-error @enderror">
			<label class="col-lg-2 col-form-label">Confirm Password</label>
			<div class="col-lg-10">
				<input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control" value="{{ old('password_confirmation') }}" required autocomplete="off">
				@error('password_confirmation')
					<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
				@enderror
			</div>
		</div>
       <div class="form-group row">
			<div class=" col-lg-12 text-right">
				<button class="btn btn-sm btn-primary" type="submit" id="update-password">{{ __('Save') }}</button>
			</div>
		</div>
    </form>
</section>
