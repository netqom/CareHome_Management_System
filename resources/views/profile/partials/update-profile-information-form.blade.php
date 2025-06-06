<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information") }}
        </p>
    </header>
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')
        {{-- <div class="form-group row @error('name') has-error @enderror">
            <label class="col-lg-2 col-form-label">Profile Image</label>
            <div class="col-lg-10">
                <div class="input-group">
                    <div class="custom-file">
                        <input id="inputGroupFile01" type="file" name="image" class="custom-file-input"
                            accept="image/*">
                        <label class="custom-file-label" for="inputGroupFile01">Choose Profile Image</label>
                    </div>
                </div>
                <div class="text-center mt-2">
                    @php

                        $profile_img = asset('assets/img/profile-pic.jpg');
                        if (!is_null(Auth::user()->profile_image)) {
                            $profile_img = asset(Auth::user()->profile_image);
                        }
                    @endphp
                    <img src="{{ $profile_img }}" style="width:20%;" class="bg-info rounded-circle"
                        alt="user-profile-img">
                </div>
            </div>
        </div> --}}
        <div class="form-group row @error('name') has-error @enderror">
            <label class="col-lg-2 col-form-label">Name</label>
            <div class="col-lg-10">
                <input type="text" name="name" placeholder="Name" class="form-control" required
                    value="{{ Auth::user()->name }}" autocomplete="off">
                @error('name')
                    <span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="form-group row @error('email') has-error @enderror">
            <label class="col-lg-2 col-form-label">Email</label>
            <div class="col-lg-10">
                <input type="email"  placeholder="Email" class="form-control"
                    value="{{ Auth::user()->email }}" autocomplete="off" readonly>
                @error('email')
                    <span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="form-group row">
            <div class=" col-lg-12 text-right">
                <button class="btn btn-sm btn-primary" type="submit">{{ __('Save') }}</button>
            </div>
        </div>
    </form>
</section>
