@extends('layouts.admin')

@section('title', 'Patient Detail')
@section('style')
    <link href="{{ asset('assets/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    @php
        $registeredDate = $patient->created_at;
        $startYear = date('Y', strtotime($registeredDate));
        $endYear = date('Y');

        $endMonth = date('n');
        $selectedYear = date('Y', strtotime($registeredDate));
        if ($selectedYear != $endYear) {
            $endMonth = 12;
        }
        $selectedMonth = date('n', strtotime($registeredDate));
    @endphp

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('patients.index') }}">Patients</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Patient Detail</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content shadow border rounded pb-0">
                        <div class="row">
                            <div class="col-md-12 d-flex align-items-start flex-column flex-md-row bg-white">
                                <div class="mb-2 mr-4">
                                    <div class="position-relative ch-img">
                                        <img src="{{ $patient->profile_image_path }}" alt="profile"
                                            class="img-fluid rounded-lg">
                                        <div
                                            class="position-absolute {{ empty($patient->deleted_at) ? 'ch-online' : '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h2 class="font-bold text-body">{{ $patient->name }} <i
                                            class="fa fa-check-circle fs-18 text-navy"></i></h2>
                                    <div class="d-flex flex-wrap mb-4 ch-info">
                                        <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4">
                                            <i class="fa fa-mobile-phone fs-18 mr-1"></i>{{ $patient->phone }}
                                        </a>
                                        <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4">
                                            <i class="fa fa-envelope  mr-1"></i> {{ $patient->email }}
                                        </a>
                                    </div>
                                    <div class="d-flex flex-wrap ch-stats">
                                        <div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
                                            <div class="d-flex align-items-center">
                                                <div class="counted font-bold fs-16 text-body">
                                                    <i
                                                        class="fa fa-home mr-1 text-navy"></i>{{ $patient->care_home ? $patient->care_home->name : '-' }}
                                                </div>
                                            </div>
                                            <div class="font-bold  text-muted">Care Home</div>
                                        </div>
                                        <div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
                                            <div class="d-flex align-items-center">
                                                <div class="counted font-bold fs-16 text-body">
                                                    <i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
                                                    {{ date('M d, Y', strtotime($patient->admission_date)) }}
                                                </div>
                                            </div>
                                            <div class="font-bold  text-muted">Admitted On</div>
                                        </div>
                                        @if ($patient->deleted_at != null)
                                            <div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
                                                <div class="d-flex align-items-center">
                                                    <div class="counted font-bold fs-16 text-body">
                                                        <i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
                                                        {{ date('d M, Y', strtotime($patient->deleted_at)) }}
                                                    </div>
                                                </div>
                                                <div class="font-bold  text-muted">Archived On</div>
                                            </div>
                                        @endif
                                        @if ($patient->discharged == 1)
                                            <div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
                                                <div class="d-flex align-items-center">
                                                    <div class="counted font-bold fs-16 text-body">
                                                        <i class="fa fa-calendar fs-14 mr-1 text-navy"></i>
                                                        {{ date('d M, Y', strtotime($patient->updated_at)) }}
                                                    </div>
                                                </div>
                                                <div class="font-bold  text-muted">Discharged On</div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-wrap ch-stats">
                                        @if ($patient->deleted_at == null)
                                            @if ($patient->getLatestPatientLog && $patient->getLatestPatientLog->is_in_house == 2)
                                                <div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{ $patient->getLatestPatientLog->comment }}">
                                                    <div class="d-flex align-items-center">
                                                        <div class="counted font-bold fs-16 text-body">
                                                            <i class="fa fa-sign-out fs-14 mr-1 text-navy"></i>
                                                            {{ $patient->getLatestPatientLog->is_in_house == 2 ? 'Yes' : '' }}
                                                        </div>
                                                    </div>
                                                    <div class="font-bold  text-muted">Out Home</div>
                                                </div>
                                                <!-- <div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
                 <div class="d-flex align-items-center">
                  <div class="counted font-bold fs-16 text-body">
                   <i class="fa fa-sign-out fs-14 mr-1 text-navy"></i>
                   {{ $patient->getLatestPatientLog->comment }}
                  </div>
                 </div>
                 <div class="font-bold  text-muted">Reason</div>
                </div> -->
                                                <div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
                                                    <div class="d-flex align-items-center">
                                                        <div class="counted font-bold fs-16 text-body">
                                                            <i class="fa fa-clock-o fs-14 mr-1 text-navy"></i>
                                                            {{ date('d M, Y h:i:s a', strtotime($patient->getLatestPatientLog->expected_return_date . '' . $patient->getLatestPatientLog->expected_return_time)) }}
                                                        </div>
                                                    </div>
                                                    <div class="font-bold  text-muted">Expected Return Date & Time</div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    @if ($patient->preferences)
                                        <div class="d-flex flex-wrap ch-stats">
                                            <div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
                                                <div class="d-flex align-items-center">
                                                    <div class="counted font-bold fs-16 text-body">
                                                        {{-- <i class="fa fa-calendar fs-14 mr-1 text-navy"></i> --}}
                                                        {{ $patient->preferences }}
                                                    </div>
                                                </div>
                                                <div class="font-bold  text-muted">Preferences</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!----Discharged checkbox--------------->
                                @if ($patient->discharged_request == 1)
                                    <div class="d-flex">
                                        <div class="patient_discharged_section">
                                            {{-- <div class="form-check" id="discharge_checkbox_div">
											<input class="form-check-input" type="checkbox" value=""name="patient_discharged" id="patient_discharged" value="1" @if ($patient->discharged == 1) checked @endif>
											<label class="form-check-label" for="patient_discharged" id="discharge_text">{{ $patient->discharged == 1 ? 'Discharged' : 'Discharge' }}</label>
										</div> --}}
                                            <div class="align-items-center">
                                                <label class="d-flex align-items-center fs-16" data-toggle="tooltip"
                                                    data-placement="top" title="{{ $patient->discharged_comment }}"><i
                                                        class="fa fa-info-circle text-info mr-1 fs-20"></i> Discharge
                                                    Request</label>
                                                <select class="form-control" name="patient_discharged"
                                                    id="patient_discharged">
                                                    <option value="">Select action</option>
                                                    <option value="1">Accept</option>
                                                    <option value="0">Reject</option>
                                                </select>
                                            </div>
                                            <div class="actions clearfix float-right" id="chekbox_loader_button_div"
                                                style="display: none;">
                                                <div class="col-lg-12 col-m-12">
                                                    <span class="spinner-border spinner-border-sm text-success"
                                                        role="status" aria-hidden="true"></span>
                                                    Wait ...
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-3 responsive-scroll-x">
                                <ul class="nav ch-tabs">
                                    <li class="nav-item">
                                        <a class="font-bold  py-3 px-2 mr-2  nav-link active patient-tab"
                                            data-tab-name="basic" id="tab8-tab" data-toggle="tab"
                                            href="#tab8">Basic</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="font-bold  py-3 px-2 mr-2  nav-link patient-tab"
                                            data-tab-name="medicine" id="tab1-tab" data-toggle="tab"
                                            href="#tab1">Medicine list</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="font-bold  py-3 px-2 mr-2  nav-link patient-tab"
                                            data-tab-name="medicine" id="tab1-tab" data-toggle="tab"
                                            href="#tab10">Medicine Report</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="font-bold  py-3 px-2 mr-2  nav-link patient-tab"
                                            data-tab-name="document" id="tab2-tab" data-toggle="tab"
                                            href="#tab2">Document list</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="font-bold  py-3 px-2 mr-2  nav-link patient-tab"
                                            data-tab-name="document" id="tab3-tab" data-toggle="tab"
                                            href="#tab3">Doctor list</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="font-bold  py-3 px-2 mr-2  nav-link patient-tab"
                                            data-tab-name="activity" id="tab4-tab" data-toggle="tab"
                                            href="#tab4">Activity Report List</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="font-bold  py-3 px-2 mr-2  nav-link patient-tab"
                                            data-tab-name="activity" id="tab5-tab" data-toggle="tab"
                                            href="#tab5">Assigned Activities List</a>
                                    </li>
                                    {{-- <li class="nav-item">
										<a class="font-bold  py-3 px-2 mr-2  nav-link patient-tab" data-tab-name="activity" id="tab6-tab" data-toggle="tab" href="#tab6">Assigned Tasks List</a>
									</li> --}}
                                    <li class="nav-item">
                                        <a class="font-bold  py-3 px-2 mr-2  nav-link patient-tab"
                                            data-tab-name="appointment" id="tab7-tab" data-toggle="tab"
                                            href="#tab7">Schedule Appointment</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="font-bold  py-3 px-2 mr-2  nav-link patient-tab"
                                            data-tab-name="incident" id="tab9-tab" data-toggle="tab"
                                            href="#tab9">Incidents</a>
                                    </li>
                                    {{-- <li class="nav-item">
										<a class="font-bold  py-3 px-2 mr-4 nav-link patient-tab" data-tab-name="expense" id="tab10-tab" data-toggle="tab" href="#tab10">Expense</a>
									</li> --}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="ibox-content shadow border rounded">
                    <div class="tab-content ">
                        <div class="tab-pane fade show active" id="tab8">
                            @include('patients.partials.patient-basic')
                        </div>
                        <div class="tab-pane fade show " id="tab1">
                            @include('patients.partials.patient-medicine')
                        </div>
                        <div class="tab-pane fade show " id="tab10">
                            @include('patients.partials.patient-medicine-report')
                        </div>
                        <div class="tab-pane fade" id="tab2">
                            @include('patients.partials.patient-document')
                        </div>
                        <div class="tab-pane fade" id="tab3">
                            @include('patients.partials.patient-doctor')
                        </div>
                        <div class="tab-pane fade" id="tab4">
                            @include('patients.partials.patient-activities')
                        </div>
                        <div class="tab-pane fade" id="tab5">
                            @include('patients.partials.assigned-activities-to-patient')
                        </div>
                        {{-- <div class="tab-pane fade" id="tab6">
							@include('patients.partials.assigned-tasks-to-patient')
						</div> --}}
                        <div class="tab-pane fade" id="tab7">
                            @include('patients.partials.patient-schedule')
                        </div>
                        <div class="tab-pane fade" id="tab9">
                            @include('patients.partials.patient-incidents')
                        </div>
                        {{-- <div class="tab-pane fade" id="tab10">
							@include('patients.partials.patient-incidents')
						</div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="staff_note_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header dark-bg-color radius-0">
                    <h5 class="modal-title color-white" id="myModalLabel">Add Staff Note</h5>
                    <button type="button" class="close color-white" data-dismiss="modal" aria-hidden="true">×</button>

                </div>
                <div class="modal-body py-3 px-3">

                    <form id="staff-note-form" action="{{ route('save-staff-note', ['patient_id' => $patient->id]) }}"
                        method="post">
                        @csrf
                        <div class="form-group mt-2 mb-0">
                            <label for="add-remark">Note</label>
                            <textarea name="note" class="form-control required" required id="add-remark" rows="3"></textarea>
                        </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-3">
                    <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Close</button>
                    <input type="hidden" name="activity_id" class="activity_id">
                    <button type="button" class="btn btn-primary" id="save-staff-note">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <div id="medicine_discontinue_note_modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header dark-bg-color radius-0">
                    <h5 class="modal-title color-white" id="myModalLabel">Add Note for Discontinue Medicine</h5>
                    <button type="button" class="close color-white" data-dismiss="modal" aria-hidden="true">×</button>

                </div>
                <div class="modal-body py-3 px-3">

                    <form id="medicine-discontinue-form"
                        action="{{ route('discontinue-patient-medicine', ['patient_id' => $patient->id]) }}"
                        method="post">
                        @csrf
                        <div class="form-group mt-2 mb-0">
                            <label for="add-remark">Note</label>
                            <textarea name="note" class="form-control required" required id="add-remark" rows="3"></textarea>
                        </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-3">
                    <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Close</button>
                    <input type="hidden" name="medicine_id" class="medicine_id">
                    <button type="button" class="btn btn-primary" id="save-discontinue-medicine-note">Save
                        changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click', '.add_staff_note', function() {
            var id = $(this).data('id');
            $('.activity_id').val(id)
            $('#staff_note_modal').modal('show');
        })

        $(document).on('click', '.discontinue_medicine', function() {
            var id = $(this).data('id');
            Swal.fire({
                text: "Are you sure you want to discontinue this medicine?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, proceed!",
                cancelButtonText: "Cancel"
            }).then((result) => {

                if (result.isConfirmed) {
                    // User clicked "Yes, proceed!"
                    $('.medicine_id').val(id);
                    $('#medicine_discontinue_note_modal').modal('show');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // User clicked "Cancel"
                    // console.log('Medicine discontinuation was canceled.');
                }
            });
        })



        var discharge_status_url = "{{ route('update-patients-discharge-status') }}";
        var patient_id = "{{ $patient->id }}";
        var medicine_list = document_list = incident_list = '';
        $(document).ready(function() {
            // Upgrade button class name
            $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn btn-white btn-sm';

            $(document).ready(function() {
                function showTabFromHash() {
                    var hash = window.location.hash;
                    if (hash) {
                        $('.nav-link[href="' + hash + '"]').tab('show');
                    }
                }

                // Show the correct tab when the page loads
                showTabFromHash();

                // Show the correct tab when the hash changes (e.g., user clicks a link)
                $(window).on('hashchange', function() {
                    showTabFromHash();
                });

                // Update the URL hash when a tab is clicked
                $('.nav-link').on('click', function() {
                    window.location.hash = $(this).attr('href');
                });
                @if ($patient->medicines->count() > 0)
                    var medicine_list = $('#medicine_list').DataTable({
                        pageLength: 50,
                        responsive: true,
                        lengthChange: false,
                        bFilter: true,
                        bSort: false,
                        "autoWidth": false
                    });
                    //console.log('medicine_list', medicine_list);
                @endif
                @if ($medicine_report_list->count() > 0)
                    var medicine_list = $('#medicine_report_list').DataTable({
                        pageLength: 50,
                        responsive: true,
                        lengthChange: false,
                        bFilter: true,
                        bSort: false,
                        "autoWidth": false
                    });
                    //console.log('medicine_list', medicine_list);
                @endif
                @if ($patient->incidents->count() > 0)
                    var incident_list = $('#incident_list').DataTable({
                        pageLength: 10,
                        responsive: true,
                        lengthChange: false,
                        bFilter: false,
                        bSort: false,
                        "autoWidth": false
                    });
                    //console.log('medicine_list', medicine_list);
                @endif
                @if ($patient->documents->count() > 0)
                    var document_list = $('#document_list').DataTable({
                        pageLength: 10,
                        responsive: true,
                        lengthChange: false,
                        bFilter: false,
                        bSort: false,
                        "autoWidth": false
                    });
                    //console.log('document_list', document_list);
                @endif
            });

            $('.patient-tab').click(function() {
                var tab_name = $(this).data('tab-name');
                console.log('tab_name', tab_name);
                /*if(tab_name == 'medicine'){
                	var otable = $('#medicine_list').dataTable();
                	console.log('otable', otable);
                	otable.fnAdjustColumnSizing();					
                }else if(tab_name == 'document'){
                	var otable = $('#document_list').dataTable();
                	console.log('otable', otable);
                	otable.fnAdjustColumnSizing();	
                }*/
            })

            $('#patient_discharged').on('change', function() {
                var current_status = $('#patient_discharged').val();
                var action_data = current_status;
                //console.log('current_status', current_status)
                var type = current_status == '1' ? 'discharge' : 're-admit';
                Swal.fire({
                    text: "Are you sure you want to " + type + " this patient?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, proceed!",
                }).then((result) => {
                    //console.log('swal result', result);
                    if (result.value) {
                        $('#discharge_checkbox_div').hide();
                        $('#chekbox_loader_button_div').show();
                        $.ajax({
                            url: discharge_status_url,
                            type: "GET",
                            data: {
                                patient_id: patient_id,
                                action_data: action_data
                            },
                        }).done(function(response) {
                            $('#chekbox_loader_button_div').hide();
                            $('#discharge_checkbox_div').show();
                            if (response.type == 'error') {
                                toastAlert(response.type, response.message);
                            } else {
                                $('.patient_discharged_section').remove();
                                toastAlert(response.type, response.message);
                                // var info_text = current_status ? 'Discharged' : 'Discharge';
                                // $('#discharge_text').text(info_text);
                            }
                        });
                    } else {
                        var checkbox = $('#patient_discharged');
                        checkbox.prop('checked', !checkbox.prop('checked'));
                    }
                });
            });

            $("#download_med_report").on("click", function(event) {
                $(this).attr('disabled', 'disabled');
                $('#blank_report').val('0');
                event.preventDefault()
                var form = document.getElementById('download_med_report_form');
                form.submit();
                setTimeout(function() {
                    $('#download_med_report').removeAttr('disabled');
                }, 2000);

            })

            $("#download_med_report_blank").on("click", function(event) {
                $(this).attr('disabled', 'disabled');
                $('#blank_report').val('1');
                event.preventDefault()
                var form = document.getElementById('download_med_report_form');
                form.submit();
                setTimeout(function() {
                    $('#download_med_report').removeAttr('disabled');
                }, 2000);
            })

            document.getElementById('med-year').addEventListener('change', function() {
                var year = this.value;
                var monthDropdown = document.getElementById('med-month');
                var selectedMonth = {{ $selectedMonth }};
                var selectedYear = {{ $selectedYear }};
                var totalMonths = 12;

                // Clear existing options
                monthDropdown.innerHTML = '';

                // If the selected year is the current year, start from the current month
                if (year == {{ $endYear }}) {
                    totalMonths = {{ date('n') }};
                }
                if (year != selectedYear) {
                    selectedMonth = 1;
                }

                // Add options for each month
                for (var i = selectedMonth; i <= totalMonths; i++) {
                    var option = document.createElement('option');
                    option.value = i;
                    option.text = new Date(year, i - 1, 1).toLocaleString('default', {
                        month: 'long'
                    });
                    monthDropdown.appendChild(option);
                }
            });
        });
    </script>

@endsection
