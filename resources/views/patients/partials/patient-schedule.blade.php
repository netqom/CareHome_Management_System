<div class="">
	<div class="ibox ">
		<div class="ibox-title d-flex pl-0 align-items-center">
			<h5>Doctor Appointment list</h5>
			@if(Auth::user()->role_id == 2 && $patient->discharged != 1 && $patient->deleted_at == NULL)
				<div class="ibox-tools">
					<a href="{{ route('add-update-patients-schedule', ['patient_id' => $patient->id, 'id' => '0']) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Schedule </a>
				</div>
			@endif
		</div>
		<div class="table-responsive">
			<table class="table table-hover no-margins" id="document_list">
				<thead>
				<tr>
					<th>Id</th>
					<th>Title</th>
					<th>Description</th>
					<th>Doctor Name</th>
                    {{-- <th>Address</th> --}}
					<th>Date/Time</th>
					{{-- <th>Status</th>	 --}}
					<th>Appointment Schedule</th>				
					<!--th>Document</th-->
					@if(Auth::user()->role_id == 2)
						<th>Action</th>
					@endif
				</tr>
				</thead>
				<tbody>
					@php 
						$patient_document_type = config('const.patient_document_type');
						$medicine_frequency = config('const.schdule_frequency'); 
					@endphp
					@forelse($patient->appointment as $key => $appointment)
					
						<tr>
							<td>{{ $key + 1 }}</td>
							<td>{{ $appointment->title ?? '' }}</td>
							<td>{{ $appointment->description ?? '' }}</td>
							<td>
								@if($appointment->doctor_name!='other')
								{{ $appointment->appointed_doctor->name ?? '' }}
								@else
								{{ $appointment->other_doctor ?? '' }}
								@endif
							</td>
							{{-- <td>{{ $appointment->address ?? '' }}</td> --}}
							<td>{{ $appointment->appointment_date && $appointment->appointment_time ? date('m-d-Y',strtotime( $appointment->appointment_date)).' '.date('h:i A',strtotime( $appointment->appointment_time)) : '' }}</td>
							{{-- <td>{{ $appointment->appointment_date && $appointment->appointment_time ? date('h:i A',strtotime( $appointment->appointment_time)) : '' }}</td> --}}
                            {{-- <td>
								@php
								 if ($appointment->status === NULL)
								 {
									$cls_name = 'label-warning';
									$text = 'Schedule';
									}
									else if ($appointment->status == 1) {
										$text = 'Visited';
										$cls_name = 'label-primary';
									}else if($appointment->status == 0){
										$text = 'Not Visited';
										$cls_name = 'label-danger';
									}

								@endphp
								<span class="label {{ $cls_name }}"> {{ $text }}</span>
							</td> --}}
							<td>
								{{$appointment->app_schedule_name}}
							</td>			
							@if(Auth::user()->role_id == 2)
								<td class="text-dark text-nowrap">
									@if($patient->deleted_at == NULL && $patient->discharged == 0)						
										{{-- <a data-toggle="tooltip" data-placement="top"  href="{{ route('add-update-patients-schedule', ['patient_id' => $patient->id, 'id' => $appointment->id]) }}" class="btn-primary btn btn-sm" title="Edit Patient Appointment">
											<i class="fa fa-edit" aria-hidden="true"></i>
										</a>  --}}

										<a data-toggle="tooltip" data-placement="top"  href="{{ route('view-patients-schedule', ['patient_id' => $patient->id, 'id' => $appointment->id]) }}" class="btn-primary btn btn-sm" title="View Patient Appointment">
											<i class="fa fa-eye" aria-hidden="true"></i>
										</a> 
										<form action="{{ route('delete-patients-schedule', $appointment->id) }}" method="post" id="delete_form_{{ $appointment->id }}" class="d-none">
											@csrf
											@method("DELETE")
										</form>
										<a data-toggle="tooltip" data-placement="top" href="javascript:;" class="btn-danger btn btn-sm confirm_delete" role="button" data-item-id="{{ $appointment->id }}" data-item-type="patient appointment" title="Delete Patient Appointment"><i class="fa fa-trash" aria-hidden="true"></i></a>
									@endif
								</td>
							@endif
						</tr>
					@empty
						<tr>
							<td class="text-center" colspan="{{Auth::user()->role_id == 2 ? 7 : 6}}">No Data Found</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
