@extends('layouts.admin')

@section('title', 'Patient Activity Detail')
@section('style')
	<link href="{{ asset('assets/css/plugins/clockpicker/clockpicker.css') }}" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">

@endsection

@section('content')
<style>
	.footer {
		display: none;
	}

	.acti-item {
		display: flex;
		justify-content: center;
		flex-wrap: wrap;
		align-items: center;
		padding: 5px 10px;
		min-width: 170px;
		border: 1px solid #dee2e6;
		border-radius: 5px;
		margin-right: 10px;
		height: 40px;
		font-size: 14px;
		color: #2f4050;
		margin-bottom: 10px;
		font-weight: 500;
	}

	.acti-item.remark {
	flex-grow: 100;
	justify-content: start;
	height: auto;
	min-height: 40px;
	order: 10;
	width: auto;
}

	.acti-item:last-child {
		margin-right: 0;
	}

	.acti-item img {
		margin-right: 5px;
		max-width: 22px;
	}

	#activityTab {
		margin-bottom: 25px;
	}

	#activityTab .nav-item a {
		background: #f9f9f9;
		color: #2f4050;
		border-radius: 5px;
		font-size: 15px;
		padding: 12px 20px;
		font-weight: normal;
	}

	#activityTab .nav-item a.active {
		background: #1ab394;
		color: #fff;
	}

	#activityTab li.nav-item {
		margin: 0 10px 10px 0;
	}

	@media (max-width: 767px) {
		.acti-item {
			min-width: 130px;
		}

		.acti-item.remark {
			height: auto
		}
	}

	.select2-results__option {
            display: flex;
            align-items: center;
        }
        .select2-results__option input {
            margin-right: 10px;
        }
</style>

	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item">
					<a href="{{ url()->previous() }}#tab10">Medicine Report</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>{{$add_medicine == 1 ? "Add" : "Update"}} Patients Activity Detail</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
		
			<div class="col-lg-12">
                    <div class="ibox product-detail border rounded shadow">
                        <div class="ibox-content">
                            <div class="row">
								<div class="col-md-12">
									<a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('users.index')) }}"><i class="fa fa-arrow-circle-o-left"></i> Back</a>
									<div class="hr-line-dashed"></div>
								</div>	
								
                              

								
								
									<div class="col-xl-6 col-lg-12 col-md-12">
										<h2 class="font-bold fs-18 mb-4 text-body mt-lg-3 mt-xl-0">Connected with Care Home</h2>
										<div class="ch-detail d-flex flex-wrap flex-sm-nowrap style1 white-bg mt-0 stat-box-shadow radius-10 p-3 align-items-center">
											<div class="mb-0 mr-4">
												<div class="position-relative ch-img">
													<a href="{{ route('homes.show', $patient->care_home->id) }}">
														<img src="{{ $patient->care_home->image_path }}" alt="care-home" class="img-fluid rounded-lg">
														<div class="position-absolute ch-online">
														</div>
													</a>
												</div>
											</div>
											<div class="flex-grow-1">
												<a href="{{ route('homes.show', $patient->care_home->id) }}">
													<h2 class="font-bold text-body fs-16">{{ $patient->care_home ? $patient->care_home->name : '-' }} </h2>
												</a>
												<div class="d-flex flex-wrap mb-0 ch-info ch-info-med">
													<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4 w-100"><i class="fa fa-mobile-phone fs-18 mr-1"></i> {{ $patient->care_home ? $patient->care_home->contact_no : '-' }}</a>
													<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4 w-100"><i class="fa fa-user-circle  mr-1"></i> {{count($patient->care_home->staff_users)}} Staff Members | {{count($patient->care_home->patients)}} Current Patients</a>
													<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4 w-100"><i class="fa fa-envelope  mr-1"></i> {{ $patient->care_home ? $patient->care_home->email : '-' }}</a>
													<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4 w-100"><i class="fa fa-map-marker fs-14 mr-1"></i>

														{{ $patient->care_home ? $patient->care_home->street.', '. $patient->care_home->city.', '.$patient->care_home->state.', '. $patient->care_home->zip_code : ''}}
													</a>
												</div>
												
											</div>
										</div>									
									</div>
									<div class="col-xl-6 col-lg-12 col-md-12">
									
									<h2 class="font-bold fs-18 mb-4 text-body">Patient Detail</h2>
									
									<div class="ch-detail d-flex flex-wrap flex-sm-nowrap style1 white-bg mt-0 stat-box-shadow radius-10 p-3 align-items-center">
										<div class="mb-0 mr-4">
											<div class="position-relative ch-img">
												
													<img src="{{ asset($patient->profile_image_path) }}" alt="staff" class="img-fluid rounded-lg">
												
												
											</div>
										</div>
										<div class="flex-grow-1">
											<h2 class="font-bold text-body fs-16">{{ $patient->name }}</h2>
											<div class="mb-4 ch-info ch-info-med">
												<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i class="fa fa-mobile-phone fs-18 mr-1"></i> {{$patient->phone}}</a>
												<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i class="fa fa-envelope  mr-1"></i> {{$patient->email}} </a>
											
											</div>											
										</div>
									</div>
								</div>
								
                            </div>
                        </div>
						
                    </div>
                </div>

			<div class="col-xl-8 offset-xl-2 col-lg-12 col-md-12 col-sm-12 dark-bg">
				   <div class="ibox">
				   	<div class="ibox-content">
                <form class="title-white daily-activity-form" id="daily-medicine-form" action="{{route('add-daily-medicine-detail',['patient_id'=>$patient->id,'id'=>0])}}" method="POST" enctype="multipart/form-data">
                    @csrf
					@php
						$shiftNames = [
								1 => 'Morning',
								2 => 'Afternoon',
								3 => 'Evening',
								4 => 'Night',
								5 => 'Ad_Hoc',
							];
					@endphp
					<div class="row">
					<div class="form-group col-xl-4 col-lg-6 col-md-12">
						<label for='images'>Report Date *</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							@if($add_medicine == 1)
								<input type="text" name="report_date" value="{{date('Y-m-d')}}" id="report_date" placeholder="Report Date" class="form-control" data-add-medicine={{$add_medicine}} readonly>
							@else
								<input type="text" name="report_date" value="{{ app('request')->input('report_date') }}" id="report_date" placeholder="Report Date" class="form-control required" data-add-medicine={{$add_medicine}} required readonly>
							@endif
						</div>
					</div>
					<div class="form-group col-xl-4 col-lg-6 col-md-12">
						<label for='images'>Shift Time *</label>
						<select name="shift_id" id="shift_time" class="form-control" >
							@foreach($shiftNames as $shift_key => $shiftTime)
								<option value="{{$shift_key}}" {{request()->segment(3) == $shift_key ? 'selected' : ''}}>{{$shiftTime}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-xl-4 col-lg-6 col-md-12">
						<label for='images'>Supervised By *</label>
						<select name="created_by" id="created_by" class="form-control required" required>
							<option value="">Select Staff</option>
							<option value="{{Auth::user()->id}}">{{Auth::user()->name}}</option>
							@foreach($staffList as $staff_k => $staff_v)
								<option value="{{$staff_k}}">{{$staff_v}}</option>
							@endforeach
						</select>
					</div>
				</div>
					
					


                    <div class="form-group form-group-activity row flex-wrap">
                    	<div class="col-12">
						<label class="w-100 mb-0" for="activity">Medicine </label>
						<div class="activity-container">
							<ul style="display:block;">
							@php
							   $medicine_time_other = config('const.medicine_time_other');
							@endphp
						
								@foreach($medicineData as $medicine)
									@php
										// Get the timeshift array for the current medicine
										$timeshiftArray = explode(',', $medicine->medicine_times_shift);
									@endphp

								
										@php
									
										//	$timeSlug = str_replace(':', '-', $time); // Replace colon with hyphen to make it a valid HTML ID
											$uniqueId = $medicine->new_id; // Create a unique ID based on medicine ID and time


											if (!empty($patientActivity) && !$patientActivity->isEmpty()) {
												$item =$patientActivity->filter(function ($item) use ($medicine) {
													return $item['field_id'] == $medicine->id && $item['med_key'] == $medicine->med_key;
												})->first();
												$medicineExists = $item !== null;
												$fieldValue = $medicineExists ? $item->field_value : null;
												$selectedOption = null;
												if ($medicineExists) {
													$optionsCollection = collect($medicine->options);
													$selectedOption = $optionsCollection->firstWhere('id', $fieldValue);
												}
											}

										@endphp

										<li class="flex-wrap mt-2 mb-1"> 
											<div class="input-group">    
												<input type="checkbox" id="medicine_checkbox_{{$uniqueId}}" name="medicine_data[{{$uniqueId}}][medicine_id]" value="{{$medicine->id}}" class="medicine_check_box"  data-id="{{$uniqueId}}" data-name="{{$medicine->name}}" data-time="{{$medicine->med_time}}" {{(!empty($item)? 'checked' : '')}}>
												@if($medicine->med_time==null)
												 	@php
														$time_ids = json_decode($medicine->time_id,true);
														if(in_array('5',$time_ids))
														{
															$medicine_time =json_decode($medicine->medicine_time,true);
															$mshift_id=request()->segment(3);
															
															echo $medicine->name.' - ('.$medicine_time_other[$mshift_id].')';
															
														}else{
															echo $medicine->name;
														}
												 	@endphp
												@else
													{{$medicine->name}} - ({{$medicine->med_time}})
											
												@endif
											</div>
											<input type="hidden" name="medicine_data[{{$uniqueId}}][med_key]" value="{{$medicine->med_key}}">
											<input type="hidden" name="medicine_data[{{$uniqueId}}][med_time]" value="{{$medicine->med_time}}">
											<input type="hidden" name="medicine_data[{{$uniqueId}}][id]" value="{{!empty($item) ? $item['id'] : 0 }}">

											<!-- Display the options under each medicine -->
											<div id="medicine_option_{{$uniqueId}}" class="w-100 mt-2 {{!empty($item)? '' : 'd-none'}}">
												@foreach ($medicine->options as $option_key => $option)
													@php
														if(!empty($item)) {
															$selected_op = $option['id'] == $item['field_value'] ? 'checked' : '';
														} else {
															$selected_op = $option_key == 0 ? 'checked' : '';
														}
													@endphp
													
													<div class="form-check form-check-inline">
														<input class="form-check-input size-20 medicine_action" type="radio" name="medicine_data[{{$uniqueId}}][field_value]" id="field_value_{{$uniqueId}}_{{$option['field_id']}}" data-remark="{{$option['remark']}}" data-medecine_id="{{$medicine->id}}"  data-time-id="{{$uniqueId}}" value="{{$option['id']}}" {{$selected_op}}>
														<label class="form-check-label" for="field_value_{{$uniqueId}}_{{$option['field_id']}}">
															{{$option['name']}}
														</label>
													</div>
												@endforeach

												<div class="form-group mt-2 mb-0 {{!empty($selectedOption) && $selectedOption['remark'] == 1 ? '' : 'd-none' }}" id="remark_{{$uniqueId}}">
													<label for="add-remark">Remark</label>
													<textarea class="form-control" name="medicine_data[{{$uniqueId}}][remark]" rows="3" id="medicine_remark_{{$uniqueId}}">{{!empty($selectedOption) && $selectedOption['remark'] == 1 ? $item['remark'] : '' }}</textarea>
												</div>
											</div>
										</li>

								@endforeach
							</ul>
							<div id="medicine_data" style="color:red"></div>
						</div>

					</div>
					</div>
					<input type="hidden" name="shift_id" value="{{request()->segment(3)}}">
                    <button type="submit" class="btn btn-primary" id="save-med-report">Submit</button>
                </form>
            </div>
             </div>
			</div>

<!-- New layout END here -->
        </div>
	</div>	
@endsection
@section('script')
<script src="{{ asset('assets/js/plugins/clockpicker/clockpicker.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
	
<script>
$(document).ready(function(){
	var add_medicine = {{$add_medicine}};
	
	if(add_medicine != 1){
		$('#report_date').datepicker({
			autoclose: true,
			format:'yyyy-mm-dd',
			todayHighlight: false,
			endDate: new Date(),
			datesDisabled: [new Date().toISOString().split('T')[0]] // Disable today's date
		}).on('changeDate', function(e) {
			var patient_id = "{{$patient->id}}";
			var shift_id = $('#shift_time').val();
			var report_date = $('#report_date').datepicker('getFormattedDate', 'yyyy-mm-dd');
			//var url = '/add-patient-medicine-report/' + patient_id + '/' + shift_id + '?report_date=' + report_date;
			var url = '/update-patient-medicine-report/' + patient_id + '/' + shift_id + '?report_date=' + report_date;
			window.location.href = url;
		});
	}
})	

$(document).on('change', '#shift_time', function(){
	var shift_id = $(this).val();
	var report_date = $('#report_date').val();
	var add_medicine = $('#report_date').data('add-medicine');
	console.log(add_medicine,'add_medicine');
	var patient_id = "{{$patient->id}}";
	if(add_medicine == 1){
		var url = '/add-patient-medicine-report/'+patient_id+'/'+shift_id+'?report_date='+report_date;
		window.location.href = url;
	}else{
		var url = '/update-patient-medicine-report/'+patient_id+'/'+shift_id+'?report_date='+report_date;
		window.location.href = url;
	}
	/*$.ajax({
                type: "POST",
                url: "{{ route('patient-activity-by-shift-date') }}",
                data: { report_date: report_date, shift_id: shift_id, patient_id: patient_id, type: 1},
                success: function(response) {
                    if (response.type == 'success') {
                        Swal.fire({
							text: response.message,
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#3085d6",
							cancelButtonColor: "#f39c12",
							confirmButtonText: "View Activity",
							cancelButtonText: "Cancel",
							//footer: '<button id="upgradeButton" class="swal2-confirm swal2-styled" style="background-color: #f39c12;">Upgrade Plan</button>',
						}).then((result) => {
							if (result.isConfirmed) {
								window.location.href = response.redirect_url;
							}
						})	
                    } else{
						window.location.href = url;
					}
                },
                error: function(err, xhr) {
                    
                },
            });	*/
});

/*$(document).on('change', '#report_date', function(){
	var report_date = $(this).val();
	var shift_id = $('#shift_time').val();
	var patient_id = "{{$patient->id}}";
	$.ajax({
                type: "POST",
                url: "{{ route('patient-activity-by-shift-date') }}",
                data: { report_date: report_date, shift_id: shift_id, patient_id: patient_id, type: 1 },
                success: function(response) {
                    if (response.type == 'success') {
                        Swal.fire({
							text: response.message,
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#3085d6",
							cancelButtonColor: "#f39c12",
							confirmButtonText: "View Activity",
							cancelButtonText: "Cancel",
							//footer: '<button id="upgradeButton" class="swal2-confirm swal2-styled" style="background-color: #f39c12;">Upgrade Plan</button>',
						}).then((result) => {
							if (result.isConfirmed) {
								window.location.href = response.redirect_url;
							}
						})	
                    } 
                },
                error: function(err, xhr) {
                    
                },
            });
})*/

$(document).on('click', '#save-activity-remark', function(){
	var activityId = $('.activity_id').val();
	var selectedActivityStatus = $('input[name="activity_status"]:checked').val();
	var activityRemark = $('#add-remark').val();
	$('#status_activity_'+activityId).val(selectedActivityStatus);
	$('#remark_activity_'+activityId).val(activityRemark);
	$('#activity-remark-form')[0].reset();
	$('#activity_remark_modal').modal('hide');
})	


$(document).on('click', '.medicine_action', function(){
	var medicineId = $(this).data('medecine_id');
	var time = $(this).data('time-id');
       if ($(this).is(':checked')) {
		var remark = $(this).data('remark');
			if(remark == '1'){
				$('#remark_'+time).removeClass('d-none');
				$('#medicine_remark_'+time).attr('required', true);
			}else{
				$('#remark_'+time).addClass('d-none');
				$('#medicine_remark_'+time).attr('required', false);
			}
        } else {
			$('#remark_'+time).addClass('d-none');
			$('#medicine_remark_'+time).attr('required', false);
        } 
})


$(document).on('click', '.medicine_check_box', function() {
	var medicineId = $(this).data('id');
	console.log("dhhdhd", medicineId);
	
       if ($(this).is(':checked')) {
			$('#medicine_option_'+medicineId).removeClass('d-none');
			var remark = $('.medicine_action').data('remark');
			if(remark == '1'){
				$('#remark_'+medicineId).removeClass('d-none');
				$('#medicine_remark_'+medicineId).attr('required', true);
			}else{
				$('#remark_'+medicineId).addClass('d-none');
				$('#medicine_remark_'+medicineId).attr('required', false);
			}
        } else {
            $('#medicine_option_'+medicineId).addClass('d-none');
        } 
    });

	function formatState(state) {
                if (!state.id) {
                    return state.text;
                }
                var isSelected = $(state.element).prop('selected');
                var $state = $(
                    '<span><input type="checkbox" ' + (isSelected ? 'checked' : '') + ' /> ' + state.text + '</span>'
                );
                return $state;
            }
	function initializeSelect2(selector, placeholderText) {
                $(selector).select2({
                    placeholder: placeholderText,
                    closeOnSelect: false,
                    templateResult: formatState,
                    templateSelection: function (state) {
                        return state.text;
                    }
                });
            }
	initializeSelect2('.multi-select', 'Select options');
    function handleRemarkChange(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var remark = selectedOption.getAttribute('data-remark');
        var remarkContainer = document.getElementById(selectElement.id + '-remark');

        if (remark == 1) {
            remarkContainer.style.display = 'block';
        } else {
            remarkContainer.style.display = 'none';
        }
    }

    // Trigger the change event on page load to handle the default value
    document.addEventListener('DOMContentLoaded', function() {
		initializeSelect2('.multi-select', 'Select options');
        var selectElements = document.querySelectorAll('select');
        selectElements.forEach(function(selectElement) {
            handleRemarkChange(selectElement);
        });
    });
</script>
@endsection