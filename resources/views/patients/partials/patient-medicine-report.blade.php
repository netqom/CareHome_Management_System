@php
	$medicine_type = config('const.medicine_type');
	$medicine_time = config('const.medicine_time');
	$intake_method = config('const.medicine_intake_method');
	$intake_guidedby = config('const.medicine_intake_supervised_by');
	$medicine_frequency = config('const.medicine_frequency');
    $medicine_time_other   = config('const.medicine_time_other');

@endphp

<div class="">
	<div class="ibox">
		<div class="ibox-title d-flex pl-0 align-items-center">
			<h5 class="mr-2 mb-0">Patient Medicine list</h5>
			@if(Auth::user()->role_id == 2 && $patient->discharged != 1 && $patient->deleted_at == NULL)
				@if($subscriptionPermission && $subscriptionPermission->allow_manage_med_report == 1)
					<form action="{{ route('download-patient-medication-report', $patient->id) }}" method="post" id="download_med_report_form" class="">
						@csrf
						<select class="mr-2" name="month" id="med-month">
							@for ($i = $selectedMonth; $i <= $endMonth; $i++)
								<option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>
									{{ date('F', mktime(0, 0, 0, $i, 1)) }}
								</option>
							@endfor
						</select>

						<select class="mr-2" name="year" id="med-year">
							@for ($year = $endYear; $year >= $startYear; $year--)
								<option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
									{{ $year }}
								</option>
							@endfor
						</select>
						<button class="btn btn-outline btn-primary btn-rounded btn-sm" id="download_med_report">
						<i class="fa fa-download" aria-hidden="true"></i> Download</button>
						<input type="hidden" name="blank_report" id="blank_report" value="0">
						<button class="btn btn-outline btn-primary btn-rounded btn-sm" id="download_med_report_blank">
							<i class="fa fa-download" aria-hidden="true"></i> Blank medical report</button>
					</form>
				@endif
			<div class="ibox-tools">
				<a href="{{ route('update-patient-medicine-report', ['patient_id' => $patient->id, 'shift_id' => 1]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> Update Medicine Report </a>

				<a href="{{ route('add-patient-medicine-report', ['patient_id' => $patient->id, 'shift_id' => 1]) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Medicine Report </a>
			</div>
			
			@endif

			 
		</div>
		<div class="table-responsive">
			<table class="table table-hover no-margins" id="medicine_report_list">
				<thead>
				<tr>
					<th>Id</th>
					<th>Medicine Name</th>
					<th>Date</th>
					<th>Time</th>
					<th>Action</th>
					<th>Reason</th>
					<th>PRN Results / Other Staff Notes</th>
                    <th>Results / Notes time</th>
					<th>Action</th>
					
				</tr>
				</thead>
				<tbody>
					
					@forelse($medicine_report_list as $key => $medicine)
					@php
					$dose='';
						$dose_type='';
						if (strpos($medicine->dose, '__') !== false) {
										
							$doseData = explode('__', $medicine->dose);
							$dose1 = $doseData[0];
							$dose_type = $doseData[1];
							$dose=$dose1.' '.$dose_type;
							if($dose_type=='other')
							{
								$dose =$medicine->other_dose;
							}
						} else {
							$dose = $medicine->dose;
							$dose_type = ''; 
						}
						
						@endphp
						<tr>
							<td>{{ $key + 1 }}</td>
							<td style=" min-width: 120px; max-width:120px; word-wrap:break-word;">{{ $medicine->name }} {{!empty($medicine->dose) ? '('.$dose.')' : ''}}
							<p class="text-muted m-0"><strong>Instruction:</strong> {{!empty($medicine->instructions) ? $medicine->instructions : 'N/A'}}</p>
							</td>
							<td>{{ date('m-d-Y',strtotime($medicine->report_date)) }}</td>
							<td>
								@php
									if($medicine->medicine_time!=0)
									{

										$medicine_time=json_decode($medicine->medicine_time,true);
										$medicine_time_keys = array_keys($medicine_time);
										$medicine_time2=json_decode($medicine->time_id,true);
										//dd($medicine_time);
                                        /*if ($medicine->activity_shift == 1 && array_key_exists('1',$medicine_time)) {
											
                                            echo date('h:i a',strtotime($medicine_time[1]));
                                        } elseif ($medicine->activity_shift == 2 && array_key_exists('2',$medicine_time)) {
											
                                            echo date('h:i a',strtotime($medicine_time[2]));
                                        } elseif ($medicine->activity_shift == 3 && array_key_exists('3',$medicine_time)) {
											
                                            echo date('h:i a',strtotime($medicine_time[3]));
                                        }
                                        elseif ($medicine->activity_shift == 4 && array_key_exists('4',$medicine_time)) {
											
                                            echo date('h:i a',strtotime($medicine_time[4]));
                                        }*/

										if(array_key_exists('5',$medicine_time))
                                        {
											
                                            $medicine_time_other_id=$medicine_time[5];
                                            echo $medicine_time_other[$medicine_time_other_id];
                                        }
										else{
											if (in_array($medicine->activity_shift, [1, 2, 3, 4])) {
												// Check if the medicine's shift matches the activity shift
												if (isset($medicine_time[$medicine->activity_shift])) {
													// Get the specific med_time using med_key
													$med_times = explode(',', $medicine_time[$medicine->activity_shift]);
													foreach ($med_times as $key => $time) {
														if (($key + 1 == $medicine->med_key) || is_null($medicine->med_key) || $medicine->med_key === '') {
															echo date('h:i a', strtotime($time));
															break;
														}
													}
												}else{
													// Handle the case where med_time or med_key is empty or null
													if (empty($medicine->med_time) && (is_null($medicine->med_key) || $medicine->med_key === '')) {
														echo date('h:i a', strtotime($medicine->activity_time));
													}
												}
											}
										}
									}else{
										$medicine_time2=json_decode($medicine->time_id,true);
										
										if(in_array('6',$medicine_time2))
                                        {											
                                            echo date('h:i a',strtotime($medicine->activity_time));
                                        }
										
									}
									
                                    
                                    @endphp
							</td>
							<td>
								{{ 	$medicine->field_value_name }}
								
							</td>
							<td style="min-width: 120px; max-width:120px; word-wrap:break-word;">{{ 	$medicine->remark }}</td>
							<td style="min-width: 120px; max-width:120px; word-wrap:break-word;">
								{{ 	$medicine->staff_note }}
							</td>
							<td>
								@if($medicine->note_time && $medicine->staff_note)
								{{ 	date('h:i a',strtotime($medicine->note_time)) }}
								@else
								@if(strtotime($medicine->activity_time)<=strtotime(date('2024-07-30')) && strtotime($medicine->activity_time)>=strtotime(date('2024-07-19')))
							{{ 	date('h:i a',strtotime($medicine->updated_activity_time)) }}
							@else
							{{ 	date('h:i a',strtotime($medicine->activity_time)) }}
							@endif
							@endif
							</td>
							<td class="text-dark text-nowrap"> 	
									@if(empty($medicine->staff_note))								
									<a data-toggle="tooltip" data-id="{{$medicine->id}}" data-placement="top"  href="#" class="btn-primary btn btn-sm add_staff_note" title="Add Staff note">
										<i class="fa fa-edit" aria-hidden="true"></i>
									</a>
									@endif
							</td>
							
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


