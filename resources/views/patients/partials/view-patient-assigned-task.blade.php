@extends('layouts.admin')

@section('title', 'View Patient Assigned Task Detail')
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
				<a href="{{ route('homes.show', $patient_assigned_task->patient->home_id) }}">Care Home</a>
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
							<a class="btn btn-white btn-sm" type="button" href="#" onclick="window.history.back(); return false;"><i class="fa fa-arrow-circle-o-left"></i> Back</a>
							<div class="hr-line-dashed"></div>
						</div>	
						<div class="col-md-12">
							<h2 class="font-bold fs-18 mb-4 text-body">Task Details</h2> 
						</div> 
						<div class="col-md-12 d-flex align-items-start flex-column flex-md-row bg-white mb-3">
							<div class="mb-2 mr-4">
								@php
									if($patient_assigned_task->user_id != 0)
									{
										$src = getUserImage($patient_assigned_task->user_id);
									}else{
										$src = getPatientImage($patient_assigned_task->patient_id);
									}
								@endphp
								<div class="position-relative ch-img">
								<img src="{{ $src }}" alt="{{ isset($patient_assigned_task->user_type) && $patient_assigned_task->user_type != '' ? $patient_assigned_task->user_type : 'image' }}" class="img-fluid rounded-lg">
									<div class="position-absolute ch-online"></div>
								</div>
							</div>
							<div class="flex-grow-1">
								{{-- <h2 class="font-bold text-body">user_name
										<i class="fa fa-check-circle fs-18 text-navy"></i></h2>  --}}
								<div class="d-flex flex-wrap ch-stats"> 
									<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
										<div class="d-flex align-items-center">
											<div class="counted font-bold fs-16 text-body">
												<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
												{{ date('d M, Y', strtotime($patient_assigned_task->start_date)) }}
											</div>
										</div>
										<div class="font-bold  text-muted">Start Date</div>
									</div>
									<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
										<div class="d-flex align-items-center">
											<div class="counted font-bold fs-16 text-body">
												<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
												{{ date('d M, Y', strtotime($patient_assigned_task->end_date)) }}
											</div>
										</div>
										<div class="font-bold  text-muted">End Date</div>
									</div>
								</div>
								<div class="d-flex flex-wrap ch-stats"> 
									<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
										<div class="d-flex align-items-center">
											<div class="counted font-bold fs-16 text-body">
												<i class="fa fa-clock-o fs-14 mr-1 text-navy"></i> 
												{{ \Carbon\Carbon::parse($patient_assigned_task->start_time)->format('h:i A') }}
											</div>
										</div>
										<div class="font-bold  text-muted">Start Time</div>
									</div>
									<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
										<div class="d-flex align-items-center">
											<div class="counted font-bold fs-16 text-body">
												<i class="fa fa-clock-o fs-14 mr-1 text-navy"></i>
												{{ \Carbon\Carbon::parse($patient_assigned_task->end_time)->format('h:i A') }}
											</div>
										</div>
										<div class="font-bold  text-muted">End Time</div>
									</div>
								</div>
							</div>
							<!----Discharged checkbox--------------->
							<div class="d-flex">
								<div class="border mb-3 mr-3 p-2 rounded ch-stats-item">
									<div>
										<label class="form-check-label">
										@php
											$task_type = config('const.task_type'); 
											if ($patient_assigned_task->task_type == 0) {
												$text_type = $task_type[0]; 
											}
											else{
												$text_type = $task_type[1];  
											}
										@endphp
										<span class="d-block font-bold">Type:</span> {{ $text_type }}
										</label>
									</div> 										
								</div>
								<div class="border mb-3 mr-3 p-2 rounded ch-stats-item">
									<div>
										<label class="form-check-label">
										@php
											$task_status = config('const.task_status'); 
											if ($patient_assigned_task->task_status == 0) {
												$text_status = $task_status[0]; 
											}
											else{
												$text_status = $task_status[1];  
											}
										@endphp
										<span class="d-block font-bold">Status:</span> {{ $text_status }}
										</label>
									</div> 										
								</div>
							</div>
						</div> 
						<div class="col-md-12 d-flex align-items-start flex-column flex-md-row bg-white mb-3">
							<div class="ibox-content mt-3 ">
								<div class="tab-content mt-2">
									<div class="tab-pane fade show active" id="tab1">
										<h3 class="text-body">{{ $patient_assigned_task->title }}</h3>
										<p>{{ $patient_assigned_task->description }}</p>
									</div> 
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	 
	</div>
</div>	 
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script type="text/javascript">
		 
    </script>
@endsection