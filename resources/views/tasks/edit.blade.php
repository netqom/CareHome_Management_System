@extends('layouts.admin')

@section('title', 'Tasks Management')
@section('style')
    <link href="{{ asset('assets/css/plugins/clockpicker/clockpicker.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
@endsection
@php
    if (request()->has('staffid') && !empty(request()->input('staffid'))) {
        $staff_id = request()->input('staffid');
        $user_detail = getUserDetail($staff_id);
        $home_id = $user_detail->home_id;
        if ($user_detail->role_id == 3) {
            $staff_div = 'd-none';
            $manager_div = '';
            $user_type = 'manager';
        } else {
            $manager_div = 'd-none';
            $staff_div = '';
            $user_type = 'staff';
        }
    } else {
        $home_id = $staff_id = $staff_div = $user_type = '';
        $manager_div = 'd-none';
    }

@endphp
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('tasks-list') }}">Tasks Management</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Add Task</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12 dark-bg">
                <form method="POST" role="form" action="{{ route('save-tasks') }}" id="task_Form">
                    <div class="ibox ">
                        <div class="ibox-title d-flex">
                            <h5>Add Task</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="">

                                <div class="row">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $get_task_detail->id }}">
                                    <input type="hidden" name="user_type" value="{{ $get_task_detail->user_type }}">

                                    @php
                                        if (
                                            $get_task_detail->user_type == 'staff' ||
                                            $get_task_detail->user_type == 'manager'
                                        ) {
                                            echo '<input type="hidden" name="user_id" value="' .
                                                $get_task_detail->user_id .
                                                '">';
                                        } else {
                                            echo '<input type="hidden" name="patient_id" value="' .
                                                $get_task_detail->patient_id .
                                                '">';
                                        }
                                    @endphp

                                    <div
                                        class="col-sm-12 col-xl-4 col-lg-6 col-md-6 form-group @error('user_type') has-error @enderror">
                                        <label class=" col-form-label" for="user_type">Care Home</label>
                                        <div class="">
                                            <select name="care_home" id="care_home" class="form-control" disabled>
                                                <option value="">Select Care Home</option>
                                                @forelse($care_homes as $home)
                                                    <option value="{{ $home->id }}"
                                                        {{ $get_task_detail->home_id == $home->id ? 'selected' : '' }}>
                                                        {{ $home->name }}</option>
                                                @empty
                                                @endforelse


                                            </select>
                                        </div>
                                        @error('user_type')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div
                                        class="col-sm-12 col-xl-4 col-lg-6 col-md-6 form-group @error('user_type') has-error @enderror">
                                        <label class=" col-form-label" for="user_type">Task For</label>
                                        <div class="">
                                            <select name="user_type" id="user_type" class="form-control" disabled>
                                                <option value="staff"
                                                    {{ $get_task_detail->user_type == 'staff' ? 'selected' : '' }}>Staff
                                                </option>
                                                <option value="manager"
                                                    {{ $get_task_detail->user_type == 'manager' ? 'selected' : '' }}>
                                                    Manager</option>
                                                {{-- <option value="client">Client</option> --}}
                                            </select>
                                        </div>
                                        @error('user_type')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if ($get_task_detail->user_type == 'manager')
                                        <div class="  col-xl-4 col-lg-6 col-md-6 form-group " id="manager_list_div">
                                            <div class="  @error('manager_id') has-error @enderror">
                                                <label class=" col-form-label" for="manager_id">Manager</label>
                                                <div class="">
                                                    <select name="manager_id" id="manager_id" class="form-control" disabled>
                                                        <option value="">Select</option>
                                                        @foreach ($managers as $manager)
                                                            <option value="{{ $manager->id }}"
                                                                {{ $get_task_detail->user_id == $manager->id ? 'selected' : '' }}>
                                                                {{ $manager->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @error('manager_id')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                    @if ($get_task_detail->user_type == 'staff')
                                        <div class="col-sm-12  col-xl-4 col-lg-6 col-md-6 form-group " id="staff_list_div">
                                            <div class=" @error('user_id') has-error @enderror">
                                                <label class=" col-form-label" for="user_id">Staff</label>
                                                <div class="">
                                                    <select name="user_id" id="user_id" class="form-control" disabled>
                                                        <option value="">Select</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}"
                                                                {{ $get_task_detail->user_id == $user->id ? 'selected' : '' }}>
                                                                {{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @error('user_id')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif

                                    <div
                                        class="col-sm-12 col-xl-4 col-lg-6 col-md-6 form-group @error('title') has-error @enderror">
                                        <label class=" col-form-label">Title</label>
                                        <div class="">
                                            <input type="text" name="title" placeholder="Title" class="form-control"
                                                required autocomplete="off" value="{{ $get_task_detail->title }}">
                                            @error('title')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div
                                        class="col-sm-12 col-xl-4 col-lg-6 col-md-12 form-group @error('task_type') has-error @enderror">
                                        @php
                                            $pre_seleted = 0;
                                            if (!is_null(old('task_type')) && old('task_type') == 0) {
                                                $pre_seleted = 1;
                                            }
                                            $content_pages = config('const.task_type');
                                        @endphp
                                        <label class=" col-form-label">Task Type</label>
                                        <div class="">
                                            <select name="task_type" id="task_type" class="form-control" required>
                                                @foreach ($content_pages as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ $get_task_detail->task_type == $key ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                                <!-- <option value="0" {{ $pre_seleted == 0 ? 'selected' : '' }}>One Time</option><option value="1" {{ $pre_seleted == 1 ? 'selected' : '' }}>Continuous</option>  -->
                                            </select>
                                            @error('task_type')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-xl-2 col-lg-3 col-md-12" id="continuous_type_div">
                                        <div class="form-group">
                                            <label class="col-form-label w-100">Continuous Type</label>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="continuous_type"
                                                    id="inlineRadio1" value="0"
                                                    {{ isset($get_task_detail->continuous_type) && $get_task_detail->continuous_type == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="inlineRadio1">Weekly</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="continuous_type"
                                                    id="inlineRadio2" value="1"
                                                    {{ isset($get_task_detail->continuous_type) && $get_task_detail->continuous_type == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="inlineRadio2">Monthly</label>
                                            </div>
                                            <!-- <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="continuous_type" id="inlineRadio3" value="2" {{ isset($get_task_detail->continuous_type) && $get_task_detail->continuous_type == 2 ? 'checked' : '' }}>
                                  <label class="form-check-label" for="inlineRadio3">Yearly</label>
                                 </div> -->
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-xl-2 col-lg-3 col-md-12 form-group" id="weekly_type_div">
                                        <div class="form-group select-2-full select-2-scroll">
                                            <label class="col-form-label" for="exampleFormControlSelect1">Select Week
                                                Days</label>
                                            @php
                                                $weekdays = config('const.week_days');
                                                $selected_days =
                                                    isset($get_task_detail->selected_week_days) &&
                                                    $get_task_detail->selected_week_days != null
                                                        ? json_decode($get_task_detail->selected_week_days, true)
                                                        : [];
                                            @endphp
                                            <select class="form-control select-week-days" id="selected_week_days"
                                                name="selected_week_days[]" multiple="multiple">
                                                @foreach ($weekdays as $day)
                                                    <option value="{{ $day }}"
                                                        {{ in_array($day, $selected_days) ? 'selected' : '' }}>
                                                        {{ $day }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-xl-2 col-lg-3 form-group" id="monthly_type_div">
                                        <div class="form-group select-2-full select-2-scroll">
                                            <label class="col-form-label" for="exampleFormControlSelect1">Select Month
                                                Days</label>
                                            @php
                                                $selected_months =
                                                    isset($get_task_detail->selected_month_dates) &&
                                                    $get_task_detail->selected_month_dates != null
                                                        ? implode(
                                                            ',',
                                                            json_decode($get_task_detail->selected_month_dates, true),
                                                        )
                                                        : '';
                                            @endphp

                                            <div class="input-group date month_date" id="specific-datepicker">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" name="selected_month_dates"
                                                    value="{{ $selected_months }}" id="selected_month_dates"
                                                    placeholder="Select Month Dates" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-12 form-group">
                                        <div class="row">
                                            <div class="col-sm-12 col-lg-3">
                                                <label class=" col-form-label" for="start_date">Start Date</label>
                                                <div class=" input-group date start_date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" name="start_date"
                                                        value="{{ $get_task_detail->start_date }}" id="start_date"
                                                        placeholder="Start Date" class="form-control required bg-white"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-lg-3 " id="end-date-div">
                                                <label class="col-form-label" for="end_date">End Date</label>
                                                <div class=" input-group date end_date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" name="end_date"
                                                        value="{{ $get_task_detail->end_date }}" id="end_date"
                                                        placeholder="End Date" class="form-control bg-white" readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-lg-3">
                                                <label class=" col-form-label" for="start_time">Start Time</label>
                                                <div class=" input-group clockpicker start-time" data-autoclose="true">
                                                    <span class="input-group-addon"><span
                                                            class="fa fa-clock-o"></span></span>
                                                    <input type="time" class="form-control required"
                                                        value="{{ $get_task_detail->start_time }}" name="start_time"
                                                        id="start_time" placeholder="Start Time">
                                                    @error('start_time')
                                                        <span class="text-danger text-left d-block"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-lg-3" id="end-time-div">
                                                <label class=" col-form-label" for="end_time">End Time</label>
                                                <div class=" input-group clockpicker end-time" data-autoclose="true">
                                                    <span class="input-group-addon"><span
                                                            class="fa fa-clock-o"></span></span>
                                                    <input type="time" value="{{ $get_task_detail->end_time }}"
                                                        name="end_time" id="end_time" placeholder="End Time"
                                                        class="form-control">
                                                    <div class="text-danger text-left d-none" role="alert"
                                                        id="end_time_err"></div>
                                                    @error('end_time')
                                                        <span class="text-danger text-left d-block"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- <div class="col-sm-12 col-lg-3" id="due-date-div">
                                                <label class=" col-form-label" for="start_date">Due Date</label>
                                                <div class=" input-group date due_date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" name="due_date"
                                                        value="{{ $get_task_detail->due_date }}" id="due_date"
                                                        placeholder="Due Date" class="form-control bg-white" required
                                                        readonly>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                    {{-- <div class="d-none col-sm-6 col-lg-3 form-group " id="client_list_div">
										<div class="  @error('patient_id') has-error @enderror">
											<label class=" col-form-label" for="patient_id">Client</label>
											<div class="">
												<select name="patient_id" id="patient_id" class="form-control">
													<option value="">Select</option>
													@foreach ($patients as $patient)
														<option value="{{ $patient->id }}">{{ $patient->name }}</option>
													@endforeach
												</select>
											</div>
										</div>	
										@error('patient_id')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror						
									</div> --}}

                                    <div class="col-12 col-lg-12 form-group @error('description') has-error @enderror">
                                        <label class=" col-form-label">Description</label>
                                        <div class="">
                                            <textarea class="form-control" name="description" placeholder="Description" rows="3" autocomplete="off">{{ $get_task_detail->description }}</textarea>
                                            @error('description')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-sm-6 col-lg-3 form-group @error('task_status') has-error @enderror">
									@php
										$pre_seleted = 0;
										if(!is_null(old('task_status')) && old('task_status') == 0){
											$pre_seleted = 1;
										} 
										$content_pages = config('const.task_status');
									@endphp
									<label class=" col-form-label">Task Status</label>
									<div class="">
										<select name="task_status" id="task_status" class="form-control" required>
											@foreach ($content_pages as $key => $value) 
												<option value="{{ $key }}" {{ old('task_status') == $key ? 'selected' : ''}}>{{ $value }}</option> 
											@endforeach 
										</select>
										@error('task_status')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div> --}}
                                <input type="hidden" name="redirectURL"
                                    value="{{ createCancelUrl(route('tasks-list')) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-white btn-sm" type="button"
                                    href="{{ createCancelUrl(route('tasks-list')) }}">Cancel</a>
                                <button class="btn btn-sm btn-primary" type="submit" id="taskForm">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/clockpicker/clockpicker.js') }}"></script>
	<script src="{{ asset('assets/js/module/task.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            var task_type = $('#task_type').val();
            var continuous_type = $('input[type=radio][name=continuous_type]:checked').val();
            
            if (continuous_type == 0) {
                $('#monthly_type_div').addClass('d-none');
            } else {
                $('#weekly_type_div').addClass('d-none');
            }
            // Initialize month_date picker
            /* $('.input-group.date.month_date').datepicker({
            	format: 'dd',
            	forceParse: false,
            	multidate: true,
            	clearBtn: true // Show clear button
            }).on('changeDate', function(e) {
            	var selectedDates = e.dates;
            	console.log("Selected Dates: ", selectedDates);
            }); */
        });
    </script>
@endsection
