{{-- <div class="row {{ $active_list != 'patient' ? 'd-none' : '' }}" id="patient_list_div"> --}}
<div class="row" id="patient_list_div">
	<div class="col-lg-12">
		<div class="ibox ">
			<div class="ibox-title d-flex border-0 pl-0">
				{{-- <!--h5>Patient List </h5--> --}}
				<form class="mr-2">
					<div class="position-relative table-search"> 
						<input type="text" placeholder="Search" class="form-control rounded pr-4" id="patient_search">
						<i class="fa fa-search"></i>
					</div>
				</form>
				<div class="ibox-tools">
					@if(Auth::user()->role_id == 2)
						@if($home->deleted_at == NULL)
							@if(checkPatientAllowedCapacity($home->id))
								<a href="{{ route('patients.create', ['home_id' => $home->id]) }}" data-toggle="tooltip" data-placement="top"   class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Patient </a>
							@else
								<a href="#" data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-sm patient_capacity_check"><i class="fa fa-plus" aria-hidden="true"></i> Add Patient </a>
							@endif
						@endif
					@endif
				</div>
			</div>
			<div class="">
				<div class="table-responsive relative">
					{{-- <table class="table table-striped table-bordered table-hover" id="patient_list"> --}}
					<table class="table" id="patient_list">
						<thead>
						<tr>
							<th style="min-width: 50px;" class="border-0">S.No</th>
							<th style="min-width: 0px;" class="border-0">Image</th>
							<th style="min-width: 200px;" class="border-0">Name</th>
							<th style="min-width: 200px;" class="border-0">Email</th>
							<th style="min-width: 70px;" class="border-0">Phone No</th>
							<th style="min-width: 70px;" class="border-0">Action</th>
						</tr>
						</thead>
						<tbody>
							@if (!$patients->isEmpty())
								@foreach ($patients as $key => $record)
									<tr>
										<td>{{ $key + 1 }}</td>
										<td><img src="{{ asset($record->profile_image_path) }}" alt="patient-image" height="auto" width="50"></td>
										<td>{{ $record->name }}
										@if($record->discharged_request == 1)
									<br/><span class="badge badge-warning py-1">Discharge request</span>
									@elseif($record->delete_request == 1 && $record->deleted_at == NULL)
									<br/><span class="badge badge-warning py-1">Delete request</span>
									@endif
										</td>
										<td>{{ $record->email }}</td>
										<td class="text-nowrap">{{ $record->phone }}</td>
										<td class="text-nowrap">
											
											<a href="{{ route('patients.show', $record->id) }}" class="btn-primary btn btn-sm" data-toggle="tooltip" data-placement="top" title="View Patient"><i class="fa fa-eye"  aria-hidden="true"></i></a>
											@if($home->deleted_at == NULL && Auth::user()->role_id != 1)
												<a href="{{ route('patients.edit', $record->id) }}" class="btn-warning btn btn-sm" data-toggle="tooltip" data-placement="top" title="Edit Patient"><i class="fa fa-edit" aria-hidden="true"></i></a>
											@endif
											{{-- <a href="{{ route('patients-show-activity', $record->id) }}" class="btn-primary btn btn-sm">View Activity</a> --}}
											@if($record->discharged == 0 && $record->deleted_at == NULL && Auth::user()->role_id != 1)
												<form action="{{ route('delete-patient', ['id' => $record->id]) }}" method="post" id="delete_form_{{ $record->id }}" class="d-none">
													@csrf
													@method("DELETE")
												</form>

												<form action="{{ route('delete-patient-request', ['id' => $record->id]) }}" method="get" id="delete_request_form_{{ $record->id }}" class="d-none">
													@csrf
													@method("GET")
												</form>
												<a href="javascript:;" class="btn-danger btn btn-sm confirm_delete" role="button" data-toggle="tooltip" data-placement="top" title="Delete Patient" data-item-id="{{ $record->id }}" data-item-type="user" data-delete-request={{$record->delete_request == 1 ? 1 : 0}}><i class="fa fa-trash" aria-hidden="true"></i></a>
											@endif
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td class="text-warning text-center" colspan="6">No Data Found</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
				<div id="pagination-section"></div>
			</div>
		</div>
	</div>
</div>