<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DocRyt</title>



	<style>
		/* 
Import the desired font from Google fonts. 
*/
		@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');

		body {
			margin: 0;
			padding: 1cm 2cm;
			color: #000;
			font-family: 'Montserrat', sans-serif;
			font-size:12px;

		}

		* {
			margin: 0px;
			padding: 0px;
			box-sizing: border-box;
		}

		.d-flex {
			display: flex;
		}

		.d-flex>* {
			display: inline-block;
		}

		.justify-content-center {
			justify-content: center;
		}

		.justify-content-between {
			justify-content: space-between;
		}

		.align-items-center {
			align-items: center;
		}

		.align-items-start {
			align-items: flex-start;
		}

		.d-block {
			display: block;
		}

		.text-center {
			text-align: center;
		}

		.green {
			color: #0097b2;
		}

		.text-color {
			color: #000;
		}

		.w-33 {
			width: 33%;
		}

		.w-50 {
			width: 50%;
		}

		.fs-14 {
			font-size: 14px;
		}

		.w-100 {
			width: 100%;
		}

		.mb-2 {
			margin-bottom: 10px;
		}

		.border-bottom {
			border-bottom: 1px solid #000;
		}

		.border-bottom-white {
			border-bottom: 1px solid #fff;
		}

		.pb-2 {
			padding-bottom: 10px;
		}

		.pb-4 {
			padding-bottom: 20px;
		}

		.pt-1 {
			padding-top: 5px;
		}

		h4 {
			font-size: 12px;
		}

		h6 {
			font-size: 10px;
			font-weight: 300;
		}

		h2 {
			font-size: 18px;
		}

		h3 {
			font-size: 14px;
		}
		td, th{font-size: 4px;}

		.px-3 {
			padding-left: 1.5%;
			padding-right: 1.5%;
		}

		.px-2 {
			padding-left: 10px;
			padding-right: 10px;
		}

		.pt-2 {
			padding-top: 10px;
		}

		.pt-3 {
			padding-top: 15px;
		}

		.pb-2 {
			padding-bottom: 10px;
		}

		.mt-3 {
			margin-top: 15px;
		}

		.pb-3 {
			padding-bottom: 15px;
		}

		.pb-4 {
			padding-bottom: 20px;
		}

		.pb-5 {
			padding-bottom: 25px;
		}

		.pt-5 {
			padding-top: 25px;
		}

		.mb-3 {
			margin-bottom: 15px;
		}

		.border-top {
			border-top: 1px solid #0097b2;
		}

		.border-right {
			border-right: 1px solid #0097b2;
		}

		.text-left {
			text-align: left;
		}

		.px-1 {
			padding-left: 5px;
			padding-right: 5px;
		}

		.w-15 {
			width: 15%;
		}

		.w-20 {
			width: 20%;
		}

		.w-10 {
			width: 10%;
		}

		.w-5 {
			width: 5%;
		}

		.h-30px {
			height: 14px;
		}

		/**.w-30px {width: 30px;}**/
		.w-40 {
			width: 40%;
		}

		.w-30 {
			width: 30%;
		}

		.w-50 {
			width: 50%;
		}

		.w-67 {
			width: 67%;
		}

		table.w-100 {
			border: 1px solid #aaa;
		}

		thead {
			background: #0097b2;
			height: 40px;
			color: #fff;
		}

		thead th {
			padding: 3px 10px;
		}

		td.border {
			border-top: 1px solid #aaa;
			border-left: 1px solid #aaa !important;
		}

		.w-120 {
			min-width: 120px;
		}

		.h-50px {
			height: 50px;
		}

		.green-bg {
			background: #0097b2;
		}

		.py-2 {
			padding-top: 10px;
			padding-bottom: 10px;
		}

		.px-2 {
			padding-left: 10px;
			padding-right: 10px;
		}

		.py-1 {
			padding-top: 5px;
			padding-bottom: 5px;
		}

		.px-1 {
			padding-left: 5px;
			padding-right: 5px;
		}

		.white {
			color: #fff;
		}

		.border-right-white {
			border-right: 1px solid #fff;
		}

		.border-right-green {
			border-right: 1px solid #0097b2;
		}

		.border-bottom-2 {
			border-bottom: 2px solid #0097b2;
		}

		.h-200 {
			height: 200px;
		}

		.h-100px {
			height: 100px;
		}

		.h-95px {
			height: 95px;
		}

		.pb-0 {
			padding-bottom: 0px;
		}

		.me-2 {
			margin-right: 10px;
		}

		.w-12 {
			width: 12%;
		}

		@media screen and (max-width: 1400px) {


			thead th,
			td {
				padding: 3px;
				font-weight: 400;
				font-size: 11px;
			}

			h2 {
				font-size: 24px;
			}

			h4 {
				font-size: 12px;
			}

		}
	</style>
</head>

<body style="padding-top:15px !impotant; padding-left: 15px; padding-right: 15px;">

	<header style="padding-top:15px; width: 100%;">
		<div class="headerSection">

			<div class="from-div px-3" style="width:100%; display: inline-block; float:left; margin-bottom:30px !important;">
				<h2 class="green text-center" style="font-size: 16px; text-align:center; margin-top:5px; margin-bottom:0px;">Medication Record</h2>
			</div>
			
			<div class="" style="display:block; width: 100%; float:left; margin-top:30px;margin-bottom:15px;">

				<div class="from-div px-3" style="width:49%; display: inline-block; vertical-align:top float:left; margin-top:0px;">
					<div class="From w-100">
						<h4 class="green text-right" style="font-size: 12px; text-align:left; margin-top:0px; margin-bottom:2px">Facility</h4>
						<p class="w-100" style="margin-bottom: 5px;">
							<span style="font-size: 12px;text-align: center;">{{$patient->care_home->name}}</span>
						</p>
						<p style="text-align: left; font-size: 10px; font-weight:500; margin: 0;">
							{{$patient->care_home->street}}
						</p>
					</div>
				</div>

				<div class="from-div px-3" style="width:49%; display: inline-block; float:right; margin-top:20px;text-align: right;">
					<span class="From text-right" style="text-align:right;">
						{{-- <h4 class="" style="display:inline-block; vertical-align: middle;">From</h4> --}}
						<p class="" style="display: inline-block; vertical-align: middle; margin-left:5px;">
							<span class="written-space text-center green" style="display: inline-block; vertical-align: middle; font-size:12px; font-weight:600;">From</span>
							<span class="written-space text-center" style="display: inline-block; vertical-align: middle; font-size:12px; font-weight:300;">{{$month}} /</span> <span class="written-space text-center" style="display: inline-block; vertical-align: middle; font-size:12px; font-weight:300;">01</span>
							<span class="written-space text-center" style="display: inline-block; vertical-align: middle; font-size:12px; font-weight:300;"> / {{$year}}</span>
						</p>
					</span>
					<span class="From text-right" style="text-align:right;">
						{{-- <h4 class="" style="display:inline-block; vertical-align: middle;">To</h4> --}}
						<p class="" style="display: inline-block; vertical-align: middle; margin-left:0;">
							<span class="written-space text-center green" style="display: inline-block; vertical-align: middle; font-size:12px; font-weight:600;">To</span>
							<span class="written-space text-center" style="display: inline-block; vertical-align: middle; font-size:12px; font-weight:300;">{{$month}} /</span> <span class="written-space text-center" style="display: inline-block; vertical-align: middle; font-size:12px; font-weight:300;">{{$lastdate}}</span>
							<span class="written-space text-center" style="display: inline-block; vertical-align: middle; font-size:12px; font-weight:300;"> / {{$year}}</span>
						</p>
					</span>
				</div>

			</div>

			<div class="headings-div w-100 border-top"
				style="display:inline-block; clear: both; margin-top:0px !important;">

				<div class="border-right pt-2 text-left px-1 pb-4"
					style="display:inline-block; width: 15%; height:25px; vertical-align: top;">
					<h6 class="green">Patient's Name</h6>
					<b class="" style="font-size:10px !important; word-wrap:break-word; max-width: 120px;">{{$patient->name}}</b>
				</div>

				<div class="border-right pt-2 text-left px-1 pb-4"
					style="display:inline-block; width: 13%; height:25px; vertical-align: top;">
					<h6 class="green">Pharmacy I.D Code</h6>
					{{-- <b class="">{{$patient->care_home->name}}</b> --}}
				</div>

				<div class="border-right pt-2 text-left px-1 pb-4"
					style="display:inline-block; width:5%; height:25px; vertical-align: top;">
					<h6 class="green">Sex</h6>
					<b class="" style="font-size:10px !important">{{$patient->gender}}</b>
				</div>

				{{-- <div class="border-right pt-2 text-left px-1 pb-4"
					style="display:inline-block; width: 10%; margin-top: 12px;">
					<h6 class="green">Birth Date</h6>
				</div> --}}

				<div class="border-right pt-2 text-left px-1 pb-4"
					style="display:inline-block; width: 9%; height:25px; vertical-align: top;">
					<h6 class="green">Height</h6>
					<b class="" style="font-size:10px !important">
						<?php
						if ($patient->height_in_feet != null && $patient->height_in_feet != 'Feet') {
							echo $patient->height_in_feet . " feet ";
						}
						if ($patient->height_in_inch && $patient->height_in_inch != 'Inch') {
							echo $patient->height_in_inch . ' inch';
						}
						?>
					</b>
				</div>

				<div class="border-right pt-2 text-left px-1 w-10 pb-4"
					style="display:inline-block; width: 13%; height:25px; vertical-align: top;">
					<h6 class="green">Weight (pounds)</h6>
					<b class="" style="font-size:10px !important">{{$patient->weight}}</b>
				</div>

				<div class="border-right pt-2 text-left px-1 pb-4"
					style="display:inline-block; width: 17%; height:25px; vertical-align: top;">
					<h6 class="green">Patient I.D Number</h6>
					<b class="" style="font-size:10px !important">{{$patient->id}}</b>
				</div>

				<div class="pt-2 text-left px-1  pb-4"
					style="display:inline-block; width: 13%; height:25px; vertical-align: top;">
					<h6 class="green">Admission Date</h6>
					<b style="font-size:10px !important">{{ date('d M, Y', strtotime($patient->admission_date)) }}</b>
				</div>

			</div>
		</div>
	</header>

	<div class="table-div w-100 text-left">
		

		<h3 style="text-align: center; padding-top:5px; padding-bottom:5px; font-size: 14px;">Additional Medical Administration Record</h3> 

		<table class="w-100"  style="padding-bottom:40px;">

			<thead>
				<tr>
					<th class="w-120">Medication Orders</th>
					<th>Hours</th>
					@for($i = 1; $i <= $totalDays; $i++)
						<th class="text-center">{{$i}}</th>
					@endfor
				</tr>
			</thead>
			@if($isBlankReport == 0)
			<tbody>
				@if(!empty($adHocresult))
						@foreach ($adHocresult['data'] as $key => $item)

								@php ksort($item); @endphp
								@foreach ($item as $shift => $date)

										<tr style="border-bottom:2px solid #000; !important">
											@if ($loop->first)
												{{-- <td rowspan="{{$loop->count}}" > --}}
												<td style="font-size:10px; min-width: 120px; max-width:120px; word-wrap:break-word;">
												@php
													$dose='';
														$dose_type='';
														if (strpos($adHocresult['med'][$key]['dose'], '__') !== false) {
																		
															$doseData = explode('__', $adHocresult['med'][$key]['dose']);
															$dose1 = $doseData[0];
															$dose_type = $doseData[1];
															$dose=$dose1.' '.$dose_type;
															if($dose_type=='other')
															{
																$dose =$adHocresult['med'][$key]['other_dose'];
															}
														} else {
															$dose = $adHocresult['med'][$key]['dose'];
															$dose_type = ''; 
														}
														
												@endphp
													{{ $adHocresult['med'][$key]['name'] }} ({{$dose}}) <br />
													Instructions: {{ !empty($adHocresult['med'][$key]['instructions']) ? $adHocresult['med'][$key]['instructions'] : 'N/A'}}
													{{-- {{ $adHocresult['med'][$key]['intake_method'] }} --}}
												</td>
												@else
												<td style="font-size:10px; min-width: 120px; max-width:120px; word-wrap:break-word;"></td>
											@endif
										
											<td>
												PRN 
											
											</td>
											@php
												$discontinue_date = !empty($adHocresult['med'][$key]['discontinue_date'])
													? (int)date('d', strtotime($adHocresult['med'][$key]['discontinue_date']))
													: null;
												$discontinue_shown = false; // To track if "Discontinued" has already been shown
												$medicineGivenToday = false; // Initialize flag to track if medicine was given today
											@endphp
											@for ($i = 1; $i <= $totalDays; $i++)
												@php
													$formattedName ='';
													$field_val = '';
													if (isset($date[$i])) {
														if ($date[$i]['field_value_name'] == 'Missed') {
															$field_val = 'M';
														} elseif ($date[$i]['field_value_name'] == 'None') {
															$field_val = 'N';
														} elseif ($date[$i]['field_value_name'] == 'Took') {
															$field_val = 'T';
														} elseif ($date[$i]['field_value_name'] == 'Declined') {
															$field_val = 'D';
														} elseif ($date[$i]['field_value_name'] == 'Vomitted') {
															$field_val = 'V';
														}



														// Get the 'added_by' value for the current index
														$addedBy = explode(' ', $date[$i]['update_by'] ?? '');
														if (count($addedBy) >= 0) {
															$firstNameInitial = substr($addedBy[0], 0, 1);
															if(isset($addedBy[1])){
																$lastName = substr($addedBy[1], 0, 1);
															}else{
																$lastName = '';
															}
															

															// Combine them
															$formattedName = ucfirst($firstNameInitial) . ucfirst($lastName);
														}

														// Mark medicine as given today if any status is present
														$medicineGivenToday = true;

													}
													else {
													 	$field_val = '';
														$formattedName='';
													}
													
													// Check if today is the discontinue date
													$isDiscontinuedToday = ($discontinue_date !== null && $discontinue_date === $i);
													// Check if tomorrow should show "DC" based on the discontinue date
													$isDiscontinuedTomorrow = ($discontinue_date !== null && $discontinue_date === $i + 1);
												@endphp
												{{-- @if (($discontinue_date != null && $i < $discontinue_date) || $discontinue_date == null)
													<td class="border w-30px text-center" style="writing-mode: vertical-lr;">
													@if(!empty($field_val) && $field_val!="")
															<span style="color: blue;">{{ $formattedName }}</span>
														@endif
													</td>
												@elseif(!$discontinue_shown)
													<td class="border w-30px text-center" colspan="{{ $totalDays - $i + 1 }}"
														style="background-color:#ccc">
														<p
															style="font-weight: bold; color:#000; padding-bottom: 1px; font-size:8px;">
															Discontinued</p>
													</td>
													@php $discontinue_shown = true; @endphp
													@break
												@endif --}}
													
												
												@if ($medicineGivenToday && !$discontinue_shown)
													<td class="border w-30px text-center" style="writing-mode: vertical-lr; padding:1px;">
														@if(!empty($field_val) && $field_val!="")
															<span style="color: blue; font-size:8px;">{{ $formattedName }}</span>
														@endif
													</td>
													@php $discontinue_shown = true; @endphp
												@elseif ($discontinue_shown && $discontinue_date != null)
													<td class="border w-30px text-center" colspan="{{ $totalDays - $i + 1 }}"
														style="background-color:#ccc; padding:1px;">
														<p style="font-weight: bold; color:#000; padding-bottom: 1px; font-size:8px;">DC </p>
													</td>
													@php $discontinue_shown = true; @endphp
													@break
												@else
													<td class="border w-30px text-center" style="writing-mode: vertical-lr; padding:1px;">
														@if(!empty($field_val) && $field_val!="")
															<span style="color: blue; font-size:8px;">{{ $formattedName }} </span>
														@endif
													</td>
												@endif
											@endfor

										</tr>

								@endforeach
								<tr><td colspan="32" style="border-bottom:1px  solid #18a689;"></td></tr>
						@endforeach
				@endif

			</tbody>
			@else
			<tbody>
				@if(!empty($adhocAllMedicine))
						@foreach ($adhocAllMedicine as $key => $item)

								{{-- @php ksort($item); @endphp
								@foreach ($item as $shift => $date) --}}

										<tr style="border-bottom:2px solid #000; !important">
										@php
												$dose='';
												$dose_type='';
												if (strpos($item->dose, '__') !== false) {
																
													$doseData = explode('__', $item->dose);
													$dose1 = $doseData[0];
													$dose_type = $doseData[1];
													$dose=$dose1.' '.$dose_type;
													if($dose_type=='other')
													{
														$dose =$item->other_dose;
													}
												} else {
													$dose = $item->dose;
													$dose_type = ''; 
												}
												
										@endphp
												<td style="font-size:10px; min-width: 120px; max-width:120px; word-wrap:break-word;">
													{{ $item->name }} ({{ $dose }}) <br />
													Instructions: {{ !empty($item->instructions) ? $item->instructions : 'N/A'}}
													{{-- {{ $adHocresult['med'][$key]['intake_method'] }} --}}
												</td>
												
											<td>
												
											
											</td>
											@for ($i = 1; $i <= $totalDays; $i++)
													@php
														if (isset($date[$i])) {
															if ($date[$i]['field_value_name'] == 'Missed') {
																$field_val = 'M';
															} elseif ($date[$i]['field_value_name'] == 'None') {
																$field_val = 'N';
															} elseif ($date[$i]['field_value_name'] == 'Took') {
																$field_val = 'T';
															} elseif ($date[$i]['field_value_name'] == 'Declined') {
																$field_val = 'D';
															} elseif ($date[$i]['field_value_name'] == 'Vomitted') {
																$field_val = 'V';
															}

														} else {
															$field_val = '';
														}
													@endphp
													<td class="border w-30px  text-center" style="writing-mode: vertical-lr;">
														{{-- <p style="font-weight: bold; color:#18a689; padding-bottom: 10px;">{{ $field_val }}</p> --}}

														@if(!empty($field_val))

														@php
															// Get the 'added_by' value for the current index
															$addedBy = explode(' ',$date[$i]['added_by']);
															$firstNameInitial = substr($addedBy[0], 0, 1);
															$lastName = substr($addedBy[1], 0, 1);

															// Combine them
															$formattedName = ucfirst($firstNameInitial).ucfirst($lastName);
														@endphp
														<span style="color: blue; font-size:9px;"></span>
													{{-- 	<span style="color: #0c479f;">{{date('h:i',strtotime($date[$i]['ad_hoc_time']))}}</span> --}}
														

														@endif

													</td>
											@endfor

										</tr>

								{{-- @endforeach --}}
								<tr><td colspan="33" style="border-bottom:1px  solid #18a689;"></td></tr>
						@endforeach
				@endif
			</tbody>
			@endif

		</table>

		

	</div>


	


	<div class="note pt-5 pb-5 d-flex w-100 green px-3">
		<h4 class="text-color" style="vertical-align: middle;">Note</h4> <strong style="width: 90%; vertical-align:middle;" >	<span class="px-3 fs-14" style="padding-left: 7px; padding-right: 7px; color: blue;">XX=On duty staff initial</span><span class="px-3 fs-14" style="padding-left: 7px; padding-right: 7px; color: blue;">DC=Discontinued</span></strong>
	</div>







</body>

</html>