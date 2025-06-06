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
					<strong>Assigned Training</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12 dark-bg">
				<form method="POST" role="form" action="{{ route('assign-training-to-staff.update', $assined_training_list->id) }}" id="assignTraining_Form">
					@csrf
					<div class="ibox">
						<div class="ibox-title d-flex">
							<h5>Edit Assigned Training</h5>
						</div>
						<div class="ibox-content">
							<div class="row">

									@method("PATCH")
									<input type="hidden" name="staff_id" value={{$assined_training_list->user_id}}>
									<input type="hidden" name="home_id" value={{$home_id}}>
									<input type="hidden" name="id" value={{$assined_training_list->id}}>

									<div class="form-group col-lg-6 col-md-6 col-sm-12 @error('course_id') has-error @enderror">
										<label>Courses</label>
										<div>
											<select class="form-control m-b" name="course_id" required id="courseId">
												<option value="">Select Course *</option>
												@foreach($training_course_list as $course)
													<option value="{{ $course->id }}" {{ $assined_training_list->course_id == $course->id ? 'selected' : ''}} data-duration="{{$course->duration}}">{{ $course->title }}</option>
												@endforeach
											</select>
											@error('course_id')
												<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
											@enderror
										</div>
									</div>

									<div class="form-group col-lg-6 col-md-6 col-sm-12 @error('course_status') has-error @enderror">
										<label>Course Status *</label>
										<div>
											<select class="form-control m-b" name="course_status" required>
												<option value="">Select Course Status</option>
												@foreach($course_status as $key => $status)
													<option value="{{ $key}}" {{ $assined_training_list->course_status == $key ? 'selected' : ''}}>{{ $status }}</option>
												@endforeach
											</select>
											@error('course_status')
												<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
											@enderror
										</div>
									</div>

									<div class="form-group col-lg-6 col-md-6 col-sm-12 @error('start_date') has-error @enderror" id="start_date_1">
										<label>Start Date *</label>
										<div>
											<div class="input-group date">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" name="start_date" id="start_date" placeholder="Start Date" class="form-control" required value="{{ \Carbon\Carbon::parse($assined_training_list->start_date)->format('m/d/Y') }}">
											</div>
											@error('start_date')
												<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
											@enderror
										</div>
									</div>

									<div class="form-group col-lg-6 col-md-6 col-sm-12 @error('end_date') has-error @enderror" id="end_date_1">
										<label>Due Date *</label>
										<div>
											<div class="input-group date">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" name="end_date" id="end_date" placeholder="Due Date" class="form-control" required value="{{ \Carbon\Carbon::parse($assined_training_list->end_date)->format('m/d/Y') }}">
											</div>
											@error('end_date')
												<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
											@enderror
										</div>
									</div>

									<div class="form-group col-lg-6 col-md-6 col-sm-12 @error('status') has-error @enderror">
										<label>Status</label>
										<div>
											<select class="form-control m-b" name="status" required>
												<option value="">Select status</option>
												<option value="1" {{ $assined_training_list->status == 1 ? 'selected' : ''}}>Active</option>
												<option value="0" {{ $assined_training_list->status == 0 ? 'selected' : '' }}>In-Active</option>
											</select>
											@error('status')
												<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
											@enderror
										</div>
									</div>
									<input type="hidden" id="course_duration" value="">
									<input type="hidden" name="redirectURL" value="{{ createCancelUrl(route('assign-training-to-staff.index', ['home_id' => $home_id,'staff_id' => $assined_training_list->user_id])) }}">

						</div>
					</div>


					<div class="form-group row">
						<div class="col-md-12 text-right">
							<a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('assign-training-to-staff.index', ['home_id' => $home_id,'staff_id' => $assined_training_list->user_id])) }}">Cancel</a>
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
        $(document).ready(function(){
			$('#role_id').change(function(){
				if($(this).val() == 4){
					$('#shift_div').removeClass('d-none');
					$('#shift_id').attr("required", true);
				}else{
					$('#shift_div').addClass('d-none');
					$('#shift_id').attr("required", false);
				}
			})

			var duration = $('#courseId').find('option:selected').data('duration');
			$('#course_duration').val(duration);

			//add calendar for course start date
			 $('#start_date_1 .input-group.date').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
				todayHighlight: true
            });

			$('#courseId').change(function(){
				var duration = $(this).find('option:selected').data('duration');
				$('#course_duration').val(duration);
			});

			// Event listener for when the start date is changed
			$('#start_date').on('change', function(){
				var startDate = $(this).val(); // Get the selected start date
				var duration = $('#course_duration').val(); // Number of days to add

				// Calculate the end date based on the duration
				var endDate = new Date(startDate);
				endDate.setDate(endDate.getDate() + parseInt(duration)); // Add duration days
				// Format the end date as required (e.g., YYYY-MM-DD)
				var formattedEndDate =('0' + (endDate.getMonth() + 1)).slice(-2) + '/' + ('0' + endDate.getDate()).slice(-2) + '/' +  endDate.getFullYear();

				// Set the end date value in the input field
				$('#end_date').val(formattedEndDate);
			});

			//add calendar for course end date
			$('#end_date_1 .input-group.date').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
				todayHighlight: true
            });

		})
    </script>
@endsection
