@extends('layouts.admin')

@section('title', 'Create User')
@section('style')
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

        .mw-50 {
            max-width: 50px !important;
        }
    </style>
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
                @if (in_array('homes', $pathInArray))
                    <li class="breadcrumb-item">
                        <a href="{{ url()->previous() }}#tab2">Staff List</a>
                    </li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ url()->previous() }}">Users</a>
                    </li>
                @endif
                <li class="breadcrumb-item active">
                    <strong>Create User</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12 dark-bg">
                <div class="ibox ">
                    <div class="ibox-title d-flex">
                        <h5>Add User </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="ibox-content">
                            <form method="POST" role="form" action="{{ route('users.store') }}" id="userAdmin_Form"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div
                                        class="form-group col-lg-6 col-md-6 col-sm-12 @error('role_id') has-error @enderror">
                                        <label class="col-form-label">Role *</label>
                                        <select class="form-control mb-0" name="role_id" id="role_id" required>
                                            <option value="">Select Role</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (Auth::user()->role_id == 2)
                                        @php
                                            $home_required = Auth::user()->role_id == 2 ? 'required' : '';
                                        @endphp
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12 @error('role_id') has-error @enderror"
                                            {{ $home_required }}>

                                            <label class="col-form-label">Care Home *</label>
                                            <select class="form-control mb-0 select2_element" name="home_id"
                                                {{ $home_required }} id="care_home_select">
                                                <option value="">Select Care Home</option>
                                                @foreach ($homes as $home)
                                                    <option value="{{ $home->id }}">{{ $home->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('home_id')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12 d-none @error('role_id') has-error @enderror"
                                            id="shift_div">

                                            <label class="col-form-label">Shift *</label>
                                            <select class="form-control m-b" name="shift_id" id="shift_id">
                                                <option value="">Select Shift</option>
                                                @foreach ($shifts as $key => $shift)
                                                    <option value="{{ $key }}"
                                                        {{ old('shift_id') == $key ? 'selected' : '' }}>
                                                        {{ $shift }}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12 d-none @error('geofencing_status') has-error @enderror"
                                            id="geo_status_div">

                                            <label class="col-form-label">Geofencing Status</label>
                                            <select class="form-control m-b" name="geofencing_status"
                                                id="geofencing_status">
                                                <option value="">Select Shift</option>
                                                @foreach ($geo_status as $key => $geo_stat)
                                                    <option value="{{ $key }}"
                                                        {{ old('geofencing_status') == $key ? 'selected' : '' }}>
                                                        {{ $geo_stat }}</option>
                                                @endforeach
                                            </select>
                                            @error('geofencing_status')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 @error('name') has-error @enderror">

                                        <label class="col-form-label">Name *</label>
                                        <input type="text" name="name" placeholder="Name" class="form-control"
                                            required autocomplete="off" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 @error('email') has-error @enderror">

                                        <label class="col-form-label">Email *</label>
                                        <input type="email" name="email" placeholder="Email" class="form-control"
                                            required autocomplete="off" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div
                                        class="form-group col-lg-6 col-md-6 col-sm-12 @error('phone_number') has-error @enderror">

                                        <label class="col-form-label">Phone *</label>
                                        <input type="text" name="phone_number" placeholder="Phone Number"
                                            class="form-control validate_phone" required autocomplete="off"
                                            value="{{ old('phone_number') }}">
                                        @error('phone_number')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 my-1">
                                        <div class="col-12 py-3 rounded-lg team-memberc text-center">
                                            <div class="form-group d-flex align-items-center text-left">
                                                <h4 class="color-white mr-2">Upload User Image</h4>
                                                <div class="d-inline-block home-image-upload">
                                                    <img alt="image" id="existing_profile_image"
                                                        class="border border-dark rounded-circle me-2 mw-50"
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
                                    {{-- <div class="form-group col-lg-6 col-md-6 col-sm-12 @error('status') has-error @enderror">

										<label class="col-form-label">Status</label>
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

								</div> --}}
                                </div>
                                <input type="hidden" name="redirectURL"
                                    value="{{ createCancelUrl(route('users.index')) }}">
                                <div class="hr-line-dashed"></div>
                                <div class="accordion shadow " id="accordionExample3">
                                    <div class="align-items-center bg-primary d-flex px-2 py-2 pointer-event"
                                        data-toggle="collapse" data-target="#collapseThree" aria-expanded="true"
                                        aria-controls="collapseThree">
                                        <h4 class="m-0">Add Document Details</h4>
                                        <i class="fa fa-chevron-down ml-auto mr-2 " aria-hidden="true"></i>
                                    </div>
                                    <div id="collapseThree" class="collapse border border-1 p-3"
                                        aria-labelledby="headingOne" data-parent="#accordionExample3">
                                        <div class="row" id="document_div">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="name">Title *</label>
                                                    <input type="text" name="doc[0][name]" id="name_0"
                                                        placeholder="Document Title" class="form-control rounded ">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="name">Document *</label>
                                                    <input type="file" name="doc[0][file]" id="file_0"
                                                        class="form-control rounded">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="dose">Is Expiry Date Applicable *</label>
                                                    <select class="form-control m-b expiry_select_box"
                                                        name="doc[0][is_expiry_applicable]" id="is_expiry_applicable_0">
                                                        <option value="">Select Options</option>
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 d-none">
                                                <div class="form-group" id="data_0">
                                                    <label for="date">Expiry Date *</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"
                                                                aria-hidden="true"></i></span>
                                                        <input type="text" name="doc[0][expiry_date]" id="date_0"
                                                            class="form-control" placeholder="Date">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="more_document_div">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 text-right add_document_fields">
                                                <button type="button" class="btn btn-outline-navy font-bold"
                                                    id="add_document_fields"><i class="fa fa-plus"
                                                        aria-hidden="true"></i> Add More Documents</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 text-right">
                        <a class="btn btn-white btn-sm" type="button"
                            href="{{ createCancelUrl(route('users.index')) }}">Cancel</a>
                        <button class="btn btn-sm btn-primary" type="submit" id="userAdminForm">Save</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#care_home_select").select2({
                placeholder: "Select care home",
                allowClear: true
            });

            // Listen for when the accordion is shown (expanded)
            $('#collapseThree').on('shown.bs.collapse', function() {

                // Add the required attribute to the necessary fields
                $('#document_div input[type="text"]').addClass('required').attr('required', true);
                $('#document_div input[type="file"]').addClass('required').attr('required', true);
                $('#document_div select').addClass('required').attr('required', true);
                $('#more_document_div input[type="text"]').removeClass('required').addClass('required')
                    .attr('required', true);
                $('#more_document_div input[type="file"]').removeClass('required').addClass('required')
                    .attr('required', true);
                $('#more_document_div select').removeClass('required').addClass('required').attr('required',
                    true);
            });
			addChangeListener(0);
            datePickerCall(0);
			$('#is_expiry_applicable_0').on('change', function() {

			})
            // Optionally, you can remove the required attribute when the accordion is hidden (collapsed)
            $('#collapseThree').on('hidden.bs.collapse', function() {

                // Remove the required attribute
                $('#document_div input[type="text"]').removeClass('required').attr('required', false);
                $('#document_div input[type="text"]').val('');
                $('#document_div input[type="file"]').removeClass('required').attr('required', false);
                $('#document_div input[type="file"]').val('');
                $('#document_div select').removeClass('required').attr('required', false);

                $('#more_document_div input[type="text"]').removeClass('required').attr('required', false);
                $('#more_document_div input[type="text"]').val('');
                $('#more_document_div input[type="file"]').removeClass('required').attr('required', false);
                $('#more_document_div input[type="file"]').val('');
                $('#more_document_div select').removeClass('required').attr('required', false);
            });

            $(document).on('change', '#care_home_select', function() {
                var careHomeId = $(this).val();
                console.log(careHomeId);
                $('#home_id').val(careHomeId);
                if (careHomeId != '') {
                    $.ajax({
                        url: "{{ route('check-is-admin-add-staff') }}", // Replace with your endpoint URL
                        method: 'POST', // Or 'POST' if needed
                        data: {
                            home_id: careHomeId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.type == 'success') {
                                $('#care_home_select').val(null).trigger('change');
                                var name = response.name;

                                Swal.fire({
                                    text: name +
                                        " staff limit exceeded. Please upgrade your plan or purchase Add-ons",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#f39c12",
                                    confirmButtonText: "Add Addon",
                                    cancelButtonText: "Upgrade Plan"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href =
                                            `/subscription-manage/${careHomeId}?add_ons=yes`;
                                    } else if (result.dismiss === Swal.DismissReason
                                        .cancel) {
                                        window.location.href =
                                            `/subscription-manage/${careHomeId}?upgrade_downgrade_plan=yes`;
                                    }
                                });
                            } else {
                                $('#care_home_select').val(null).trigger('change');
                                var name = response.name;
                                if(response.subscription_status == true){
                                    Swal.fire({
                                        text: name +
                                            " Subscription not active. Please buy a new subscription",
                                        icon: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: "#3085d6",
                                        cancelButtonColor: "#f39c12",
                                        confirmButtonText: "Buy",
                                        cancelButtonText: "Cancel"
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href =
                                                `/subscription-manage/${careHomeId}?new_plan=yes`;
                                        }else if (result.dismiss === Swal.DismissReason
                                        .cancel) {
                                                window.location.href =
                                                    `/users`;
                                        }
                                    });
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle any errors
                            console.error("An error occurred: ", error);
                            Swal.fire({
                                text: "An error occurred while processing your request. Please try again.",
                                icon: "error"
                            });
                        }
                    });
                }
            })
            $('#role_id').change(function() {
                if ($(this).val() == 4) {
                    $('#shift_div').removeClass('d-none');
                    $('#shift_id').attr("required", true);
                    $('#geo_status_div').removeClass('d-none');
                    $('#geofencing_status').attr("required", true);
                } else {
                    $('#shift_div').addClass('d-none');
                    $('#shift_id').attr("required", false);
                    $('#geo_status_div').addClass('d-none');
                    $('#geofencing_status').attr("required", false);
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

        var maxFieldsDoc = 10;
        var fieldCountDoc = 1;

        $("#add_document_fields").click(function() {
            if (fieldCountDoc < maxFieldsDoc) {
                var newFieldDoc = `
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Title *</label>
                                    <input type="text" name="doc[` + fieldCountDoc + `][name]" id="name_`+fieldCountDoc+`" placeholder="Document Title" class="form-control rounded required" required>
                                </div>
                            </div>
							<div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Document *</label>
                                    <input type="file" name="doc[` + fieldCountDoc + `][file]" id="file_`+fieldCountDoc+`" class="form-control rounded required" required>
                                </div>
                            </div>
							<div class="col-lg-6">
                                <div class="form-group">
                                    <label for="dose">Is Expiry Date Applicable *</label>
                                    <select class="form-control m-b expiry_select_box required" name="doc[` +
                    fieldCountDoc + `][is_expiry_applicable]" id="is_expiry_applicable_${fieldCountDoc}" required>
                                        <option value="">Select Options</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none">
                                <div class="form-group" id="data_${fieldCountDoc}">
                                    <label for="date">Expiry Date *</label>
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" name="doc[` + fieldCountDoc +
                    `][date]" id=date_${fieldCountDoc} class="form-control required date_input_` + fieldCountDoc + `" required placeholder="Expiry Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-4 text-right">
                                <button type="button" class="btn btn-outline-danger font-bold remove_doc"><i class="fa fa-trash"></i> Remove</button>
                            </div>
                        </div>
                    `;
                $("#more_document_div").append(newFieldDoc);
                datePickerCall(fieldCountDoc);
                addChangeListener(fieldCountDoc);
                fieldCountDoc++;
            } else {
                $(".add_document_fields").hide();
            }
        });
        $("#more_document_div").on("click", ".remove_doc", function() {
            $(this).closest(".row").remove();
            fieldCountDoc--;
        });

        function datePickerCall(count) {
            var mem = $(`#data_${count} .input-group.date`).datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
                startDate: new Date()
            });
        }

        function addChangeListener(count) {
            $(`#is_expiry_applicable_${count}`).on('change', function() {
                const expiryDateInput = $(`#date_${count}`);
				console.log(expiryDateInput,$(this).val())
                if ($(this).val() === '1') {
					expiryDateInput.closest('.form-group').parent().removeClass('d-none')
                    expiryDateInput.attr('required', 'required');
                } else {
					expiryDateInput.closest('.form-group').parent().addClass('d-none')
                    expiryDateInput.removeAttr('required');
                }
            });
        }
    </script>
@endsection
