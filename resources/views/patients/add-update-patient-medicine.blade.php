@extends('layouts.admin')

@section('title', 'Create/Update Patient Medicine')

@section('style')
    <link href="{{ asset('assets/css/plugins/clockpicker/clockpicker.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">

@endsection
@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .clockpicker {
            display: flex;
        }

        .clockpicker .invalid-feedback {
            order: 3;
        }

        .select2-results__option {
            display: flex;
            align-items: center;
        }

        .select2-results__option input {
            margin-right: 10px;
        }

        .clockpicker-tick-disabled {
            opacity: 0.5;
            /* Example: reduce opacity for disabled ticks */
            cursor: default;
            /* Example: change cursor to default to indicate disabled state */
            pointer-events: none !important;
            /* Disable pointer events to prevent clickability */
        }

        .input-group.date.month_date[data-custom="specific-datepicker"] .datepicker-days .datepicker-switch,
        .input-group.date.month_date[data-custom="specific-datepicker"] .datepicker-days .prev,
        .input-group.date.month_date[data-custom="specific-datepicker"] .datepicker-days .next,
        .input-group.date.month_date[data-custom="specific-datepicker"] .datepicker-days .datepicker-months,
        .input-group.date.month_date[data-custom="specific-datepicker"] .datepicker-days .datepicker-years,
        .input-group.date.month_date[data-custom="specific-datepicker"] .datepicker-days .datepicker-decades,
        .input-group.date.month_date[data-custom="specific-datepicker"] .datepicker-days .datepicker-centuries {
            display: none;
        }

        .datepicker table tr td.day {
            display: table-cell;
        }

        .datepicker table tr td.old,
        .datepicker table tr td.new {
            visibility: hidden;
        }
    </style>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url()->previous() }}">Patient</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>{{ $id == 0 ? 'Create' : 'Update' }} Patient Medicine</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-12">
                <form method="POST" role="form"
                    action="{{ route('save-patients-medicine', ['patient_id' => $patient_id, 'id' => $id]) }}"
                    id="patient_medicine_Form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @php
                            $m_start = $m_end = $a_start = $a_end = $e_start = $e_end = $n_start = $n_end = '';
                            $times = config('const.log_times');

                            if ($care_home_activity_time && $care_home_activity_time->morning_time != null) {
                                $m_time = explode('-', $care_home_activity_time->morning_time);
                                $m_start = trim($m_time[0]);
                                $m_end = trim($m_time[1]);
                            } else {
                                $m_time = explode('-', $times[1]);
                                $m_start = trim($m_time[0]);
                                $m_end = trim($m_time[1]);
                            }
                            if ($care_home_activity_time && $care_home_activity_time->afternoon_time != null) {
                                $a_time = explode('-', $care_home_activity_time->afternoon_time);
                                $a_start = trim($a_time[0]);
                                $a_end = trim($a_time[1]);
                            } else {
                                $a_time = explode('-', $times[2]);
                                $a_start = trim($a_time[0]);
                                $a_end = trim($a_time[1]);
                            }
                            if ($care_home_activity_time && $care_home_activity_time->evening_time != null) {
                                $e_time = explode('-', $care_home_activity_time->evening_time);
                                $e_start = trim($e_time[0]);
                                $e_end = trim($e_time[1]);
                            } else {
                                $e_time = explode('-', $times[3]);
                                $e_start = trim($e_time[0]);
                                $e_end = trim($e_time[1]);
                            }
                            if ($care_home_activity_time && $care_home_activity_time->night_time != null) {
                                $n_time = explode('-', $care_home_activity_time->night_time);
                                $n_start = trim($n_time[0]);
                                $n_end = trim($n_time[1]);
                            } else {
                                $n_time = explode('-', $times[4]);
                                $n_start = trim($n_time[0]);
                                $n_end = trim($n_time[1]);
                            }
                            //dd($m_start, $m_end,$a_start,$a_end,$e_start,$e_end ,$n_start,$n_end);
                        @endphp
                        <div class="col-xl-7 col-lg-12 col-md-12 mt-5 dark-bg">
                            <div class="ibox">
                                <div class="ibox-content ff shadow border rounded">
                                    <div class="ibox-content">

                                        <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient_id }}">
                                        <fieldset class="w-100">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="row" id="medicine_div">
                                                        <div class="d-flex w-100 flex-md-wrap">
                                                            <div class="col mob-full">
                                                                <div class="form-group">
                                                                    <label for="name">Name *</label>
                                                                    <input type="text" name="medicines[0][medicine_name]"
                                                                        id="name"
                                                                        value="{{ $medicine ? $medicine->name : '' }}"
                                                                        placeholder="Medicine Name"
                                                                        class="form-control rounded" required>
                                                                </div>
                                                            </div>

                                                            <div class="col mob-full">
                                                                <label for="time">Dose *</label>
                                                                <div class="form-group d-flex dose-grp">
                                                                    @php
                                                                        $dose = '';
                                                                        $dose_type = '';
                                                                        $other_dose_med_div = 'd-none';
                                                                        if (!empty($medicine)) {
                                                                            if (
                                                                                strpos($medicine->dose, '__') !== false
                                                                            ) {
                                                                                $doseData = explode(
                                                                                    '__',
                                                                                    $medicine->dose,
                                                                                );
                                                                                $dose = $doseData[0];
                                                                                $dose_type = $doseData[1];
                                                                                if ($dose_type == 'other') {
                                                                                    $other_dose_med_div = '';
                                                                                }
                                                                            } else {
                                                                                $dose = $medicine->dose;
                                                                                $dose_type = '';
                                                                            }
                                                                        }
                                                                    @endphp

                                                                    <input type="text" class="form-control"
                                                                        value="{{ $medicine ? $dose : '' }}"
                                                                        name="medicines[0][dose]" id=""
                                                                        placeholder="Dose"
                                                                        class="form-control rounded required" required>

                                                                    <select class="dose-quantity input-group-text"
                                                                        name="medicines[0][dose_type]" id="dose-quantity"
                                                                        class="form-control rounded" required>
                                                                        <option value="mg"
                                                                            {{ $medicine && $dose_type == 'mg' ? 'selected' : '' }}>
                                                                            mg</option>
                                                                        <option value="mcg"
                                                                            {{ $medicine && $dose_type == 'mcg' ? 'selected' : '' }}>
                                                                            mcg</option>
                                                                        <option value="ml"
                                                                            {{ $medicine && $dose_type == 'ml' ? 'selected' : '' }}>
                                                                            ml</option>
                                                                        <option value="µL"
                                                                            {{ $medicine && $dose_type == 'µL' ? 'selected' : '' }}>
                                                                            µL</option>
                                                                        <option value="other"
                                                                            {{ $medicine && $dose_type == 'other' ? 'selected' : '' }}>
                                                                            Other</option>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col mob-full {{ $other_dose_med_div }}"
                                                                id="other_dose_med_div">
                                                                <div class="form-group">
                                                                    <label for="other">Other</label>
                                                                    <input type="text" name="medicines[0][other_dose]"
                                                                        id="other_med_option"
                                                                        value="{{ $medicine ? $medicine->other_dose : '' }}"
                                                                        placeholder="Other Option"
                                                                        class="form-control rounded">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @php
                                                            $other_intake_method_div = 'd-none';
                                                            $pre_intake_method = $medicine
                                                                ? $medicine->intake_method
                                                                : '';
                                                            if ($medicine && $medicine->intake_method == 4) {
                                                                $other_intake_method_div = '';
                                                            }

                                                            $other_medicine_type_div = 'd-none';
                                                            $pre_medicine_type = $medicine
                                                                ? $medicine->medicine_type
                                                                : '';
                                                            if ($medicine && $medicine->medicine_type == 5) {
                                                                $other_medicine_type_div = '';
                                                            }
                                                        @endphp
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="time">Medication-Type *</label>
                                                                <select name="medicines[0][medicine_type]"
                                                                    id="medicine_type" class="form-control rounded"
                                                                    required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($medicine_type as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            @if ($pre_medicine_type == $key) selected @endif>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group {{ $other_medicine_type_div }}"
                                                                id="other_medicine_type_div">
                                                                <label for="time">Medication-Type Other</label>
                                                                <input type="text"
                                                                    name="medicines[0][medicine_type_other]"
                                                                    id="medicine_type_other"
                                                                    value="{{ $medicine ? $medicine->medicine_type_other : '' }}"
                                                                    placeholder="Medication Type Other"
                                                                    class="form-control rounded">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="time">Intake Method *</label>
                                                                <select name="medicines[0][intake_method]"
                                                                    id="intake_method" class="form-control rounded"
                                                                    required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($intake_method as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            @if ($pre_intake_method == $key) selected @endif>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group {{ $other_intake_method_div }}"
                                                                id="other_intake_method_div">
                                                                <label for="time">Other Intake Method</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $medicine ? $medicine->other_intake_method : '' }}"
                                                                    name="medicines[0][other_intake_method]"
                                                                    id="other_intake_method"
                                                                    placeholder="Other Intake Method"
                                                                    class="form-control rounded">
                                                            </div>
                                                        </div>


                                                        @php
                                                            $other_intake_supervised_by_div = 'd-none';
                                                            $pre_intake_supervised_by = $medicine
                                                                ? $medicine->intake_supervised_by
                                                                : '';
                                                            if ($medicine && $medicine->intake_supervised_by == 4) {
                                                                $other_intake_supervised_by_div = '';
                                                            }
                                                        @endphp
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="time">Supervise By *</label>
                                                                <select name="medicines[0][intake_supervised_by]"
                                                                    id="intake_supervised_by" class="form-control rounded"
                                                                    required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($intake_guidedby as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            @if ($pre_intake_supervised_by == $key) selected @endif>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group {{ $other_intake_supervised_by_div }}"
                                                                id="other_intake_supervised_by_div">
                                                                <label for="time">Other Supervise By</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $medicine ? $medicine->intake_supervised_other : '' }}"
                                                                    name="medicines[0][intake_supervised_other]"
                                                                    id="intake_supervised_other"
                                                                    placeholder="Intake Supervised By"
                                                                    class="form-control rounded">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="time">Instructions *</label>
                                                                <textarea name="medicines[0][instructions]" placeholder="Add instructions" required>{{ $medicine ? $medicine->instructions : '' }}</textarea>
                                                            </div>
                                                        </div>

                                                        @php
                                                            $pre_time_array = [];
                                                            $other_time_div = 'd-none';
                                                            $pre_time_id = $medicine
                                                                ? json_decode($medicine->time_id, true)
                                                                : '';
                                                            if ($pre_time_id != null) {
                                                                if (is_array($pre_time_id)) {
                                                                    $pre_time_array = $pre_time_id;
                                                                } else {
                                                                    $pre_time_array[] = $pre_time_id;
                                                                }
                                                            }

                                                            if ($medicine && in_array(4, $pre_time_array)) {
                                                                $other_time_div = '';
                                                            }
                                                        @endphp
                                                        {{-- <div class="col-lg-3">
													<div class="form-group">
														<label for="time">Time *</label>
														<select name="medicines[0][time_id][]" id="time_id" class="form-control rounded med_time_select" multiple="multiple" required>
															<option value="">Select</option>
															@foreach ($medicine_time as $key => $value)
																<option value="{{ $key }}" @if (in_array($key, $pre_time_array)) selected @endif>{{ $value }}</option>
															@endforeach
														</select>
													</div>
												</div> --}}



                                                        {{-- <div class="col-lg-3 {{ $other_time_div }}" id="other_time_div">
													<div class="form-group">
														<label for="time">Other Time</label>
														<div class="input-group clockpicker" data-autoclose="true">
															<input type="text" class="form-control" value="{{ $medicine ? $medicine->time_other : '' }}" name="medicines[0][time_other]" id="time_other" placeholder="Medicine Other TIme" class="form-control rounded">
															<span class="input-group-addon">
																<span class="fa fa-clock-o"></span>
															</span>
														</div>
													</div>
												</div> --}}
                                                        @php
                                                            $time_id = $medicine
                                                                ? json_decode($medicine->time_id, true)
                                                                : '';
                                                            $medicine_time =
                                                                $medicine && $medicine->medicine_time != null
                                                                    ? json_decode($medicine->medicine_time, true)
                                                                    : '';
                                                            $pre_time_array = [];
                                                            $medicine_time_array = [];
                                                            if ($time_id != null) {
                                                                if (is_array($time_id)) {
                                                                    $pre_time_array = $time_id;
                                                                } else {
                                                                    $pre_time_array[] = $time_id;
                                                                }
                                                            }
                                                            if ($medicine_time != null) {
                                                                if (is_array($medicine_time)) {
                                                                    $medicine_time_array = $medicine_time;
                                                                } else {
                                                                    $medicine_time_array[] = $medicine_time;
                                                                }
                                                            }

                                                        @endphp
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-5 col-lg-12 col-md-12 mt-xl-5 dark-bg">
                            <div class="ibox right-ibox">
                                <div class="ibox-content">

                                    @php
                                        $other_pre_med_frequency_weekdiv = 'd-none';
                                        $week_array = [];
                                        $month_array = '';
                                        $other_pre_med_frequency_monthdiv = 'd-none';
                                        $pre_med_frequency = $medicine ? $medicine->med_frequency : '';
                                        if ($medicine && $medicine->med_frequency == 4) {
                                            $other_pre_med_frequency_monthdiv = '';
                                            $month_array = implode(
                                                ',',
                                                json_decode($medicine->other_med_frequency, true),
                                            );
                                        }
                                        if (
                                            $medicine &&
                                            ($medicine->med_frequency == 2 || $medicine->med_frequency == 3)
                                        ) {
                                            $other_pre_med_frequency_weekdiv = '';
                                            $week_array = json_decode($medicine->other_med_frequency, true);
                                        }
                                    @endphp
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="time">Frequency *</label>
                                            <select name="medicines[0][med_frequency]" id="med_frequency"
                                                class="form-control rounded med_frequency" required>
                                                <option value="">Select</option>
                                                @foreach ($medicine_frequency as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if ($pre_med_frequency == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 {{ $other_pre_med_frequency_weekdiv }}" id="weekly_type_div">
                                        <div class="form-group select-2-full">
                                            <label for="exampleFormControlSelect1" class="w-100">Select Week Days</label>
                                            @php
                                                $weekdays = config('const.week_days');
                                            @endphp
                                            <select class="form-control select-week-days" id="week_days_select"
                                                name="medicines[0][other_med_frequency_week][]" multiple="multiple">
                                                @foreach ($weekdays as $day)
                                                    <option value="{{ $day }}"
                                                        {{ in_array($day, $week_array) ? 'selected' : '' }}>
                                                        {{ $day }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 {{ $other_pre_med_frequency_monthdiv }}" id="monthly_type_div">
                                        <div class="form-group select-2-full">
                                            <label for="exampleFormControlSelect1">Select Month Days</label>
                                            <div class="input-group date month_date" id="specific-datepicker">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" name="medicines[0][other_med_frequency_month][]"
                                                    value="{{ $month_array }}" id="selected_month_dates"
                                                    placeholder="Select Month Dates" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 {{ !empty($medicine) && ($medicine->med_frequency == 5 || $medicine->med_frequency == 3) ? '' : 'd-none' }}"
                                        id="bi_daily_type_div">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <div>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" name="medicines[0][bi_daily_start_date]"
                                                        value="{{ !empty($medicine) && !empty($medicine->bi_daily_start_date) ? date('m/d/Y', strtotime($medicine->bi_daily_start_date)) : '' }}"
                                                        id="bi_daily_start_date" placeholder="Start Date"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <div>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" name="medicines[0][bi_daily_end_date]"
                                                        value="{{ !empty($medicine) && !empty($medicine->bi_daily_end_date) ? date('m/d/Y', strtotime($medicine->bi_daily_end_date)) : '' }}"
                                                        id="bi_daily_end_date" placeholder="End Date"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-12">
                                        <div class="align-items-center d-flex">
                                            <label class="checkbox-inline i-checks mr-2 flex-shrink-0 mb-0">
                                                <input type="checkbox" name="medicines[0][time_id][]" value="1"
                                                    class="time-checkbox"
                                                    {{ in_array(1, $pre_time_array) ? 'checked' : '' }} /> Morning
                                            </label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input type="text"
                                                    value="{{ array_key_exists(1, $medicine_time_array) ? $medicine_time_array[1] : '' }}"
                                                    name="medicines[0][medicine_time][1][]" id="am_time"
                                                    placeholder="Select Time"
                                                    class="form-control rounded am-time timepicker-input" readonly>
                                                <span class="input-group-addon">
                                                    <span class="fa fa-clock-o"></span>
                                                </span>
                                            </div>

                                            <button type="button" class="btn btn-primary ml-2 add-clockpicker"
                                                data-id="1">+</button>
                                            <input type="hidden" id="add-clockpicker-1" value=0>
                                        </div>
                                        <!-- Container to hold additional clockpickers -->
                                        <div id="additional-clockpickers-1"
                                            class="align-items-end d-flex flex-column justify-content-end w-100 mb-2">
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <div class="align-items-center d-flex">
                                            <label class="checkbox-inline i-checks mr-2 flex-shrink-0 mb-0">
                                                <input type="checkbox" name="medicines[0][time_id][]" value="2"
                                                    class="time-checkbox"
                                                    {{ in_array(2, $pre_time_array) ? 'checked' : '' }} /> Afternoon
                                            </label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input type="text"
                                                    value="{{ array_key_exists(2, $medicine_time_array) ? $medicine_time_array[2] : '' }}"
                                                    name="medicines[0][medicine_time][2][]" id="pm_time"
                                                    placeholder="Select Time"
                                                    class="form-control rounded am-time timepicker-input" readonly>
                                                <span class="input-group-addon">
                                                    <span class="fa fa-clock-o"></span>
                                                </span>
                                            </div>

                                            <button type="button" class="btn btn-primary ml-2 add-clockpicker"
                                                data-id="2">+</button>
                                            <input type="hidden" id="add-clockpicker-2" value=0>
                                        </div>
                                        <!-- Container to hold additional clockpickers -->
                                        <div id="additional-clockpickers-2"
                                            class="align-items-end d-flex flex-column justify-content-end w-100 mb-2">
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <div class="align-items-center d-flex">
                                            <label class="checkbox-inline i-checks mr-2 flex-shrink-0 mb-0">
                                                <input type="checkbox" name="medicines[0][time_id][]" value="3"
                                                    class="time-checkbox"
                                                    {{ in_array(3, $pre_time_array) ? 'checked' : '' }} /> Evening
                                            </label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input type="text"
                                                    value="{{ array_key_exists(3, $medicine_time_array) ? $medicine_time_array[3] : '' }}"
                                                    name="medicines[0][medicine_time][3][]" id="evening_time"
                                                    placeholder="Select Time"
                                                    class="form-control rounded am-time timepicker-input" readonly>
                                                <span class="input-group-addon">
                                                    <span class="fa fa-clock-o"></span>
                                                </span>
                                            </div>

                                            <button type="button" class="btn btn-primary ml-2 add-clockpicker"
                                                data-id="3">+</button>
                                            <input type="hidden" id="add-clockpicker-3" value=0>
                                        </div>
                                        <!-- Container to hold additional clockpickers -->
                                        <div id="additional-clockpickers-3"
                                            class="align-items-end d-flex flex-column justify-content-end w-100 mb-2">
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <div class="align-items-center d-flex">
                                            <label class="checkbox-inline i-checks mr-2 flex-shrink-0 mb-0">
                                                <input type="checkbox" name="medicines[0][time_id][]" value="4"
                                                    class="time-checkbox"
                                                    {{ in_array(4, $pre_time_array) ? 'checked' : '' }} /> Night
                                            </label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input type="text"
                                                    value="{{ array_key_exists(4, $medicine_time_array) ? $medicine_time_array[4] : '' }}"
                                                    name="medicines[0][medicine_time][4][]" id="night_time"
                                                    placeholder="Select Time"
                                                    class="form-control rounded am-time timepicker-input" readonly>
                                                <span class="input-group-addon">
                                                    <span class="fa fa-clock-o"></span>
                                                </span>
                                            </div>

                                            <button type="button" class="btn btn-primary ml-2 add-clockpicker"
                                                data-id="4">+</button>
                                            <input type="hidden" id="add-clockpicker-4" value=0>
                                        </div>
                                        <!-- Container to hold additional clockpickers -->
                                        <div id="additional-clockpickers-4"
                                            class="align-items-end d-flex flex-column justify-content-end w-100 mb-2">
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <div class="align-items-center d-flex">
                                            <label class="checkbox-inline i-checks mr-2 flex-shrink-0 mb-0">
                                                <input type="checkbox" name="medicines[0][time_id][]" value="5"
                                                    class="time-checkbox"
                                                    {{ in_array(5, $pre_time_array) ? 'checked' : '' }} /> Other
                                            </label>
                                            <div class="w-100">
                                                <select name="medicines[0][medicine_time][5]" id="time_id"
                                                    class="form-control rounded am-time">
                                                    <option value="">Select</option>
                                                    @foreach ($medicine_time_other as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ array_key_exists(5, $medicine_time_array) && $medicine_time_array[5] == $key ? 'selected' : '' }}>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <div class="align-items-center d-flex">
                                            <label class="checkbox-inline i-checks mr-2 flex-shrink-0 mb-0 w-100">
                                                <input type="checkbox" name="medicines[0][time_id][]" value="6"
                                                    class="time-checkbox"
                                                    {{ in_array(6, $pre_time_array) ? 'checked' : '' }} /> As Needed <span
                                                    class="as-needed-info"><i class="fa fa-info-circle"
                                                        aria-hidden="true"></i> Use this for ad-hoc medicine.</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="check_time_data" style="color:red"></div>
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <div class="col-md-12 text-right">
                                    <a class="btn btn-white btn-sm" type="button"
                                        href="{{ createCancelUrl(route('patients.show', $patient_id)) }}">Cancel</a>
                                    <button class="btn btn-sm btn-primary" type="submit"
                                        id="patientMedicineForm">Save</button>
                                </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
           // Initialize start date picker
            $('#bi_daily_start_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                startDate: new Date() // Start from current date
            }).on('changeDate', function(selected) {
                var startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate() + 1); // Add one day to start date

                // Update end date picker to allow selection only after the chosen start date
                $('#bi_daily_end_date').datepicker('setStartDate', startDate);
            });

            // Initialize end date picker
            $('#bi_daily_end_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                startDate: new Date() // Start from current date
            });
            
            // Function to set end date to one month after start date
            function setEndDateOneMonthAfter(startDate) {
                var endDate = new Date(startDate);
                endDate.setMonth(endDate.getMonth() + 1); // Set end date to one month after start date
                $('#bi_daily_end_date').datepicker('setStartDate', '-1d'); // Reset start date restriction
                $('#bi_daily_end_date').datepicker('setDate', endDate);
            }

            // When start date changes, update end date accordingly
            $('#bi_daily_start_date').on('changeDate', function(selected) {
                // setEndDateOneMonthAfter(selected.date);
            });
            //$('.clockpicker').clockpicker();

            // Initialize end date one month after today
            // setEndDateOneMonthAfter(new Date());
            //    var allowedTimes = ['09:00', '10:00'];
            //    function isTimeAllowed(time) {
            //             return allowedTimes.includes(time);
            //         }
            /* var hour_min =5:00;
            var hour_max = 11:45;
            $('.clockpicker').clockpicker({
                autoclose: true,
                donetext: "Done",
                afterShow: function() {
                    // Disable hours outside the specified range
                    $(".clockpicker-hours").find(".clockpicker-tick").each(function(index, element) {
                        var hour = parseInt($(element).html());
                        if (hour < hour_min || hour > hour_max) {
                            $(element).addClass('clockpicker-tick-disabled').off('click');
                        }
                    });
                    
                   
                },
            }); */

            // $(document).on('click', '.clockpicker-tick-disabled', function(event){
            // 	event.stopImmediatePropagation();
            // 	event.preventDefault();
            // })
            // $('.clockpicker-tick-disabled').on('click', function(event) {
            //                         event.stopImmediatePropagation();
            //                         event.preventDefault();
            //                     });


            $('#medicine_type').change(function() {
                if ($(this).val() == 5) {
                    $('#other_medicine_type_div').removeClass('d-none');
                    $('#medicine_type_other').attr('required', true);
                } else {
                    $('#other_medicine_type_div').addClass('d-none');
                    $('#medicine_type_other').attr('required', false);
                }
            })

            $('#time_id').change(function() {
                if ($(this).val() && $(this).val().includes('4')) {
                    $('#other_time_div').addClass('d-none');
                    $('#time_other').attr('required', false);
                } else {

                    $('#other_time_div').removeClass('d-none');
                    $('#time_other').attr('required', true);
                }
            })
            $('.med_time_select').select2({
                placeholder: 'Select a time',
            });


            $('#intake_method').change(function() {
                if ($(this).val() == 4) {
                    $('#other_intake_method_div').removeClass('d-none');
                    $('#other_intake_method').attr('required', true);
                } else {
                    $('#other_intake_method_div').addClass('d-none');
                    $('#other_intake_method').attr('required', false);
                }
            })
            $('#intake_supervised_by').change(function() {
                if ($(this).val() == 4) {
                    $('#other_intake_supervised_by_div').removeClass('d-none');
                    $('#intake_supervised_other').attr('required', true);
                } else {
                    $('#other_intake_supervised_by_div').addClass('d-none');
                    $('#intake_supervised_other').attr('required', false);
                }
            })
            $('#dose-quantity').change(function() {
                if ($(this).val() == 'other') {
                    $('#other_dose_med_div').removeClass('d-none');
                    $('#other_med_option').attr('required', true);
                } else {
                    $('#other_dose_med_div').addClass('d-none');
                    $('#other_med_option').attr('required', false);
                }
            })
            $('.med_frequency').change(function() {
                if ($(this).val() == 1) {
                    $('#weekly_type_div').addClass('d-none');
                    $('#monthly_type_div').addClass('d-none');
                    $('#week_days_select').attr('required', false);
                    $('#selected_month_dates').attr('required', false);
                    $('#bi_daily_type_div').addClass('d-none');
                    $('#bi_daily_start_date').attr('required', false);
                    $('[data-custom="specific-datepicker"]').on('show.bs.modal', function() {
                        $(this).find('.prev, .next').css('display', 'none');
                    });
                } else if ($(this).val() == 2) {
                    $('#bi_daily_type_div').addClass('d-none');
                    $('#weekly_type_div').removeClass('d-none');
                    $('#monthly_type_div').addClass('d-none');
                    $('#week_days_select').attr('required', true);
                    $('#selected_month_dates').attr('required', false);
                    $('#bi_daily_start_date').attr('required', false);
                    $('[data-custom="specific-datepicker"]').on('show.bs.modal', function() {
                        $(this).find('.prev, .next').css('display', 'none');
                    });
                } else if ($(this).val() == 3) {
                    $('#weekly_type_div').removeClass('d-none');
                    $('#monthly_type_div').addClass('d-none');
                    $('#week_days_select').attr('required', true);
                    $('#selected_month_dates').attr('required', false);
                    $('#bi_daily_type_div').removeClass('d-none');
                    $('#bi_daily_start_date').attr('required', true);
                    $('[data-custom="specific-datepicker"]').on('show.bs.modal', function() {
                        $(this).find('.prev, .next').css('display', 'none');
                    });
                } else if ($(this).val() == 5) {
                    $('#weekly_type_div').addClass('d-none');
                    $('#bi_daily_type_div').removeClass('d-none');
                    $('#monthly_type_div').addClass('d-none');
                    $('#week_days_select').attr('required', false);
                    $('#bi_daily_start_date').attr('required', true);
                    $('#selected_month_dates').attr('required', false);
                    $('[data-custom="specific-datepicker"]').on('show.bs.modal', function() {
                        $(this).find('.prev, .next').css('display', 'block');
                    });
                } else {
                    $('#bi_daily_type_div').addClass('d-none');
                    $('#weekly_type_div').addClass('d-none');
                    $('#monthly_type_div').removeClass('d-none');
                    $('#week_days_select').attr('required', false);
                    $('#selected_month_dates').attr('required', true);
                    $('#bi_daily_start_date').attr('required', false);
                    $('[data-custom="specific-datepicker"]').on('show.bs.modal', function() {
                        $(this).find('.prev, .next').css('display', 'none');
                    });
                }
            })

            function formatState(state) {
                if (!state.id) {
                    return state.text;
                }
                var isSelected = $(state.element).prop('selected');
                var $state = $(
                    '<span><input type="checkbox" ' + (isSelected ? 'checked' : '') + ' /> ' + state.text +
                    '</span>'
                );
                return $state;
            }

            function initializeSelect2(selector, placeholderText) {
                $(selector).select2({
                    placeholder: placeholderText,
                    closeOnSelect: false,
                    templateResult: formatState,
                    templateSelection: function(state) {
                        return state.text;
                    }
                });
            }

            initializeSelect2('.select-week-days', 'Select week days');
            initializeSelect2('.select_month_dates', 'Select month days');

            function updateCheckboxState(e) {
                var $select = $(e.target);
                $select.find('option').each(function() {
                    var $option = $(this);
                    var $checkbox = $select.siblings('.select2-container').find('li[title="' + $option
                        .text() + '"] input[type="checkbox"]');
                    $checkbox.prop('checked', $option.prop('selected'));
                });
            }

            function clearOtherDropdowns(selectedDropdown) {
                if (selectedDropdown === 'week') {
                    $('.select_month_dates').val(null).trigger('change');
                } else if (selectedDropdown === 'month') {
                    $('.select-week-days').val(null).trigger('change');
                }
            }

            $('.select-week-days').on('select2:select select2:unselect', function(e) {
                updateCheckboxState(e);
                clearOtherDropdowns('week');
            });

            $('.select_month_dates').on('select2:select select2:unselect', function(e) {
                updateCheckboxState(e);
                clearOtherDropdowns('month');
            });

            // Trigger updateCheckboxState on initial load to ensure checkboxes reflect initial selections
            $('.select-week-days').trigger('change.select2');
            //  $('.select_month_dates').trigger('change.select2');
            $('#specific-datepicker').datepicker({
                format: 'dd',
                forceParse: false,
                multidate: true,
                clearBtn: true,
                beforeShowDay: function(date) {
                    var day = date.getDate();
                    if (day >= 1 && day <= 31) {
                        return {
                            classes: 'day'
                        };
                    } else {
                        return false;
                    }
                }
            }).on('show', function(e) {
                $(this).find('.datepicker-days .prev, .datepicker-days .next').css('display', 'none');
                // Ensure the button is only added once
                if (!$('.datepicker-footer').length) {
                    var $footer = $('<div class="datepicker-footer text-center mt-2"></div>');
                    var $button = $('<button class="btn btn-primary btn-sm date-ok-btn">OK</button>');
                    $footer.append($button);

                    // Append the footer to the datepicker
                    $('.datepicker-days').append($footer);

                    // Add click event to the button
                    $button.on('click', function() {
                        $('.input-group.date.month_date').datepicker('hide');
                        // You can add any other action you need here
                    });
                }
            }).on('changeDate', function(e) {
                var selectedDates = e.dates;
            });
            $('#specific-datepicker').on('show', function(e) {
                $('.datepicker-days .prev, .datepicker-days .next').css('display', 'none');
            });
        });
        $(document).ready(function() {
            function updateCheckboxState() {
                var isAmChecked = $('[name="medicines[0][time_id][]"][value="1"]').is(':checked');
                var isPmChecked = $('[name="medicines[0][time_id][]"][value="2"]').is(':checked');
                var isEveningChecked = $('[name="medicines[0][time_id][]"][value="3"]').is(':checked');
                var isNightChecked = $('[name="medicines[0][time_id][]"][value="4"]').is(':checked');
                var isOtherChecked = $('[name="medicines[0][time_id][]"][value="5"]').is(':checked');
                var isAsNeededChecked = $('[name="medicines[0][time_id][]"][value="6"]').is(':checked');

                // Disable/Enable checkboxes based on the conditions
                if (isAmChecked || isPmChecked || isEveningChecked || isNightChecked) {
                    $('[name="medicines[0][time_id][]"][value="5"], [name="medicines[0][time_id][]"][value="6"]')
                        .prop('disabled', true).closest('div').addClass('disabled');
                } else {
                    $('[name="medicines[0][time_id][]"][value="5"], [name="medicines[0][time_id][]"][value="6"]')
                        .prop('disabled', false).closest('div').removeClass('disabled');
                }

                if (isOtherChecked) {
                    $('[name="medicines[0][time_id][]"][value="1"], [name="medicines[0][time_id][]"][value="2"], [name="medicines[0][time_id][]"][value="3"], [name="medicines[0][time_id][]"][value="4"], [name="medicines[0][time_id][]"][value="6"]')
                        .prop('disabled', true).closest('div').addClass('disabled');
                } else if (!isAmChecked && !isPmChecked && !isEveningChecked && !isNightChecked) {
                    $('[name="medicines[0][time_id][]"][value="1"], [name="medicines[0][time_id][]"][value="2"], [name="medicines[0][time_id][]"][value="3"], [name="medicines[0][time_id][]"][value="4"]')
                        .prop('disabled', false).closest('div').removeClass('disabled');
                }

                if (isAsNeededChecked) {
                    $('.time-checkbox').not('[name="medicines[0][time_id][]"][value="6"]').prop('disabled', true)
                        .closest('div').addClass('disabled');
                } else if (!isOtherChecked && !isAmChecked && !isPmChecked && !isEveningChecked && !
                    isNightChecked) {
                    $('.time-checkbox').prop('disabled', false).closest('div').removeClass('disabled');
                }

                // Update required attribute on inputs
                $('.time-checkbox').each(function() {
                    const input = $(this).closest('.align-items-center').find('.am-time');
                    if (this.checked) {
                        input.prop('required', true);
                        input.prop('readonly', true);
                        input.addClass('bg-white');
                    } else {
                        input.prop('required', false);
                        input.prop('readonly', true);
                        input.removeClass('bg-white');
                    }
                });
            }

            $('.time-checkbox').each(function() {
                const input = $(this).closest('.align-items-center').find('.am-time');
                const clockpicker = input.closest('.clockpicker');
                const additionalClockpickers = $(`#additional-clockpickers-${$(this).val()}`);

                // Initial state
                input.prop('disabled', !this.checked);
                input.prop('required', this.checked);
                //clockpicker.css('pointer-events', this.checked ? 'auto' : 'none');

                /* 	 if (this.checked) {
                		intializeClockPicker(input);
                	}  */

                // Add event listener to each checkbox
                $(this).on('ifChanged', function() {
                    
                    input.prop('disabled', !this.checked);
                    input.prop('required', this.checked);
                    //clockpicker.css('pointer-events', this.checked ? 'auto' : 'none');
                    if (!this.checked) {
                        input.val('');
                        // Remove all additional clockpickers and reset counter
                        additionalClockpickers.empty();
                        $('#add-clockpicker-' + $(this).val()).val(0); // Reset the counter

                        // Destroy the clockpicker
                        input.clockpicker('remove');
                    } else {
                        // Initialize the clockpicker

                        if (input.val() == '') {
                            intializeClockPicker(input);
                        }
                    }
                    updateCheckboxState();
                });
            });

            // Initial call to set the correct state on page load
            updateCheckboxState();

        });
        $(document).on("blur", ".timepicker-input", function() {

            $(this).clockpicker('remove');
            //$(this).val('');
            intializeClockPicker($(this))
        })

        function intializeClockPicker(ele) {
            var ele_id = ele.attr('id');
            $('#' + ele_id).attr('autocomplete', 'off');
            var start = '';
            var end = '';
            //dd($m_start, $m_end,$a_start,$a_end,$e_start,$e_end ,$n_start,$n_end);	
            if (ele_id == 'am_time' || ele_id == 'am_time1' || ele_id == 'am_time2') {
                start = '<?php echo $m_start; ?>';
                end = '<?php echo $m_end; ?>';
            } else if (ele_id == 'pm_time' || ele_id == 'pm_time1' || ele_id == 'pm_time2') {
                start = '<?php echo $a_start; ?>';
                end = '<?php echo $a_end; ?>';
            } else if (ele_id == 'evening_time' || ele_id == 'evening_time1' || ele_id == 'evening_time2') {
                start = '<?php echo $e_start; ?>';
                end = '<?php echo $e_end; ?>';
            } else if (ele_id == 'night_time' || ele_id == 'night_time1' || ele_id == 'night_time2') {
                start = '<?php echo $n_start; ?>';
                end = '<?php echo $n_end; ?>';
            }
            var startHour = parseInt(start.split(':')[0], 10);
            var endHour = parseInt(end.split(':')[0], 10);
            var startMinute = parseInt(start.split(':')[1], 10);
            var endMinute = parseInt(end.split(':')[1], 10);

            if (startMinute == endMinute) {
                endMinute = '59';
            }

            let allowedHours = [];
            let allowedMinutes = [];

            // Loop through hours from 5 to 11
            for (let hour = startHour; hour <= endHour; hour++) {
                // Format hour as string with leading zeros if needed
                let formattedHour = hour;

                // Add hour to hours array
                allowedHours.push(formattedHour);

                // Loop through minutes (0 and 30)

            }
            for (let minute = startMinute; minute <= endMinute; minute++) {
                // Format minute as string with leading zeros if needed
                let formattedMinute = minute;

                // Add minute to allowedMinutes array
                allowedMinutes.push(formattedMinute);
            }

            // Display or use hours and minutes arrays

            var timepicker = $('#' + ele_id).clockpicker({
                autoclose: true,
                twelvehour: false, // Set to false for 24-hour format
                afterHourSelect: function() {
                    var c = timepicker.data();
                    if ($.inArray(c.clockpicker.hours, allowedHours) === -1) {
                        $('.clockpicker-span-hours').text('00');
                    }
                },
                afterDone: function() {
                    var selectedTime = timepicker.val(); // Get the selected time from input
                    var hours = selectedTime.split(':')[0]; // Extract hours
                    hours = parseInt(hours, 10);


                    //  var minutes = selectedTime.split(':')[1];
                    //  minutes = parseInt(minutes, 10);
                    if ($.inArray(hours, allowedHours) === -1) {
                        $('.clockpicker-span-hours').text('00');
                        // timepicker.val('00:'+selectedTime.split(':')[1])
                        timepicker.val("")
                    }
                    // if( $.inArray(minutes, allowedMinutes) === -1){
                    // 	$('.clockpicker-span-hours').text('00');
                    // 	// timepicker.val('00:'+selectedTime.split(':')[1])
                    // 	timepicker.val("")
                    // }
                },
                // afterMinuteSelect: function () {
                // 	var c = timepicker.data();
                // 	if ($.inArray(c.clockpicker.minutes, allowedMinutes) === -1) {
                // 		$('.clockpicker-span-minutes').text('00');
                // 		timepicker.val("")
                // 	}
                // },
                beforeShow: function() {
                    // Add a slight delay to ensure the clockpicker DOM elements are available
                    setTimeout(function() {
                        // Disable all hours that are not allowed
                        $('.clockpicker-hours .clockpicker-tick').each(function() {
                            var hour = parseInt($(this).text());
                            if ($.inArray(hour, allowedHours) === -1) {
                                $(this).addClass('clockpicker-tick-disabled');
                            }
                        });

                        // Disable all minutes that are not allowed
                        $('.clockpicker-minutes .clockpicker-tick').each(function() {
                            var minute = parseInt($(this).text());

                            if ($.inArray(minute, allowedMinutes) === -1) {
                                $(this).addClass('clockpicker-tick-disabled');
                            }
                        });



                    }, 100); // Adjust the delay if necessary
                }
            });

        }

        $(document).ready(function() {
            var maxClockpickers = 2; // Maximum number of additional clockpickers
            //var counts = { 1: 0, 2: 0, 3: 0, 4: 0 }; // Counters for each shift

            $('.add-clockpicker').on('click', function() {
                var shift = $(this).data('id');
                var counts = $('#add-clockpicker-' + shift).val();

                //var shift_count = counts[shift]+1;
                var shift_count = parseInt(counts) + 1;
                var time = shift == 1 ? 'am_time' + shift_count : shift == 2 ? 'pm_time' + shift_count :
                    shift == 3 ? 'evening_time' + shift_count : 'night_time' + shift_count;

                //if (counts[shift] < maxClockpickers) {
                if (counts < maxClockpickers) {
                    var newClockpicker = `
						<div class="align-items-center d-flex mt-1 w-100" style="max-width: 254px;">
						<div class="input-group clockpicker" data-autoclose="true">
							<input type="text" name="medicines[0][medicine_time][${shift}][]" id="${time}" placeholder="Select Time" class="form-control rounded timepicker-input am-time" readonly>
							<span class="input-group-addon">
								<span class="fa fa-clock-o"></span>
							</span>
						</div>
						<button type="button" class="btn btn-warning ml-2 remove-clockpicker" style="min-width: 33.5px" data-id="${shift}">-</button>
					</div>
					`;
                    $(`#additional-clockpickers-${shift}`).append(newClockpicker);
                    intializeClockPicker($(`#${time}`));
                    //counts[shift]++;
                    counts++;
                    $('#add-clockpicker-' + shift).val(counts);
                    // If the checkbox is checked, remove readonly and initialize clockpicker
                    if ($(`input[name="medicines[0][time_id][]"][value="${shift}"]`).is(':checked')) {
                        $(`#${time}`).prop('readonly', false);
                        $(`#${time}`).addClass('bg-white');
                        //initializeClockPicker($(`#${time}`));
                    }
                } else {
                    toastAlert('error', 'You can only add up to 2 additional time pickers.');
                }
            });

            // Remove clockpicker
            $(document).on('click', '.remove-clockpicker', function() {
                var shift = $(this).data('id');
                var counts = $('#add-clockpicker-' + shift).val();
                $(this).closest('.align-items-center').remove(); // Remove the entire row
                //$(this).prev('.input-group').remove(); // Remove the input-group element
                //$(this).remove(); // Remove the button
                //counts[shift]--; // Decrease the count
                counts--; // Decrease the count
                $('#add-clockpicker-' + shift).val(counts);
            });
        });
    </script>
@endsection
