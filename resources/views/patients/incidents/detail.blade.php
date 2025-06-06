@extends('layouts.admin')

@section('title', 'Incident Detail')
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
                    <a href="{{ route('incident-reports-list') }}">Incidents</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Incident Detail</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                <div class="ibox-title d-flex align-items-center justify-content-between pr-3">
						<h5>Incident Detail</h5>
						<div class="ibox-tool">
							<a href="{{ route('download-single-incident-detail', $incident->id) }}" class="btn btn-outline btn-primary btn-rounded btn-sm">
								<i class="fa fa-download"></i> Download
							</a>
						</div>
					</div>
                <div class="ibox-content shadow border rounded pb-0 mb-4">
						<div class="row">
							<div class="col-md-12 d-flex align-items-start flex-column flex-md-row bg-white">
								<div class="mb-2 mr-4">
									<div class="position-relative ch-img">
										<img src="{{ $incident->patient->profile_image_path }}" alt="profile" class="img-fluid rounded-lg">
										<div class="position-absolute ch-online"></div>
									</div>
								</div>
								<div class="flex-grow-1">
									<h2 class="font-bold text-body">{{ $incident->patient->name }} <i class="fa fa-check-circle fs-18 text-navy"></i></h2>
									<div class="d-flex flex-wrap mb-4 ch-info">
										<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4">
											<i class="fa fa-mobile-phone fs-18 mr-1"></i>{{ $incident->patient->phone }}
										</a>
										<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4">
											<i class="fa fa-envelope  mr-1"></i> {{ $incident->patient->email }}
										</a>
									</div>
									<div class="d-flex flex-wrap ch-stats">
										<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
											<div class="d-flex align-items-center">
												<div class="counted font-bold fs-16 text-body">
													<i class="fa fa-home mr-1 text-navy"></i>{{ $incident->patient->care_home ? $incident->patient->care_home->name : '-' }}
												</div>
											</div>
											<div class="font-bold  text-muted">Care Home</div>
										</div>
										<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
											<div class="d-flex align-items-center">
												<div class="counted font-bold fs-16 text-body">
													<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
													{{ date('M d, Y', strtotime($incident->patient->admission_date)) }}
												</div>
											</div>
											<div class="font-bold  text-muted">Admitted On</div>
										</div>
										@if($incident->patient->deleted_at != NULL)
											<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
												<div class="d-flex align-items-center">
													<div class="counted font-bold fs-16 text-body">
														<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
														{{ date('d M, Y', strtotime($incident->patient->deleted_at)) }}
													</div>
												</div>
												<div class="font-bold  text-muted">Archived On</div>
											</div>
										@endif
										@if($incident->patient->discharged == 1)
											<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
												<div class="d-flex align-items-center">
													<div class="counted font-bold fs-16 text-body">
														<i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
														{{ date('d M, Y', strtotime($incident->patient->updated_at)) }}
													</div>
												</div>
												<div class="font-bold  text-muted">Discharged On</div>
											</div>
										@endif
									</div>
									<div class="d-flex flex-wrap ch-stats">
										@if($incident->patient->deleted_at == NULL)
											@if($incident->patient->getLatestPatientLog && $incident->patient->getLatestPatientLog->is_in_house == 2)
												<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
													<div class="d-flex align-items-center">
														<div class="counted font-bold fs-16 text-body">
															<i class="fa fa-sign-out fs-14 mr-1 text-navy"></i>
															{{ $incident->patient->getLatestPatientLog->is_in_house == 2 ? 'Yes' : ''}}
														</div>
													</div>
													<div class="font-bold  text-muted">Out Home</div>
												</div>
												<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
													<div class="d-flex align-items-center">
														<div class="counted font-bold fs-16 text-body">
															<i class="fa fa-sign-out fs-14 mr-1 text-navy"></i>
															{{ $incident->patient->getLatestPatientLog->comment }}
														</div>
													</div>
													<div class="font-bold  text-muted">Reason</div>
												</div>
												<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
													<div class="d-flex align-items-center">
														<div class="counted font-bold fs-16 text-body">
															<i class="fa fa-clock-o fs-14 mr-1 text-navy"></i>
															{{ date('d M, Y h:i:s a', strtotime($incident->patient->getLatestPatientLog->expected_return_date."".$incident->patient->getLatestPatientLog->expected_return_time)) }}
														</div>
													</div>
													<div class="font-bold  text-muted">Expected Return Date & Time</div>
												</div>
											@endif
										@endif
									</div>
									
								</div>
							
							</div>
						
						</div>
					</div>

                    <div class="ibox-content shadow border rounded pb-0">
                        <h2 class="font-bold fs-18 mb-2 text-body">Incident Report Detail</h2>
						 <div class="row">
                            <div class="col-md-12 d-flex align-items-start flex-column flex-md-row bg-white mb-0">
                                <div class="ibox-content p-0">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="tab1">
                                            <h3 class="text-body">{{$incident->title}}</h3>
                                            <p>{{$incident->incident_reason}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

							<div class="col-md-12 d-flex align-items-start flex-column flex-md-row bg-white mb-3">
                                <div class="ibox-content p-0">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="tab2">
                                            <h3 class="text-body">Action Taken</h3>
                                            <p>{{$incident->action_taken}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive" style="min-height: auto;">
                                    <table class="border table">
                                        <tbody>
                                            <tr class="text-white" style="background: #2f4050;">
                                            <th class="font-normal" style="width:150px;">Reported by</th>
                                                <th class="font-normal">Date</th>
                                                <th class="font-normal">Time</th>
                                                <th class="font-normal">Reported to 9-1-1</th>
                                                <th class="font-normal">Reported to RCS</th>
                                                <th class="font-normal">Reported to Manager</th>
                                                <th class="font-normal" style="width: 300px;">Reported to Other</th>
                                            </tr>
                                            <tr>
                                            <td class="" >{{ $incident->incident_created_by }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($incident->patient->created_at)->format('m-d-Y') }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($incident->patient->created_at)->format('h:i A') }}</td>
                                                <td class="text-center"> {{ $incident->report_to_nine ? 'Yes' : 'No' }}</td>
                                                <td class="text-center">{{ $incident->report_to_rcs ? 'Yes' : 'No' }}</td>
                                                <td class="text-center">{{ $incident->report_to_manager ? 'Yes' : 'No' }}</td>
                                               <td class="">
                                                <span class="incident-content">
                                                    {{ substr($incident->report_to_other ? $incident->other : 'No', 0, 100) }}
                                                    <span class="more-text">{{ strlen($incident->other) > 100 ? '...' : '' }}</span>
                                                </span>
                                                @if(strlen($incident->other) > 100)
                                                    <a href="javascript:void;" class="text-info read-more">Read more</a>
                                                @endif
                                            </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
    <script>
    $(document).ready(function(){
        $(".read-more").click(function(){
            var $this = $(this);
            var $content = $this.prev('.incident-content');
            $content.toggleClass("expanded");
            if ($content.hasClass("expanded")) {
                $content.html('{{ $incident->other }}');
                $this.text("Read less");
            } else {
                $content.html('{{ substr($incident->report_to_other ? $incident->other : 'No', 0, 100) }}<span class=\"more-text\">...</span>');
                $this.text("Read more");
            }
        });
    });
</script>
@endsection
