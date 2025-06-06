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

        .daily-activity-form .form-group {
            margin-bottom: 1rem;
            flex: 0 0 50%;
            max-width: 50%;
            padding-right: 15px;
            padding-left: 15px;
        }

        .daily-activity-form>h3 {
            color: #fff;
            width: 100%;
            padding-left: 15px;
            padding-right: 15px;
        }

        .form-group-activity {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
            padding: 0px !important;
            margin: 0px;
        }

        .form-group-activity label,
        .form-group-activity h3 {
            width: 100%;
            padding: 0px 15px;
        }

        .form-group-activity .activity-container {
            padding: 0px 15px;
        }

        .daily-activity-form input[type="file"] {
            color: #fff;
        }

        @media (max-width: 767px) {
            .acti-item {
                min-width: 130px;
            }

            .acti-item.remark {
                height: auto
            }

            .daily-activity-form .form-group {
                flex: 0 0 100%;
                max-width: 100%;
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
                    <a href="{{ url()->previous() }}#tab4">Activity Report</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Add Patients Activity Detail</strong>
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
                                <a class="btn btn-white btn-sm" type="button"
                                    href="{{ createCancelUrl(route('users.index')) }}"><i
                                        class="fa fa-arrow-circle-o-left"></i> Back</a>
                                <div class="hr-line-dashed"></div>
                            </div>

                            <div class="col-lg-6 col-md-12">

                                <h2 class="font-bold fs-18 mb-4 text-body">Patient Detail</h2>

                                <div class="ch-detail d-flex flex-wrap flex-sm-nowrap">
                                    <div class="mb-2 mr-4">
                                        <div class="position-relative ch-img">

                                            <img src="{{ asset($patient->profile_image_path) }}" alt="staff"
                                                class="img-fluid rounded-lg">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h2 class="font-bold text-body fs-16">{{ $patient->name }}</h2>
                                        <div class="mb-4 ch-info">
                                            <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                    class="fa fa-mobile-phone fs-18 mr-1"></i> {{ $patient->phone }}</a>
                                            <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                    class="fa fa-envelope  mr-1"></i> {{ $patient->email }} </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <h2 class="font-bold fs-18 mb-4 text-body">Connected with Care Home</h2>
                                <div class="ch-detail d-flex flex-wrap flex-sm-nowrap">
                                    <div class="mb-2 mr-4">
                                        <div class="position-relative ch-img">
                                            <a href="{{ route('homes.show', $patient->care_home->id) }}">
                                                <img src="{{ $patient->care_home->image_path }}" alt="care-home"
                                                    class="img-fluid rounded-lg">
                                                <div class="position-absolute ch-online">
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <a href="{{ route('homes.show', $patient->care_home->id) }}">
                                            <h2 class="font-bold text-body fs-16">
                                                {{ $patient->care_home ? $patient->care_home->name : '-' }} </h2>
                                        </a>
                                        <div class="d-flex flex-wrap mb-4 ch-info">
                                            <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                    class="fa fa-mobile-phone fs-18 mr-1"></i>
                                                {{ $patient->care_home ? $patient->care_home->contact_no : '-' }}</a>
                                            <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                    class="fa fa-user-circle  mr-1"></i>
                                                {{ count($patient->care_home->staff_users) }} Staff Members |
                                                {{ count($patient->care_home->patients) }} Current Patients</a>
                                            <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                    class="fa fa-envelope  mr-1"></i>
                                                {{ $patient->care_home ? $patient->care_home->email : '-' }}</a>
                                            <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                    class="fa fa-map-marker fs-14 mr-1"></i>

                                                {{ $patient->care_home ? $patient->care_home->street . ', ' . $patient->care_home->city . ', ' . $patient->care_home->state . ', ' . $patient->care_home->zip_code : '' }}
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 dark-bg">
                <div class="ibox">
                    <div class="ibox-content">
                        <form class="title-white daily-activity-form row" id="daily-activity-form"
                            action="{{ route('add-daily-activity-detail', ['patient_id' => $patient->id, 'id' => 0]) }}"
                            method="POST" enctype="multipart/form-data">
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
                            <div class="form-group">
                                <label for='images'>Report Date *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="report_date"
                                        value="{{ app('request')->input('report_date') }}" id="report_date"
                                        placeholder="Report Date" class="form-control required" required readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for='images'>Shift Time *</label>
                                <select name="shift_id" id="shift_time" class="form-control">
                                    @foreach ($shiftNames as $shift_key => $shiftTime)
                                        <option value="{{ $shift_key }}"
                                            {{ request()->segment(3) == $shift_key ? 'selected' : '' }}>
                                            {{ $shiftTime }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @php

                                renderFormFields($activity_form[0]['subchilds'], $activities);
                            @endphp

                            <div class="form-group">
                                <label for='images'>Select Images</label>
                                <input type="file" name="images[]" accept="image/*">
                            </div>
                            <div class="form-group col-lg-6 col-md-12">
                                <label for='images'>Select Pdf</label>
                                <input type="file" name="pdf[]" accept="application/pdf">
                            </div>

                            <!-- <input type="hidden" name="shift_id" value="{{ request()->segment(3) }}"> -->
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-primary" id="submit_daily_activity">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- New layout END here -->
        </div>
    </div>
    <!-- Modal -->
    <div id="activity_remark_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header dark-bg-color radius-0">
                    <h5 class="modal-title color-white" id="myModalLabel">Modal header</h5>
                    <button type="button" class="close color-white" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body py-3 px-3">

                    <form id="activity-remark-form">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input size-20" type="radio" name="activity_status" id="yes1"
                                value="1">
                            <label class="form-check-label" for="yes1">
                                Yes
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input size-20" type="radio" name="activity_status" id="no2"
                                value="0">
                            <label class="form-check-label" for="no2">
                                No
                            </label>
                        </div>

                        <div class="active-status-err" style="color:red;"></div>
                        <div class="form-group mt-2 mb-0">
                            <label for="add-remark">Remark</label>
                            <textarea class="form-control" id="add-remark" rows="3"></textarea>
                        </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-3">
                    <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Close</button>
                    <input type="hidden" class="activity_id">
                    <button type="button" class="btn btn-primary" id="save-activity-remark">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/clockpicker/clockpicker.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#report_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                endDate: new Date()
            });
        })

        $(document).on('change', '#shift_time', function() {
            var shift_id = $(this).val();
            var report_date = $('#report_date').val();
            var patient_id = "{{ $patient->id }}";
            var url = '/get-daily-activity-form/' + patient_id + '/' + shift_id + '?report_date=' + report_date;
            $.ajax({
                type: "POST",
                url: "{{ route('patient-activity-by-shift-date') }}",
                data: {
                    report_date: report_date,
                    shift_id: shift_id,
                    patient_id: patient_id,
                    type: 0
                },
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
                    } else {
                        window.location.href = url;
                    }
                },
                error: function(err, xhr) {

                },
            });
        });

        $(document).on('change', '#report_date', function() {
            var report_date = $(this).val();
            var shift_id = $('#shift_time').val();
            var patient_id = "{{ $patient->id }}";
            $.ajax({
                type: "POST",
                url: "{{ route('patient-activity-by-shift-date') }}",
                data: {
                    report_date: report_date,
                    shift_id: shift_id,
                    patient_id: patient_id,
                    type: 0
                },
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
        })

        $(document).on('click', '#save-activity-remark', function() {
            var activityId = $('.activity_id').val();
            var selectedActivityStatus = $('input[name="activity_status"]:checked').val();

            // Check if any radio button is selected
            if (!selectedActivityStatus) {
                //alert('Please select an activity status.');
                $('.active-status-err').text('Please select an activity status.');
                return; // Prevent the form submission
            } else {
                $('.active-status-err').text('');
            }
            var activityRemark = $('#add-remark').val();
            $('#status_activity_' + activityId).val(selectedActivityStatus);
            $('#remark_activity_' + activityId).val(activityRemark);
            $('#activity-remark-form')[0].reset();
            $('#activity_remark_modal').modal('hide');
        })

        $(document).on('click', '.activity_check_box', function() {

            if ($(this).is(':checked')) {
                var activityId = $(this).data('id');
                var activityName = $(this).data('name');
                $('#myModalLabel').html(activityName);
                $('.activity_id').val(activityId)
                $('#activity_remark_modal').modal('show');

            } else {
                $('#myModalLabel').text('');
                $('#activity-remark-form')[0].reset();
                $('#activity_remark_modal').modal('hide');
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
                templateSelection: function(state) {
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
