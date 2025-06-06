@extends('layouts.admin')

@section('title', 'Edit Care Home')
@section("style")
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!--link href="{{ asset('assets/css/plugins/steps/jquery.steps.css') }}" rel="stylesheet"-->
<link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet">
<style>
	.wizard > div.content {
    background: #f3f3f3;
}
.wizard > div.content > .body {
    position: relative;
    width: 100%;
    height: auto;
}
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
.payment-radio {
			opacity: 0;
			position: absolute;
			inset: 0;
			z-index: 99;
			cursor: pointer;
		}
    .payment-card {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
        border-color: transparent;
        border-width: 2px;
    }  
    .payment-radio:checked + .payment-card {
    border-color: #1ab394;
    border-width: 2px;
}
.steps-tab {
    position: relative;
    display: block;
    width: 100%;
}
.steps-tab > ul > li {
    width: 25%;
	display: block;
    padding: 0;
	float: left;
}
.steps-tab .current a {
    background: #1AB394;
    color: #fff;
    cursor: default;	    
}
.steps-tab .disabled a {
    background: #eee;
    color: #aaa;
    cursor: default;
}
.steps-tab a {
   display: block;
    width: auto;
    margin: 0 0.5em 0.5em;
    padding: 8px;
    text-decoration: none;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}
.map-div {
    height: 140px;
    border: 1px solid #e5e6e7;
    background: #e3e3e3;
}
.card-form {
	border: 1px solid #dcdcdc;
    padding: 7px;
	border-radius: 5px;
}

.active-tab {
    background-color: #18a689;
    border-color: #18a689;
	border-radius: 5px;
    color: #FFFFFF;
}

.inactive-tab {
    background: #eee;
	border-color: #e3e1e1;
	border-radius: 5px;
    color: #aaa;
}
.error-message {
    /* display: none; */
    width: 100%;
    margin-top: 0.25rem;
    font-size: 91%;
    color: #dc3545;
    text-align: start;
}

</style>
	
@endsection
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
					<strong>Edit Care Homes</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox ">
					<div class="ibox-content ff shadow border rounded">
						<div class="ibox-content">
							<form method="POST" role="form" action="{{ route('homes.update', $home->id) }}" id="careHomeForm" enctype="multipart/form-data">
								@csrf
								@method("PATCH")
								<fieldset class="w-100" id="form_step_1">
									<!--h2 class="border-bottom font-normal mb-3 pb-2">General Information</h2-->
									<div class="row">
										<div class="col-lg-2 my-1">
											<div class="bg-info col-12 py-3 rounded-lg team-memberc text-center">
												<div class="form-group">
													<h4 class="mb-3">Upload Home Image</h4>
													<div class="d-inline-block home-image-upload">
														<img alt="image" id="existing_home_image" class="border border-dark rounded-circle me-2" src="{{ $home->image_path }}">
														<button type="button" id="select_home_image"><i class="fa fa-pencil"></i></button>
													</div>
													<input type="file" class="form-control" name="image" id="home_image" accept="image/*"  style="display:none" onchange="handleFiles(this)">
												</div>
											</div>
										</div>
										<div class="col-lg-10">
											<div class="row">
												<div class="col-lg-4">
													<div class="form-group">
														<label for="name">Name *</label>
														<input  type="text" name="name" id="name" placeholder="Name" class="form-control rounded required" value="{{ $home->name }}">
													</div>
												</div>
												<div class="col-lg-4">
													<div class="form-group">
														<label for="email">Email *</label>
														<input type="email" name="email" id="email" placeholder="Email" class="form-control rounded required" value="{{ $home->email }}">
													</div>
												</div>
												<div class="col-lg-4">
													<div class="form-group">
														<label for="contact_no">Contact No *</label>
														<input type="text" name="contact_no" id="contact_no" placeholder="Contact No" class="form-control rounded required validate_phone" value="{{ $home->contact_no }}" required>
													</div>
												</div>
												<div class="col-lg-12">
													<div class="form-group">
														<label for="about">About *</label>
														<textarea class="form-control rounded" name="about" id="about" placeholder="Info About Care Home" rows="4" autocomplete="off">{{ $home->about }}</textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="row">
												<div class="col-lg-12">
													<div class="form-group">
														<label for="street">Street Name *</label>
														<input type="text" name="street" id="street" placeholder="Street Name" class="form-control rounded required" value="{{ $home->street }}">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label for="city">City Name *</label>
														<input type="text" name="city" id="city" placeholder="City Name" class="form-control rounded required" value="{{ $home->city }}">
													</div>
													<div class="form-group">
														<label for="state">State Name *</label>
														<input type="text" name="state" id="state" placeholder="State Name" class="form-control rounded required" value="{{ $home->state }}">
													</div>
													<div class="form-group">
														<label for="privacy_policy">Care Home Policy</label>
														<input type="file" class="form-control rounded valid_doc" name="privacy_policy" id="privacy_policy" placeholder="Policy of Care Home" rows="4" autocomplete="off" >
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label for="zip_code">Zip Code *</label>
														<input type="number" name="zip_code" id="zip_code" placeholder="Zip Code" class="form-control rounded required" value="{{ $home->zip_code }}">
													</div>
													<div class="form-group">
														<label for="status">Status *</label>
														<select name="status" id="status" class="form-control rounded required" >
															<option value="">Select status</option>
															<option value="1" {{$home->status == 1 ? 'selected' : '' }}>Active</option>
															<option value="0" {{$home->status == 0 ? 'selected' : '' }}>In-Active</option>
														</select>
													</div>
													{{-- <div class="form-group">
														<label for="term_conditions">Term & Conditions *</label>
														<input type="file"  class="form-control rounded valid_doc" name="term_conditions" id="term_conditions" placeholder="Term & Conditions of Care Home" rows="4" autocomplete="off"  >
													</div> --}}
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="row">
												<div class="col-lg-6">
													<div class="form-group">
														<label for="location_lat">Location Lat *</label>
														<input type="text" name="location_lat" id="location_lat" readonly class="form-control rounded required" value="{{ $home->location_lat }}">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label for="location_long">Location Longitude *</label>
														<input type="text" name="location_long" id="location_long" readonly class="form-control rounded required" value="{{ $home->location_long }}">
													</div>
												</div>
												<div class="col-lg-12">
													<div class="form-group">
														<label for="geofencing_radius">Geofencing Radius (Miles)</label>
														<input type="number" name="geofencing_radius" id="geofencing_radius" class="form-control rounded" value="{{ $home->geofencing_radius }}">
													</div>
												</div>
												<div class="col-lg-12">
													<div id="map" class="map-div rounded"></div>
												</div>
											</div>
										</div>
										
										
									</div>
									<div class="hr-line-dashed"></div>
									<div class="form-group row">
										<div class="col-md-12 text-right">
											<a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('homes.index')) }}">Cancel</a>
											<button class="btn btn-sm btn-primary" type="submit" id="careHome_Form">Save</button>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@php
		$location_lat = !is_null($home->location_lat) ? $home->location_lat : 47.116386;
		$location_long = !is_null($home->location_long) ? $home->location_long : -101.299591;
	@endphp
@endsection
@section('script')
	<!-- BS custom file -->
    <script src="{{ asset('assets/js/plugins/bs-custom-file/bs-custom-file-input.min.js') }}"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script type="text/javascript">
		$(document).ready(function () {
			//map functionlaity start
			let map = new L.map('map' , { center:['{{$location_lat}}', '{{$location_long}}'], zoom:2 });
			let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
			map.addLayer(layer);
			let marker = null;
			//When map is loaded set default marker
			map.whenReady(function(event){
               //console.log('map on load event fire', event);
				if(marker !== null){
					map.removeLayer(marker);
				}
				marker = L.marker(['{{$location_lat}}', '{{$location_long}}']).addTo(map);
				$('#location_lat').val('{{$location_lat}}');
				$('#location_long').val('{{$location_long}}');
			})
			
			//On Map click get location lat and long
			map.on('click', (event)=> {
				if(marker !== null){
					map.removeLayer(marker);
				}
				marker = L.marker([event.latlng.lat , event.latlng.lng]).addTo(map);
				$('#location_lat').val(event.latlng.lat);
				$('#location_long').val(event.latlng.lng);
			})
			
			//map functionlaity end
			//change care home image start
			$('#select_home_image').click(function(){
				 $('#home_image').trigger('click')
			})
			//change care home image end


			
		}) 
		//preview home image on select Start
		const handleFiles = (input) => {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#existing_home_image').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		//create form validation object

		$('.required').keyup(function(){
				let element_id = $(this).attr('id');
				let element_type = $(this).attr('type');
				let element_val = $(this).val();
				var form_valid = true;
				if($(this).val() != ''){
					if(element_type == 'email'){
						var valid_email = IsEmail(element_val);
						if(!valid_email){
							$(this).focus();
							//remove if any erorr element exist
							$('#'+element_id+'-error').remove();
							//add new error element
							let error_html = '<div id="'+element_id+'-error" class="error-message">Please enter a valid email address..</div>';
							$(this).after(error_html);
							form_valid = false;
						}
					}else if(element_type == 'number'){
						var valid_zip = IsValidZipCode(element_val);
						if(!valid_zip){
							$(this).focus();
							//remove if any erorr element exist
							$('#'+element_id+'-error').remove();
							//add new error element
							let error_html = '<div id="'+element_id+'-error" class="error-message">Please enter a valid zipcode..</div>';
							$(this).after(error_html);
							form_valid = false;
						}
					}
					if(form_valid){
						let element_id = $(this).attr('id');
						$('#'+element_id+'-error').fadeOut(300, function(){ $(this).remove();});
					}
				}	
			})
    </script>
@endsection