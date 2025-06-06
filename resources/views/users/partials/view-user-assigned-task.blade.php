@extends('layouts.admin')

@section('title', 'View User Assigned Task Detail')
@section('style')
	<link href="{{ asset('assets/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="{{ route('dashboard') }}">Home</a>
			</li> 
			<li class="breadcrumb-item">
				<a href="{{ route('homes.show', $user_assigned_task->userAssignedTask->home_id) }}">Care Home</a>
			</li>
			<li class="breadcrumb-item active">
				<strong>View Assigned Task Detail</strong>
			</li>
		</ol>
	</div>
</div>	
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-content shadow border rounded pb-0">
					<div class="row">							
						<div class="col-md-12">
							<a class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Back" type="button" href="#" onclick="window.history.back(); return false;"><i class="fa fa-arrow-circle-o-left"></i> Back</a>
							<div class="hr-line-dashed"></div>
						</div>	
						<div class="col-md-12">
							<h2 class="font-bold fs-18 mb-4 text-body">Task Details</h2> 
						</div> 
						<div class="col-md-12 d-flex align-items-start flex-column flex-md-row bg-white mb-3">
							<div class="mb-2 mr-4">
								@php
									if($user_assigned_task->user_id != 0)
									{
										$src = getUserImage($user_assigned_task->user_id);
									}else{
										$src = getPatientImage($user_assigned_task->patient_id);
									}
								@endphp
								<div class="position-relative ch-img">
								<img src="{{ $src }}" alt="{{ isset($user_assigned_task->user_type) && $user_assigned_task->user_type != '' ? $user_assigned_task->user_type : 'image' }}" class="img-fluid rounded-lg">
									<div class="position-absolute ch-online"></div>
								</div>
							</div>
							
							<div class="flex-grow-1">
							<h2 class="font-bold text-body">{{ isset($user_assigned_task->userAssignedTask) && $user_assigned_task->userAssignedTask->name != '' ? $user_assigned_task->userAssignedTask->name: '' }}
							<i class="fa fa-check-circle fs-18 text-navy"></i></h2> 
								{{-- <h2 class="font-bold text-body">user_name
										<i class="fa fa-check-circle fs-18 text-navy"></i></h2>  --}}
								<div class="d-flex flex-wrap ch-stats"> 
									<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
										<div class="d-flex align-items-center">
											<div class="counted font-bold fs-16 text-body">
												<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
												{{ date('M d, Y', strtotime($user_assigned_task->start_date)) }}
											</div>
										</div>
										<div class="font-bold  text-muted">Start Date</div>
									</div>
									@if($user_assigned_task->task_type == '1')
										<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
											<div class="d-flex align-items-center">
												<div class="counted font-bold fs-16 text-body">
													<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
													{{ date('M d, Y', strtotime($user_assigned_task->end_date)) }}
												</div>
											</div>
											<div class="font-bold  text-muted">End Date</div>
										</div>
									@endif
									<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
										<div class="d-flex align-items-center">
											<div class="counted font-bold fs-16 text-body">
												<i class="fa fa-user fs-14 mr-1 text-navy"></i>
												{{ $user_assigned_task->added_by->name }}
											</div>
										</div>
										<div class="font-bold  text-muted">Created By</div>
									</div>
								</div>
								<div class="d-flex flex-wrap ch-stats"> 
									<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
										<div class="d-flex align-items-center">
											<div class="counted font-bold fs-16 text-body">
												<i class="fa fa-clock-o fs-14 mr-1 text-navy"></i> 
												{{ \Carbon\Carbon::parse($user_assigned_task->start_time)->format('h:i A') }}
											</div>
										</div>
										<div class="font-bold  text-muted">Start Time</div>
									</div>
									@if($user_assigned_task->task_type == '1')
										<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
											<div class="d-flex align-items-center">
												<div class="counted font-bold fs-16 text-body">
													<i class="fa fa-clock-o fs-14 mr-1 text-navy"></i>
													{{ \Carbon\Carbon::parse($user_assigned_task->end_time)->format('h:i A') }}
												</div>
											</div>
											<div class="font-bold  text-muted">End Time</div>
										</div>
									@endif

									<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
										<div class="d-flex align-items-center">
										@php
											$task_type = config('const.task_type'); 
											if ($user_assigned_task->task_type == 0) {
												$text_type = $task_type[0]; 
											}
											else{
												$text_type = $task_type[1];  
											}

											if($user_assigned_task->task_type == 1){
												if($user_assigned_task->continuous_type == 0){
													$task_continue_type = 'Weekly';
												}else{
													$task_continue_type = 'Monthly';
												}
											}else{
												$task_continue_type = '';
											}
										@endphp
											<div class="counted font-bold fs-16 text-body">
												{{ $text_type }} - {{$task_continue_type}}
											</div>
										</div>
										<div class="font-bold  text-muted">Type</div>
									</div>
									<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
										<div class="d-flex align-items-center">
										@php
											$task_status = config('const.task_status'); 
											if ($user_assigned_task->task_status == '0') {
												$text_status = $task_status[0]; 
											}else if($user_assigned_task->task_status == '2'){
												$text_status = $task_status[2]; 
											}
											else if($user_assigned_task->task_status == '1'){
												$text_status = $task_status[1];  
											}
											else{
												$text_status = 'Pending';  
											}
										@endphp
											<div class="counted font-bold fs-16 text-body">
												{{ $text_status }}
											</div>
										</div>
										<div class="font-bold  text-muted">Status</div>
									</div>
								</div>
								@if ($user_assigned_task->task_type == 1)
									<div class="d-flex flex-wrap ch-stats"> 
										<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
											<div class="d-flex align-items-center">
											@php
												if($user_assigned_task->continuous_type == 0){
													$task_continue_type = 'Week Days';
													$selected_days = json_decode($user_assigned_task->selected_week_days);
												}else if($user_assigned_task->continuous_type == 1){
													$task_continue_type = 'Month Dates';
													$selected_days = json_decode($user_assigned_task->selected_month_dates);
												}
											@endphp
												<div class="counted font-bold fs-16 text-body">
													@if($selected_days)
														{{ implode(", ", $selected_days) }}
													@endif
												</div>
											</div>
											<div class="font-bold  text-muted">{{$task_continue_type}}</div>
										</div>
									</div>
								@endif
							</div>
							<!----Discharged checkbox--------------->
							<div class="d-flex">
								{{-- <div class="border mb-3 mr-3 p-2 rounded ch-stats-item">
									<div>
										<label class="form-check-label">
										@php
											$task_type = config('const.task_type'); 
											if ($user_assigned_task->task_type == 0) {
												$text_type = $task_type[0]; 
											}
											else{
												$text_type = $task_type[1];  
											}
										@endphp
										<span class="d-block font-bold">Type:</span> {{ $text_type }}
										</label>
									</div> 										
								</div> --}}
								{{-- <div class="border mb-3 mr-3 p-2 rounded ch-stats-item">
									<div>
										<label class="form-check-label">
										@php
											$task_status = config('const.task_status'); 
											if ($user_assigned_task->task_status == '0') {
												$text_status = $task_status[0]; 
											}else if($user_assigned_task->task_status == '2'){
												$text_status = $task_status[2]; 
											}else if($user_assigned_task->task_status == '1'){
												$text_status = $task_status[1];  
											}
											else{
												$text_status = '--';  
											}
										@endphp
										<span class="d-block font-bold">Status:</span> {{ $text_status }}
										</label>
									</div> 										
								</div> --}}
							</div>
						</div> 
						<div class="col-md-12 d-flex align-items-start flex-column flex-md-row bg-white mb-3">
							<div class="ibox-content mt-3 ">
								<div class="tab-content mt-2">
									<div class="tab-pane fade show active" id="tab1">
										<h3 class="text-body">{{ $user_assigned_task->title }}</h3>
										<p>{{ $user_assigned_task->description }}</p>
									</div> 
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	 

		@if($user_assigned_task->task_type == 1)
			<div class="col-lg-12">
				<div class="ibox-content shadow border rounded">
					<div class="tab-content ">
						<div class="tab-pane fade show active" id="tab1">
							@include('users.partials.user-assigned-task-remark-list')
						</div>
					</div>
				</div>
			</div>
			@endif
	</div>
</div>	 
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script type="text/javascript">
		 
    </script>
@endsection