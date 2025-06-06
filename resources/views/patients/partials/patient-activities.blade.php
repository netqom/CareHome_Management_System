<div class="">
	<div class="ibox">
		<div class="ibox-title d-flex pl-0">
			<h5>Patient Activity List</h5>
			@if(Auth::user()->role_id == 2 && $patient->discharged != 1 && $patient->deleted_at == NULL)
				
			 <div class="ibox-tools">
					<a href="{{ route('get-daily-activity-form', ['patient_id' => $patient->id,'shift_id'=>1]) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Daily Activity </a>
				</div> 
			@endif
		</div>
		<div class="">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
						<tr>
							<th>#</th>
							<th>Reported By</th>
							<th>Date of Report</th>
							<th>Time of Report</th>
							<th>Action</th>
						</tr>
                    </thead>
                    <tbody>
						@forelse($logs as $key => $log)
							<tr>
								<td>{{ $key + 1 }}</td>
								{{-- <td>{{ $log->patient ? $log->patient->name : '-' }}</td> --}}
                                <td>{{ $log->added_by ? $log->added_by->name : '-' }}</td>
								<td>{{ date('m-d-Y', strtotime($log->report_date)) }}</td>
								<td>{{ date('h:i a', strtotime($log->updated_at))}} </td>
								<td><a data-toggle="tooltip" data-placement="top"  href="{{ route('patients-show-activity-detail', ['patient_id' => $patient->id, 'id' => $log->id]) }}" class="btn-primary btn btn-sm">View Detail</a></td>
							</tr>
						@empty
							<tr><td class="text-center text-info" colspan="5">No Data Found</td></tr>
						@endforelse
                    </tbody>
                </table>
            </div>

        </div>
	</div>
</div>