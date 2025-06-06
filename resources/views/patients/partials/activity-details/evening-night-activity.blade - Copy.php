@php $activity_form = getShiftLogField(3);
	 $shift_activity = $activity_form[0]->subchilds;
@endphp
<table class="table table-bordered">
	<tbody>
		<tr class="evening-night-title">
			<td class="font-weight-bold text-center text-white" colspan="2">{{ $activity_form[0]->name }} Activity Report</td>
		</tr>
		@foreach($shift_activity as $activity)
			<tr>
				<td class="evening-night-label font-weight-bold">{{ $activity->name }}</td>
				@if($activity->subchilds->count() > 0)
					<td class="evening-night-values">
						@foreach($activity->subchilds as $key => $activity_sub)
							{{ getLogFieldValue($log->id, $activity_sub->id) }}
							@if($key + 1 < $activity->subchilds->count())
								,
							@endif
						@endforeach
					</td>
				@else
					<td class="evening-night-values">
						{{ getLogFieldValue($log->id, $activity->id) }}
					</td>
				@endif
			</tr>
		@endforeach
		<tr class="border-none">
			<td class="border-none"></td>
			<td class="border-none d-flex justify-content-between">
				<p>Reporter: <span class="border-bottom border-dark border-3"> {{ $night_time != '' ? ($log->added_by ? $log->added_by->name : '-') : '' }}</span></p>
				<p>Time: <span class="border-bottom border-dark border-3">{{ $night_time }}</span></p>
			</td>
		</tr>
	</tbody>	
</table>
