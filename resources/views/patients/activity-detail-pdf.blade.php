<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Activity</title>
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
		body, *{font-family: "Open Sans", sans-serif;}
		body{margin:0px !important;}
		p{margin-top:0px !important; margin-bottom:0px !important;}
	.green {color: #0097b2;}
	.border-top {border-top: 1px solid #0097b2;}
	.border-right {border-right: 1px solid #0097b2;}
	.headings-div > .border-right {min-height: 40px;}
	h4 {font-size: 12px;}
	h6 {font-size: 10px; font-weight: 300; margin-top: 5px; margin-bottom: 5px;}
		.border-3 {border-width:3px !important;}
		.w-100{width: 100%;}
		.table {
		  font-size: 11px;
		  border-collapse: collapse;
		  font-family: "Open Sans", sans-serif;

		}
		.acti-status {display: inline-block; width: 30%; }
		.remark {width: 55%; display: inline-block; vertical-align: middle; padding-left: 5%; box-sizing: border-box; border-left: 1px solid #ddd;margin-top: 0px;padding-top: 20px !important;}
		/*.table tr,.table td {
		   height: 10px;
		}*/

		.table>tbody>tr>td, 
		.table>tbody>tr>th, 
		.table>tfoot>tr>td, 
		.table>tfoot>tr>th, 
		.table>thead>tr>td, 
		.table>thead>tr>th
		{
		  padding:0px 6px; 
		} 
		.font-weight-bold,
		.table>tbody>tr>th, 
		.table>tfoot>tr>th, 
		.table>thead>tr>th {
			font-weight: 600;
		}
		tr.morning-title {
			text-align: center;
			color: #fff;
			font-weight: 600;
			min-height:30px;
		}

		tbody tr:nth-child(2) .afternoon-values  > div, tbody tr:nth-child(2) .breakfast-values  > div ,tbody tr:nth-child(2) .evening-night-values  > div {margin-top: 3px !important;}
		tr.afternoon-title {
			text-align: center;
			color: #fff;
			font-weight: 600;
		}
		tr.evening-night-title {
			text-align: center;
			color: #fff;
			font-weight: 600;
		}

		table.table-bordered{
			border: 1px solid #ddd;
			margin-top:20px;
		  }
		table.table-bordered > thead > tr > th{
			border: 1px solid #ddd;
		}
		table.table-bordered > tbody > tr > td{
			border: 1px solid #ddd;
    		margin-left: 12px !important;
		}
		.w-200 {width: 200px;}
		.w-458 {width: 458px;}

		.morning-title {
			background-color: #046070 !important;
		}
		.morning-label {
			background-color: #c0d5a0 !important; 
		}

		.morning-values {
			background-color: #f4ffef !important;
		}

		.afternoon-title {
			background-color:rgba(0, 151, 178, 1) !important;
		}
		/*.afternoon-label {
			background-color: #9bb8dd !important;
		}

		.afternoon-values {
			background-color: #e5f0ff !important;
		}**/

		.evening-night-title {
			background-color:rgba(0, 151, 178, 0.8) !important;
		}

		.night-title{
			background-color:rgba(0, 151, 178, 0.6) !important;
		}
		/** .evening-night-label {
			background-color: #fff5bf !important;
		}

		.evening-night-values {
			background-color: #fff5bf2e !important;
		}**/

		.ad-hoc-title{background:#1ab394 !important;}
		.border-none{border:none !important;}
		table.table-bordered tr.border-none {
    border: 0px solid #fff !important;
}
		.text-center {text-align: center;}
		.text-white {color: #fff;}
		.d-flex {
			display: flex;
			flex-wrap: wrap;
		}
		.flex-column {
			flex-direction: column;
		}
	.acti-item {		
			margin-bottom: 0px;
			padding: 0px 10px;
			border-right: 1px solid #d1d1d1;
			margin-right: 5px;
			gap: 3px;
			display: inline-block;
			text-align: center;
			box-sizing: border-box;
			vertical-align:middle;
			
		}
		span{margin:0px !important;}
		.acti-item strong {
				align-self: start;
		}
		.font-bold {
			margin-bottom: 3px;
		}

		.acti-item img {
			max-width: 10px;
			margin-right: 5px;
		}
		.acti-item.remark {
			border: 0;
			text-align: left;
		}
		.acti-item img {max-height: 13px;}
		.single-line-value .acti-item {
			flex-direction: column;
			align-items: start;
			margin-bottom:2px;
			gap: 4px;
			border-top: 0;
			border-right: 0;
			border-left: 0;
			border-bottom: 1px dashed #b9b9b9;
			padding-bottom: 2px;
			text-align: left;
			display: block;
			width: 100%;
		}
		.single-line-value .acti-item img {
			margin-top: 0 !important;
		}
		.mr-1 {
    margin-right: 5px;
}
	</style>
  </head>
<body>
@php 
	//$report = isset($log->report) && !is_null($log->report) ? json_decode($log->report, true) : '';
	//$report = $report != '' ? json_decode($report, true) : [];
	//$morning_activity   = isset($report['morning']) ? $report['morning'] : [];
	//$afternoon_activity = isset($report['afternoon']) ? $report['afternoon'] : [];
	//$night_activity     = isset($report['night']) ? $report['night'] : [];
	 //Morning Data
	 $morning_form = getShiftLogField(1);
	 $msubchilds = $morning_form[0]->subchilds;

		$morning_activity = $msubchilds->filter(function($msubchilds) {
			return strcasecmp($msubchilds['slug'], 'medication') !== 0;
		});
	// $morning_activity = $morning_form[0]->subchilds;
	 //Afternoon Data
	 $afternoon_form = getShiftLogField(2);
	 $asubchilds = $afternoon_form[0]->subchilds;

		$afternoon_activity = $asubchilds->filter(function($asubchilds) {
			return strcasecmp($asubchilds['slug'], 'medication') !== 0;
		});
	 //Evening Data
	 $evening_form = getShiftLogField(3);
	 $esubchilds = $evening_form[0]->subchilds;

	$evening_activity = $esubchilds->filter(function($esubchilds) {
		return strcasecmp($esubchilds['slug'], 'medication') !== 0;
	});
	 $night_form = getShiftLogField(4);
	 $nsubchilds = $night_form[0]->subchilds;

	$night_activity = $nsubchilds->filter(function($nsubchilds) {
		return strcasecmp($nsubchilds['slug'], 'medication') !== 0;
	});
	
	$times  = json_decode($log->report_time, true);
	$morning_time       = isset($times['1']) ? $times['1'] : '';
	$afternoon_time     = isset($times['2']) ? $times['2'] : '';
	$evening_time         = isset($times['3']) ? $times['3'] : '';
	$night_time         = isset($times['4']) ? $times['4'] : '';
	$adhocData=getAdHocShiftLog($log->id);
@endphp

<div class="card p-5" style="width: 100%; margin: 0 auto; margin-bottom:0px; overflow:hidden;">
<header style="padding-top:0px; width: 100%;">
		<div class="headerSection">

		<div class="from-div px-3" style="width:100%; display: inline-block;">
					<h2 class="green text-right" style="font-size: 16px; text-align:center; margin-top:5px; margin-bottom:0px;">Daily Activity Report</h2>
		</div>

			<div class="" style="display:block; width: 100%;">

				<div class="from-div px-3" style="width:49%; display:inline-block; vertical-align:top">
				<div class="From  w-100 d-flex">
				<h2 class="green text-right" style="font-size: 12px; text-align:left; margin-top:0px; margin-bottom:5px;">
				<span>{{$log->patient->care_home->name}}</span>
				</h2>
				<h4 class="" style="color:#000; display: inline-block; vertical-align: middle; width:100%; margin-top:0px; margin-bottom:0px; font-size:10px; font-weight:300;">{{$log->patient->care_home->street}}</h4>
				</div>

					
					
				</div>

				

				<div class="from-div px-3" style="width:49%; display: inline-block;">
					<h2 class="green text-right" style="font-size: 12px; text-align:right; margin-top:5px; margin-bottom:5px;">Patient Name: {{$log->patient->name}}</h2>
					<div class="From green w-100 text-right" style="text-align:right;">

						<h4 class="" style="display: inline-block; vertical-align: middle; width:100%; margin-top:0px; margin-bottom:0px; color:#000; font-size:10px; font-weight:300;">
							<span style="font-size:10px; font-weight:300;">Report Date: </span>
							<span class="written-space" style="display: inline-block; vertical-align: middle; font-size:10px; font-weight:300;">{{date('m',strtotime($log->report_date))}} /</span> <span class="written-space" style="display: inline-block; vertical-align: middle; font-size:10px; font-weight:300;">{{date('d',strtotime($log->report_date))}}</span> <span class="written-space text-center" style="display: inline-block; vertical-align: middle; font-size:10px; font-weight:300;"> / {{date('Y',strtotime($log->report_date))}}</span>
						</h4>
					</div>

				</div>

			</div>

			
		</div>
	</header>
</div>

<div class="table-responsive-sm">
	<div class="card p-5" style="width: 100%; margin: 0 auto;">
		
		<!-- Morning activity Start--->
		@if($morning_time)
		@php

		$activityData = explode('__', $morning_time);
		$outHpme = '';
		//dd($activityData);
		$morning_time = $activityData[0];
		if(count($activityData) > 1){
			$outHpme = 'OutHome';
		}
		@endphp
		<table class="table table-bordered" cellspacing="0" cellpadding="5" style="margin-bottom: 10px; width: 100%; margin-top:-10px;">
			<tbody>
				<tr class="morning-title">
					<td colspan="3" ><strong style="line-height:1; font-size:12px;">{{ $morning_form[0]->name }} Activity Report</strong></td>
				</tr>
				@if(!empty($outHpme))
				<tr>
					<td colspan="3" class="evening-night-label font-weight-bold">
						
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
					</td>
				</tr>
				@endif
				@foreach($morning_activity as $activity)
				@if(isActivityEmpty(1, $log->id, $activity->id))
					<tr>
						<td class="breakfast-label font-weight-bold">{{ $activity->name }}</td>
						@if($activity->subchilds->count() > 0)
							<td class="breakfast-values">
								
								@php 
								$cls = $activity->slug == 'medication' ? 'single-line-value' : '';
								@endphp
								<div class="{{$cls}}">
									@foreach($activity->subchilds as $key => $activity_sub)
										{!! getLogFieldValue(1, $log->id, $activity_sub->id) !!}
										@if($key + 1 < $activity->subchilds->count())
											
										@endif
									@endforeach
								</div>
								
							</td>
						@else
							<td class="breakfast-values">
								
								@php 
								$cls = $activity->name == 'Activity'? 'single-line-value' : '';
								@endphp
								<div class="{{$cls}}">
								{!! getLogFieldValue(1, $log->id, $activity->id) !!}
								</div>
								
							</td>
						@endif
					</tr>
					@endif
				@endforeach
				{{-- @php 
					$imgArr = $log->images->toArray();
					$imgData = array_filter($imgArr, function($item) {
						return $item['shift_id'] === 1;
					});
				@endphp
				@if(!empty($imgData))
				<tr>
					<td colspan="2">
						<ul style="margin: 0px; padding:00px 0 0;list-style: none;">
							
							
							@foreach($imgData as $key => $img)
								<li style="display: inline-block; margin: 0 10px 0px 0;">
									<img src="{{$img['image_url']}}" width="100px" height="100px" alt="img"/>
								</li>
							@endforeach
						</ul>
					</td>
				</tr>
				@endif --}}
				@php 
				if($morning_time != ''){
					$log_created = \App\Models\PatientActivity::where(['log_id' => $log->id, 'shift_id' => 1])->first();
				}
			@endphp
			@if(!empty($log_created))
				<tr class="border-none">
					<td class="border-none" style="width: 200px;"></td>
					<td class="border-none d-flex justify-content-between" style="padding: 0;width: 458px; height: auto;">
					
						<p style="display:inline-block"><strong>Reporter:</strong> <span style="border-bottom: 1px solid #000;padding: 0 15px 2px 15px;"> {{ $morning_time != '' ? ($log_created->added_by ? \Illuminate\Support\Str::limit($log_created->added_by->name, 30) : '-') : '' }}</span></p>
						<p style="display:inline-block"><strong>Time:</strong> <span style="border-bottom: 1px solid #000;padding: 0 15px 2px 15px;">{{ $morning_time }}</span></p>
					</td>
				</tr>
				@endif
			</tbody>	
		</table>
		@endif
		<!-- Morning activity End--->
		<!-- Afternoon activity Start--->
		@if($afternoon_time)
		@php

		$activityData = explode('__', $afternoon_time);
		$outHpme = '';
		//dd($activityData);
		$afternoon_time = $activityData[0];
		if(count($activityData) > 1){
			$outHpme = 'OutHome';
		}
		@endphp
		<table class="table table-bordered" cellspacing="0" cellpadding="5" style="margin-bottom: 10px; width:100%;">
			<tbody>
				<tr class="afternoon-title">
					<td colspan="3" ><strong style="line-height:1; font-size: 12px;">{{ $afternoon_form[0]->name }} Activity Report</strong></td>
				</tr>
				@if(!empty($outHpme))
				<tr>
					<td colspan="3" class="evening-night-label font-weight-bold">
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
					</td>
				</tr>
				@endif
				@foreach($afternoon_activity as $activity)
				@if(isActivityEmpty(2, $log->id, $activity->id))
					<tr>
						<td class="afternoon-label font-weight-bold">{{ $activity->name }}</td>
						@if($activity->subchilds->count() > 0)
							<td class="afternoon-values">
								@php 
								$cls = $activity->slug == 'medication' || $activity->slug == 'Activity'? 'single-line-value' : 'd-flex';
								@endphp
								<div class="{{$cls}}">
									@foreach($activity->subchilds as $key => $activity_sub)
										{!! getLogFieldValue(2, $log->id, $activity_sub->id) !!}
										@if($key + 1 < $activity->subchilds->count())
											
										@endif
									@endforeach
								</div>
							</td>
						@else
							<td class="afternoon-values">
								@php 
								$cls = $activity->name == 'Activity'? 'single-line-value' : '';
								@endphp
								<div class="{{$cls}}">
									{!! getLogFieldValue(2, $log->id, $activity->id) !!}
								</div>
							</td>
						@endif
					</tr>
					@endif
				@endforeach
				{{-- @php 
					$imgArr = $log->images->toArray();
					$imgData = array_filter($imgArr, function($item) {
						return $item['shift_id'] === 2;
					});
				@endphp
				@if(!empty($imgData))
				<tr>
					<td colspan="2">
						<ul style="margin: 0px 0 0; padding: 0px 0 0;list-style: none;">
							
							@foreach($imgData as $key => $img)
								<li style="display: inline-block; margin: 0 5px 0px 0;">
									<img src="{{$img['image_url']}}" width="100px" height="100px" alt="img"/>
								</li>
							@endforeach
						</ul>
					</td>
				</tr>
				@endif --}}
				@php 
							if($afternoon_time != ''){
								$log_created = \App\Models\PatientActivity::where(['log_id' => $log->id, 'shift_id' => 2])->first();
							}
						@endphp
				@if(!empty($log_created))
				<tr class="border-none">
					<td class="border-none" style="width: 200px;"></td>
					<td class="border-none d-flex justify-content-between" style="padding: 0;width: 458px; height: auto;">
						
						<p style="display:inline-block"><strong>Reporter:</strong> <span style="border-bottom: 1px solid #000;padding: 0 15px 2px 15px;">{{ $afternoon_time != '' ? ($log_created->added_by ? \Illuminate\Support\Str::limit($log_created->added_by->name, 30) : '-') : '' }}</span></p>
						<p style="display:inline-block"><strong>Time:</strong> <span style="border-bottom: 1px solid #000;padding: 0 15px 2px 15px;">{{ $afternoon_time }}</span></p>
					</td>
				</tr>
				@endif
			</tbody>	
		</table>
		@endif
		<!-- Afternoon activity Start--->
		<!-- Evening activity Start--->
		@if($evening_time)
		@php

		$activityData = explode('__', $evening_time);
		$outHpme = '';
		//dd($activityData);
		$evening_time = $activityData[0];
		if(count($activityData) > 1){
			$outHpme = 'OutHome';
		}
		@endphp
		<table class="table table-bordered" cellspacing="0" cellpadding="5" style="margin-bottom: 10px; width:100%;">
			<tbody>
				<tr class="evening-night-title">
					<td colspan="3" ><strong style="line-height:1; font-size: 12px;">{{ $evening_form[0]->name }} Activity Report</strong></td>
				</tr>
				@if(!empty($outHpme))
				<tr>
					<td colspan="3" class="evening-night-label font-weight-bold">
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
					</td>
				</tr>
				@endif
				@foreach($evening_activity as $activity)
				@if(isActivityEmpty(3, $log->id, $activity->id))
					<tr>
						<td class="evening-night-label font-weight-bold">{{ $activity->name }}</td>
						@if($activity->subchilds->count() > 0)
							<td class="evening-night-values">
								@php 
								$cls = $activity->slug == 'medication' || $activity->slug == 'Activity' ? 'single-line-value' : '';
								@endphp
								<div class="{{$cls}}">
									@foreach($activity->subchilds as $key => $activity_sub)
										{!! getLogFieldValue(3, $log->id, $activity_sub->id) !!}
										@if($key + 1 < $activity->subchilds->count())
											
										@endif
									@endforeach
								</div>
							</td>
						@else
							<td class="evening-night-values">
								@php 
								$cls = $activity->name == 'Activity'? 'single-line-value' : '';
								@endphp
								<div class="{{$cls}}">
									{!! getLogFieldValue(3, $log->id, $activity->id) !!}
								</div>
							</td>
						@endif
					</tr>
					@endif
				@endforeach
				{{-- @php 
					$imgArr = $log->images->toArray();
					$imgData = array_filter($imgArr, function($item) {
						return $item['shift_id'] === 3;
					});
				@endphp
				@if(!empty($imgData))
				<tr>
					<td colspan="2">
						<ul style="margin: 0px 0 0; padding: 0px 0 0;list-style: none;">
							
							@foreach($imgData as $key => $img)
								<li style="display: inline-block; margin: 0 5px px 0;">
									<img src="{{$img['image_url']}}" width="100px" height="100px" alt="img"/>
								</li>
							@endforeach
						</ul>
					</td>
				</tr>
				@endif --}}
				@php 
							if($evening_time != ''){
								$log_created = \App\Models\PatientActivity::where(['log_id' => $log->id, 'shift_id' => 3])->first();
							}
						@endphp
				@if(!empty($log_created))
				<tr class="border-none">
					<td class="border-none" style="width: 200px;"></td>
					<td class="border-none d-flex justify-content-between" style="padding: 0;width: 458px; height: auto;">
						
						<p style="display:inline-block"><strong>Reporter:</strong> <span style="border-bottom: 1px solid #000;padding: 0 15px 2px 15px;"> {{ $evening_time != '' ? ($log_created->added_by ?  \Illuminate\Support\Str::limit($log_created->added_by->name, 30) : '-') : '' }}</span></p>
						<p style="display:inline-block"><strong>Time:</strong> <span style="border-bottom: 1px solid #000;padding: 0 15px 2px 15px;">{{ $evening_time  }}</span></p>
					</td>
				</tr>
				@endif
			</tbody>	
		</table>
		@endif
		<!-- Evening activity End--->
		<!-- Evening activity Start--->
		@if($night_time)
		@php

		$activityData = explode('__', $night_time);
		$outHpme = '';
		//dd($activityData);
		$night_time = $activityData[0];
		if(count($activityData) > 1){
			$outHpme = 'OutHome';
		}
		@endphp
		<table class="table table-bordered" cellspacing="0" cellpadding="5" style="margin-bottom: 10px; width: 100%;">
			<tbody>
				<tr class="evening-night-title night-title">
					<td colspan="3" ><strong style="line-height:1; font-size: 12px;">{{ $night_form[0]->name }} Activity Report</strong></td>
				</tr>
				@if(!empty($outHpme))
				<tr>
					<td colspan="3" class="evening-night-label font-weight-bold">
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
					</td>
				</tr>
				@endif
				@foreach($night_activity as $activity)
				@if(isActivityEmpty(4, $log->id, $activity->id))
					<tr>
						<td class="evening-night-label font-weight-bold">{{ $activity->name }}</td>
						@if($activity->subchilds->count() > 0)
							<td class="evening-night-values">
								@php 
								$cls = $activity->slug == 'medication' || $activity->slug == 'Activity' ? 'single-line-value' : '';
								@endphp
								<div class="{{$cls}}">
									@foreach($activity->subchilds as $key => $activity_sub)
										{!! getLogFieldValue(4, $log->id, $activity_sub->id) !!}
										@if($key + 1 < $activity->subchilds->count())
											
										@endif
									@endforeach
								</div>
							</td>
						@else
							<td class="evening-night-values">
								@php 
								$cls = $activity->name == 'Activity'? 'single-line-value' : '';
								@endphp
								<div class="{{$cls}}">
									{!! getLogFieldValue(4, $log->id, $activity->id) !!}
								</div>
							</td>
						@endif
					</tr>
					@endif
				@endforeach
				{{-- @php 
					$imgArr = $log->images->toArray();
					$imgData = array_filter($imgArr, function($item) {
						return $item['shift_id'] === 4;
					});
				@endphp
				@if(!empty($imgData))
				<tr>
					<td colspan="2">
						<ul style="margin: 0px 0 0; padding: 0px 0 0;list-style: none;">
							
							@foreach($imgData as $key => $img)
								<li style="display: inline-block; margin: 0 5px 0px 0;">
									<img src="{{$img['image_url']}}" width="100px" height="100px" alt="img"/>
								</li>
							@endforeach
						</ul>
					</td>
				</tr>
				@endif --}}
				@php 
				if($night_time != ''){
					$log_created = \App\Models\PatientActivity::where(['log_id' => $log->id, 'shift_id' => 4])->first();
				}
			@endphp
				@if(!empty($log_created))
				<tr class="border-none">
					<td class="border-none" style="width: 200px;"></td>
					<td class="border-none d-flex justify-content-between" style="padding: 0;width: 458px;height: auto;">
					
						<p style="display:inline-block"><strong>Reporter:</strong> <span style="border-bottom: 1px solid #000;padding: 0 15px 2px 15px;"> {{ $night_time != '' ? ($log_created->added_by ?  \Illuminate\Support\Str::limit($log_created->added_by->name, 30) : '-') : '' }}</span></p>
						<p style="display:inline-block"><strong>Time:</strong> <span style="border-bottom: 1px solid #000;padding: 0 15px 2px 15px;">{{ $night_time  }}</span></p>
					</td>
				</tr>
				@endif
			</tbody>	
		</table>
		@endif
		<!-- Evening activity End--->
		
		<!-- Adhoc activity start--->
		@if(!$adhocData->isEmpty())
		
		<table class="table table-bordered" cellspacing="0" cellpadding="5" style="margin-bottom: 10px; width: 100%;">
			<tbody>
				@foreach($adhocData as $data)
				<tr class="afternoon-title ad-hoc-title">
					<td colspan="2"><strong style="line-height:1; font-size: 12px;">Ad-hoc Activity Report</strong></td>
				</tr>
				<tr>
					<td colspan="2" class="afternoon-values">
						<p style="margin: 0 0 5px;"><strong>{{$data->title}}</strong></p>
						<p style="margin: 0 0 5px;">{{$data->comment}}</p>
					</td>
				</tr>
				
				@php 
                           
				$log_created_by = \App\Models\PatientActivity::where(['log_id' => $log->id, 'id' => $data->activity_id])->first();
			
				@endphp
				<tr>
					<td>
						<p style="margin: 0 0 5px"><strong>Time:</strong> <span style="border-bottom: 1px solid #000;padding: 0 15px 2px 15px;">{{date('h:i a',strtotime($log_created_by->created_at))}}</span></p>
					</td>
					<td><p style="margin: 0 0 5px"><strong>Posted By:</strong> <span style="border-bottom: 1px solid #000;padding: 0 15px 2px 15px;">{{ \Illuminate\Support\Str::limit($log_created_by->added_by->name, 30) }}</span></p>
					</td>
				</tr>
				@endforeach
				{{-- @php 
					$imgArr = $log->images->toArray();
					$imgData = array_filter($imgArr, function($item) {
						return $item['shift_id'] ===5;
					});
				@endphp
					@if(!empty($imgData))
				<tr>
					<td colspan="2">
						<ul style="margin: 0px 0 0; padding: 0px 0 0;list-style: none;">
							
							@foreach($imgData as $key => $img)
								<li style="display: inline-block; margin: 0 5px 0px 0;">
									<img src="{{$img['image_url']}}" width="100px" height="100px" alt="img"/>
								</li>
							@endforeach
						</ul>
					</td>
				</tr>
				@endif --}}
			</tbody>
		</table>
	
		@endif
		
		<!-- Adhoc activity End--->

		
		
	</div>
</div>
</body>
</html>