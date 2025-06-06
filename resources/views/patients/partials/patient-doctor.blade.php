@php
	$medicine_type = config('const.medicine_type');
	$medicine_time = config('const.medicine_time');
	$intake_method = config('const.medicine_intake_method');
	$intake_guidedby = config('const.medicine_intake_supervised_by');
@endphp
<div class="">
	<div class="ibox">
		<div class="ibox-title d-flex pl-0">
			<h5>Patient Doctor list</h5> 
			@if(Auth::user()->role_id == 2 && $patient->discharged != 1 && $patient->deleted_at == NULL)
				<div class="ibox-tools">
					<a href="{{ route('add-update-patients-doctor', ['patient_id' => $patient->id, 'id' => '0']) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Doctor </a>
				</div>
			@endif
		</div>
		<div class="table-responsive">
			<table class="table table-hover no-margins" id="doctor_list">
				<thead>
				<tr>
					<th>Id</th>
					<th>Name</th>
					<th>Phone</th>
					<th>Email</th>
					<th>Role</th>  
					<th>Fax Number</th> 
					@if(Auth::user()->role_id == 2)
						<th>Action</th>
					@endif 
				</tr>
				</thead>
				<tbody>
					@forelse($patient->doctors as $key => $medicine)
						<tr>
							<td>{{ $key + 1 }}</td>
							<td>{{ $medicine->name }}</td>
							<td>{{ $medicine->phone }}</td>
							<td>{{ $medicine->email }}</td> 
							<td>{{ $medicine->doctor_role }}</td> 
							<td>{{ $medicine->fax_number }}</td> 
							@if(Auth::user()->role_id == 2)
							<td class="text-dark text-nowrap"> 
								@if($patient->deleted_at == NULL && $patient->discharged == 0)	
									<a href="{{ route('add-update-patients-doctor', ['patient_id' => $patient->id, 'id' => $medicine->id]) }}" class="btn-primary btn btn-sm"  data-toggle="tooltip" data-placement="top" title="Edit Patient's Doctor">
										<i class="fa fa-edit" aria-hidden="true"></i>
									</a> 
									{{-- <form action="{{ route('delete-patients-medicine', $medicine->id) }}" method="post" id="delete_form_{{ $medicine->id }}" class="d-none">
										@csrf
										@method("DELETE")
									</form> 
									<a href="javascript:;" class="btn-danger btn btn-sm confirm_delete" role="button" data-item-id="{{ $medicine->id }}" data-item-type="patient medicine" title="Delete Patients Medicine"><i class="fa fa-trash" aria-hidden="true"></i></a>
									--}}
								@endif
							</td>
							@endif
						</tr>
					@empty
						<tr>
							<td class="text-center" colspan="{{Auth::user()->role_id == 2 ? 8 : 7}}">No Data Found</td>
						</tr>						
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>