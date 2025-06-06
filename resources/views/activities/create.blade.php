@extends('layouts.admin')

@section('title', 'Create Activity')
@section('style')
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
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('activities.index') }}">Activities</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Create Activity</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12 dark-bg">
                <form method="POST" role="form" action="{{ route('activities.store') }}" id="activityForm">
                    @csrf
                    <div class="ibox">

                        <div class="row">

                            <div class="col-lg-6 col-sm-12">

                                <div class="ibox-content h-100 py-m-0">
                                    <div class="ibox-title d-flex px-0">
                                        <h5>Add Activity </h5>
                                    </div>
                                    <div class="row">
                                        <div
                                            class="form-group col-12 col-md-12 col-lg-12 col-xl-12  @error('shift_id') has-error @enderror">
                                            <label class="col-form-label">Shift *</label>
                                            <div class="">
                                                @foreach ($shifts as $key => $shift)
                                                    <label class="checkbox-inline i-checks mr-2"> 
														<input type="checkbox" name="shift_id[]" value="{{ $key }}"> <i></i>
                                                        {{ $shift }} 
													</label>
                                                @endforeach
                                                @error('shift_id')
                                                    <span class="text-danger text-left d-block"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div
                                            class="form-group col-12 col-md-2 col-lg-6 col-xl-6 @error('name') has-error @enderror">
                                            <label class="col-form-label">Name *</label>
                                            <div class="">
                                                <input type="text" name="name" placeholder="Name" class="form-control"
                                                    required autocomplete="off" value="{{ old('name') }}">
                                                @error('name')
                                                    <span class="text-danger text-left d-block"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div
                                            class="form-group col-12 col-md-12 col-lg-6 col-xl-6 @error('type') has-error @enderror">
                                            <label for="contact_no" class="col-form-label">Activity For *</label>
                                            <div class="">
                                                <select class="form-control m-b required" name="type" id="activity_type">
                                                    <option value="">Select option</option>
                                                    @foreach ($activity_for as $key => $value)
                                                        <option value="{{ $key }}"
                                                            @if ($key == 1) selected @endif>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="home-div  d-none col-12 col-md-12 col-lg-6 col-xl-12">
                                            <div class="form-group    ">
                                                <label for="care_home" class="col-form-label">Care Home</label>
                                                <div class="">
                                                    <select class="form-control " name="home_id" id="home_id">
                                                        <option value="">Choose Care Home</option>
                                                        @foreach ($care_home as $key => $value)
                                                            <option value="{{ $value->id }}">{{ $value->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="home-div  d-none col-12 ">
                                            <div class="form-group">
                                                <label for="care_home" class="col-form-label">Patients</label>
                                                <div class="">
                                                    <select class="form-control " name="patient_id[]" id="patient_id"
                                                        multiple>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-6 col-sm-12">
                                <div class="ibox-content h-100 py-m-0">
                                    <div class="row">
                                        <div class="form-group col-12 col-md-12 col-lg-6 col-xl-6">
                                            <label for="duration" class="col-form-label">Duration (in minutes) *</label>
                                            <div class="">
                                                <input type="number" name="duration" id="duration" placeholder="Duration"
                                                    class="form-control rounded required integer_no" min=1>
                                            </div>
                                        </div>

                                        <div class="form-group col-12 col-md-12 col-lg-6 col-xl-6">
                                            <label for="contact_no" class="col-form-label">Frequency *</label>
                                            <div class="">
                                                <input type="number" name="frequency" id="frequency"
                                                    placeholder="Frequency" class="form-control rounded required integer_no"
                                                    min=1>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-12 col-lg-6 col-xl-6">
                                            <label for="contact_no" class="col-form-label">Recurrence *</label>
                                            <div class="">
                                                <select class="form-control required" name="recurrence" id="recurrence">
                                                    <option value="">Choose option</option>
                                                    @foreach ($activity_recurrence as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-12 col-lg-6 col-xl-6 d-none"
                                            id="activity-perform_week">
                                            <label for="contact_no" class="col-form-label">Activity Perform *(<span
                                                    id="activity-perform-lbl">in week</span>)</label>
                                            @php
                                                $weekdays = config('const.week_days');
                                            @endphp
                                            <select class="form-control select-week-days w-100" id="week_days_select"
                                                name="activity_performance_day[]" multiple="multiple">
                                                @foreach ($weekdays as $day)
                                                    <option value="{{ $day }}">{{ $day }}</option>
                                                @endforeach

                                            </select>
                                            <!-- <div class="">
                  <input type="number" name="activity_performance_day" id="activity_performance_day" class="form-control rounded" placeholder="2" min="1">

                 </div> -->
                                        </div>
                                        <div class="form-group col-12 col-md-12 col-lg-6 col-xl-6 d-none"
                                            id="activity-perform_month">
                                            <label for="contact_no" class="col-form-label">Activity Perform *(<span
                                                    id="activity-perform-lbl">in month</span>)</label>
                                            <div class="input-group date month_date" id="specific-datepicker">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" name="activity_performance_month"
                                                    id="selected_month_dates" placeholder="Select Month Dates"
                                                    class="form-control">
                                            </div>
                                            <!-- <div class="">
                  <input type="number" name="activity_performance_day" id="activity_performance_day" class="form-control rounded" placeholder="2" min="1">

                 </div> -->
                                        </div>


                                        {{-- 	<div class="form-group col-12 col-md-12 col-lg-6 col-xl-6 @error('status') has-error @enderror">
									<label class="col-form-label">Status</label>
									<div class="">
										@php
											$pre_seleted = 1;
											if(!is_null(old('status')) && old('status') == 0){
												$pre_seleted = 0;
											}
										@endphp
										<select class="form-control m-b" name="status" required>
											<option value="">Select status</option>
											<option value="1" {{ $pre_seleted == 1 ? 'selected' : ''}}>Active</option>
											<option value="0" {{ $pre_seleted == 0 ? 'selected' : '' }}>In-Active</option>
										</select>
										@error('status')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div> --}}
                                        <div class="form-group col-12 ">
                                            <label for="description" class="col-form-label">Description *</label>
                                            <div class="">
                                                <textarea class="form-control rounded required" name="description" id="description" placeholder="Description"
                                                    rows="4" autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="text-right">
                                    <a class="btn btn-white btn-sm" type="button"
                                        href="{{ createCancelUrl(route('activities.index')) }}">Cancel</a>
                                    <button class="btn btn-sm btn-primary" type="submit"
                                        id="activity_Form">Save</button>
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
            $('#home_id').on('click', function() {
                var home_id = $(this).val();
                console.log(home_id);
                $.ajax({
                    type: "GET",
                    url: "{{ route('get-care-home-patients') }}",
                    data: {
                        home_id: home_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('#patient_id').empty();
                        $('#patient_id').append('<option value="">Select Patient</option>');
                        $.each(data, function(key, value) {
                            $('#patient_id').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });

                    },
                    error: function(err, xhr) {
                        console.log(err);
                    },
                });
            })
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
            // show hide activity perform div (in days)
            $('#recurrence').change(function() {
                // Get the selected option value
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

            $('#activity_type').change(function() {
                var id = $(this).val();
                console.log(id);

                if (id == 2) {
                    $('#patient_id').addClass('required');
                    $('#home_id').addClass('required');
                    $('.home-div').show();
                    $('.home-div').removeClass('d-none');
                } else {
                    $('.home-div').hide();
                    $('.home-div').addClass('d-none');
                    $('#home_id').removeClass('required');
                    $('#patient_id').removeClass('required');
                }
            })
        });
    </script>
@endsection
