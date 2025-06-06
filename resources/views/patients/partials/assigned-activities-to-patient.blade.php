<div class="">
	<div class="ibox ">
		<div class="ibox-title d-flex pl-0">
			<h5>Assigned Activities List </h5>
			@if(Auth::user()->role_id == 2 && $patient->discharged != 1 && $patient->deleted_at == NULL) 
				<div class="ibox-tools">
					<a href="{{ route('assign-activity-to-patient', ['patient_id' => $patient->id, 'id' => '0']) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Assign Acitivity </a>
				</div>
			@endif
		</div>
		<div class="">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
						<tr>
							<th>#</th>
							<th>Activity Name</th>
							<th>Duration(in minutes)</th>
							<th>Frequency</th>
							<th>Recurrence</th>
							<th>Action</th>
						</tr>
                    </thead>
                    <tbody>
						@forelse($patient->getPatientAssignedActivities as $key => $value)
							<tr>
								<td>{{ $key + 1 }}</td>
								<td>{{ $value->name ? ucfirst($value->name) : '-' }}</td>
                                <td>{{ $value->duration }}</td>
								<td>{{ $value->frequency }}</td>
								<td>
									@foreach($activity_recurrence as $key => $val)
									@if($value->recurrence === $key)
										{{$val}}
									@endif
									@endforeach 
								</td>
								<td>
									<a data-toggle="tooltip" data-placement="top" title="View Assigned Activity"  href="{{ route('view-patient-assigned-activity', ['patient_id' => $patient->id, 'id' => $value->id]) }}" class="btn-primary btn btn-sm"><i class="fa fa-eye"></i></a>
									
									@if(Auth::user()->role_id == 1 || Auth::user()->id == $value->created_by)
										@if($patient->deleted_at == NULL && $patient->discharged == 0)
											<a href="{{ route('assign-activity-to-patient', ['patient_id' => $patient->id, 'id' => $value->id]) }}" data-toggle="tooltip" data-placement="top" title="Edit Assigned Activity" class="btn-warning btn btn-sm"><i class="fa fa-edit"></i></a>
											<form action="{{ route('delete-patient-assigned-activity', $value->id) }}" method="post" id="delete_form_{{ $value->id }}" class="d-none">
												@csrf
												@method("DELETE")
											</form>
											<a href="javascript:;" class="btn-danger btn btn-sm confirm_delete" data-toggle="tooltip" data-placement="top"   role="button" data-item-id="{{ $value->id }}" data-item-type="patient assigned activity" title="Delete Assigned Activity"><i class="fa fa-trash" aria-hidden="true"></i></a>
											{{-- <a href="{{ route('delete-patient-assigned-activity', $value->id) }}" class="btn-primary btn btn-sm"><i class="fa fa-trash"></i></a> --}}
										@endif
									@endif
									
								</td>
							</tr>
						@empty
							<tr><td class="text-center text-info" colspan="6">No Data Found</td></tr>
						@endforelse
                    </tbody>
                </table>
            </div>

        </div>
	</div>
</div>