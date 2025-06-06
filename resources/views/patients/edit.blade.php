@extends('layouts.admin')

@section('title', 'Edit Patient')
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
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item">
					<a href="{{ url()->previous() }}">Patient</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Edit Patient</strong>
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
							<form method="POST" role="form" action="{{ route('patients.update', $patient->id) }}" id="edit_patient_Form" enctype="multipart/form-data">
								@csrf
                                @method("PATCH")
                                <input type="hidden" name="home_id" value="{{ $patient->home_id }}">
                                <input type="hidden"  id="edit_patient_id" value="{{ $patient->id }}">
								<fieldset class="w-100" id="form_step_2">
									<!--h2 class="border-bottom font-normal mb-3 pb-2">General Information</h2-->
									<div class="row">
										<div class="col-lg-2 my-1">
											<div class="bg-info col-12 py-3 rounded-lg team-memberc text-center">
												<div class="form-group">
													<h4 class="mb-3">Upload Patient Image</h4>
													<div class="d-inline-block home-image-upload">
														<img alt="image" id="existing_profile_image" class="border border-dark rounded-circle me-2" src="{{ $patient->profile_image_path }}">
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
														<input  type="text" name="name" id="name" placeholder="Name" class="form-control rounded required" value="{{ $patient->name }}">
													</div>
												</div>
												<div class="col-lg-4">
													<div class="form-group">
														<label for="email">Email </label>
														<input type="email" name="email" id="email" placeholder="Email" class="form-control rounded" value="{{ $patient->email }}" {{$patient->email!=null && !empty($patient->email)? 'disabled' :''}}>
													</div>
												</div>
												<div class="col-lg-4">
													<div class="form-group">
														<label for="contact_no">Phone No </label>
														<input type="text" name="phone_number" id="phone_no" placeholder="Phone No." class="form-control rounded" value="{{ $patient->phone }}">
													</div>
												</div>
												<div class="col-lg-12">
													<div class="form-group">
														<label for="patient_info">Patient Info</label>
														<textarea class="form-control rounded" name="patient_info" id="patient_info" placeholder="Info About Patient" rows="4" autocomplete="off">{{ $patient->patient_info }}</textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="row">
                                                <div class="col-lg-3">
													<div class="form-group">
														<label for="address">Address *</label>
														<input type="text" name="address" id="address" placeholder="Address" class="form-control rounded required" value="{{ $patient->address }}">
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label for="street">Emergency Contact Name</label>
														<input type="text" name="emergency_contact_name" id="emergency_contact_name" placeholder="Emergency Contact Name" class="form-control rounded" value="{{ $patient->emergency_contact_name }}">
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label for="street">Emergency Contact *</label>
														<input type="text" name="emergency_contact" id="emergency_contact" placeholder="Emergency Contact" class="form-control rounded required" value="{{ $patient->emergency_contact }}">
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group" id="admission_date_1">
														<label for="admission_date">Admission Date *</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														    <input type="text" name="admission_date" id="admission_date" placeholder="Admission Date" class="form-control required" value="{{ \Carbon\Carbon::parse($patient->admission_date)->format('m/d/Y') }}">
                                                        </div>
													</div>
                                                </div>
                                                <div class="col-lg-3">
													<div class="form-group">
														<label for="per_day_cost">Per Day Cost*</label>
														<input type="text" name="per_day_cost" id="per_day_cost" placeholder="Per Day Cost" class="form-control rounded required" value="{{ $patient->per_day_cost }}">
													</div>
												</div>
                                                <div class="col-lg-3">
													<div class="form-group">
														<label for="per_day_reserve_cost">Per Day Reserve Cost</label>
														<input type="text" name="per_day_reserve_cost" id="per_day_reserve_cost" placeholder="Per Day Reserve Cost" class="form-control rounded" value="{{ !empty($patient->per_day_reserve_cost) ? $patient->per_day_reserve_cost : '' }}">
													</div>
												</div>
                                                {{-- <div class="col-lg-3">
													<div class="form-group">
														<label for="status">Status *</label>
														<select name="status" id="status" class="form-control rounded required" >
															<option value="">Select status</option>
															<option value="1" {{$patient->status == 1 ? 'selected' : '' }}>Active</option>
															<option value="0" {{$patient->status == 0 ? 'selected' : '' }}>In-Active</option>
														</select>
													</div>
												</div> --}}
                                                <div class="col-lg-3">
													<div class="form-group">
														<label for="initial_payment">Initial Payment *</label>
														<select name="initial_payment" id="initial_payment" class="form-control rounded required" >
															<option value="">Select yes or no</option>
															<option value="1" {{$patient->initial_payment == 1 ? 'selected' : '' }}>Yes</option>
															<option value="0" {{$patient->initial_payment == 0 ? 'selected' : '' }}>No</option>
														</select>
													</div>
												</div>
												<div class="col-lg-3">
                                                    <div class="form-group" id="payment_amount" style="{{$patient->initial_payment == 0 ? 'display:none;' : ''}}">
														<label for="inital_payment_amount">Initial Payment Amount</label>
														<input type="text" name="inital_payment_amount" id="inital_payment_amount" placeholder="Initial Payment Amount" class="form-control rounded" value="{{$patient->inital_payment_amount}}">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="hr-line-dashed"></div>
									<div class="form-group row">
										<div class="col-md-12 text-right">
											<a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('homes.show', $patient->home_id)) }}">Cancel</a>
											<button class="btn btn-sm btn-primary" type="button" id="editpatientForm">Save</button>
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
			var initial_payment = $('#initial_payment').val();
			if(initial_payment === "1") {
					$('#inital_payment_amount').addClass('required');
                } else {
					$('#inital_payment_amount').removeClass('required');
                }
			
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
                autoclose: true,
				todayHighlight: true
            });

            // show hide initial payment amount div
            $('#initial_payment').change(function(){
                // Get the selected option value
                var selectedValue = $(this).val();
                // Show or hide the div based on the selected option
                if(selectedValue === "1") {
                    $('#payment_amount').show();
                    // $('#inital_payment_amount').prop('required',true);
					$('#inital_payment_amount').addClass('required');
                } else {
                    $('#payment_amount').hide();
                    // $('#inital_payment_amount').prop('required',false);
					$('#inital_payment_amount').removeClass('required');
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
		// Function to check if email exists
function checkEmailExists(email) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "{{ route('patients.check-email-exsist') }}",
            data: { email: email },
            success: function(response) {
                if (response.exsist === true) {
                    resolve(false);
                } else {
                    resolve(true);
                }
            },
            error: function(err) {
                reject(err); // Handle error
            },
        });
    });
}

// Function to validate the form
async function validateForm() {
    var form_valid = true;
    var focus = '';
    var form = $('#edit_patient_Form');
    var validationPromises = [];

    // Validate each required field
    $('#form_step_2').find('input, select, textarea').each(function() {
        let element_id = $(this).attr('id');
        let element_type = $(this).attr('type');
        let element_val = $(this).val();

        if ($(this).hasClass('required') && element_val === '') {
            // Focus on the first error element
            if (focus === '') {
                $(this).focus();
                focus = 'yes';
            }
            // Remove if any error element exists
            $('#' + element_id + '-error').remove();
            // Add new error element
            let error_html = '<div id="' + element_id + '-error" class="error-message">This field is required.</div>';
            $(this).after(error_html);
            form_valid = false;
        } else if ($(this).hasClass('required') && (element_id === 'emergency_contact' || element_id === 'care_person_contact')) {
            var valid_contact = IsValidPhoneNumber(element_val);
            if (!valid_contact) {
                $(this).focus();
                // Remove if any error element exists
                $('#' + element_id + '-error').remove();
                // Add new error element
                let error_html = '<div id="' + element_id + '-error" class="error-message">Please enter a valid phone number.</div>';
                $(this).after(error_html);
                form_valid = false;
            } else {
                $('#' + element_id + '-error').remove();
            }
        } 
		else if(element_id == 'phone_no'  && element_val!=='')
		{
			
			var valid_contact = IsValidPhoneNumber(element_val);
				if(!valid_contact){
					$(this).focus();
					//remove if any erorr element exist
					$('#'+element_id+'-error').remove();
					//add new error element
					let error_html = '<div id="'+element_id+'-error" class="error-message">Please enter a valid phone number..</div>';
					$(this).after(error_html);
					form_valid = false;
				}
					else{
					$('#' + element_id + '-error').remove();
				}
		}
		else if ((element_id === 'per_day_cost' || element_id === 'per_day_reserve_cost' || element_id === 'inital_payment_amount') && element_val !== '') {
            var valid_integer = isValidNumber(element_val);
            if (!valid_integer) {
                $(this).focus();
                // Remove if any error element exists
                $('#' + element_id + '-error').remove();
                // Add new error element
                let error_html = '<div id="' + element_id + '-error" class="error-message">Please enter a valid number.</div>';
                $(this).after(error_html);
                form_valid = false;
            } else {
                $('#' + element_id + '-error').remove();
            }
        } 
		/*else if (element_id === 'email' && !$(this).is(':disabled')) {
            // Check email existence asynchronously
            let self = $(this);
            validationPromises.push(
                checkEmailExists(element_val).then(valid_email => {
                    if (!valid_email) {
                        // Email doesn't exist or error occurred
                        self.focus(); // Adjust focus behavior as needed
                        $('#' + element_id + '-error').remove();
                        let error_html = '<div id="' + element_id + '-error" class="error-message">This email already exists.</div>';
                        self.after(error_html);
                        form_valid = false;
                    } else {
                        $('#' + element_id + '-error').remove();
                    }
                }).catch(err => {
                    console.error("Email validation error:", err);
                    form_valid = false;
                })
            );
        }*/
    });

    await Promise.all(validationPromises);

    return form_valid;
}

// Event handler for form submission
$('#editpatientForm').on('click', async function(e) {
    e.preventDefault();
    var form_valid = await validateForm();
    if (form_valid) {
        ShowFormLoading();
        $('#edit_patient_Form')[0].submit();
    }
});

	//create form validation object
    </script>
@endsection