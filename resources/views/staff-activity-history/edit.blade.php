@extends('layouts.admin')

@section('title', 'Tasks Management')
@section('style')
	<link href="{{ asset('assets/css/plugins/clockpicker/clockpicker.css') }}" rel="stylesheet">
@endsection
@section('content')
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item">
					<a href="{{ route('tasks-list') }}">Tasks Management</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Edit Task</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox ">
					<div class="ibox-title d-flex">
						<h5>Edit Task</h5>
					</div>
					<div class="ibox-content">
						<div class="ibox-content">
							<form method="POST" role="form" action="{{ route('save-tasks') }}" id="task_Form">
								@csrf
                                <input type="hidden" name="id" value="{{$get_task_detail->id}}">
                                <input type="hidden" name="user_type" value="{{$get_task_detail->user_type}}">
                                
								@php if($get_task_detail->user_type == 'staff' || $get_task_detail->user_type == 'manager'){
									echo '<input type="hidden" name="user_id" value="'.$get_task_detail->user_id.'">';
								}else{
									echo '<input type="hidden" name="patient_id" value="'.$get_task_detail->patient_id.'">';
								} @endphp
								<div class="form-group row">
									<label class="col-lg-2 col-form-label">User Type</label>
									<div class="col-lg-10">
										<select class="form-control" disabled>
											<option value="staff" {{ $get_task_detail->user_type == 'staff' ? 'selected' : ''}}>Staff</option>
											<option value="manager" {{ $get_task_detail->user_type == 'manager' ? 'selected' : ''}}>Manager</option>
											<option value="client" {{ $get_task_detail->user_type == 'client' ? 'selected' : ''}}>Client</option>
										</select>
									</div>
								</div>

								<div class="{{ $get_task_detail->user_type == 'staff' ? '' : 'd-none'}}" id="staff_list_div">
									<div class="form-group row">
									<label class="col-lg-2 col-form-label" for="user_id">Staff</label>
										<div class="col-lg-10">
											<select class="form-control" disabled>
												<option value="">Select</option>
												@foreach($users as $user)
													<option value="{{ $user->id }}" {{ $get_task_detail->user_id == $user->id ? 'selected' : ''}}>{{ $user->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>

								<div class="{{ $get_task_detail->user_type == 'manager' ? '' : 'd-none'}}" id="manager_list_div">
									<div class="form-group row">
										<label class="col-lg-2 col-form-label" for="manager_id">Manager</label>
										<div class="col-lg-10">
											<select class="form-control" disabled>
												<option value="">Select</option>
												@foreach($managers as $manager)
													<option value="{{ $manager->id }}" {{ $get_task_detail->user_id == $manager->id ? 'selected' : ''}}>{{ $manager->name }}</option>
												@endforeach
											</select>
										</div>
									</div>							
								</div>

								<div class="{{ $get_task_detail->user_type == 'client' ? '' : 'd-none'}}" id="client_list_div">
									<div class="form-group row">
										<label class="col-lg-2 col-form-label" for="patient_id">Client</label>
										<div class="col-lg-10">
											<select class="form-control" disabled>
												<option value="">Select</option>
												@foreach($patients as $patient)
													<option value="{{ $patient->id }}" {{ $get_task_detail->patient_id == $patient->id ? 'selected' : ''}}>{{ $patient->name }}</option>
												@endforeach
											</select>
										</div>
									</div>							
								</div>

								<div class="form-group row @error('title') has-error @enderror">
									<label class="col-lg-2 col-form-label">Title</label>
									<div class="col-lg-10">
										<input type="text" name="title" placeholder="Title" class="form-control" required autocomplete="off" value="{{ $get_task_detail->title }}">
										@error('title')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>

								<div class="form-group row @error('description') has-error @enderror">
									<label class="col-lg-2 col-form-label">Description</label>
									<div class="col-lg-10">
										<textarea class="form-control" name="description" placeholder="Description" rows="3" required autocomplete="off">{{ $get_task_detail->description }}</textarea>
										@error('description')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>

								<div class="form-group row">
									<label class="col-lg-2 col-form-label" for="start_date">Start Date</label>
									<div class="col-lg-4 input-group date start_date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input type="text" name="start_date" value="{{ $get_task_detail->start_date }}" id="start_date" placeholder="Start Date" class="form-control required" >
									</div>

									<label class="col-lg-2 col-form-label" for="end_date">End Date</label>
									<div class="col-lg-4 input-group date end_date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input type="text" name="end_date" value="{{ $get_task_detail->end_date }}" id="end_date" placeholder="End Date" class="form-control required" >  
									</div> 
								</div>

								<div class="form-group row">
									<label class="col-lg-2 col-form-label" for="start_time">Start Time</label>
									<div class="col-lg-4 input-group clockpicker" data-autoclose="true">   
										<span class="input-group-addon"><span class="fa fa-clock-o"></span></span> 
										<input type="text" class="form-control" value="{{ $get_task_detail->start_time }}" name="start_time" id="start_time" placeholder="Start TIme" class="form-control rounded">
									</div>
									<label class="col-lg-2 col-form-label" for="end_time">End Time</label>
									<div class="col-lg-4 input-group clockpicker" data-autoclose="true">   
										<span class="input-group-addon"><span class="fa fa-clock-o"></span></span> 
										<input type="text" class="form-control" value="{{ $get_task_detail->end_time }}" name="end_time" id="end_time" placeholder="End TIme" class="form-control rounded"> 
									</div>
								</div>
								@php 
									$task_type = config('const.task_type');
								@endphp
								<div class="form-group row @error('task_type') has-error @enderror"> 
									<label class="col-lg-2 col-form-label">Task Type</label>
									<div class="col-lg-10">
										<select name="task_type" id="task_type" class="form-control" required> 
											@foreach($task_type as $key => $value) 
												<option value="{{ $key }}" {{ $get_task_detail->task_type == $key ? 'selected' : ''}}>{{ $value }}</option> 
											@endforeach
										</select>
										@error('task_type')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								@php 
									$task_status = config('const.task_status');
								@endphp
								<div class="form-group row @error('task_status') has-error @enderror"> 
									<label class="col-lg-2 col-form-label">Task Status</label>
									<div class="col-lg-10">
										<select name="task_status" id="task_status" class="form-control" required> 
											@foreach($task_status as $key => $value) 
												<option value="{{ $key }}" {{ $get_task_detail->task_status == $key ? 'selected' : ''}}>{{ $value }}</option> 
											@endforeach
										</select>
										@error('task_status')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								 

								<input type="hidden" name="redirectURL" value="{{ createCancelUrl(route('tasks-list')) }}">
								<div class="hr-line-dashed"></div>
								<div class="form-group row">
                                    <div class="col-md-12 text-right">
                                        <a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('tasks-list')) }}">Cancel</a>
                                        <button class="btn btn-sm btn-primary" type="submit" id="taskForm">Save</button>
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
<script src="{{ asset('assets/js/plugins/clockpicker/clockpicker.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {   
            // Initialize start datepicker
			$('.input-group.date.start_date').datepicker({
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true,
				startDate: new Date(),
				todayHighlight: true 
			});

			// Initialize end datepicker
			$('.input-group.date.end_date').datepicker({
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true 
			}).on('show', function(e){
				var startDate = $('.input-group.date.start_date').datepicker('getDate');
				if (startDate) {
					$(this).datepicker('setStartDate', startDate);
				}
			});
			$('.clockpicker').clockpicker(); 

			
        });
    </script>
@endsection