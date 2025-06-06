@extends('layouts.admin')

@section('title', 'Patient Detail')

@section('content')
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item">
					<a href="{{ route('homes.show', $patient_assigned_activity->home_id) }}">Care Home</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>View Assigned Activitiy Detail</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
                <div class="col-lg-12">

                    <div class="ibox product-detail">
                        <div class="ibox-content border rounded shadow">
                            <div class="row">
								<div class="col-md-12">
									<a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('homes.show', $patient_assigned_activity->home_id)) }}"><i class="fa fa-arrow-circle-o-left"></i> Back</a>
									<div class="hr-line-dashed"></div>
								</div>	

								<div class="col-md-12">
									<h2 class="font-bold fs-18 mb-4 text-body">Patient Detail with Assigned Activity</h2>
									<div class="ch-detail d-flex flex-wrap flex-sm-nowrap">
										<div class="mb-2 mr-4">
											<div class="position-relative ch-img">
												<img src="{{ $patient_assigned_activity->patient_detail->profile_image_path}}" alt="staff" class="img-fluid rounded-lg">
												
												<div class="position-absolute {{$patient_assigned_activity->patient_detail->status == 1 && $patient_assigned_activity->patient_detail->deleted_at == null ? 'ch-online' : ''}}">
												</div>
											</div>
										</div>
										<div class="flex-grow-1">
											<h2 class="font-bold text-body fs-16">{{ $patient_assigned_activity->patient_detail->name }} </h2>
											<div class="mb-4 ch-info">
												<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i class="fa fa-mobile-phone fs-18 mr-1"></i> {{$patient_assigned_activity->patient_detail->phone}}</a>
												<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i class="fa fa-envelope  mr-1"></i> {{$patient_assigned_activity->patient_detail->email}} </a>
                                                <p class="font-bold mt-3 text-navy fs-14 mb-2">Activity:</p>
												<div class="d-flex mb-2">
														<div class="align-items-center d-flex font-bold  mb-2 mr-4 text-muted">
															<i class="fa fa-tasks mr-1"></i> {{ ucfirst($patient_assigned_activity->name)}}
														</div>
													
                                                        <div class="align-items-center d-flex font-bold  mb-2 mr-4 text-muted">
                                                            <i class="fa fa-clock-o  mr-1"></i>{{$patient_assigned_activity->duration}} Minutes <em class="ml-1">(@foreach($activity_recurrence as $key => $value)
                                                                @if($patient_assigned_activity->recurrence == $key){{$value}}@endif
                                                            @endforeach)</em>
                                                        </div>
												</div>
                                                <p>{{$patient_assigned_activity->description}}</p>	
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
    <script type="text/javascript">
		$(document).ready(function () {
			
		}) 
	</script>	
@endsection