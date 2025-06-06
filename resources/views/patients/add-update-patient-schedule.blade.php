@extends('layouts.admin')

@section('title', 'Create/Update Patient Schedule')
@section('style')
    <link href="{{ asset('assets/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/plugins/clockpicker/clockpicker.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
@endsection
@section('content')
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
                    <strong>{{ $id == 0 ? 'Create' : 'Update' }} Patient Schedule</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <form method="POST" role="form"
            action="{{ route('save-patients-schedule', ['patient_id' => $patient_id, 'id' => $id]) }}" id="schedule_form">
            @csrf
            <div class="row">

                <div class="col-xl-8 col-lg-12 col-md-12 mt-5 dark-bg">
                    <div class="ibox ">
                        <div class="ibox-content ff shadow border rounded">
                            <div class="">
                                <div class="row">
                                    <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient_id }}">
                                    <div class="col-sm-12 col-md-12 col-lg-6 form-group">
                                        <label class=" col-form-label" for="start_date">Appointment Date *</label>
                                        <div class=" input-group date start_date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" name="appointment_date"
                                                value="{{ !empty($appointment->appointment_date) ? date('m/d/Y', strtotime($appointment->appointment_date)) : '' }}"
                                                id="start_date" placeholder="Start Date" class="form-control required">
                                        </div>
                                    </div>
                                    <div
                                        class="col-sm-12 col-md-12 col-lg-6 form-group @error('start_time') has-error @enderror @error('end_time') has-error @enderror">
                                        <label class=" col-form-label" for="start_time">Appointment Time *</label>
                                        <div class=" input-group clockpicker" data-autoclose="true">
                                            <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                            <input type="time" class="form-control"
                                                value="{{ !empty($appointment->appointment_time) ? $appointment->appointment_time : '' }}"
                                                name="appointment_time" id="start_time" placeholder="Start Time"
                                                class="form-control rounded required bg-white" readonly required>
                                            @error('appointment_time')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div
                                        class="col-sm-12 col-md-12 col-lg-6 form-group @error('title') has-error @enderror">
                                        <label class=" col-form-label">Title *</label>
                                        <div class="">
                                            <input type="text" name="title" placeholder="Title" class="form-control"
                                                required autocomplete="off"
                                                value="{{ !empty($appointment->title) ? $appointment->title : '' }}">
                                            @error('title')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div
                                        class="col-sm-12 col-md-12 col-lg-6 form-group @error('doctor_name') has-error @enderror">
                                        <label class=" col-form-label">Doctor Name *</label>
                                        <div class="">
                                            <select name="doctor_name" placeholder="Select Doctor" class="form-control"
                                                autocomplete="off" value="" id="doctor_name_select" required>

                                                @if ($doctors->isEmpty())
                                                    <option value=""></option>
                                                    <option value="add_doc">Add Doctor</option>
                                                @else
                                                    <option value="">Select Doctor</option>
                                                @endif
                                                @forelse($doctors as $key=>$doctor)
                                                    <option value="{{ $key }}"
                                                        {{ !empty($appointment->doctor_name) && $appointment->doctor_name == $key ? 'selected' : '' }}>
                                                        {{ $doctor }}</option>
                                                @empty
                                                @endforelse
                                                <option value="other"
                                                    {{ !empty($appointment->doctor_name) && $appointment->doctor_name == 'other' ? 'selected' : '' }}>
                                                    Other</option>
                                                @error('doctor_name')
                                                    <span class="text-danger text-left d-block"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </select>
                                        </div>
                                    </div>
                                    @php
                                        $other_doctor_div = 'd-none';
                                        if ($appointment && $appointment->doctor_name == 'other') {
                                            $other_doctor_div = '';
                                        }
                                    @endphp
                                    <div class="col-12 col-lg-12 col-lg-6 form-group {{ $other_doctor_div }} @error('other_doctor') has-error @enderror"
                                        id="other_doctor_div">
                                        <label class=" col-form-label">Other Doctor</label>
                                        <div class="">
                                            <input class="form-control" name="other_doctor" id="other_doctor"
                                                placeholder="Enter Other Doctor Name" autocomplete="off"
                                                value="{{ !empty($appointment->other_doctor) ? $appointment->other_doctor : '' }}">
                                            @error('other_doctor')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-12 form-group @error('description') has-error @enderror">
                                        <label class=" col-form-label">Description *</label>
                                        <div class="">
                                            <textarea class="form-control" name="description" placeholder="Description" rows="3" required
                                                autocomplete="off">{{ !empty($appointment->description) ? $appointment->description : '' }}</textarea>
                                            @error('description')
                                                <span class="text-danger text-left d-block"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- <input type="hidden" name="redirectURL" value="{{ createCancelUrl(route('tasks-list')) }}"> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12 col-md-12 mt-xl-5 dark-bg">
                    <div class="ibox right-ibox">
                        <div class="ibox-content">

                            @php
                                $other_pre_med_frequency_weekdiv = 'd-none';
                                $week_array = [];
                                $month_array = '';
                                $other_pre_med_frequency_monthdiv = 'd-none';
                                $pre_med_frequency = isset($appointment->schedule_frequency)
                                    ? $appointment->schedule_frequency
                                    : '';
                                //$pre_med_frequency = $appointment->schedule_frequency ? $appointment->schedule_frequency : '';
                                if (isset($appointment->schedule_frequency) && $appointment->schedule_frequency == 4) {
                                    $other_pre_med_frequency_monthdiv = '';
                                    $month_array = implode(
                                        ',',
                                        json_decode($appointment->other_schedule_frequency, true),
                                    );
                                }
                                if (
                                    isset($appointment->schedule_frequency) &&
                                    ($appointment->schedule_frequency == 2 || $appointment->schedule_frequency == 3)
                                ) {
                                    $other_pre_med_frequency_weekdiv = '';
                                    $week_array = json_decode($appointment->other_schedule_frequency, true);
                                }
                            @endphp
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="time">Frequency *</label>
                                    <select name="appointment[schedule_frequency]" id="med_frequency"
                                        class="form-control rounded med_frequency required" required>
                                        <option value="">Select</option>
                                        @foreach ($medicine_frequency as $key => $value)
                                            {{-- @if ($key != 1) --}}
                                            <option value="{{ $key }}"
                                                {{ isset($appointment->schedule_frequency) && $appointment->schedule_frequency == $key ? 'selected' : '' }}
                                                @if (isset($appointment->schedule_frequency) && $appointment->schedule_frequency == $key) selected @endif>{{ $value }}
                                            </option>

                                            {{-- @endif --}}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 {{ $other_pre_med_frequency_weekdiv }}" id="weekly_type_div">
                                <div class="form-group select-2-full">
                                    <label for="exampleFormControlSelect1" class="w-100">Select Week Days *</label>
                                    @php
                                        $weekdays = config('const.week_days');
                                    @endphp
                                    <select class="form-control select-week-days" id="week_days_select"
                                        name="appointment[other_schedule_frequency_week][]" multiple="multiple">
                                        @foreach ($weekdays as $day)
                                            <option value="{{ $day }}"
                                                {{ in_array($day, $week_array) ? 'selected' : '' }}>{{ $day }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12 {{ $other_pre_med_frequency_monthdiv }}" id="monthly_type_div">
                                <div class="form-group select-2-full">
                                    <label for="exampleFormControlSelect1">Select Month Days *</label>

                                    <div class="input-group date month_date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" name="appointment[other_schedule_frequency_month][]"
                                            value="{{ $month_array }}" id="selected_month_dates"
                                            placeholder="Select Month Dates" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a class="btn btn-white btn-sm" type="button"
                                href="{{ createCancelUrl(route('tasks-list')) }}">Cancel</a>
                            <button class="btn btn-sm btn-primary" type="submit"
                                id="patient_schedule_form">Save</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    </div>
    <!-- Modal -->
    <div id="add_doctor_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header dark-bg-color radius-0">
                    <h5 class="modal-title color-white" id="myModalLabel">Add Doctor</h5>
                    <button type="button" class="close color-white" data-dismiss="modal" aria-hidden="true">×</button>

                </div>
                <div class="modal-body py-3 px-3">

                    <form method="POST" role="form" action="" id="patient_doctor_Form_schedule"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="ibox mb-0">
                            <div class="ibox-content ff p-0">
                                <div class="ibox-content p-0">

                                    <input type="hidden" name="patient_id" id="patient_id"
                                        value="{{ $patient_id }}">
                                    <fieldset class="w-100">

                                        <div class="row" id="doctor_div">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="doctor_name">Name *</label>
                                                    <input type="text" name="doctors[0][name]" placeholder="Name"
                                                        class="form-control rounded required">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="doctor_email">Email *</label>
                                                    <input type="email" name="doctors[0][email]" placeholder="Email"
                                                        class="form-control rounded required">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="doctor_phone_number">Phone No *</label>
                                                    <input type="tel" name="doctors[0][phone]"
                                                        placeholder="Phone No." class="form-control rounded required">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="doctor_address">Address *</label>
                                                    <input type="text" name="doctors[0][address]"
                                                        placeholder="Address" class="form-control rounded required">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="doctor_role">Designation *</label>
                                                    <input type="text" name="doctors[0][role]" placeholder="Role"
                                                        class="form-control rounded required">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="doctor_role">Fax Number *</label>
                                                    <input type="text" name="doctors[0][fax_number]"
                                                        placeholder="Fax Number"
                                                        class="form-control rounded valid_fax required">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0 pb-0 px-0">
                            <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Close</button>
                            <input type="hidden" class="activity_id">
                            <button type="button" class="btn btn-primary" id="save-doctor-button">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script src="{{ asset('assets/js/plugins/clockpicker/clockpicker.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.clockpicker').clockpicker();
                $('.input-group.date.start_date').datepicker({
                    keyboardNavigation: false,
                    forceParse: false,
                    calendarWeeks: true,
                    autoclose: true,
                    startDate: new Date(),
                    todayHighlight: true
                });

                $('#type_id').change(function() {
                    if ($(this).val() == 2) {
                        $('#other_remark_div').removeClass('d-none');
                        $('#other_remark').attr('required', true);
                    } else {
                        $('#other_remark_div').addClass('d-none');
                        $('#other_remark').attr('required', false);
                    }
                })
                $('#doctor_name_select').change(function() {
                    if ($(this).val() == 'other') {
                        $('#other_doctor_div').removeClass('d-none');
                        $('#other_doctor').attr('required', true);
                    } else if ($(this).val() == 'add_doc') {
                        $('#other_doctor_div').addClass('d-none');
                        $('#other_doctor').attr('required', false);
                        $('#add_doctor_modal').modal('show');
                    } else {
                        $('#other_doctor_div').addClass('d-none');
                        $('#other_doctor').attr('required', false);
                    }
                })
            })
            $(document).ready(function() {
                $("#save-doctor-button").on("click", function(event) {
                    event.preventDefault();
                    let form = $('#patient_doctor_Form_schedule');
                    validator = form.validate();
                    if (form.valid()) {
                        let nform = $('#patient_doctor_Form_schedule')[0];
                        let formData = new FormData(nform);
                        $.ajax({
                            type: "POST",
                            url: "{{ route('add-new-doctor') }}",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.status == 'success') {
                                    toastAlert(response.status, response.message);
                                    let newOption = new Option(response.doctor.name, response.doctor
                                        .id, true, true);
                                    $("#doctor_name_select option[value='add_doc']").remove();
                                    $("#doctor_name_select").append(newOption);
                                    $("#doctor_name_select").val(response.doctor.id).trigger(
                                        'change');

                                    $('#add_doctor_modal').modal('hide');

                                }
                            },
                            error: function(xhr, status, error) {
                                // handle error
                            }
                        });
                    }
                });

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
                        } else {
                            input.prop('required', false);
                        }
                    });
                }

                $('.time-checkbox').each(function() {
                    const input = $(this).closest('.align-items-center').find('.am-time');
                    const clockpicker = input.closest('.clockpicker');

                    // Initial state
                    input.prop('disabled', !this.checked);
                    input.prop('required', this.checked);
                    clockpicker.css('pointer-events', this.checked ? 'auto' : 'none');

                    if (this.checked) {
                        clockpicker.clockpicker({
                            autoclose: true
                        });
                    }

                    // Add event listener to each checkbox
                    $(this).on('ifChanged', function() {
                        input.prop('disabled', !this.checked);
                        input.prop('required', this.checked);
                        clockpicker.css('pointer-events', this.checked ? 'auto' : 'none');
                        if (!this.checked) {
                            input.val('');
                            // Destroy the clockpicker
                            clockpicker.clockpicker('remove');
                        } else {
                            // Initialize the clockpicker
                            clockpicker.clockpicker({
                                autoclose: true
                            });
                        }
                        updateCheckboxState();
                    });
                });

                // Initial call to set the correct state on page load
                updateCheckboxState();
            });
            $(document).ready(function() {
                $('.clockpicker').clockpicker();

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
                    if ($(this).val() == 1 || $(this).val() == 5) {
                        $('#weekly_type_div').addClass('d-none');
                        $('#monthly_type_div').addClass('d-none');
                        $('#week_days_select').attr('required', false);
                        $('#selected_month_dates').attr('required', false);
                    } else if ($(this).val() == 2 || $(this).val() == 3) {
                        $('#weekly_type_div').removeClass('d-none');
                        $('#monthly_type_div').addClass('d-none');
                        $('#week_days_select').attr('required', true);
                        $('#selected_month_dates').attr('required', false);
                    } else {
                        $('#weekly_type_div').addClass('d-none');
                        $('#monthly_type_div').removeClass('d-none');
                        $('#week_days_select').attr('required', false);
                        $('#selected_month_dates').attr('required', true);
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
                $('.input-group.date.month_date').datepicker({
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
            });
        </script>
    @endsection
