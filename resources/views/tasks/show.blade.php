@extends('layouts.admin')

@section('title', 'Task Detail')
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
					<a href="{{ route('tasks-list') }}">Tasks Management</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Task Detail</strong>
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
								<a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('tasks-list')) }}"><i class="fa fa-arrow-circle-o-left"></i> Back</a>
								<div class="hr-line-dashed"></div>
							</div>	
							<div class="col-md-12">
								<h2 class="font-bold fs-18 mb-4 text-body">Task Detail</h2> 
							</div> 
							<div class="col-md-12 d-flex align-items-start flex-column flex-md-row bg-white mb-3">
								<div class="mb-2 mr-4">
									<div class="position-relative ch-img">
										@php
											if($tasks->user_id != 0)
											{
												$src = getUserImage($tasks->user_id);
											}else{
												$src = getPatientImage($tasks->patient_id);
											}
										@endphp
									<img src="{{ $src }}" alt="{{ isset($tasks->user_type) && $tasks->user_type != '' ? $tasks->user_type : 'image' }}" class="img-fluid rounded-lg">
										<div class="position-absolute ch-online"></div>
									</div>
								</div>
								<div class="flex-grow-1">
									<h2 class="font-bold text-body">{{ isset($tasks->user_name) && $tasks->user_name != '' ? $tasks->user_name : (isset($tasks->patient_name) && $tasks->patient_name != '' ? $tasks->patient_name : '') }}
										 <i class="fa fa-check-circle fs-18 text-navy"></i></h2> 
									<div class="d-flex flex-wrap ch-stats"> 
										<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
											<div class="d-flex align-items-center">
												<div class="counted font-bold fs-16 text-body">
													<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
													{{ date('M d, Y', strtotime($tasks->start_date)) }}
												</div>
											</div>
											<div class="font-bold  text-muted">Start Date</div>
										</div>
										@if($tasks->task_type == 1)
											<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
												<div class="d-flex align-items-center">
													<div class="counted font-bold fs-16 text-body">
														<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
														{{ date('M d, Y', strtotime($tasks->end_date)) }}
													</div>
												</div>
												<div class="font-bold  text-muted">End Date</div>
											</div>
										@endif
										<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
											<div class="d-flex align-items-center">
												<div class="counted font-bold fs-16 text-body">
													<i class="fa fa-user fs-14 mr-1 text-navy"></i>
													{{ $tasks->added_by->name }}
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
													{{ \Carbon\Carbon::parse($tasks->start_time)->format('h:i A') }}
												</div>
											</div>
											<div class="font-bold  text-muted">Start Time</div>
										</div>
										@if($tasks->task_type == 1)
											<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
												<div class="d-flex align-items-center">
													<div class="counted font-bold fs-16 text-body">
														<i class="fa fa-clock-o fs-14 mr-1 text-navy"></i>
														{{ \Carbon\Carbon::parse($tasks->end_time)->format('h:i A') }}
													</div>
												</div>
												<div class="font-bold  text-muted">End Time</div>
											</div>
										@endif
										<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
											<div class="d-flex align-items-center">
											@php
												$task_type = config('const.task_type'); 
												if ($tasks->task_type == 0) {
													$text_type = $task_type[0]; 
												}
												else{
													$text_type = $task_type[1];  
												}

												if($tasks->task_type == 1){
													if($tasks->continuous_type == 0){
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
												//dd($task_status, $tasks->task_status);
												if ($tasks->task_status == '0') {
													$text_status = $task_status[0]; 
												}else if($tasks->task_status == '2'){
													$text_status = $task_status[2]; 
												}
												else if($tasks->task_status == '1'){
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
									@if ($tasks->task_type == 1)
										<div class="d-flex flex-wrap ch-stats"> 
											<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
												<div class="d-flex align-items-center">
												@php
													if($tasks->continuous_type == 0){
														$task_continue_type = 'Week Days';
														$selected_days = json_decode($tasks->selected_week_days);
													}else if($tasks->continuous_type == 1){
														$task_continue_type = 'Month Dates';
														$selected_days = json_decode($tasks->selected_month_dates);
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
								
							</div> 
							<div class="col-md-12 d-flex align-items-start flex-column flex-md-row bg-white mb-3">
								<div class="ibox-content mt-3 ">
									<div class="tab-content mt-2">
										<div class="tab-pane fade show active" id="tab1">
											<h3 class="text-body">{{ $tasks->title }}</h3>
											<p>{{ $tasks->description }}</p>
										</div> 
									</div>
								</div>
							</div>
							@if($tasks->task_type == 1)
							<div class="mt-0">
								<ul class="nav ch-tabs">
									<li class="nav-item">
										<a class="font-bold  py-3 px-2 mr-4 nav-link active patient-tab" data-tab-name="medicine" id="tab1-tab" data-toggle="tab" href="#tab1">Task Remarks</a>
									</li>
								</ul>
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>	 
			@if($tasks->task_type == 1)
			<div class="col-lg-12">
				<div class="ibox-content shadow border rounded">
					<div class="tab-content ">
						<div class="tab-pane fade show active" id="tab1">
							@include('tasks.partials.task-remark-list')
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