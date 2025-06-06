@extends('layouts.admin')

@section('title', 'Create Patient')
@section('style')
    <link href="{{ asset('assets/css/plugins/clockpicker/clockpicker.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
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

        .select_week_full .select2 {
            width: 100% !important;
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
                    @if ($home_id == 0)
                        <a href="{{ route('patients.index') }}">Patient</a>
                    @else
                        <a href="{{ route('homes.show', $home_id) }}#tab3">Patient</a>
                    @endif
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
                <div class="ibox shadow border rounded">
                    <!-- <div class="row form-tabs no-gutters">
                                    <div class="col-lg-6">
                                        <a class="btn btn-block active-tab btn-lg header-tab rounded-0" href="javascript:;"
                                            id="tab_header_1">Biographic & Primary Care Person Information</a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a class="btn btn-block inactive-tab btn-lg header-tab rounded-0" href="javascript:;"
                                            id="tab_header_2">Medical History & Medications </a>
                                    </div>
                                    {{-- <div class="col-lg-4">
							<a class="btn btn-block inactive-tab btn-lg header-tab rounded-0" href="javascript:;" id="tab_header_3">Activities to Perform</a>
						</div> --}}
                                </div> -->

                    <div class="ibox-content">
                        <div class="">
                            <div class="ibox-title pl-0">
                                <h5>Patient Information </h5>
                            </div>
                            <form method="POST" role="form" action="{{ route('patients.store') }}" id="patient_Form"
                                enctype="multipart/form-data" class="wizard-big-----1">
                                @csrf
                                <input type="hidden" name="home_id" id="home_id" value="{{ $home_id }}">
                                <fieldset class="w-100" id="form_step_2">
                                    <!--h2 class="border-bottom font-normal mb-3 pb-2">General Information</h2-->
                                    <div class="row">
                                        <div class="col-lg-2 my-1">
                                            <div class="bg-info col-12 py-3 rounded-lg team-memberc text-center">
                                                <div class="form-group">
                                                    <h4 class="mb-3">Upload Patient Image</h4>
                                                    <div class="d-inline-block home-image-upload">
                                                        <img alt="image" id="existing_profile_image"
                                                            class="border border-dark rounded-circle me-2"
                                                            src="{{ asset('assets/img/patient_dummy.png') }}">
                                                        <button type="button" id="select_profile_image"><i
                                                                class="fa fa-pencil"></i></button>
                                                    </div>
                                                    <input type="file" class="form-control" name="profile_image"
                                                        id="profile_image" accept="image/*" style="display:none"
                                                        onchange="handleFiles(this)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10">
                                            <div class="row">
                                                @if ($home_id == 0)
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="d-block" for="staffName">Care Homes *</label>
                                                            <select class="form-control select2_element required"
                                                                name="care_home" id="care_home">
                                                                <option></option>
                                                                @foreach ($care_homes as $careHomeKey => $care_home)
                                                                    <option value="{{ $care_home->id }}">
                                                                        {{ $care_home->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="name">Name *</label>
                                                        <input type="text" name="name" id="name"
                                                            placeholder="Name" class="form-control rounded required">
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="email">Email </label>
                                                        <input type="email" name="email" id="email"
                                                            placeholder="Email" class="form-control rounded ">
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="contact_no">Phone No </label>
                                                        <input type="tel" name="phone_number" id="phone_no"
                                                            placeholder="Phone No." class="form-control rounded ">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="patient_info">Patient Info</label>
                                                        <textarea class="form-control rounded" name="patient_info" id="patient_info" placeholder="Info About Patient"
                                                            rows="4" autocomplete="off"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="address">Address *</label>
                                                        <input type="text" name="address" id="address"
                                                            placeholder="Address" class="form-control rounded required">
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="street">Emergency Contact Name</label>
                                                        <input type="tel" name="emergency_contact_name"
                                                            id="emergency_contact_name"
                                                            placeholder="Emergency Contact Name"
                                                            class="form-control rounded">
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="street">Emergency Contact *</label>
                                                        <input type="tel" name="emergency_contact"
                                                            id="emergency_contact" placeholder="Emergency Contact"
                                                            class="form-control rounded required">
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group" id="admission_date_1">
                                                        <label for="admission_date">Admission Date *</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i
                                                                    class="fa fa-calendar"></i></span>
                                                            <input type="text" name="admission_date"
                                                                id="admission_date" placeholder="Admission Date"
                                                                class="form-control required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="per_day_cost">Per Day Cost*</label>
                                                        <input type="text" name="per_day_cost" id="per_day_cost"
                                                            placeholder="Per Day Cost"
                                                            class="form-control rounded required">
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="per_day_reserve_cost">Per Day Reserve Cost</label>
                                                        <input type="text" name="per_day_reserve_cost"
                                                            id="per_day_reserve_cost" placeholder="Per Day Reserve Cost"
                                                            class="form-control rounded">
                                                    </div>
                                                </div>
                                                {{-- <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="status">Status *</label>
                                                        <select name="status" id="status"
                                                            class="form-control rounded required">
                                                            <option value="">Select status</option>
                                                            <option value="1">Active</option>
                                                            <option value="0">In-Active</option>
                                                        </select>
                                                    </div>
                                                </div> --}}
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="initial_payment">Initial Payment *</label>
                                                        <select name="initial_payment" id="initial_payment"
                                                            class="form-control rounded required">
                                                            <option value="">Select yes or no</option>
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group" id="payment_amount" style="display:none;">
                                                        <label for="inital_payment_amount">Initial Payment Amount *</label>
                                                        <input type="text" name="inital_payment_amount"
                                                            id="inital_payment_amount"
                                                            placeholder="Initial Payment Amount"
                                                            class="form-control rounded">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="hr-line-dashed"></div>
                                            <h4 class="mb-3">Primary Care Person Information</h4>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="name">Name</label>
                                                                <input type="text" name="care_person_name"
                                                                    id="care_person_name" placeholder="Name"
                                                                    class="form-control rounded">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="contact_no">Phone No</label>
                                                                <input type="tel" name="care_person_contact"
                                                                    id="care_person_contact" placeholder="Contact No."
                                                                    class="form-control rounded">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="contact_no">Relationship with Patient</label>
                                                                <input type="text" name="care_person_relation"
                                                                    id="care_person_relation"
                                                                    placeholder="Relationship with Patient"
                                                                    class="form-control rounded">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="address">Address</label>
                                                                <input type="text" name="care_person_address"
                                                                    id="care_person_address" placeholder="Address"
                                                                    class="form-control rounded">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="hr-line-dashed"></div>
                                            <h4 class="mb-3">Biography Information</h4>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <div class="form-group ">
                                                                <label for="height">Height</label>
                                                                <div class="d-flex">
                                                                    <select name="height_in_feet" id="height_in_feet"
                                                                        class="form-control rounded mr-1">
                                                                        <option value="">Feet</option>
                                                                        @for ($hf = 1; $hf <= 7; $hf++)
                                                                            <option value="{{ $hf }}">
                                                                                {{ $hf }}</option>
                                                                        @endfor
                                                                    </select>
                                                                    <select name="height_in_inch" id="height_in_inch"
                                                                        class="form-control rounded mr-1">
                                                                        <option value="">Inch</option>
                                                                        @for ($hi = 1; $hi <= 12; $hi++)
                                                                            <option value="{{ $hi }}">
                                                                                {{ $hi }}</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="d-flex">
                                                                <div class="form-group w-50 mr-1">
                                                                    <label for="weight">Weight (in pounds)</label>
                                                                    <input type="number" name="weight" id="weight"
                                                                        placeholder="weight" class="form-control rounded">
                                                                    <div class="error d-none text-danger"
                                                                        id="weight-error"></div>
                                                                </div>
                                                                <div class="form-group w-50 mr-1">
                                                                    <label for="gender">Sex</label>
                                                                    <select name="gender" id="gender"
                                                                        class="form-control rounded mr-1">
                                                                        <option value="">Sex</option>
                                                                        <option Value="M">Male</option>
                                                                        <option value="F">Female</option>
                                                                        <option value="O">Other</option>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="blood_group">Blood Group</label>
                                                                <select name="blood_group" id="blood_group"
                                                                    class="form-control rounded">
                                                                    <option value="">Select Blood Group</option>
                                                                    <option value="Unknown">Unknown</option>
                                                                    <option value="A+">A+</option>
                                                                    <option value="A-">A-</option>
                                                                    <option value="B+">B+</option>
                                                                    <option value="B-">B-</option>
                                                                    <option value="AB+">AB+</option>
                                                                    <option value="AB-">AB-</option>
                                                                    <option value="O+">O+</option>
                                                                    <option value="O-">O-</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="preferences">Preferences</label>
                                                                <input type="text" name="preferences" id="preferences"
                                                                    placeholder="Preferences"
                                                                    class="form-control rounded">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    {{-- <div class="actions clearfix float-right">
										<a class="btn btn-primary font-bold" href="javascript:;" data-current-step="1" role="menuitem" onclick="validateFormStep('1', '2')">Next <i class="fa fa-chevron-right"></i></a>
									</div> --}}
                                    @include('patients.partials.patient-medicine-document-form')
                                    @include('patients.partials.patient-add-activity-form')

                                    <input type="hidden" name="redirectURL"
                                        value="{{ createCancelUrl(route('patients.index')) }}">
                                    <div class="hr-line-dashed"></div>
                                    <div class="actions clearfix float-right" id="submit_button_div">
                                        <a class="btn btn-white btn-sm" type="button"
                                            href="{{ createCancelUrl(route('patients.index')) }}">Cancel</a>
                                        <a class="btn btn-primary font-bold" href="javascript:;" data-current-step="2"
                                            onclick="validateFormStep(2)">Submit</a>
                                    </div>
                                    <div class="actions clearfix float-right" id="loader_button_div"
                                        style="display: none;">
                                        <div class="col-lg-12 col-m-12">
                                            <button type="button" class="btn btn-primary" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Wait Processing...
                                            </button>
                                        </div>
                                    </div>
                                </fieldset>
                                {{-- <fieldset class="d-none medication_div" id="form_step_2">
									@include('patients.partials.patient-medicine-document-form')
								</fieldset> --}}
                                {{-- <fieldset class="d-none assign_activity" id="form_step_3">
									@include('patients.partials.patient-activity-form')
								</fieldset> --}}

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/plugins/bs-custom-file/bs-custom-file-input.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/clockpicker/clockpicker.js') }}"></script>
    <script type="text/javascript">
        function clockPickerCall() {
            $('.clockpicker').clockpicker();
        }

        function datePickerCall() {
            var mem = $('#data_1 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
            });
        }
        $(document).ready(function() {
            $("#care_home").select2({
                placeholder: "Select care home",
                allowClear: true
            });

            $('.select_staffs').select2({
                placeholder: 'Select Staff',
            });
            $(document).on('change', '#care_home', function() {
                var careHomeId = $(this).val();

                $('#home_id').val(careHomeId);
                if (careHomeId != '') {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get-care-home-staffs') }}",
                        data: {
                            home_id: careHomeId
                        },
                        dataType: 'json',
                        success: function(data) {

                            if (data.status && data.status == 'failed') {
                                $('#care_home').val(null).trigger('change');

                                Swal.fire({
                                    text: "Patient limit exceeded. Please upgrade your plan or purchase Add-ons",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#f39c12",
                                    confirmButtonText: "Add Addon",
                                    cancelButtonText: "Upgrade Plan"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href =
                                            `/subscription-manage/${careHomeId}?add_ons=yes&type=patient`;
                                    } else if (result.dismiss === Swal.DismissReason
                                        .cancel) {
                                        window.location.href =
                                            `/subscription-manage/${careHomeId}?upgrade_downgrade_plan=yes&type=patient`;
                                    }
                                });
                            }else{
                                $('#care_home').val(null).trigger('change');
                                var name = data.name;
                                if(data.subscription_status == true){
                                    Swal.fire({
                                        text: name + " Subscription not active. Please buy a new subscription",
                                        icon: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: "#3085d6",
                                        cancelButtonColor: "#f39c12",
                                        confirmButtonText: "Buy",
                                        cancelButtonText: "Cancel"
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href =
                                                `/subscription-manage/${careHomeId}?new_plan=yes&type=patient`;
                                        } else if (result.dismiss === Swal.DismissReason
                                        .cancel) {
                                                window.location.href =
                                                    `/patients`;
                                        }
                                    });
                                }else{
                                    window.location.href = `/patients`;
                                }
                            }
                            $('#staff_ids').empty();
                            $('#staff_ids').append('<option value="">Select Staff</option>');
                            $.each(data, function(key, value) {
                                $('#staff_ids').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });

                        },
                        error: function(err, xhr) {
                            console.log(err);
                        },
                    });
                }
            })
            //change care home image start
            $('#select_profile_image').click(function() {
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
            $('#initial_payment').change(function() {
                // Get the selected option value
                var selectedValue = $(this).val();

                // Show or hide the div based on the selected option
                if (selectedValue === "1") {
                    $('#payment_amount').show();
                    $('#inital_payment_amount').addClass('required');
                } else {
                    $('#payment_amount').hide();
                    $('#inital_payment_amount').removeClass('required');
                }
            });

            // show hide activity perform div (in days)
            // $('#recurrence').change(function(){
            //     // Get the selected option value
            //     var selectedValue = $(this).val();
            // 	if(selectedValue === "2"){
            // 		$('#activity-perform-lbl').text('in week');
            // 	}else if(selectedValue === "3"){
            // 		$('#activity-perform-lbl').text('in month');
            // 	}
            //     // Show or hide the div based on the selected option
            //     if(selectedValue === "2" || selectedValue === "3") {
            //         $('#activity-perform').removeClass('d-none');
            //         $('#activity_performance_day').addClass('required');
            //     } else {
            //         $('#activity-perform').addClass('d-none');
            //         $('#activity_performance_day').removeClass('required',false);
            //     }
            // });


            clockPickerCall();
            $('#medicine_type').change(function() {
                if ($(this).val() == 5) {
                    $('#other_medicine_type_div').removeClass('d-none');
                    // $('#medicine_type_other').addClass('required');
                } else {
                    $('#other_medicine_type_div').addClass('d-none');
                    // $('#medicine_type_other').removeClass('required');
                }
            })

            $('#time_id').change(function() {
                if ($(this).val() && $(this).val().includes('4')) {
                    $('#other_time_div').removeClass('d-none');
                    // $('#time_other').addClass('required');
                } else {
                    $('#other_time_div').addClass('d-none');
                    // $('#time_other').removeClass('required');
                }
            })
            $('.med_time_select').select2({
                placeholder: 'Select a time',
            });

            $('#intake_method').change(function() {
                if ($(this).val() == 4) {
                    $('#other_intake_method_div').removeClass('d-none');

                } else {
                    $('#other_intake_method_div').addClass('d-none');

                }
            })
            $('#intake_supervised_by').change(function() {
                if ($(this).val() == 4) {
                    $('#other_intake_supervised_by_div').removeClass('d-none');
                    // $('#intake_supervised_other').addClass('required');
                } else {
                    $('#other_intake_supervised_by_div').addClass('d-none');
                    // $('#intake_supervised_other').removeClass('required');
                }
            })

            datePickerCall();
            $('#type_id').change(function() {
                if ($(this).val() == 2) {
                    $('#other_remark_div').removeClass('d-none');
                    // $('#other_remark').addClass('required');
                } else {
                    $('#other_remark_div').addClass('d-none');
                    // $('#other_remark').removeClass('required');
                }
            })

            //form element key up remove error filed
            $(document).on("keyup change", '.required', function() {
                let element_id = $(this).attr('id');
                let element_type = $(this).attr('type');
                let element_val = $(this).val();
                var form_valid = true;
                if ($(this).val() != '') {
                    if (element_type == 'email') {
                        var valid_email = IsEmail(element_val);
                        if (!valid_email) {
                            $(this).focus();
                            //remove if any erorr element exist
                            $('#' + element_id + '-error').remove();
                            //add new error element
                            let error_html = '<div id="' + element_id +
                                '-error" class="error-message">Please enter a valid email address..</div>';
                            $(this).after(error_html);
                            form_valid = false;
                        }
                    } else if (element_type == 'text' && (element_id ==
                            'emergency_contact' || element_id == 'care_person_contact')) {
                        var valid_phone_no = IsValidPhoneNumber(element_val);
                        if (!valid_phone_no) {
                            $(this).focus();
                            //remove if any erorr element exist
                            $('#' + element_id + '-error').remove();
                            //add new error element
                            let error_html = '<div id="' + element_id +
                                '-error" class="error-message">Please enter a valid contact number.</div>';
                            $(this).after(error_html);
                            form_valid = false;
                        }
                    } else if ($(this).hasClass('valid_fax') && element_val !== '') {

                        var valid_fax = isValidFaxNumber(element_val);
                        if (!valid_fax) {
                            $(this).focus();
                            //remove if any erorr element exist
                            $('#' + element_id + '-error').remove();
                            //add new error element
                            let error_html = '<div id="' + element_id +
                                '-error" class="error-message">Please enter a valid fax number..</div>';
                            $(this).after(error_html);
                            form_valid = false;
                        } else {
                            $('#' + element_id + '-error').remove();
                        }
                    } else if ($(this).hasClass('valid_integer') && element_val !== '') {

                        var valid_fax = isValidIntger(element_val);
                        if (!valid_fax) {
                            $(this).focus();
                            //remove if any erorr element exist
                            $('#' + element_id + '-error').remove();
                            //add new error element
                            let error_html = '<div id="' + element_id +
                                '-error" class="error-message">Please enter a valid number..</div>';
                            $(this).after(error_html);
                            form_valid = false;
                        } else {
                            $('#' + element_id + '-error').remove();
                        }
                    }

                    if (form_valid) {
                        let element_id = $(this).attr('id');
                        $('#' + element_id + '-error').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                }
            })
        })
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

        //create form validation object
        var _validator;
        $(function() {
            _validator = $("#patient_Form").validate();
        });
        //preview home image on select end
        const goToStep = (go_to, current) => {
            $('#form_step_' + current).addClass('d-none');
            $('#form_step_' + go_to).removeClass('d-none');

            $('#tab_header_' + current).removeClass('active-tab').addClass('inactive-tab');
            $('#tab_header_' + go_to).removeClass('inactive-tab').addClass('active-tab');
        }

        function checkEmailExists(email, callback) {
            $.ajax({
                type: "POST",
                url: "{{ route('patients.check-email-exsist') }}",
                data: {
                    email: email
                },
                success: function(response) {

                    if (response.exsist == true) {
                        callback(false);
                    } else {
                        callback(true);
                    }
                },
                error: function(err, xhr) {
                    callback(false); // Handle error
                },
            });
        }
        //validate form step start
        const validateFormStep = (step, next) => {

            var form_valid = true;
            var focus = '';
            $(document).on('blur', '.validate_email', function() {
                let element_id = $(this).attr('id');
                let element_val = $(this).val();

                if (element_val !== '') {
                    if (!IsEmail(element_val)) {
                        // Remove any existing error element
                        $('#' + element_id + '-error').remove();
                        // Add new error element
                        let error_html = '<div id="' + element_id +
                            '-error" class="error-message">Please enter a valid email.</div>';
                        $(this).after(error_html);
                    } else {
                        // Remove error element if email is valid
                        $('#' + element_id + '-error').remove();
                    }
                } else {
                    // Remove error message if input is empty
                    $('#' + element_id + '-error').remove();
                }
            });
            $('#form_step_' + step).find('input, select, textarea').each(function() {

                let element_id = $(this).attr('id');
                let element_type = $(this).attr('type');
                let element_val = $(this).val();
                let element_class = $(this).attr('class');

                if ($(this).hasClass('required') && element_val == '') {
                    //focus first erroe element
                    if (focus == '') {
                        $(this).focus();
                        focus = 'yes';
                    }
                    //remove if any erorr element exist
                    $('#' + element_id + '-error').remove();
                    //add new error element
                    let error_html = '<div id="' + element_id +
                        '-error" class="error-message">This field is required.</div>';
                    if ($(this).hasClass("select2-hidden-accessible")) {
                        $(this).next('.select2-container').after(
                        error_html); // Place error after the Select2 container
                    } else {
                        $(this).after(error_html);
                    }
                    form_valid = false;
                } else if ($(this).hasClass('required') && (element_id ==
                        'emergency_contact' || element_id == 'care_person_contact')) {

                    var valid_contact = IsValidPhoneNumber(element_val);
                    if (!valid_contact) {
                        $(this).focus();
                        //remove if any erorr element exist
                        $('#' + element_id + '-error').remove();
                        //add new error element
                        let error_html = '<div id="' + element_id +
                            '-error" class="error-message">Please enter a valid phone number..</div>';
                        $(this).after(error_html);
                        form_valid = false;
                    } else {
                        $('#' + element_id + '-error').remove();
                    }
                } else if (element_id == 'phone_no' && element_val !== '') {

                    var valid_contact = IsValidPhoneNumber(element_val);
                    if (!valid_contact) {
                        $(this).focus();
                        //remove if any erorr element exist
                        $('#' + element_id + '-error').remove();
                        //add new error element
                        let error_html = '<div id="' + element_id +
                            '-error" class="error-message">Please enter a valid phone number..</div>';
                        $(this).after(error_html);
                        form_valid = false;
                    } else {
                        $('#' + element_id + '-error').remove();
                    }
                }
                /*else if (element_id == 'email') {
                    var self = $(this);
                    // Check email existence asynchronously
                    checkEmailExists(element_val, function(valid_email) {
                        if (!valid_email) {
                            // Email doesn't exist or error occurred
                            self.focus(); // Adjust focus behavior as needed
                            $('#' + element_id + '-error').remove();
                            let error_html = '<div id="' + element_id + '-error" class="error-message">This email already exist.</div>';
                            self.after(error_html);
                            form_valid = false;
                        } else {
                            $('#' + element_id + '-error').remove();
                        }
                    });
                }*/

                /* else if(element_id == 'fax_number' && element_val !== '')
                {
                    
                    var valid_fax = isValidFaxNumber(element_val);
                        if(!valid_fax){
                            $(this).focus();
                            //remove if any erorr element exist
                            $('#'+element_id+'-error').remove();
                            //add new error element
                            let error_html = '<div id="'+element_id+'-error" class="error-message">Please enter a valid fax number..</div>';
                            $(this).after(error_html);
                            form_valid = false;
                        }
                        else{
                        $('#' + element_id + '-error').remove();
                        }
                } */
                else if ($(this).hasClass('required') && $(this).hasClass('valid_fax')) {

                    var valid_fax = isValidFaxNumber(element_val);
                    if (!valid_fax) {
                        $(this).focus();
                        //remove if any erorr element exist
                        $('#' + element_id + '-error').remove();
                        //add new error element
                        let error_html = '<div id="' + element_id +
                            '-error" class="error-message">Please enter a valid fax number..</div>';
                        $(this).after(error_html);
                        form_valid = false;
                    } else {
                        $('#' + element_id + '-error').remove();
                    }
                } else if ($(this).hasClass('required') && $(this).hasClass('valid_integer')) {

                    var valid_fax = isValidIntger(element_val);
                    if (!valid_fax) {
                        $(this).focus();
                        //remove if any erorr element exist
                        $('#' + element_id + '-error').remove();
                        //add new error element
                        let error_html = '<div id="' + element_id +
                            '-error" class="error-message">Please enter a valid number..</div>';
                        $(this).after(error_html);
                        form_valid = false;
                    } else {
                        $('#' + element_id + '-error').remove();
                    }
                } else if ((element_id == 'per_day_cost' || element_id == 'per_day_reserve_cost' ||
                        element_id == 'inital_payment_amount') && element_val !== '') {
                    var valid_integer = isValidNumber(element_val);
                    if (!valid_integer) {
                        $(this).focus();
                        //remove if any erorr element exist
                        $('#' + element_id + '-error').remove();
                        //add new error element
                        let error_html = '<div id="' + element_id +
                            '-error" class="error-message">Please enter a valid number.</div>';
                        $(this).after(error_html);
                        form_valid = false;
                    } else {
                        $('#' + element_id + '-error').remove();
                    }
                }
            });
            if (form_valid) {
                if (step == 1) {
                    $('#form_step_' + step).addClass('d-none');
                    $('#form_step_' + next).removeClass('d-none');
                    $('#tab_header_' + step).removeClass('active-tab').addClass('inactive-tab');
                    $('#tab_header_' + next).removeClass('inactive-tab').addClass('active-tab');
                } else if (step == 2) {

                    $('#submit_button_div').hide();
                    $('#loader_button_div').show();
                    $('.form-loader').show();
                    submitFormData();
                }
            }
        }

        function addFaxNumberValidationForDynamicInputs() {
            $(document).on('input', '.dynamic_fax_input', function() {
                var element_val = $(this).val();

                if (element_val !== '') {

                    var valid_fax = isValidFaxNumber(element_val);
                    if (!valid_fax) {
                        $(this).focus();
                        var element_id = $(this).attr('id');
                        $('#' + element_id + '-error').remove();
                        var error_html = '<div id="' + element_id +
                            '-error" class="error-message">Please enter a valid fax number.</div>';
                        $(this).after(error_html);
                    } else {
                        $('#' + element_id + '-error').remove();
                    }
                }
            });
        }

        // Call the function to apply fax number validation for dynamically added input fields
        addFaxNumberValidationForDynamicInputs();

        function submitFormData() {
            let form = $('#patient_Form')[0];
            if ($('#collapseFour').is(':visible')) {
                let allGroupsValid = true;

                // Find all unique activity groups
                $('[name^="activity"][name$="[activity_shift_id][]"]').each(function() {
                    let groupName = $(this).attr('name').match(/activity\[\d+\]/)[0];
                    let groupChecked = $(`input[name^="${groupName}"][name$="[activity_shift_id][]"]:checked`)
                        .length > 0;
                    let newActivity = groupName.replace(/\[(\d+)\]/g, '-$1');
                    if (!groupChecked) {
                        allGroupsValid = false;
                        $('#submit_button_div').show();
                        $('#loader_button_div').hide();
                        $('.form-loader').css('display', 'none');
                        $("#" + newActivity + "-error").text('Please select at least one activity shift').show();
                        return false; // Exit loop if a group is invalid
                    }
                    $("#" + newActivity + "-error").text('Please select at least one activity shift').hide();
                });

                if (!allGroupsValid) {
                    $('#submit_button_div').show();
                    $('#loader_button_div').hide();
                    $('.form-loader').css('display', 'none');
                    return; // Stop form submission if any group is invalid
                }
            }
            let formData = new FormData(form);

            $.ajax({
                type: "POST",
                url: "{{ route('patients.store') }}",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $('#submit_button_div').show();
                    $('#loader_button_div').hide();
                    $('.form-loader').css('display', 'none');
                    if (response.status == 'success') {
                        toastAlert(response.status, response.message);
                        //clear the form on submit
                        $('#patient_Form')[0].reset();
                        window.location.href = BASE_URL + '/' + 'homes/' + response.home_id + '#tab3';
                    } else {
                        toastAlert(response.status, response.message);
                    }
                },
                error: function(err, xhr) {
                    $('#submit_button_div').show();
                    $('#loader_button_div').hide();
                    $('.form-loader').css('display', 'none');
                    if (err.responseJSON.message === undefined) {
                        toastAlert("error", err.responseJSON);
                    } else {
                        toastAlert("error", err.responseJSON.message);
                    }
                },
            });
        };

        var rowCounter = 1;
        // Add fields
        $('#addFields').click(function() {
            if (rowCounter <= 5) {
                var fieldsHtml = `
						<div class="row added-fields mt-2">
							<div class="col-lg-6">
								<div class="form-group">
									<label for="name">Activity Name *</label>
									<select class="form-control m-b" name="activity[` + rowCounter + `]" id="activity_name">
										<option value="">Choose Activity</option>
										@foreach ($activities as $activity)
											<option value="{{ $activity->id }}">{{ $activity->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="duration">Duration (in minutes)</label>
									<input type="text" name="duration[` + rowCounter + `]" placeholder="Duration" class="form-control rounded valid_integer">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="contact_no">Frequency(times a day)</label>
									<input type="text" name="frequency[` + rowCounter + `]" placeholder="Frequency" class="form-control rounded valid_integer">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="contact_no">Recurrence</label>
									<select class="form-control m-b" name="recurrence[` + rowCounter + `]" id="recurrence` + rowCounter + `">
										<option value="">Choose option</option>
										@foreach ($activity_recurrence as $key => $value)
											<option value="{{ $key }}">{{ $value }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group d-none" id="activity-perform` + rowCounter + `">
									<label for="contact_no">Activity Perform (in Days)</label>
									<input type="text" name="activity_performance_day[` + rowCounter +
                    `]" class="form-control rounded" placeholder="2" id="activity_performance_day` + rowCounter + `">
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label for="description">Description</label>
									<textarea class="form-control rounded" name="description[` + rowCounter + `]" id="description" placeholder="Description" rows="4" autocomplete="off"></textarea>
								</div>
							</div>
							<div class="col-lg-12 text-right">
								<button type="button" class="btn btn-danger removeFields">-</button>
							</div>
						</div>
					`;
                $('.row:last').after(fieldsHtml);
            } else {
                toastAlert("error", 'Maximum 5 rows allowed.');
            }
            rowCounter++;

        });

        // Remove fields
        $(document).on('click', '.removeFields', function() {
            // Decrement rowCounter
            rowCounter--;
            $(this).closest('.added-fields').remove();
        });

        $(document).on('change', 'select[name^="recurrence"]', function() {
            var selectedValue = $(this).val();
            var rowCounter = $(this).attr('name').match(/\d+/)[0]; // Extract the rowCounter from the name attribute
            if (selectedValue === "2") {
                $('#activity-perform-lbl' + rowCounter).text('in week');
            } else if (selectedValue === "3") {
                $('#activity-perform-lbl' + rowCounter).text('in month');
            }

            if (selectedValue === "2" || selectedValue === "3") {
                $('#activity-perform' + rowCounter).removeClass('d-none');
                $('#activity_performance_day' + rowCounter).addClass('required');
            } else {
                $('#activity-perform' + rowCounter).addClass('d-none');
                $('#activity_performance_day' + rowCounter).removeClass('required');
            }
        });

        $(document).ready(function() {
            var maxFields = 10;
            var fieldCount = 1;

            $("#add_doctor_fields").click(function() {
                if (fieldCount < maxFields) {
                    var newField = `
                        <div class="row">
                            <div class="col-lg-3 col-lg">
                                <div class="form-group">
                                    <label for="doctor_name">Name *</label>
                                    <input type="text" name="doctors[` + fieldCount +
                        `][name]" placeholder="Name" class="form-control rounded required" id="doc_name_` +
                        fieldCount + `">
                                </div>
                            </div>
                            <div class="col-lg-3 col-lg">
                                <div class="form-group">
                                    <label for="doctor_email">Email *</label>
                                    <input type="email" id="doctor_email_` + fieldCount + `" name="doctors[` +
                        fieldCount + `][email]" placeholder="Email" class="form-control rounded validate_email required">
                                </div>
                            </div>
                            <div class="col-lg-3 col-lg">
                                <div class="form-group">
                                    <label for="doctor_phone_number">Phone No *</label>
                                    <input type="tel" name="doctors[` + fieldCount +
                        `][phone]" placeholder="Phone No." class="form-control rounded required" id="doc_phn_` +
                        fieldCount + `">
                                </div>
                            </div>
                            <div class="col-lg-3 col-lg">
                                <div class="form-group">
                                    <label for="doctor_address">Address *</label>
                                    <input type="text" name="doctors[` + fieldCount +
                        `][address]" placeholder="Address" class="form-control rounded required" id="doc_address_` +
                        fieldCount + `">
                                </div>
                            </div>
                            <div class="col-lg-3 col-lg">
                                <div class="form-group">
                                    <label for="doctor_role">Designation *</label>
                                    <input type="text" name="doctors[` + fieldCount +
                        `][role]" placeholder="Role" class="form-control rounded required" id="doc_desig_` +
                        fieldCount + `">
                                </div>
                            </div>
                            <div class="col-lg-3 col-lg">
                            <div class="form-group">
                                <label for="doctor_role">Fax Number *</label>
                                <input type="text" name="doctors[` + fieldCount +
                        `][fax_number]" placeholder="Fax Number" class="form-control rounded valid_fax dynamic_fax_input required" id="fax_number" id="fax_number_` +
                        fieldCount + `">
                            </div>
                        </div>
                            <div class="col-lg-12 mb-4 text-right">
                                <button type="button" class="btn btn-outline-danger font-bold remove_doctor"><i class="fa fa-trash"></i> Remove</button>
                            </div>
                        </div>
                    `;
                    $("#more_doctors_div").append(newField);
                    fieldCount++;
                }
            });

            // Remove field when clicking on remove button
            $("#more_doctors_div").on("click", ".remove_doctor", function() {
                $(this).closest(".row").remove();
                fieldCount--;
            });

            var maxFieldsMedicine = 10;
            var fieldCountMedicine = 1;

            $("#add_medicine_fields").click(function() {
                if (fieldCountMedicine < maxFieldsMedicine) {
                    var newFieldMedicine = `
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input  type="text" name="medicines[` + fieldCountMedicine + `][medicine_name]" id="name" placeholder="Medicine Name" class="form-control rounded ">
                                </div>
                            </div>
                            @php
                                $other_medicine_type_divs = 'd-none';
                            @endphp
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="time">Medication-Type</label>
                                    <select name="medicines[` + fieldCountMedicine +
                        `][medicine_type]" id="medicine_type` + fieldCountMedicine + `" class="form-control rounded">
                                        <option value="">Select</option>
                                        @foreach ($medicine_type as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 {{ $other_medicine_type_divs }}" id="other_medicine_type_div` +
                        fieldCountMedicine + `">
                                <div class="form-group">
                                    <label for="time">Medication-Type Other</label>
                                    <input type="text" name="medicines[` + fieldCountMedicine +
                        `][medicine_type_other]" id="medicine_type_other` + fieldCountMedicine + `"  placeholder="Medication Type Other" class="form-control rounded">
                                </div>
                            </div>
                            @php
                                $other_time_div = 'd-none';
                            @endphp
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="time">Time</label>
                                    <select  name="medicines[` + fieldCountMedicine + `][time_id][]" id="time_id` +
                        fieldCountMedicine + `" class="form-control rounded med_time_select" multiple="multiple">
                                        <option value="">Select</option>
                                        @foreach ($medicine_time as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 {{ $other_time_div }}" id="other_time_div` + fieldCountMedicine + `">
                                <div class="form-group">
                                    <label for="time">Other Time</label>
                                    <div class="input-group clockpicker" data-autoclose="true">
                                        <span class="input-group-addon">
                                            <span class="fa fa-clock-o"></span>
                                        </span>
                                        <input type="text" class="form-control" name="medicines[` +
                        fieldCountMedicine + `][time_other]" id="time_other" placeholder="Medicine Other TIme" class="form-control rounded">
                                    </div>
                                </div>
                            </div>
                            	@php
                                $other_intake_method_div = 'd-none';
                            @endphp
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="time">Intake Method</label>
                                    <select name="medicines[` + fieldCountMedicine +
                        `][intake_method]" id="intake_method` + fieldCountMedicine + `" class="form-control rounded">
                                        <option value="">Select</option>
                                        @foreach ($intake_method as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                             <div class="col-lg-3 {{ $other_intake_method_div }}" id="other_intake_method_div` +
                        fieldCountMedicine + `">
                                <div class="form-group">
                                    <label for="time">Other Intake Method</label>
                                    <input type="text" class="form-control" value="" name="medicines[` +
                        fieldCountMedicine + `][other_intake_method]" id="other_intake_method" placeholder="Other Intake Method" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="time">Dose</label>
                                    <input type="text" name="medicines[` + fieldCountMedicine + `][dose]" id="dose" placeholder="Dose" class="form-control rounded">
                                </div>
                            </div>
                            @php
                                $other_intake_supervised_by_div = 'd-none';
                            @endphp
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="time">Supervise By</label>
                                    <select name="medicines[` + fieldCountMedicine +
                        `][intake_supervised_by]" id="intake_supervised_by` + fieldCountMedicine +
                        `" class="form-control rounded">
                                        <option value="">Select</option>
                                        @foreach ($intake_guidedby as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 {{ $other_intake_supervised_by_div }}" id="other_intake_supervised_by_div` +
                        fieldCountMedicine + `">
                                <div class="form-group">
                                    <label for="time">Other Supervise By</label>
                                    <input type="text" class="form-control" name="medicines[` + fieldCountMedicine + `][intake_supervised_other]" id="intake_supervised_other" placeholder="Intake Supervised By" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-4 text-right">
                                <button type="button" class="btn btn-outline-danger font-bold remove_medicine"><i class="fa fa-trash"></i> Remove</button>
                            </div>
                        </div>
                    `;
                    $("#more_medicines_div").append(newFieldMedicine);
                    fieldCountMedicine++;
                    clockPickerCall();
                    applySelect2ToDynamicElements();

                }
            });

            function applySelect2ToDynamicElements() {
                $('.med_time_select').select2({
                    placeholder: 'Select a time',
                });
            }

            // Remove field when clicking on remove button
            $("#more_medicines_div").on("click", ".remove_medicine", function() {
                $(this).closest(".row").remove();
                fieldCountMedicine--;
            });
            $("#more_medicines_div").on("change", "[id^=medicine_type]", function() {
                var currentFieldCount = $(this).attr("id").replace("medicine_type", "");
                if ($(this).val() == 5) {
                    $('#other_medicine_type_div' + currentFieldCount).removeClass('d-none');
                } else {
                    $('#other_medicine_type_div' + currentFieldCount).addClass('d-none');
                }
            });
            $("#more_medicines_div").on("change", "[id^=time_id]", function() {
                var currentFieldCount = $(this).attr("id").replace("time_id", "");
                if ($(this).val() && $(this).val().includes('4')) {
                    $('#other_time_div' + currentFieldCount).removeClass('d-none');
                } else {
                    $('#other_time_div' + currentFieldCount).addClass('d-none');
                }
            });
            $("#more_medicines_div").on("change", "[id^=intake_method]", function() {
                var currentFieldCount = $(this).attr("id").replace("intake_method", "");
                if ($(this).val() == 4) {
                    $('#other_intake_method_div' + currentFieldCount).removeClass('d-none');
                } else {
                    $('#other_intake_method_div' + currentFieldCount).addClass('d-none');
                }
            });
            $("#more_medicines_div").on("change", "[id^=intake_supervised_by]", function() {
                var currentFieldCount = $(this).attr("id").replace("intake_supervised_by", "");
                if ($(this).val() == 4) {
                    $('#other_intake_supervised_by_div' + currentFieldCount).removeClass('d-none');
                } else {
                    $('#other_intake_supervised_by_div' + currentFieldCount).addClass('d-none');
                }
            });


            var maxFieldsDoc = 10;
            var fieldCountDoc = 1;

            $("#add_document_fields").click(function() {
                if (fieldCountDoc < maxFieldsDoc) {
                    var newFieldDoc = `
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Title *</label>
                                    <input type="text" name="doc[` + fieldCountDoc + `][document_name]" id="name_` +
                        fieldCountDoc + `" placeholder="Document Title" class="form-control rounded required">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group" id="data_1">
                                    <label for="date">Date *</label>
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" name="doc[` + fieldCountDoc + `][date]" id="date_` +
                        fieldCountDoc + `" class="form-control required" placeholder="Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Document *</label>
                                    <input type="file" name="doc[` + fieldCountDoc +
                        `][file]" class="form-control rounded required" id="file_` + fieldCountDoc + `">
                                </div>
                            </div>
                            @php
                                $document_types = config('const.patient_document_type');
                                $remark = 'd-none';
                                $document_type = 1;
                            @endphp
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="dose">Type</label>
                                    <select class="form-control m-b required" name="doc[` + fieldCountDoc +
                        `][type]" id="type_id` + fieldCountDoc + `">
                                        @foreach ($document_types as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 {{ $remark }}" id="other_remark_div` + fieldCountDoc + `">
                                <div class="form-group">
                                    <label for="other_remark">Other Remark *</label>
                                    <input type="text" class="form-control other_remark required" name="doc[` +
                        fieldCountDoc + `][remark]" id="other_remark" placeholder="Other Remark" class="form-control rounded">
                                </div>
                            </div>    
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="dose">Display to Staff</label>
                                    <select class="form-control m-b " name="doc[` + fieldCountDoc + `][display_to_staff]" id="display_to_staff" >
                                        <option value="">Select Options</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="col-lg-12 mb-4 text-right">
                                <button type="button" class="btn btn-outline-danger font-bold remove_doc"><i class="fa fa-trash"></i> Remove</button>
                            </div>
                        </div>
                    `;
                    $("#more_document_div").append(newFieldDoc);
                    fieldCountDoc++;
                }
                datePickerCall();
                applySelect2ToDynamicStaffElements();
            });

            function applySelect2ToDynamicStaffElements() {
                $('.select_staffs').select2({
                    placeholder: 'Select a staff',
                });
            }

            // Remove field when clicking on remove button
            $("#more_document_div").on("click", ".remove_doc", function() {
                $(this).closest(".row").remove();
                fieldCountDoc--;
            });
            $("#more_document_div").on("change", "[id^=type_id]", function() {
                var currentFieldCount = $(this).attr("id").replace("type_id", "");
                if ($(this).val() == 2) {
                    $('#other_remark_div' + currentFieldCount).removeClass('d-none');
                } else {
                    $('#other_remark_div' + currentFieldCount).addClass('d-none');
                }
            });
        });

        $(document).ready(function() {
            var maxFieldsActivity = 10;
            var fieldCountActivity = 1;
            $(document).on("change", ".activity-recurrence", function() {
                // Get the selected option value
                var selectedValue = $(this).val();
                var id = $(this).attr('id');

                // Show or hide the div based on the selected option
                if (selectedValue === "2") {
                    $('#activity-perform_week_' + id).removeClass('d-none');
                    $('#activity-perform_month_' + id).addClass('d-none');
                    $('#week_days_select-' + id).addClass('required');
                    $('#week_days_select-' + id).attr('required', true);
                    $('#selected_month_dates-' + id).attr('required', false);
                    $('#selected_month_dates-' + id).removeClass('required');
                } else if (selectedValue === "3") {
                    $('#activity-perform_week_' + id).addClass('d-none');
                    $('#activity-perform_month_' + id).removeClass('d-none');
                    $('#week_days_select-' + id).removeClass('required');
                    $('#week_days_select-' + id).attr('required', false);
                    $('#selected_month_dates-' + id).addClass('required');
                    $('#selected_month_dates-' + id).attr('required', true);
                    $('[data-custom="specific-datepicker"]').on('show.bs.modal', function() {
                        $(this).find('.prev, .next').css('display', 'none');
                    });
                } else {
                    $('#activity-perform_month_' + id).addClass('d-none');
                    $('#selected_month_dates-' + id).removeClass('required');
                    $('#activity-perform_week_' + id).addClass('d-none');
                    $('#week_days_select-' + id).removeClass('required');
                    $('#selected_month_dates-' + id).attr('required', false);
                    $('#week_days_select-' + id).attr('required', false);
                }
            })

            function formatState(state) {
                if (!state.id) {
                    return state.text;
                }
                var isSelected = $(state.element).prop('selected');
                var $state = $(
                    '<span><input type="checkbox" ' + (isSelected ? 'checked' : '') + ' /> ' + state.text +
                    '</span>'
                );
                return $state;
            }

            function initializeSelect2(selector, placeholderText) {
                $(selector).select2({
                    placeholder: placeholderText,
                    closeOnSelect: false,
                    templateResult: formatState,
                    templateSelection: function(state) {
                        return state.text;
                    }
                });
            }

            function initializeMonthPicker(selector, placeholderText) {
                $(selector).datepicker({
                    format: 'dd',
                    forceParse: false,
                    multidate: true,
                    clearBtn: true,
                    todayHighlight: true,
                    beforeShowDay: function(date) {
                        var day = date.getDate();
                        if (day >= 1 && day <= 31) {
                            return {
                                classes: 'day'
                            };
                        } else {
                            return false;
                        }
                    }
                }).on('show', function(e) {
                    $(this).find('.datepicker-days .prev, .datepicker-days .next').css('display', 'none');
                    // Ensure the button is only added once
                    if (!$('.datepicker-footer').length) {
                        var $footer = $('<div class="datepicker-footer text-center mt-2"></div>');
                        var $button = $('<button class="btn btn-primary btn-sm date-ok-btn">OK</button>');
                        $footer.append($button);

                        // Append the footer to the datepicker
                        $('.datepicker-days').append($footer);

                        // Add click event to the button
                        $button.on('click', function() {
                            $('.input-group.date.month_date').datepicker('hide');
                            // You can add any other action you need here
                        });
                    }
                }).on('changeDate', function(e) {
                    var selectedDates = e.dates;
                });
                $(selector).on('show', function(e) {
                    $('.datepicker-days .prev, .datepicker-days .next').css('display', 'none');
                });
            }

            initializeSelect2('.select-week-days-0', 'Select week days');
            initializeMonthPicker('.specific-datepicker-0', 'Select Month Dates');
            $("#add_activity_fields").click(function() {

                if (fieldCountActivity < maxFieldsActivity) {
                    var newFieldActivity = `
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-3   @error('shift_id') has-error @enderror">
                            <label class="col-form-label">Shift</label>
                            <div class="">
                                @foreach ($shifts as $key => $shift)
                                <label class="checkbox-inline i-checks mr-2">                             
                                    <input type="checkbox" name="activity[` + fieldCountActivity + `][activity_shift_id][]" value="{{ $key }}" style="position: absolute; opacity: 0;">
                                    <div class="icheckbox_square-green static" style="position: relative;"></div>
                                    <i></i> {{ $shift }} 
                                </label>
                                @endforeach
                                @error('shift_id')
                                <span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
                                @enderror
                                 <div id="activity-` + fieldCountActivity + `-error" class="error-message" style="display:none">This field is required.</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3   @error('name') has-error @enderror">
                            <label class="col-form-label">Name *</label>
                            <div class="">
                                <input type="text" name="activity[` + fieldCountActivity +
                        `][activity_name]" placeholder="Name" class="form-control required" required autocomplete="off" value="{{ old('name') }}" id="activity_name_` +
                        fieldCountActivity + `">
                                @error('name')
                                    <span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> 							
                        <div class="col-12 col-md-6 col-lg-3  ">
                            <label for="duration" class="col-form-label">Duration (in minutes) *</label>
                            <div class="">
                                <input type="number" name="activity[` + fieldCountActivity +
                        `][activity_duration]" id="activity_duration_` + fieldCountActivity + `" placeholder="Duration" class="form-control rounded required valid_integer" min=1>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3  ">
                            <label for="contact_no" class="col-form-label">Frequency *</label>
                            <div class="">
                                <input type="number" name="activity[` + fieldCountActivity +
                        `][activity_frequency]" id="activity_frequency_` + fieldCountActivity + `" placeholder="Frequency" class="form-control rounded required valid_integer" min=1>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3  ">
                            <label for="contact_no" class="col-form-label">Recurrence *</label>
                            <div class="">
                                <select class="form-control activity-recurrence required" name="activity[` +
                        fieldCountActivity + `][activity_recurrence]" id="` + fieldCountActivity + `">
                                    <option value="">Choose option</option>
                                    @foreach ($activity_recurrence as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       <div class="col-12 col-md-6 col-lg-3 d-none select_week_full" id="activity-perform_week_` +
                        fieldCountActivity + `">
									<label for="contact_no" class="col-form-label">Activity Perform (<span id="activity-perform-lbl">in week</span>)</label>
									@php
             $weekdays = config('const.week_days');
         @endphp
									<select class="form-control select-week-days-` + fieldCountActivity +
                        ` w-100 required" id="week_days_select-` + fieldCountActivity +
                        `" name="activity[` + fieldCountActivity + `][activity_performance_day][]" multiple="multiple">
										@foreach ($weekdays as $day)
										<option value="{{ $day }}">{{ $day }}</option>
										@endforeach
										
									</select>
									
								</div>
                                <div class="col-12 col-md-6 col-lg-3 d-none" id="activity-perform_month_` +
                        fieldCountActivity + `">
									<label for="contact_no" class="col-form-label">Activity Perform (<span id="activity-perform-lbl">in month</span>)</label>
									<div class="input-group date month_date specific-datepicker-` + fieldCountActivity + `">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" name="activity[` + fieldCountActivity +
                        `][activity_performance_month]" id="selected_month_dates-` + fieldCountActivity + `" placeholder="Select Month Dates" class="form-control required" >  
									</div> 
								
								</div>
                       
                        <div class="form-group col-12 col-lg-6 ">
                            <label for="description" class="col-form-label">Description *</label>
                            <div class="">
                                <textarea class="form-control rounded required" name="activity[` + fieldCountActivity +
                        `][activity_description]" id="activity_description_` + fieldCountActivity + `" placeholder="Description" rows="4" autocomplete="off"></textarea>
                            </div>
                        </div>
						<div class="col-lg-12 mb-4 text-right">
                                <button type="button" class="btn btn-outline-danger font-bold remove_activity"><i class="fa fa-trash"></i> Remove</button>
                            </div>
                    </div>
                    `;
                    $("#more_activity_div").append(newFieldActivity);
                    initializeSelect2('.select-week-days-' + fieldCountActivity, 'Select week days');
                    initializeMonthPicker('.specific-datepicker-' + fieldCountActivity,
                        'Select Month Dates');
                    fieldCountActivity++;

                }
            });

            // Remove field when clicking on remove button
            $("#more_activity_div").on("click", ".remove_activity", function() {
                $(this).closest(".row").remove();
                fieldCountActivity--;
            });

            $('#weight').on('input', function() {
                var value = $(this).val();
                if (/^\d+(\.\d{0,2})?$/.test(value)) {
                    // Valid input
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                    $('#weight-error').addClass('d-none');
                } else {
                    // Invalid input
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                    $('#weight-error').removeClass('d-none');
                    $('#weight-error').text('Please enter a valid input')
                }
            });


            // Listen for when the accordion is shown (expanded)
            $('#collapseOne').on('shown.bs.collapse', function() {
                // Add the required attribute to the necessary fields
                $('#doctor_div input[type="text"]').addClass('required');
                $('#doctor_div input[type="tel"]').addClass('required');
                $('#doctor_div input[type="email"]').addClass('required');
                $('#doctor_div select').addClass('required');
                $('#more_doctors_div input[type="text"]').removeClass('required').addClass('required');
                $('#more_doctors_div input[type="tel"]').removeClass('required').addClass('required');
                $('#more_doctors_div input[type="email"]').removeClass('required').addClass('required');
                $('#more_doctors_div select').removeClass('required').addClass('required');


            });

            // Optionally, you can remove the required attribute when the accordion is hidden (collapsed)
            $('#collapseOne').on('hidden.bs.collapse', function() {
                // Remove the required attribute
                $('#doctor_div input[type="text"]').removeClass('required');
                $('#doctor_div input[type="text"]').val('');
                $('#doctor_div input[type="tel"]').removeClass('required');
                $('#doctor_div input[type="tel"]').val('');
                $('#doctor_div input[type="email"]').removeClass('required');
                $('#doctor_div input[type="email"]').val('');
                $('#doctor_div select').removeClass('required');
                $('#doctor_div select').val('');

                $('#more_doctors_div input[type="text"]').removeClass('required');
                $('#more_doctors_div input[type="text"]').val('');
                $('#more_doctors_div input[type="tel"]').removeClass('required');
                $('#more_doctors_div input[type="tel"]').val('');
                $('#more_doctors_div input[type="email"]').removeClass('required');
                $('#more_doctors_div input[type="email"]').val('');
                $('#more_doctors_div select').removeClass('required');
                $('#more_doctors_div select').val('');


            });
            // Listen for when the accordion is shown (expanded)
            $('#collapseThree').on('shown.bs.collapse', function() {

                // Add the required attribute to the necessary fields
                $('#document_div input[type="text"]').addClass('required');
                $('#document_div input[type="file"]').addClass('required');
                $('#document_div select').addClass('required');
                //$('#document_div .other_remark').removeClass('required');
                $('#document_div .display_to_staff').removeClass('required');
                $('#more_document_div input[type="text"]').removeClass('required').addClass('required');
                $('#more_document_div input[type="file"]').removeClass('required').addClass('required');
                $('#more_document_div select').removeClass('required').addClass('required');
                //$('#more_document_div .other_remark').removeClass('required');
                $('#more_document_div .display_to_staff').removeClass('required');
            });

            // Optionally, you can remove the required attribute when the accordion is hidden (collapsed)
            $('#collapseThree').on('hidden.bs.collapse', function() {

                // Remove the required attribute
                $('#document_div input[type="text"]').removeClass('required');
                $('#document_div input[type="text"]').val('');
                $('#document_div input[type="file"]').removeClass('required');
                $('#document_div input[type="file"]').val('');
                $('#document_div select').removeClass('required');
                //  $('#document_div select').val('');
                $('#document_div .other_remark').removeClass('required');
                $('#document_div .other_remark').val('');
                $('#document_div .display_to_staff').removeClass('required');
                $('#document_div .display_to_staff').val('');

                $('#more_document_div input[type="text"]').removeClass('required');
                $('#more_document_div input[type="text"]').val('');
                $('#more_document_div input[type="file"]').removeClass('required');
                $('#more_document_div input[type="file"]').val('');
                $('#more_document_div select').removeClass('required');
                //   $('#more_document_div select').val('');
                $('#more_document_div .other_remark').removeClass('required');
                $('#more_document_div .other_remark').val('');
                $('#more_document_div .display_to_staff').removeClass('required');
                $('#more_document_div .display_to_staff').val('');
            });

            // Listen for when the accordion is shown (expanded)
            $('#collapseFour').on('shown.bs.collapse', function() {
                // Add the required attribute to the necessary fields

                $('#activity_div_extra input[type="text"]').addClass('required');
                $('#activity_div_extra input[type="number"]').addClass('required');
                $('#activity_div_extra textarea').addClass('required');
                $('#activity_div_extra select').addClass('required');

                $('#more_activity_div input[type="text"]').removeClass('required').addClass('required');
                $('#more_activity_div input[type="number"]').removeClass('required').addClass('required');
                $('#more_activity_div select').removeClass('required').addClass('required');
                $('#more_activity_div textarea').removeClass('required').addClass('required');
            });

            // Optionally, you can remove the required attribute when the accordion is hidden (collapsed)
            $('#collapseFour').on('hidden.bs.collapse', function() {

                // Remove the required attribute
                $('#activity_div_extra input[type="text"]').removeClass('required');
                $('#activity_div_extra input[type="text"]').val('');
                $('#activity_div_extra input[type="number"]').removeClass('required');
                $('#activity_div_extra input[type="number"]').val('');
                $('#activity_div_extra textarea').removeClass('required');
                $('#activity_div_extra textarea').val('');
                $('#activity_div_extra select').removeClass('required');
                $('#activity_div_extra select').val('');

                $('#more_activity_div input[type="text"]').removeClass('required');
                $('#more_activity_div input[type="text"]').val('');
                $('#more_activity_div input[type="number"]').removeClass('required');
                $('#more_activity_div input[type="number"]').val('');
                $('#more_activity_div textarea').removeClass('required');
                $('#more_activity_div textarea').val('');
                $('#more_activity_div select').removeClass('required');
                $('#more_activity_div select').val('');

            });

        });
    </script>
@endsection
