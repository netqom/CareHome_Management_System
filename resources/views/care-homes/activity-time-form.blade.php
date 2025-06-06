@extends('layouts.admin')

@section('title', 'Create Care Home')

@section('style')
	<link href="{{ asset('assets/css/plugins/clockpicker/clockpicker.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
@endsection
@php
	$m_start = $m_end = $a_start = $a_end = $e_start = $e_end =  $n_start = $n_end ='';
	$times = config('const.log_times');
	
		if($activity_time && $activity_time->morning_time!=null)
		{
			$m_time = explode('-', $activity_time->morning_time);
			$m_start = trim($m_time[0]);
			$m_end   = trim($m_time[1]);
		}else{
			$m_time = explode('-', $times[1]);
			$m_start = trim($m_time[0]);
			$m_end   = trim($m_time[1]);
		}
		if($activity_time && $activity_time->afternoon_time!=null)
		{
		
			$a_time = explode('-', $activity_time->afternoon_time);
			$a_start = trim($a_time[0]);
			$a_end   = trim($a_time[1]);
		}else{
			$a_time = explode('-', $times[2]);
			$a_start = trim($a_time[0]);
			$a_end   = trim($a_time[1]);
		}
		if($activity_time && $activity_time->evening_time!=null)
		{
			$e_time = explode('-', $activity_time->evening_time);
			$e_start = trim($e_time[0]);
			$e_end   = trim($e_time[1]);
		}else{
			$e_time = explode('-', $times[3]);
			$e_start = trim($e_time[0]);
			$e_end   = trim($e_time[1]);
		}
		if($activity_time && $activity_time->night_time!=null)
		{
			$n_time = explode('-', $activity_time->night_time);
			$n_start = trim($n_time[0]);
			$n_end   = trim($n_time[1]);
		}else{
			$n_time = explode('-', $times[4]);
			$n_start = trim($n_time[0]);
			$n_end   = trim($n_time[1]);
		}
		
		
	
		
		
		
		
		
	
@endphp
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
					<strong>Add/Update Activity Time</strong>
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
							<form method="POST" role="form" action="{{ route('save-activity-time-form') }}" id="careHomeActivityForm" enctype="multipart/form-data">
								@csrf
								<input type="hidden" name="home_id" id="home_id" value="{{ $home->id }}">
								<input type="hidden" name="id" id="id" value="{{ $activity_time ? $activity_time->id : 0 }}">
								<span class="badge badge-info w-100 mt-2 mb-2 py-2"><h3 class="m-0">Activity Time For Care Home: {{ $home->name }}</h3><span style="font-size: 12px">(This time will work as per PDT timezone.)</span></span>
								<div class="form-group row @error('role_id') has-error @enderror">
									<label class="col-lg-6 col-form-label">Morning Activity Time</label>
									<div class="row col-lg-12">
										<div class="col-lg-6">
											<div class="input-group clockpicker" data-autoclose="true">
												<input type="text" name="morning_start_time" id="morning_start_time" placeholder="Morning Start Time" class="form-control timepicker-input" value="{{ date('h:i A',strtotime($m_start)) }}" required >
												 {{-- <span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
												</span>  --}}
											</div>
										</div>
										<div class="col-lg-6">
											<div class="input-group clockpicker" data-autoclose="true">
												<input type="text" name="morning_end_time" id="morning_end_time" placeholder="Morning End Time" class="form-control timepicker-input" value="{{ date('h:i A',strtotime($m_end)) }}" required >
												<div id="morning_end_time-error" class="invalid-feedback d-none"></div>
											{{-- 	 <span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
												</span> --}}
											</div>
										</div>
									</div>
								</div>
								<div class="form-group row @error('role_id') has-error @enderror">
									<label class="col-lg-6 col-form-label">Afternoon Activity Time</label>
									<div class="row col-lg-12">
										<div class="col-lg-6">
											<div class="input-group clockpicker" data-autoclose="true">
												<input type="text" name="afternoon_start_time" id="afternoon_start_time" placeholder="Afternoon Start Time" class="form-control timepicker-input" value="{{ date('h:i A',strtotime($a_start)) }}" required >
												{{-- <span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
												</span>  --}}
											</div>
										</div>
										<div class="col-lg-6">
											<div class="input-group clockpicker" data-autoclose="true">
												<input type="text" name="afternoon_end_time" id="afternoon_end_time" placeholder="Afternoon End Time" class="form-control timepicker-input" value="{{ date('h:i A',strtotime($a_end)) }}" required >
												<div id="afternoon_end_time-error" class="invalid-feedback d-none"></div>
												 {{-- <span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
												</span>  --}}
											</div>
										</div>
									</div>
								</div>
								<div class="form-group row @error('role_id') has-error @enderror">
									<label class="col-lg-6 col-form-label">Evening Activity Time</label>
									<div class="row col-lg-12">
										<div class="col-lg-6">
											<div class="input-group clockpicker" data-autoclose="true">
												<input type="text" name="evening_start_time" id="evening_start_time" placeholder="Evening Start Time" class="form-control timepicker-input" value="{{ date('h:i A',strtotime($e_start)) }}" required >
											 	{{-- <span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
												</span>  --}}
											</div>
										</div>
										<div class="col-lg-6">
											<div class="input-group clockpicker" data-autoclose="true">
												<input type="text" name="evening_end_time" id="evening_end_time" placeholder="Evening Start Time" class="form-control timepicker-input" value="{{ date('h:i A',strtotime($e_end)) }}" required >
												<div id="evening_end_time-error" class="invalid-feedback d-none"></div>
												 {{-- <span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
												</span>  --}}
											</div>
										</div>
									</div>
								</div>
								<div class="form-group row @error('role_id') has-error @enderror">
									<label class="col-lg-6 col-form-label">Night Activity Time</label>
									<div class="row col-lg-12">
										<div class="col-lg-6">
											<div class="input-group clockpicker" data-autoclose="true">
												<input type="text" name="night_start_time" id="night_start_time" placeholder="Night Start Time" class="form-control timepicker-input" value="{{ date('h:i A',strtotime($n_start)) }}" required >
												 {{-- <span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
												</span>  --}}
											</div>
										</div>
										<div class="col-lg-6">
											<div class="input-group clockpicker" data-autoclose="true">
												<input type="text" name="night_end_time" id="night_end_time" placeholder="Night Start Time" class="form-control timepicker-input" value="{{ date('h:i A',strtotime($n_end)) }}" required >
												<div id="night_end_time-error" class="invalid-feedback d-none"></div>
												{{-- <span class="input-group-addon">
													<span class="fa fa-clock-o"></span>
												</span> --}}
											</div>
										</div>
									</div>
								</div>
								<div class="hr-line-dashed"></div>
								<div class="form-group row">
                                    <div class="col-md-12 text-right">
                                        <a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('homes.index')) }}">Cancel</a>
                                        <button class="btn btn-sm btn-primary" type="button" id="care_home_activity_form">Save</button>
                                    </div>
                                </div>
							</form>
						</div>
						<div class="special-note">
							<strong>Note:</strong> The shift activity time will be adjusted as per the current day time. Likewise, for Night activity time you have to select time till 23:00pm only. 
						</div>
					</div>

					
				</div>
			</div>
		</div>
	</div>
@endsection
@section('script')
	<script src="{{ asset('assets/js/plugins/clockpicker/clockpicker.js') }}"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Timepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script type="text/javascript">
		$(document).ready(function() {
			$('.timepicker-input').timepicker({
        showMeridian: true, // Enables AM/PM option
        minuteStep: 1, // Step size for minutes
        defaultTime: false, // Do not set a default time
        showInputs: false, // Hide manual input boxes
        disableFocus: true // Prevent the input field from being focused
    });

    // Function to set min attribute for end time inputs
    function setMinEndTime(startInputId, endInputId) {
        let startInput = $(`#${startInputId}`);
        let endInput = $(`#${endInputId}`);

        startInput.on('change', function() {
            let startTime = startInput.val();
            if (startTime) {
                endInput.attr('min', startTime);
            } else {
                endInput.removeAttr('min');
            }
        });

        // Trigger the change event in case start time is pre-filled
        startInput.trigger('change');
    }

    // Apply the function to each pair of start and end times
    setMinEndTime('morning_start_time', 'morning_end_time');
    setMinEndTime('afternoon_start_time', 'afternoon_end_time');
    setMinEndTime('evening_start_time', 'evening_end_time');
    setMinEndTime('night_start_time', 'night_end_time');

    // Function to validate times
	function validateTimes() {
    let isValid = true;

    function parseTime(timeString) {
        // Assumes timeString format is "hh:mm AM/PM"
        let [time, modifier] = timeString.split(' ');
        let [hours, minutes] = time.split(':');
        
        if (hours === '12') {
            hours = '00';
        }
        if (modifier === 'PM') {
            hours = parseInt(hours, 10) + 12;
        }

        return new Date(`1970-01-01T${hours}:${minutes}:00`);
    }

    function validateTime(startInputId, endInputId, activityName) {
        let startTime = $(`#${startInputId}`).val();
        let endTime = $(`#${endInputId}`).val();
        console.log("startTime", startTime, "endTime", endTime);

        if (endTime && startTime) {
            let start = parseTime(startTime);
            let end = parseTime(endTime);
            
            if (end < start) {
				$(`#${endInputId}`+`-error`).text(`${activityName} end time must be later than start time.`).removeClass('d-none').show();
                isValid = false;
            }else{
				$(`#${endInputId}`+`-error`).addClass('d-none').hide();
			}
        }
    }

    validateTime('morning_start_time', 'morning_end_time', 'Morning');
    validateTime('afternoon_start_time', 'afternoon_end_time', 'Afternoon');
    validateTime('evening_start_time', 'evening_end_time', 'Evening');
    validateTime('night_start_time', 'night_end_time', 'Night');

    return isValid;
}


    // Validate times on form submission
    $('#care_home_activity_form').on('click', function(e) {
		e.preventDefault();
		var form = $('#careHomeActivityForm');
		form.validate();
		if(form.valid())
		{
			if (!validateTimes()) {
            e.preventDefault(); // Prevent form submission if validation fails
        	}else{
				ShowFormLoading();
			form[0].submit();
			}
		}
       
    }); 
	

    // Event listener to revert to default "now" time if invalid time format entered
    /* $('.clockpicker input[type="text"]').on('blur', function() {
        let inputValue = $(this).val();
        if (inputValue && !/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(inputValue)) {
            $(this).val('now');
        }
    }); */
});

    </script>
@endsection