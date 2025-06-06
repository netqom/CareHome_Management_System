@extends('layouts.admin')

@section('title', 'Create/Update Patient Document')
@section('style')
    <link href="{{ asset('assets/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
@endsection
@section('content')
    <style>
        .select-week-days~.select2-container {
            width: 100% !important;
        }

        .select2-results__option {
            display: flex;
            align-items: center;
        }

        .select2-results__option input {
            margin-right: 10px;
        }
    </style>
    @php
        $week_array = [];
        $month_array = '';
        $activity_weekdiv = 'd-none';
        $activity_monthdiv = 'd-none';
        if ($assigned_activity && $assigned_activity->recurrence == 2) {
            $activity_weekdiv = '';
            $week_array = json_decode($assigned_activity->activity_performance_day, true);
        }
        if ($assigned_activity && $assigned_activity->recurrence == 3) {
            $activity_monthdiv = '';
            $month_array = implode(',', json_decode($assigned_activity->activity_performance_day, true));
        }
    @endphp
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
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
                    <strong>{{ $id == 0 ? 'Assign Activity to' : 'Update assigned acctivity to' }} Patient</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12 dark-bg">
                <form method="POST" role="form"
                    action="{{ route('save-patient-assign-activity', ['patient_id' => $patient_id, 'id' => $id]) }}"
                    id="patient_assign_activity_Form" enctype="multipart/form-data">
                    @csrf
                    <div class="ibox ">
                        <div class="ibox-content ff shadow border rounded">
                            <div class="ibox-content">

                                <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient_id }}">
                                <input type="hidden" name="home_id" id="patient_id" value="{{ $home_id }}">
                                <input type="hidden" name="type" id="patient_id" value="2">
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-4   @error('shift_id') has-error @enderror">
                                        <label class="col-form-label">Shift *</label>
                                        <div class="">
                                            @php $shift_ids = $assigned_activity ? explode(',', $assigned_activity->shift_id) : []; @endphp
                                            @foreach ($shifts as $key => $shift)
                                                @php $checked = ''; @endphp
                                                @if (in_array($shift, $shift_ids))
                                                    $checked = 'checked';
                                                @endif
                                                <label class="checkbox-inline i-checks mr-2">
                                                    <input type="checkbox" name="activity[0][activity_shift_id][]"
                                                        value="{{ $key }}"
                                                        @if (in_array($key, $shift_ids)) checked="true" @endif>
                                                    <i></i> {{ $shift }}
                                                </label>
                                            @endforeach
                                            @error('shift_id')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4   @error('name') has-error @enderror">
                                        <label class="col-form-label">Name *</label>
                                        <div class="">
                                            <input type="text" name="activity[0][activity_name]" placeholder="Name"
                                                class="form-control" required autocomplete="off"
                                                value="{{ $assigned_activity ? $assigned_activity->name : old('name') }}">
                                            @error('name')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="duration">Duration (in minutes) *</label>
                                            <input type="number" name="activity[0][activity_duration]"
                                                id="activity_duration" placeholder="Duration"
                                                class="form-control rounded integer_no" required
                                                value="{{ $assigned_activity ? $assigned_activity->duration : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="contact_no">Frequency *</label>
                                            <input type="number" name="activity[0][activity_frequency]"
                                                id="activity_frequency" placeholder="Frequency"
                                                class="form-control rounded integer_no" required
                                                value="{{ $assigned_activity ? $assigned_activity->frequency : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="contact_no">Recurrence *</label>
                                            <select class="form-control m-b" name="activity[0][activity_recurrence]"
                                                id="activity_recurrence" required>
                                                <option value="">Choose option</option>
                                                @foreach ($activity_recurrence as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if ($assigned_activity && $assigned_activity->recurrence == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group {{ $activity_weekdiv }}" id="activity-perform_week">
                                            <label for="contact_no">Activity Perform * (<span id="activity-perform-lbl">in
                                                    week</span>)</label>
                                            @php
                                                $weekdays = config('const.week_days');
                                            @endphp
                                            <select class="form-control select-week-days w-100" id="week_days_select"
                                                name="activity[0][activity_performance_day][]" multiple="multiple">
                                                @foreach ($weekdays as $day)
                                                    <option value="{{ $day }}"
                                                        {{ in_array($day, $week_array) ? 'selected' : '' }}>
                                                        {{ $day }}</option>
                                                @endforeach

                                            </select>
                                            {{-- <input type="number" name="activity[0][activity_performance_day]" id="activity_performance_day" class="form-control rounded integer_no" placeholder="2" value="{{$assigned_activity ? $assigned_activity->activity_performance_day : ''}}"> --}}
                                        </div>
                                        <div class="form-group {{ $activity_monthdiv }}" id="activity-perform_month">
                                            <label for="contact_no">Activity Perform * (<span id="activity-perform-lbl">in
                                                    month</span>)</label>
                                           
                                            <div class="input-group date month_date" id="specific-datepicker">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" name="activity[0][activity_performance_month]"
                                                    value="{{ $month_array }}" id="selected_month_dates"
                                                    placeholder="Select Month Dates" class="form-control">
                                            </div>
                                            {{-- <input type="number" name="activity[0][activity_performance_day]" id="activity_performance_day" class="form-control rounded integer_no" placeholder="2" value="{{$assigned_activity ? $assigned_activity->activity_performance_day : ''}}"> --}}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 @error('status') has-error @enderror">
                                        <label class="col-form-label">Status *</label>
                                        <div class="">
                                            @php
                                                $pre_seleted = isset($assigned_activity->status)
                                                    ? $assigned_activity->status
                                                    : '';
                                            @endphp
                                            <select class="form-control m-b" name="activity[0][activity_status]" required>
                                                <option value="">Select status</option>
                                                <option value="1" {{ $pre_seleted == 1 ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="0" {{ $pre_seleted == 0 ? 'selected' : '' }}>In-Active
                                                </option>
                                            </select>
                                            @error('status')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">Description *</label>
                                            <textarea class="form-control rounded" name="activity[0][activity_description]" id="activity_description"
                                                placeholder="Description" rows="4" autocomplete="off" required>{{ $assigned_activity ? $assigned_activity->description : '' }}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a class="btn btn-white btn-sm" type="button"
                                href="{{ createCancelUrl(route('patients.show', $patient_id)) }}">Cancel</a>
                            <button class="btn btn-sm btn-primary" type="submit" id="patientActivityForm">Save</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
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
                console.log("Selected Dates: ", selectedDates);
            });
            $('#specific-datepicker').on('show', function(e) {
                $('.datepicker-days .prev, .datepicker-days .next').css('display', 'none');
            });
            var recurrence = {{ $assigned_activity ? $assigned_activity->recurrence : '1' }};

            if (recurrence == "2") {
                $('#activity-perform-lbl').text('in week');
            } else if (recurrence == "3") {
                $('#activity-perform-lbl').text('in month');
            }
            // Show or hide the div based on the selected option
            if (recurrence == "2" || recurrence == "3") {
                $('#activity-perform').removeClass('d-none');
            } else {
                $('#activity-perform').addClass('d-none');
            }

            // show hide activity perform div (in days)

            $('#activity_recurrence').change(function() {
                var selectedValue = $(this).val();

                // Show or hide the div based on the selected option
                if (selectedValue === "2") {
                    $('#activity-perform_week').removeClass('d-none');
                    $('#activity-perform_month').addClass('d-none');
                    $('#week_days_select').addClass('required');
                    $('#selected_month_dates').removeClass('required');
                } else if (selectedValue === "3") {
                    $('#activity-perform_week').addClass('d-none');
                    $('#activity-perform_month').removeClass('d-none');
                    $('#week_days_select').removeClass('required');
                    $('#selected_month_dates').addClass('required');
                    $('[data-custom="specific-datepicker"]').on('show.bs.modal', function() {
                        $(this).find('.prev, .next').css('display', 'none');
                    });
                } else {
                    $('#activity-perform_month').addClass('d-none');
                    $('#selected_month_dates').removeClass('required');
                    $('#activity-perform_week').addClass('d-none');
                    $('#week_days_select').removeClass('required');
                }
            })

        })
    </script>
@endsection
