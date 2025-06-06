@extends('layouts.admin')

@section('title', 'Create Patient')
@section("style")
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
</style>
@endsection
@section('content')
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item">
					<a href="{{ route('homes.show', $home_id) }}">Patient</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Create Patient</strong>
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
							<form method="POST" role="form" action="{{ route('patients.store') }}" id="patient_Form" enctype="multipart/form-data">
								@csrf
                                <input type="hidden" name="home_id" value="{{ $home_id }}">
								<fieldset class="w-100" id="form_step_1">
									<!--h2 class="border-bottom font-normal mb-3 pb-2">General Information</h2-->
									<div class="row">
										<div class="col-lg-2 my-1">
											<div class="bg-info col-12 py-3 rounded-lg team-memberc text-center">
												<div class="form-group">
													<h4 class="mb-3">Upload Patient Image</h4>
													<div class="d-inline-block home-image-upload">
														<img alt="image" id="existing_profile_image" class="border border-dark rounded-circle me-2" src="{{ asset('assets/img/patient_dummy.png') }}">
														<button type="button" id="select_profile_image"><i class="fa fa-pencil"></i></button>
													</div>
													<input type="file" class="form-control" name="profile_image" id="profile_image" accept="image/*"  style="display:none" onchange="handleFiles(this)">
												</div>
											</div>
										</div>
										<div class="col-lg-10">
											<div class="row">
												<div class="col-lg-4">
													<div class="form-group">
														<label for="name">Name *</label>
														<input  type="text" name="name" id="name" placeholder="Name" class="form-control rounded required">
													</div>
												</div>
												<div class="col-lg-4">
													<div class="form-group">
														<label for="email">Email *</label>
														<input type="email" name="email" id="email" placeholder="Email" class="form-control rounded required">
													</div>
												</div>
												<div class="col-lg-4">
													<div class="form-group">
														<label for="contact_no">Phone No *</label>
														<input type="text" name="phone_number" id="phone_no" placeholder="Phone No." class="form-control rounded required">
													</div>
												</div>
												<div class="col-lg-12">
													<div class="form-group">
														<label for="patient_info">Patient Info</label>
														<textarea class="form-control rounded" name="patient_info" id="patient_info" placeholder="Info About Patient" rows="4" autocomplete="off"></textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="row">
                                                <div class="col-lg-12">
													<div class="form-group">
														<label for="address">Address *</label>
														<input type="text" name="address" id="address" placeholder="Address" class="form-control rounded required">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label for="street">Emergency Contact *</label>
														<input type="text" name="emergency_contact" id="emergency_contact" placeholder="Emergency Contact" class="form-control rounded required">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group" id="admission_date_1">
														<label for="admission_date">Admission Date *</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														    <input type="text" name="admission_date" id="admission_date" placeholder="Admission Date" class="form-control required" >
                                                        </div>
													</div>
                                                </div>
                                                <div class="col-lg-6">
													<div class="form-group">
														<label for="per_day_cost">Per Day Cost*</label>
														<input type="text" name="per_day_cost" id="per_day_cost" placeholder="Per Day Cost" class="form-control rounded required">
													</div>
												</div>
                                                <div class="col-lg-6">
													<div class="form-group">
														<label for="per_day_reserve_cost">Per Day Reserve Cost</label>
														<input type="text" name="per_day_reserve_cost" id="per_day_reserve_cost" placeholder="Per Day Reserve Cost" class="form-control rounded">
													</div>
												</div>
                                                <div class="col-lg-6">
													<div class="form-group">
														<label for="status">Status *</label>
														<select name="status" id="status" class="form-control rounded required" >
															<option value="">Select status</option>
															<option value="1">Active</option>
															<option value="0">In-Active</option>
														</select>
													</div>
												</div>
                                                <div class="col-lg-6">
													<div class="form-group">
														<label for="initial_payment">Initial Payment *</label>
														<select name="initial_payment" id="initial_payment" class="form-control rounded required" >
															<option value="">Select yes or no</option>
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
													</div>
                                                    <div class="form-group" id="payment_amount" style="display:none;">
														<label for="inital_payment_amount">Initial Payment Amount</label>
														<input type="text" name="inital_payment_amount" id="inital_payment_amount" placeholder="Initial Payment Amount" class="form-control rounded">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="hr-line-dashed"></div>
									<div class="assign_activity">
										<h4 class="mb-3">Assign Activity to Patient</h4>
										<div class="col-lg-12">
											<div class="row">
												<div class="col-lg-6">
													<div class="form-group">
														<label for="name">Activity Name *</label>
														<select class="form-control m-b" name="activity" id="activity_name">
															<option value="">Choose Activity</option>
															@foreach($activities as $activity)
																<option value="{{ $activity->id }}">{{ $activity->name }}</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label for="duration">Duration (in minutes)</label>
														<input type="text" name="duration" id="duration" placeholder="Duration" class="form-control rounded integer_no">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label for="contact_no">Frequency(times a day)</label>
														<input type="text" name="frequency" id="frequency" placeholder="Frequency" class="form-control rounded integer_no">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label for="contact_no">Recurrence</label>
														<select class="form-control m-b" name="recurrence" id="recurrence">
															<option value="">Choose option</option>
															@foreach($activity_recurrence as $key => $value)
																<option value="{{ $key }}">{{ $value }}</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="col-lg-12">
													<div class="form-group">
														<label for="description">Description</label>
														<textarea class="form-control rounded" name="description" id="description" placeholder="Description" rows="4" autocomplete="off"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 text-right">
											<a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('homes.show', $home_id)) }}">Cancel</a>
											<button class="btn btn-sm btn-primary" type="submit" id="patientForm">Save</button>
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
@endsection
@section('script')
	<!-- BS custom file -->
    <script src="{{ asset('assets/js/plugins/bs-custom-file/bs-custom-file-input.min.js') }}"></script>
    <script type="text/javascript">
		$(document).ready(function () {
			//change care home image start
			$('#select_profile_image').click(function(){
				 $('#profile_image').trigger('click')
			})
			//change care home image end

            //add calendar for admission date
            $('#admission_date_1 .input-group.date').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            // show hide initial payment amount div
            $('#initial_payment').change(function(){
                // Get the selected option value
                var selectedValue = $(this).val();
                console.log(selectedValue);
                // Show or hide the div based on the selected option
                if(selectedValue === "1") {
                    $('#payment_amount').show();
                    $('#inital_payment_amount').prop('required',true);
                } else {
                    $('#payment_amount').hide();
                    $('#inital_payment_amount').prop('required',false);
                }
            });
		}) 
		//preview home image on select Start
		const handleFiles = (input) => {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#existing_profile_image').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		//create form validation object
    </script>
@endsection