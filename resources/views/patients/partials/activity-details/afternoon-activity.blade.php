@php 
	$activity_form = getShiftLogField(2);
	foreach ($activity_form[0]['subchilds'] as $key => $subchild) {
			if (strcasecmp($subchild['slug'], 'medication') === 0) {
				unset($activity_form[0]['subchilds'][$key]);
			}
		}
		
		
	 $shift_activity = $activity_form[0]->subchilds;
	 $log_created_by = \App\Models\PatientActivity::where(['log_id' => $log->id, 'shift_id' => 2])->first();
@endphp
@if($afternoon_time != '')
@php

$activityData = explode('__', $afternoon_time);
$outHpme = '';
//dd($activityData);
$afternoon_time = $activityData[0];
if(count($activityData) > 1){
	$outHpme = 'OutHome';
}
@endphp
	{{-- @if($log->is_in_house == 1) --}}
		@foreach($shift_activity as $activity)
		@if(isActivityEmpty(2, $log->id, $activity->id))
			<div>
				<h3 class="text-body text-navy fs-16 my-3">{{ $activity->name }}</h3>
				<div class="d-flex flex-wrap">
					@if($activity->subchilds->count() > 0)
						@foreach($activity->subchilds as $key => $activity_sub)
							{!! getLogFieldValue(2, $log->id, $activity_sub->id) !!}
						@endforeach
					@else
						{!! getLogFieldValue(2, $log->id, $activity->id) !!}
					@endif	
				</div>
			</div>
			@endif
		@endforeach
		<div class="row mb-3 py-3">
			@php 
				 $imgArr = $log->images->toArray();
				$imgData = array_filter($imgArr, function($item) {
					return $item['shift_id'] === 2;
				});
			@endphp
			@foreach($imgData as $key => $img)
				<div class="col-auto px-2 mb-3">
					<img src="{{$img['image_url']}}" width="100px" height="100px" alt="img"/>
				</div>
			@endforeach
		</div>
		@if(!empty($outHpme))
			<div>
	<p><strong>{{$outHpme}}</strong></p>
		<p><strong>Reason for out home:</strong> {{!empty($log->comment) ? $log->comment : 'N/A'}}</p>
		<p><strong>Expected Date/Time:</strong> @if(!empty($log->expected_return_date))
						{{date('m-d-Y',strtotime($log->expected_return_date))}} {{!empty($log->expected_return_time) ? $log->expected_return_time : ''}}
						@else
						N/A
						@endif
						</p>
		
	</div>
		@endif
		@if(!empty($log_created_by))
		<div class="d-flex justify-content-between align-items-center flex-wrap mb-5">
			<span class="text-muted">Reporter: {{ $afternoon_time != '' ? ($log_created_by->added_by ? $log_created_by->added_by->name : '-') : '' }}</span>
			<span class="text-muted">Reporting Time: {{ $afternoon_time }}</span>
		</div>
	@endif 
@else
	<div>
		<p>No Activity</p>
	</div>
@endif
