@extends('layouts.admin')

@section('title', 'View Patient Schedule Detail')
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
				<a href="{{ route('homes.show', $patient_appointment->patient_detail->home_id) }}">Care Home</a>
			</li>
			<li class="breadcrumb-item active">
				<strong>View Patient Schedule Detail</strong>
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
                                <a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('homes.show', $patient_appointment->patient_detail->home_id)) }}"><i class="fa fa-arrow-circle-o-left"></i> Back</a>
                                <div class="hr-line-dashed"></div>
                            </div>	

                            <div class="col-md-12">
                                <h2 class="font-bold fs-18 mb-4 text-body">Patient Detail with Scheduled Appointment</h2>
                                <div class="ch-detail d-flex flex-wrap flex-sm-nowrap">
                                    <div class="mb-2 mr-4">
                                        <div class="position-relative ch-img">
                                            <img src="{{ $patient_appointment->patient_detail->profile_image_path}}" alt="staff" class="img-fluid rounded-lg">
                                            
                                            <div class="position-absolute {{$patient_appointment->patient_detail->status == 1 && $patient_appointment->patient_detail->deleted_at == null ? 'ch-online' : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h2 class="font-bold text-body fs-16">{{ $patient_appointment->patient_detail->name }} </h2>
                                        <div class="mb-4 ch-info">
                                            <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i class="fa fa-mobile-phone fs-18 mr-1"></i> {{$patient_appointment->patient_detail->phone}}</a>
                                            <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i class="fa fa-envelope  mr-1"></i> {{$patient_appointment->patient_detail->email}} </a>
                                            <p class="font-bold mt-3 text-navy fs-14 mb-2">Schedule Appointment:</p>
                                            <div class="d-flex flex-column mb-2 ">
                                                    <div class="align-items-center d-flex font-bold  mb-2 mr-4 text-muted">
                                                        <i class="fa fa-tag mr-1"></i> {{ ucfirst($patient_appointment->title)}}
                                                    </div>

                                                    <p>{{$patient_appointment->description}}</p>	
                                            </div>
                                            <div class="d-flex flex-wrap ch-stats"> 
                                                <div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
                                                    <div class="d-flex align-items-center">
                                                        <div class="counted font-bold fs-16 text-body">
                                                            <i class="fa fa-clock-o fs-14 mr-1 text-navy" aria-hidden="true"></i> 
                                                            {{ date('m-d-Y',strtotime( $patient_appointment->appointment_date))}} {{ date('h:i A',strtotime( $patient_appointment->appointment_time)) }}
                                                        </div>
                                                    </div>
                                                    <div class="font-bold  text-muted">Appointment Date/Time</div>
                                                </div>
                                                
                                                <div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
                                                    <div class="d-flex align-items-center">
                                                        <div class="counted font-bold fs-16 text-body">
                                                            @foreach ($medicine_frequency as $key => $value)
                                                            @if($patient_appointment->schedule_frequency == $key)
                                                                {{ ucfirst($value) }}
                                                            @endif
                                                        @endforeach 
                                                        </div>
                                                    </div>
                                                    <div class="font-bold  text-muted">Appointment Schedule</div>
                                                </div>

                                                @if($patient_appointment->schedule_frequency == 2 || $patient_appointment->schedule_frequency == 3 || $patient_appointment->schedule_frequency == 4)
                                                <div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
                                                    <div class="d-flex align-items-center">
                                                        @php
                                                            if($patient_appointment->schedule_frequency == 2 || $patient_appointment->schedule_frequency == 3){
                                                                $schedule_type = 'Week Days';
                                                            }else if($patient_appointment->schedule_frequency == 4){
                                                                $schedule_type = 'Month Dates';
                                                            }else{
                                                                $schedule_type = '';
                                                            }
                                                        @endphp
                                                        <div class="counted font-bold fs-16 text-body">
                                                            @if(!empty($patient_appointment->other_schedule_frequency) && $patient_appointment->other_schedule_frequency != null)
                                                            {{ implode(", ", json_decode($patient_appointment->other_schedule_frequency)) }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="font-bold  text-muted">{{$schedule_type}}</div>
                                                </div>
                                                @endif
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


        @if($patient_appointment->schedule_frequency)
            <div class="col-lg-12">
                <div class="ibox-content shadow border rounded">
                    <div class="tab-content ">
                        <div class="tab-pane fade show active" id="tab1">
                            @include('patients.partials.patient-schedule-remark-list')
                        </div>
                    </div>
                </div>
            </div>
        @endif
</div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script type="text/javascript">
		 
    </script>
@endsection