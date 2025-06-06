@extends('layouts.admin')

@section('title', 'Edit User')
@section('style')
    <style>
        .home-image-upload {
            position: relative;
        }

        .home-image-upload img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .home-image-upload button {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #3a3a3a;
            border: 0;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            color: #fff;
            box-shadow: 0px 0px 6px 1px #c3c3c3;
        }

        .mw-50 {
            max-width: 50px !important;
        }
    </style>
@section('content')
@section('content')
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				@php 
					$previousUrl = URL::previous();
					$path = parse_url($previousUrl, PHP_URL_PATH);
					$pathInArray = explode('/', $path);
				@endphp
				@if(in_array('homes', $pathInArray))
				<li class="breadcrumb-item">
					<a href="{{ url()->previous() }}#tab2">Staff List</a>
				</li>
				@else
					<li class="breadcrumb-item">
						<a href="{{ url()->previous() }}">Users</a>
					</li>
				@endif
				<li class="breadcrumb-item active">
					<strong>Edit User</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox ">
					<!-- <div class="ibox-title d-flex">
						<h5>Edit User </h5>
					</div> -->
						<div class="ibox-content shadow border rounded">
							<form method="POST" role="form" action="{{ route('users.update', $user->id) }}" id="userAdmin_Form" enctype="multipart/form-data">
							<div class="row">	
							@csrf
								@method("PATCH")
								<div class="form-group col-12 col-md-6 col-lg-3 @error('role_id') has-error @enderror">
									<label class="col-form-label">Role</label>
									<div class="">
										<select class="form-control " name="role_id" id="role_id" required>
											<option value="">Select Role</option>
											@foreach($roles as $role)
												<option value={{ $role->id }} {{ $user->role_id ==  $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
											@endforeach
										</select>
										@error('role_id')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								{{-- @if(Auth::user()->role_id == 2)
									@php $home_required = Auth::user()->role_id == 2 ? 'required' : ''; @endphp
									<div class="form-group col-12 col-md-6 col-lg-3 @error('role_id') has-error @enderror" {{ $home_required }}>
										<label class="col-form-label">Care Home</label>
										<div class="">
											<select class="form-control " name="home_id" {{ $home_required }}>
												@foreach($homes as $home)
													@if( $user->home_id ==  $home->id)
														<option value="{{ $home->id }}" selected>{{ $home->name }}</option>
													@endif
												@endforeach
											</select>
											@error('home_id')
												<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
											@enderror
										</div>
									</div>
								@else
									<input type="hidden" name="home_id"	value="{{ $user->home_id }}">
								@endif	 --}}
								{{-- <div class="form-group col-12 col-md-6 col-lg-3 @if($user->role_id == 3) d-none @endif @error('role_id') has-error @enderror" id="shift_div">
									<label class="col-form-label">Shift</label>
									<div class="">
										<select class="form-control " name="shift_id" {{ $user->role_id == 4 ? 'required' : '' }}>
											<option value="">Select Shift</option>
											@foreach($shifts as $key => $shift)
												<option value="{{ $key }}" {{ $user->shift_id ==  $key ? 'selected' : '' }}>{{ $shift }}</option>
											@endforeach
										</select>
										@error('role_id')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div> --}}
								{{-- <div class="form-group col-12 col-md-6 col-lg-3 @if($user->role_id == 3) d-none @endif @error('geofencing_status') has-error @enderror" id="geo_status_div">
									<label class="col-form-label">Geofencing Status</label>
									<div class="">
										<select class="form-control " name="geofencing_status" id="geofencing_status">
											<option value="">Select Shift</option>
											@foreach($geo_status as $key => $geo_stat)
												<option value="{{ $key }}" {{ $user->geofencing_status ==  $key ? 'selected' : '' }}>{{ $geo_stat }}</option>
											@endforeach
										</select>
										@error('geofencing_status')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group col-12 col-md-6 col-lg-3 @error('name') has-error @enderror">
									<label class="col-form-label">Name</label>
									<div class="">
										<input type="text" name="name" placeholder="Name" class="form-control" required autocomplete="off" value="{{ $user->name }}">
										@error('name')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group col-12 col-md-6 col-lg-3 @error('email') has-error @enderror">
									<label class="col-form-label">Email</label>
									<div class="">
										<input type="email" name="email" placeholder="Email" class="form-control" required autocomplete="off" value="{{ $user->email }}" disabled>
										@error('email')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group col-12 col-md-6 col-lg-3 @error('phone_number') has-error @enderror">
									<label class="col-form-label">Phone</label>
									<div class="">
										<input type="text" name="phone_number" placeholder="Phone Number" class="form-control validate_phone" required autocomplete="off" value="{{ $user->phone_number }}">
										@error('phone_number')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group col-12 col-md-6 col-lg-3 @error('status') has-error @enderror">
									<label class="col-form-label">Status</label>
									<div class="">
										<select class="form-control " name="status" required>
											<option value="">Select status</option>
											<option value="1" {{ $user->status ==  1 ? 'selected' : '' }}>Active</option>
											<option value="0" {{ $user->status ==  0 ? 'selected' : '' }}>In-Active</option>
										</select>
										@error('status')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="col-lg-3 my-1">
									<div class="col-12 py-3 rounded-lg team-memberc text-center">
										<div class="form-group d-flex align-items-center text-left">
											<h4 class="mr-2">Upload User Image</h4>
											<div class="d-inline-block home-image-upload">
												<img alt="image" id="existing_profile_image"
													class="border border-dark rounded-circle me-2 mw-50"
													src="{{ $user->image_path }}">
												<button type="button" id="select_profile_image"><i
														class="fa fa-pencil"></i></button>
											</div>
											<input type="file" class="form-control" name="profile_image"
												id="profile_image" accept="image/*" style="display:none"
												onchange="handleFiles(this)">
										</div>
									</div>
								</div> --}}
								{{-- <input type="hidden" name="redirectURL" value="{{ createCancelUrl(route('users.index')) }}">
								<div class="hr-line-dashed"></div>
								<div class="form-group col-12 ">
                                    <div class="col-md-12 text-right">
                                        <a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('users.index')) }}">Cancel</a>
                                        <button class="btn btn-sm btn-primary" type="submit" id="userAdminForm">Save</button>
                                    </div>
                                </div> --}}
                                @if (Auth::user()->role_id == 2)
                                    @php $home_required = Auth::user()->role_id == 2 ? 'required' : ''; @endphp
                                    <div class="form-group col-12 col-md-6 col-lg-3 @error('role_id') has-error @enderror"
                                        {{ $home_required }}>
                                        <label class="col-form-label">Care Home *</label>
                                        <div class="">
                                            <select class="form-control " name="home_id" {{ $home_required }}>
                                                @foreach ($homes as $home)
                                                    @if ($user->home_id == $home->id)
                                                        <option value="{{ $home->id }}" selected>{{ $home->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('home_id')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="home_id" value="{{ $user->home_id }}">
                                @endif
                                <div class="form-group col-12 col-md-6 col-lg-3 @if ($user->role_id == 3) d-none @endif @error('role_id') has-error @enderror"
                                    id="shift_div">
                                    <label class="col-form-label">Shift *</label>
                                    <div class="">
                                        <select class="form-control " name="shift_id"
                                            {{ $user->role_id == 4 ? 'required' : '' }}>
                                            <option value="">Select Shift</option>
                                            @foreach ($shifts as $key => $shift)
                                                <option value="{{ $key }}"
                                                    {{ $user->shift_id == $key ? 'selected' : '' }}>{{ $shift }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 col-lg-3 @if ($user->role_id == 3) d-none @endif @error('geofencing_status') has-error @enderror"
                                    id="geo_status_div">
                                    <label class="col-form-label">Geofencing Status</label>
                                    <div class="">
                                        <select class="form-control " name="geofencing_status" id="geofencing_status">
                                            <option value="">Select Shift</option>
                                            @foreach ($geo_status as $key => $geo_stat)
                                                <option value="{{ $key }}"
                                                    {{ $user->geofencing_status == $key ? 'selected' : '' }}>
                                                    {{ $geo_stat }}</option>
                                            @endforeach
                                        </select>
                                        @error('geofencing_status')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 col-lg-3 @error('name') has-error @enderror">
                                    <label class="col-form-label">Name *</label>
                                    <div class="">
                                        <input type="text" name="name" placeholder="Name" class="form-control"
                                            required autocomplete="off" value="{{ $user->name }}">
                                        @error('name')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 col-lg-3 @error('email') has-error @enderror">
                                    <label class="col-form-label">Email *</label>
                                    <div class="">
                                        <input type="email" name="email" placeholder="Email" class="form-control"
                                            required autocomplete="off" value="{{ $user->email }}" disabled>
                                        @error('email')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 col-lg-3 @error('phone_number') has-error @enderror">
                                    <label class="col-form-label">Phone *</label>
                                    <div class="">
                                        <input type="text" name="phone_number" placeholder="Phone Number"
                                            class="form-control validate_phone" required autocomplete="off"
                                            value="{{ $user->phone_number }}">
                                        @error('phone_number')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 col-lg-3 @error('status') has-error @enderror">
                                    <label class="col-form-label">Status *</label>
                                    <div class="">
                                        <select class="form-control " name="status" required>
                                            <option value="">Select status</option>
                                            <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>In-Active
                                            </option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-3 my-1">
                                    <div class="col-12 py-3 rounded-lg team-memberc text-center">
                                        <div class="form-group d-flex align-items-center text-left">
                                            <h4 class="mr-2">Upload User Image</h4>
                                            <div class="d-inline-block home-image-upload">
                                                <img alt="image" id="existing_profile_image"
                                                    class="border border-dark rounded-circle me-2 mw-50"
                                                    src="{{ $user->image_path }}">
                                                <button type="button" id="select_profile_image"><i
                                                        class="fa fa-pencil"></i></button>
                                            </div>
                                            <input type="file" class="form-control" name="profile_image"
                                                id="profile_image" accept="image/*" style="display:none"
                                                onchange="handleFiles(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="redirectURL"
                                value="{{ createCancelUrl(route('users.index')) }}">
                            <div class="hr-line-dashed"></div>
                            <div class="form-group col-12 ">
                                <div class="col-md-12 text-right">
                                    <a class="btn btn-white btn-sm" type="button"
                                        href="{{ createCancelUrl(route('users.index')) }}">Cancel</a>
                                    <button class="btn btn-sm btn-primary" type="submit"
                                        id="userAdminForm">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#role_id').change(function() {
                console.log('role changed', $(this).val());
                if ($(this).val() == 4) {
                    $('#shift_div').removeClass('d-none');
                    $('#shift_id').attr("required", true);
                } else {
                    $('#shift_div').addClass('d-none');
                    $('#shift_id').attr("required", false);
                }
            })
        })
        //change care home image start
        $('#select_profile_image').click(function() {
            $('#profile_image').trigger('click')
        });

        //preview home image on select Start
        const handleFiles = (input) => {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#existing_profile_image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
