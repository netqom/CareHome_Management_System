
<div class="">
	<div class="ibox">
		


		<div class="ibox-title d-flex align-items-center justify-content-between pr-3">
						<h5>Patient Incident list</h5>
						@if(!$patient->incidents->isEmpty())
						<div class="ibox-tool">
							<a href="{{ route('patients-download-incident-detail', $patient->id) }}" class="btn btn-outline btn-primary btn-rounded btn-sm">
				<i class="fa fa-download"></i> Download
			</a>
						</div>
						@endif
					</div>
	
		<div class="table-responsive">
			<table class="table table-hover no-margins" id="incident_list">
				<thead>
				<tr>
					<th>Id</th>
                    <th style="width: 300px">Title</th>
                    <th>Reported By</th>
                    <th>Date of Incident</th>
                    <th>Time of Incident</th>
                    <th>Action</th>
				</tr>
				</thead>
				<tbody>
					@forelse($patient->incidents as $key => $incident)
						<tr>
							<td>{{ $key + 1 }}</td>
							<td>
							@if(strlen($incident->title) > 70)
								{{ substr($incident->title, 0, 70) }}...
							@else
								{{ $incident->title }}
							@endif
						</td>
							<td>@if(strlen($incident->added_by->name) > 30)
                    {{ substr($incident->added_by->name, 0, 30) }}...
                @else
                    {{ $incident->added_by->name }}
                @endif</td>
						
							<td>{{ date('m-d-Y',strtotime($incident->created_at)) }}</td>
							<td>{{ date('H:i A',strtotime($incident->created_at)) }}</td>
                            <td><a data-toggle="tooltip" data-placement="top" title="View Patient Incident" href="{{ route('patients-show-incident-detail', $incident->id) }}" class="btn-primary btn btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
							
							
							
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