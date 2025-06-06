@extends('layouts.admin')

@section('title', 'Patient Activity Detail')

@section('content')
<style>
	.footer {
		display: none;
	}

	.acti-item {
		display: flex;
		justify-content: center;
		flex-wrap: wrap;
		align-items: center;
		padding: 5px 10px;
		min-width: 170px;
		border: 1px solid #dee2e6;
		border-radius: 5px;
		margin-right: 10px;
		height: 40px;
		font-size: 14px;
		color: #2f4050;
		margin-bottom: 10px;
		font-weight: 500;
	}

	.acti-item.remark {
	flex-grow: 100;
	justify-content: start;
	height: auto;
	min-height: 40px;
	order: 10;
	width: auto;
}

	.acti-item:last-child {
		margin-right: 0;
	}

	.acti-item img {
		margin-right: 5px;
		max-width: 22px;
	}

	#activityTab {
		margin-bottom: 25px;
	}

	#activityTab .nav-item a {
		background: #f9f9f9;
		color: #2f4050;
		border-radius: 5px;
		font-size: 15px;
		padding: 12px 20px;
		font-weight: normal;
	}

	#activityTab .nav-item a.active {
		background: #1ab394;
		color: #fff;
	}

	#activityTab li.nav-item {
		margin: 0 10px 10px 0;
	}

	@media (max-width: 767px) {
		.acti-item {
			min-width: 130px;
		}

		.acti-item.remark {
			height: auto
		}
	}
</style>
@php 
	//$report = json_decode($log->report, true);
	if(!empty($log))
	{
	$times  = json_decode($log->report_time, true);
	}
	//echo"<pre>";print_r($report);die;
	//$morning_activity   = $report['morning'];
	//$afternoon_activity = $report['afternoon'];
	//$night_activity     = $report['night'];
	
	$morning_time       = isset($times['1']) ? $times['1'] : '';
	$afternoon_time     = isset($times['2']) ? $times['2'] : '';
	$evening_time         = isset($times['3']) ? $times['3'] : '';
	$night_time         = isset($times['4']) ? $times['4'] : '';
@endphp
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				@php 
					$previousUrl = URL::previous();
					
					// Parse the URL to get the path
					$path = parse_url($previousUrl, PHP_URL_PATH);
					//pathInArray = explode('/', $path);
					
					
				@endphp
				
				<li class="breadcrumb-item">
					@if($path == '/staff-activity-history-list')
						<a href="{{ url()->previous() }}">Activity List</a>
					@else
					<a href="{{ url()->previous() }}#tab4">Activity Report</a>
						{{-- <a href="{{ route('homes.show', $patient->home_id) }}">Care Home</a> --}}
					@endif
				</li>
				<li class="breadcrumb-item active">
					<strong>Patients Activity Detail</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
	<!-- New layout START from here -->
				<div class="ibox shadow border rounded">
					<div class="ibox-title d-flex align-items-center justify-content-between pr-3">
						<h5>"{{ $patient->care_home->name }}" Daily Summary Report</h5>
						@if(!empty($log))
						<div class="ibox-tool">
							<a href="{{ route('patients-download-activity-detail', $log->id) }}" class="btn btn-outline btn-primary btn-rounded btn-sm">
								<i class="fa fa-download"></i> Download
							</a>
						</div>
						@endif
					</div>
					<div class="ibox-content relative pt-0">
						<div class="hr-line-dashed m-0"></div>
						<div class="d-flex justify-content-between flex-wrap pt-4 mb-5">
							<!-- <div class="d-flex">
								<div class="border rounded p-1 mr-4 mb-2">
									<img width="90" alt="" title=""	src="{{ asset($patient->profile_image_path) }}">
								</div>
								<h2 class="font-bold fs-14 text-body mt-3">{{ $patient->name }} - Patient</h2>
							</div> -->
							<div class="mb-2 mr-4">
									<div class="position-relative ch-img">
										<img src="{{ $patient->profile_image_path }}" alt="profile" class="img-fluid rounded-lg">
										<div class="position-absolute {{$patient->status == 1 && $patient->deleted_at == null ? 'ch-online' : ''}}"></div>
									</div>
								</div>
								<div class="flex-grow-1">
									<h2 class="font-bold text-body">{{ $patient->name }} <i class="fa fa-check-circle fs-18 text-navy"></i></h2>
									<div class="d-flex flex-wrap mb-4 ch-info">
										<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4">
											<i class="fa fa-mobile-phone fs-18 mr-1"></i>{{ $patient->phone }}
										</a>
										<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4">
											<i class="fa fa-envelope  mr-1"></i> {{ $patient->email }}
										</a>
									</div>
									<div class="d-flex flex-wrap ch-stats">
										<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
											<div class="d-flex align-items-center">
												<div class="counted font-bold fs-16 text-body">
													<i class="fa fa-home mr-1 text-navy"></i>{{ $patient->care_home ? $patient->care_home->name : '-' }}
												</div>
											</div>
											<div class="font-bold  text-muted">Care Home</div>
										</div>
										<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
											<div class="d-flex align-items-center">
												<div class="counted font-bold fs-16 text-body">
													<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
													{{ date('M d, Y', strtotime($patient->admission_date)) }}
												</div>
											</div>
											<div class="font-bold  text-muted">Admitted On</div>
										</div>
										@if($patient->deleted_at != NULL)
											<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
												<div class="d-flex align-items-center">
													<div class="counted font-bold fs-16 text-body">
														<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
														{{ date('M d, Y', strtotime($patient->deleted_at)) }}
													</div>
												</div>
												<div class="font-bold  text-muted">Archived On</div>
											</div>
										@endif
										@if($patient->discharged == 1)
											<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
												<div class="d-flex align-items-center">
													<div class="counted font-bold fs-16 text-body">
														<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
														{{ date('M d, Y', strtotime($patient->updated_at)) }}
													</div>
												</div>
												<div class="font-bold  text-muted">Discharged On</div>
											</div>
										@endif
									</div>
									
									@if($patient->preferences)
									<div class="d-flex flex-wrap ch-stats">
										<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
											<div class="d-flex align-items-center">
												<div class="counted font-bold fs-16 text-body">
													{{-- <i class="fa fa-calendar fs-14 mr-1 text-navy"></i> --}}
													{{ $patient->preferences }}
												</div>
											</div>
											<div class="font-bold  text-muted">Preferences</div>
										</div>
									</div>
									@endif
								</div>
							<div>
								<span class="text-muted border rounded p-2 pr-5 d-block">
									<i class="fa fa-calendar"></i>
									@if(!empty($log))
									{{ date('m-d-Y',strtotime($log->report_date)) }}
									@else
									{{ date('m-d-Y') }}
									@endif
								</span>
							</div>
						</div>
						<div class="mt-4">
							<ul class="nav border-0" id="activityTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link {{empty(request()->get('shift')) || request()->get('shift') == 1 ? 'active' : ''}}" id="MorningActivityReport-tab" data-toggle="tab"	href="#MorningActivityReport" role="tab" 
										aria-controls="MorningActivityReport" aria-selected="true">Morning Activity</a>
								</li>
								<li class="nav-item">
									<a class="nav-link {{!empty(request()->get('shift')) && request()->get('shift') == 2 ? 'active' : ''}}" id="AfternoonActivityReport-tab" data-toggle="tab" href="#AfternoonActivityReport" role="tab" 
										aria-controls="AfternoonActivityReport"	aria-selected="false">Afternoon Activity</a>
								</li>
								<li class="nav-item">
									<a class="nav-link {{!empty(request()->get('shift')) && request()->get('shift') == 3 ? 'active' : ''}}" id="EveningActivityReport-tab" data-toggle="tab" href="#EveningActivityReport" role="tab" aria-controls="EveningActivityReport" 
										aria-selected="false">Evening Activity</a>
								</li>
								<li class="nav-item">
									<a class="nav-link {{!empty(request()->get('shift')) && request()->get('shift') == 4 ? 'active' : ''}}" id="NightActivityReport-tab" data-toggle="tab" href="#NightActivityReport" role="tab" aria-controls="NightActivityReport" 
										aria-selected="false">Night Activity</a>
								</li>
								<li class="nav-item">
									<a class="nav-link {{!empty(request()->get('shift')) && request()->get('shift') == 5 ? 'active' : ''}}" id="AdhocActivityReport-tab" data-toggle="tab" href="#AdhocActivityReport" role="tab" aria-controls="AdhocActivityReport" 
										aria-selected="false">Ad-hoc Activity</a>
								</li>
								<li class="nav-item">
									<a class="nav-link " id="ActivityAttachments-tab" data-toggle="tab" href="#ActivityAttachments" role="tab" aria-controls="ActivityAttachments" 
										aria-selected="false">Activity Attachments</a>
								</li>
							</ul>
							<div class="tab-content" id="activityTabContent">
								<div class="tab-pane fade {{empty(request()->get('shift')) || request()->get('shift') == 1 ? 'show active' : ''}} " id="MorningActivityReport" role="tabpanel" aria-labelledby="MorningActivityReport-tab">
									@include('patients.partials.activity-details.morning-activity')
								</div>
								<div class="tab-pane fade {{!empty(request()->get('shift')) && request()->get('shift') == 2 ? 'show active' : ''}}" id="AfternoonActivityReport" role="tabpanel" aria-labelledby="AfternoonActivityReport-tab">
									@include('patients.partials.activity-details.afternoon-activity')
								</div>
								<div class="tab-pane fade {{!empty(request()->get('shift')) && request()->get('shift') == 3 ? 'show active' : ''}}" id="EveningActivityReport" role="tabpanel" aria-labelledby="EveningActivityReport-tab">
									@include('patients.partials.activity-details.evening-night-activity')
								</div>
								<div class="tab-pane fade {{!empty(request()->get('shift')) && request()->get('shift') == 4 ? 'show active' : ''}}" id="NightActivityReport" role="tabpanel" aria-labelledby="NightActivityReport-tab">
									@include('patients.partials.activity-details.night-activity')
								</div>
								<div class="tab-pane fade {{!empty(request()->get('shift')) && request()->get('shift') == 5 ? 'show active' : ''}}" id="AdhocActivityReport" role="tabpanel" aria-labelledby="AdhocActivityReport-tab">
									@include('patients.partials.activity-details.ad-hoc-activity')
								</div>
								<div class="tab-pane fade " id="ActivityAttachments" role="tabpanel" aria-labelledby="ActivityAttachments-tab">
									@include('patients.partials.activity-details.activity-attachments')
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<!-- New layout END here -->
        </div>
	</div>	
@endsection
@section('script')
    <script type="text/javascript">
	 //page script goes here
    </script>
@endsection