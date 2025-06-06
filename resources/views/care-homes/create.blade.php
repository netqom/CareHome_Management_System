@extends('layouts.admin')

@section('title', 'Create Care Home')

@section('content')
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item">
					<a href="{{ route('homes.index') }}">Care Homes</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Create Care Homes</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox ">
					<!-- <div class="ibox-title d-flex">
						<h5>Add Care Homes </h5>
					</div> -->
					<div class="ibox-content">
						<div class="ibox-content">
							<form method="POST" role="form" action="{{ route('homes.store') }}" id="careHomeForm" enctype="multipart/form-data">
								@csrf
								<span class="badge badge-info w-100 mt-2 mb-2 py-2"><h3 class="m-0">Care Home Admin Info</h3></span>
								<div class="form-group row @error('role_id') has-error @enderror">
									<label class="col-lg-2 col-form-label">User</label>
									<div class="col-lg-10">
										<input type="text" id="user_id" placeholder="Search Added User" class="form-control" autocomplete="off" value="{{ old('user_id') }}">
										@error('user_id')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
										<span class="form-text m-b-none">If you need to attach previous added user it must be seleted from here</span>
									</div>
								</div>
								<input type="hidden" name="selected_user_id" id="selected_user_id" value="0">
								<div class="form-group row @error('role_id') has-error @enderror">
									<label class="col-lg-2 col-form-label">Role</label>
									<div class="col-lg-10">
										<select class="form-control m-b" name="role_id" id="role_id" required>
											@foreach($roles as $role)
												<option value="{{ $role->id }}">{{ $role->name }}</option>
											@endforeach
										</select>
										@error('role_id')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row @error('admin_name') has-error @enderror">
									<label class="col-lg-2 col-form-label">Care Home Admin Name</label>
									<div class="col-lg-10">
										<input type="text" name="admin_name" id="admin_name" placeholder="Care Home Admin Name" class="form-control" required autocomplete="off" value="{{ old('admin_name', getCareHomeAuthInfo('name')) }}">
										@error('admin_name')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row @error('admin_email') has-error @enderror">
									<label class="col-lg-2 col-form-label">Care Home Admin Email</label>
									<div class="col-lg-10">
										<input type="email" name="admin_email" id="admin_email" placeholder="Care Home Admin Email" class="form-control" required autocomplete="off" value="{{ old('admin_email', getCareHomeAuthInfo('email')) }}">
										@error('admin_email')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row @error('admin_phone_number') has-error @enderror">
									<label class="col-lg-2 col-form-label">Phone</label>
									<div class="col-lg-10">
										<input type="text" name="admin_phone_number" id="admin_phone_number" placeholder="Care Home Admin Phone Number" class="form-control" required autocomplete="off" value="{{ old('admin_phone_number', getCareHomeAuthInfo('phone_number')) }}">
										@error('admin_phone_number')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<span class="badge badge-info w-100 mt-2 mb-2 py-2"><h3 class="m-0">Care Home Details</h3></span>
								<div class="form-group row @error('name') has-error @enderror">
									<label class="col-lg-2 col-form-label">Care Home Image</label>
									<div class="col-lg-10">
										<div class="input-group">
											 <div class="custom-file">
												 <input id="inputGroupFile01" type="file" class="custom-file-input" name="image" accept="image/*">
												 <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
											 </div>
										 </div>
									 </div>
								 </div>
								<div class="form-group row @error('name') has-error @enderror">
									<label class="col-lg-2 col-form-label">Name</label>
									<div class="col-lg-10">
										<input type="text" name="name" placeholder="Name" class="form-control" required autocomplete="off" value="{{ old('name') }}">
										@error('name')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row @error('email') has-error @enderror">
									<label class="col-lg-2 col-form-label">Email</label>
									<div class="col-lg-10">
										<input type="text" name="email" placeholder="Email" class="form-control" required autocomplete="off" value="{{ old('email') }}">
										@error('email')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row @error('contact_no') has-error @enderror">
									<label class="col-lg-2 col-form-label">Contact No</label>
									<div class="col-lg-10">
										<input type="text" name="contact_no" placeholder="Contact No" class="form-control" required autocomplete="off" value="{{ old('contact_no') }}">
										@error('contact_no')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row @error('about') has-error @enderror">
									<label class="col-lg-2 col-form-label">About</label>
									<div class="col-lg-10">
										<textarea class="form-control" name="about" placeholder="Info About Care Home" rows="3" required autocomplete="off">{{ old('about') }}</textarea>
										@error('about')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<!--div class="form-group row @error('address') has-error @enderror">
									<label class="col-lg-2 col-form-label">Address</label>
									<div class="col-lg-10">
										<input type="text" name="address" placeholder="Address" class="form-control" required autocomplete="off" value="{{ old('address') }}">
										@error('address')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div-->
								<div class="form-group row @error('street') has-error @enderror">
									<label class="col-lg-2 col-form-label">Street Name</label>
									<div class="col-lg-10">
										<input type="text" name="street" placeholder="Street" class="form-control" required autocomplete="off" value="{{ old('street') }}">
										@error('street')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row @error('city') has-error @enderror">
									<label class="col-lg-2 col-form-label">City Name</label>
									<div class="col-lg-10">
										<input type="text" name="city" placeholder="City" class="form-control" required autocomplete="off" value="{{ old('city') }}">
										@error('city')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row @error('state') has-error @enderror">
									<label class="col-lg-2 col-form-label">State Name</label>
									<div class="col-lg-10">
										<input type="text" name="state" placeholder="State" class="form-control" required autocomplete="off" value="{{ old('state') }}">
										@error('state')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row @error('zip_code') has-error @enderror">
									<label class="col-lg-2 col-form-label">Zip Code</label>
									<div class="col-lg-10">
										<input type="text" name="zip_code" placeholder="Zip Code" class="form-control" required autocomplete="off" value="{{ old('zip_code') }}">
										@error('zip_code')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<!--div class="form-group row @error('capacity') has-error @enderror">
									<label class="col-lg-2 col-form-label">Capacity</label>
									<div class="col-lg-10">
										<input type="number" min="1" name="capacity" placeholder="Capacity" class="form-control integer_no" required autocomplete="off" value="{{ old('capacity', 1) }}">
										@error('capacity')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div-->
								<div class="form-group row @error('location_lat') has-error @enderror">
									<label class="col-lg-2 col-form-label">Location Latitude</label>
									<div class="col-lg-10">
										<input type="text" name="location_lat" placeholder="Location Latitude" class="form-control" required autocomplete="off" value="{{ old('location_lat') }}">
										@error('location_lat')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row @error('location_long') has-error @enderror">
									<label class="col-lg-2 col-form-label">Location Longitude</label>
									<div class="col-lg-10">
										<input type="text" name="location_long" placeholder="Location Longitude" class="form-control" required autocomplete="off" value="{{ old('location_long') }}">
										@error('location_long')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group row @error('status') has-error @enderror">
									<label class="col-lg-2 col-form-label">Status</label>
									<div class="col-lg-10">
										@php
											$pre_seleted = 1;
											if(!is_null(old('status')) && old('status') == 0){
												$pre_seleted = 0;
											}
										@endphp
										<select class="form-control m-b" name="status" required>
											<option value="">Select status</option>
											<option value="1" {{ $pre_seleted == 1 ? 'selected' : ''}}>Active</option>
											<option value="0" {{ $pre_seleted == 0 ? 'selected' : '' }}>In-Active</option>
										</select>
										@error('status')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="hr-line-dashed"></div>
								<div class="form-group row">
                                    <div class="col-md-12 text-right">
                                        <a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('homes.index')) }}">Cancel</a>
                                        <button class="btn btn-sm btn-primary" type="submit" id="careHome_Form">Save</button>
                                    </div>
                                </div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('script')
	<!-- BS custom file -->
    <script src="{{ asset('assets/js/plugins/bs-custom-file/bs-custom-file-input.min.js') }}"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script type="text/javascript">
		$(document).ready(function () {
			bsCustomFileInput.init()
		}) 
        $(function() {
		  $("#user_id").autocomplete({                
				source: function (request, response) {
					$.ajax({
						type: "GET",
						contentType: "application/json; charset=utf-8",
						url: "{{ route('homes-search-users') }}",
						dataType: "json",
						data:{QueryFilter: request.term},
						success: function (data) {
							console.log('resppnse data', data.users);
							response($.map(data.users, function (item) {                                
								var AC = new Object();
								//autocomplete default values REQUIRED
								AC.label = item.name;
								AC.value = item.name;
								//extend values
								AC.id           = item.id;
								AC.role_id      = item.role_id;
								AC.name         = item.name;
								AC.email        = item.email;
								AC.phone_number = item.phone_number;

								return AC
							}));       
						}                                             
					});
				},
				minLength: 3,
				select: function (event, ui) {                    
					$("#selected_user_id").val(ui.item.id);
					$("#admin_name").val(ui.item.name);
					$("#role_id").val(ui.item.role_id);
					$("#admin_email").val(ui.item.email);
					$("#admin_phone_number").val(ui.item.phone_number);
				 }                    
			});
		 });
    </script>
@endsection