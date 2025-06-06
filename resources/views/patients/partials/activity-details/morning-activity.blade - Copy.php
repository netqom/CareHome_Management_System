@php $activity_form = getShiftLogField(1);
	 $shift_activity = $activity_form[0]->subchilds;
@endphp
<table class="table table-bordered" cellspacing="0" cellpadding="5">
	<tbody>
		<tr class="breakfast-title">
			<td class="font-weight-bold text-center text-white" colspan="2">{{ $activity_form[0]->name }} Activity Report</td>
		</tr>
		@foreach($shift_activity as $activity)
			<tr>
				<td class="breakfast-label font-weight-bold">{{ $activity->name }}</td>
				@if($activity->subchilds->count() > 0)
					<td class="breakfast-values">
						@foreach($activity->subchilds as $key => $activity_sub)
							{{ getLogFieldValue($log->id, $activity_sub->id) }}
							@if($key + 1 < $activity->subchilds->count())
								,
							@endif
						@endforeach
					</td>
				@else
					<td class="breakfast-values">
						{{ getLogFieldValue($log->id, $activity->id) }}
					</td>
				@endif
			</tr>
		@endforeach
		<tr class="border-none">
			<td class="border-none"></td>
			<td class="border-none d-flex justify-content-between">
				<p>Reporter: <span class="border-bottom border-dark border-3"> {{ $log->added_by ? $log->added_by->name : '-' }}</span></p>
				<p>Time: <span class="border-bottom border-dark border-3">{{ $morning_time != '' ? $morning_time : date('h:i:sa', strtotime($log->created_at)) }}</span></p>
			</td>
		</tr>
	</tbody>	
</table>