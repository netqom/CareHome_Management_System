<div class="">
	<div class="ibox">
		<div class="ibox-title d-flex pl-0">
			<h5>Appointment Remarks list</h5>
		</div>
		<div class="table-responsive">
			<table class="table table-hover no-margins" id="task_remark_list">
				<thead>
				<tr>
                    <th>#</th>
					<th>Appointment Mark Date </th>
					<th>Appointment Status</th>
                    <th>Appointment Marked By</th>
				</tr>
				</thead>
				<tbody>
					@forelse($patient_appointment->patient_appointment_remarks as $key => $appointment_remark)
                        @php
                            if ($appointment_remark->status == 1) {
                                $text_status = 'Visited';
                                $cls_name = 'text-info';
                            }else if($appointment_remark->status == 0){
                                $text_status = 'Not Visited';
                                $cls_name = 'text-danger';
                            }
                        @endphp
						<tr>
							<td>{{ $key + 1 }}</td>
							<td>{{ date('m-d-Y',strtotime( $appointment_remark->appointment_date)) }}</td>
                            <td style="max-width: 400px;">
                                <span class="d-block {{$cls_name}}">{{ $text_status }}</span>
                                @if($appointment_remark->status == 0)
                                    <p><strong>Reason: </strong> {{ $appointment_remark->remark }}</p>
                                @endif
                            </td>
                            <td>{{ $appointment_remark->added_by->name }}</td>
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