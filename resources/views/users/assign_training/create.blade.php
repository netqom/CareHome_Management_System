@extends('layouts.admin')

@section('title', 'Assign Training')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('users.index') }}">Users</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Assign Training</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12 dark-bg">
                <form method="POST" role="form" action="{{ route('assign-training-to-staff.store') }}"
                    id="assignTraining_Form">
                    @csrf
                    <div class="ibox ">
                        <div class="ibox-title d-flex">
                            <h5>Assign Training</h5>
                        </div>
                        <div class="ibox-content">

                            <div class="row">

                                <input type="hidden" name="staff_id" value={{ $user_id }}>
                                <input type="hidden" name="home_id" value={{ $home_id }}>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 @error('course_id') has-error @enderror">
                                    <label>Courses *</label>
                                    <div class="">
                                        <select class="form-control m-b" name="course_id" required id="courseId">
                                            <option value="">Select Course</option>
                                            @foreach ($training_course_list as $course)
                                                <option value="{{ $course->id }}" data-duration="{{ $course->duration }}">
                                                    {{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('course_id')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div
                                    class="form-group col-lg-6 col-md-6 col-sm-12 @error('course_status') has-error @enderror">
                                    <label>Course Status *</label>
                                    <div>
                                        <select class="form-control m-b" name="course_status" required>
                                            <option value="">Select Course Status</option>
                                            @foreach ($course_status as $key => $status)
                                                <option value="{{ $key }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        @error('course_status')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 @error('start_date') has-error @enderror"
                                    id="start_date_1">
                                    <label>Start Date *</label>
                                    <div>
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" name="start_date" id="start_date" placeholder="Start Date"
                                                class="form-control" required>
                                        </div>
                                        @error('start_date')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 @error('end_date') has-error @enderror"
                                    id="end_date_1">
                                    <label>Due Date *</label>
                                    <div>
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" name="end_date" id="end_date" placeholder="Due Date"
                                                class="form-control" required>
                                        </div>
                                        @error('end_date')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 @error('end_date') has-error @enderror"
                                    id="end_date_1">
                                    <label>Course Duration</label>
                                    <input type="number" class="form-control" id="course_duration" value="" placeholder="Course Duration">
                                </div>
                                {{--
								 <div class="form-group col-lg-6 col-md-6 col-sm-12 @error('status') has-error @enderror">
									<label>Status</label>
									<div>
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
								</div> --}}

                                <input type="hidden" name="redirectURL"
                                    value="{{ createCancelUrl(route('assign-training-to-staff.index', ['home_id' => $home_id, 'staff_id' => $user_id])) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a class="btn btn-white btn-sm" type="button"
                                href="{{ createCancelUrl(route('assign-training-to-staff.index', ['home_id' => $home_id, 'staff_id' => $user_id])) }}">Cancel</a>
                            <button class="btn btn-sm btn-primary" type="submit" id="assignTrainingForm">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#role_id').change(function() {
                if ($(this).val() == 4) {
                    $('#shift_div').removeClass('d-none');
                    $('#shift_id').attr("required", true);
                } else {
                    $('#shift_div').addClass('d-none');
                    $('#shift_id').attr("required", false);
                }
            })


            //add calendar for course start date


            $('#courseId').change(function() {
                var duration = $(this).find('option:selected').data('duration');
                $('#course_duration').val(duration);
            });
            // Initialize datepicker for start date
            $('#start_date_1 .input-group.date').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                startDate: new Date() // Start from current date
            }).on('changeDate', function(selected) {
                // Set the minimum date for the end date picker
                var startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate() + 1); // Add one day to start date

                $('#end_date_1 .input-group.date').datepicker('setStartDate', startDate);
            });

            // Initialize datepicker for end date
            $('#end_date_1 .input-group.date').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                startDate: new Date() // Start from current date
            });

            // Event listener for when the start date is changed
            $('#start_date').on('change', function() {
                var startDate = $(this).val(); // Get the selected start date
                var duration = $('#course_duration').val(); // Number of days to add

                // Check if start date is not empty and duration is a valid number
                if (startDate && !isNaN(parseInt(duration))) {
                    // Calculate the end date based on the duration
                    var endDate = new Date(startDate);
                    endDate.setDate(endDate.getDate() + parseInt(duration)); // Add duration days

                    // Format the end date as required (e.g., MM/DD/YYYY)
                    var formattedEndDate = ('0' + (endDate.getMonth() + 1)).slice(-2) + '/' + ('0' + endDate
                        .getDate()).slice(-2) + '/' + endDate.getFullYear();

                    // Set the end date value in the input field
                    $('#end_date').val(formattedEndDate);
                } else {
                    // If start date or duration is invalid, clear the end date input
                    $('#end_date').val('');
                }
            });
            $('#end_date').on('change', function() {
                var startDate = $('#start_date').val(); // Get the selected start date
                var endDate = $(this).val(); // Get the selected end date

                // Check if both start and end dates are not empty
                if (startDate && endDate) {
                    var startTimestamp = new Date(startDate).getTime();
                    var endTimestamp = new Date(endDate).getTime();

                    // Check if the end date is greater than or equal to the start date
                    if (endTimestamp < startTimestamp) {
                        // If the end date is older than the start date, set it to the start date
                        $('#end_date').val(startDate);
                    }
                }
            });
        })
    </script>
@endsection
