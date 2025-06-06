@extends('layouts.admin')

@section('title', 'Patient Medicine Detail')
@section('style')
    <link href="{{ asset('assets/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <style>
        .time-badges span {
            min-width: 65px;
            display: inline-block;
            text-align: center;
            margin-right: 5px;
        }

        .shift-group {
            margin-bottom: 10px;
        }

        .shift-group .label {
            margin-left: 5px;
            font-size: 11px;
        }
    </style>
@endsection
@section('content')
    @php
        $medicine_type = config('const.medicine_type');
        $medicine_time = config('const.medicine_time');
        $intake_method = config('const.medicine_intake_method');
        $intake_guidedby = config('const.medicine_intake_supervised_by');
        $medicine_time_other   = config('const.medicine_time_other');

    @endphp
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('homes.show', $patient->care_home->id) }}">Care Home</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Patient Medicine Detail</strong>
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
                                        <div class="position-absolute ch-online"></div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h2 class="font-bold text-body">{{ $patient->name }} <i
                                            class="fa fa-check-circle fs-18 text-navy"></i></h2>
                                    <div class="d-flex flex-wrap mb-4 ch-info">
                                        <a href="tel:{{ !is_null($patient->phone) ? $patient->phone : '' }}"
                                            class="align-items-center d-flex font-bold  mb-2 mr-2">
                                            <i class="fa fa-mobile-phone fs-18 mr-1"></i>{{ $patient->phone }}
                                        </a>
                                        <span style="font-weight: var(--fa-style,900); margin-right: 7px; color: #8d8d8d;">
                                            |
                                        </span>
                                        <a href="mailto:{{ !is_null($patient->email) ? $patient->email : '' }}"
                                            class="align-items-center d-flex font-bold  mb-2 mr-2">
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
                                                        {{ date('M d, Y', strtotime($patient->deleted_at)) }}
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
                                                        {{ date('M d, Y', strtotime($patient->updated_at)) }}
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
                                                            {{ date('M d, Y h:i:s a', strtotime($patient->getLatestPatientLog->expected_return_date . '' . $patient->getLatestPatientLog->expected_return_time)) }}
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
                            <div class="mt-3">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="ibox-content shadow border rounded">
                    <div class="tab-content ">
                        <div class="row mb-3">
                            <div class="col">
                                @php
                                    $dose = '';
                                    $dose_type = '';
                                    if (strpos($medicine->dose, '__') !== false) {
                                        $doseData = explode('__', $medicine->dose);
                                        $dose1 = $doseData[0];
                                        $dose_type = $doseData[1];
                                        $dose = $dose1 . ' ' . $dose_type;
                                        if ($dose_type == 'other') {
                                            $dose = $medicine->other_dose;
                                        }
                                    } else {
                                        $dose = $medicine->dose;
                                        $dose_type = '';
                                    }

                                    $medicine_time2 = [];
                                    if ($medicine->time_id != 0) {
                                        $jsonData = json_decode($medicine->time_id, true);
                                    }
                                    if ($medicine->medicine_time != 0) {
                                        $medicine_time2 = json_decode($medicine->medicine_time, true);
                                    }

                                    $first = true;

                                    //$medicine_time = json_decode($medicine->medicine_time, true);
                                    //dd($medicine);
                                    switch ($medicine->med_frequency) {
                                        case 1:
                                            $med_frequency = 'Daily';
                                            break;
                                        case 2:
                                            $med_frequency = 'Weekly';
                                            break;
                                        case 3:
                                            $med_frequency = 'Bi-weekly';
                                            break;
                                        case 4:
                                            $med_frequency = 'Monthly Days';
                                            break;
                                        case 5:
                                            $med_frequency = 'Bi-Daily';
                                            break;
                                        default:
                                            $med_frequency = 'Daily';
                                            break;
                                    }

                                    $weekDays =
                                        $medicine->other_med_frequency != null
                                            ? json_decode($medicine->other_med_frequency)
                                            : [];
                                @endphp
                                <div class="fs-14 mb-1 text-body"><strong>Medicine Name:</strong> {{ $medicine->name }}
                                    ({{ $dose }})</div>
                                <div class="fs-14 mb-1 text-body"><strong>Med. Type:</strong>
                                    @if (!is_null($medicine->medicine_type_other))
                                        <span class="label label-warning">{{ $medicine->medicine_type_other }}</span>
                                    @else
                                        {{ $medicine_type[$medicine->medicine_type] }}
                                    @endif
                                </div>
                                <div class="fs-14 mb-1 text-body"><strong>{{ $med_frequency }}:</strong>
                                    @if ($medicine->med_frequency == 1 || $medicine->med_frequency == 5)
                                        {{ $medicine->med_frequency == 1 ? 'Daily' : 'Bi-Daily' }}
                                    @else
                                        @forelse ($weekDays as $day)
                                            {{ $day }},
                                        @empty
                                            No data found
                                        @endforelse
                                    @endif
                                </div>
                                <div class="fs-14 mb-1 text-body"><strong>Time:</strong>
                                    <div class="time-badges">
                                        @if ($jsonData !== null)
                                            @if (is_array($jsonData))

                                                @foreach ($jsonData as $jd)
                                                    <div class="shift-group">
                                                        {{ ucwords($medicine_time[$jd]) }}: <!-- Display shift name -->
                                                        @if (in_array($jd, [1, 2, 3, 4]))
                                                            @if (!empty($medicine_time2) && array_key_exists($jd, $medicine_time2))
                                                                @foreach (explode(',', $medicine_time2[$jd]) as $item)
                                                                    @if (!empty($item))
                                                                        <span
                                                                            class="label label-warning">{{ date('h:i A', strtotime($item)) }}</span>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @elseif ($jd == 5)
                                                            @if (!empty($medicine_time2) && array_key_exists(5, $medicine_time2))
                                                                @php 
                                                                    $medicine_time_other_id = $medicine_time2[5];
                                                                @endphp
                                                                <span class="label label-warning">{{$medicine_time_other[$medicine_time_other_id]}} </span>
                                                            @endif
                                                        @else
                                                            <span>No times available</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                {{ $medicine_time[$medicine->time_id] }}
                                                <br>
                                                @if (!is_null($medicine->time_other) && $medicine->time_id == 4)
                                                    <span
                                                        class="label label-warning">{{ date('h:i A', strtotime($medicine->time_other)) }}</span>
                                                @endif
                                            @endif
                                        @else
                                            {{ $medicine_time[$medicine->time_id] }}
                                            <br>
                                            @if (!is_null($medicine->time_other) && $medicine->time_id == 4)
                                                <span
                                                    class="label label-warning">{{ date('h:i A', strtotime($medicine->time_other)) }}</span>
                                            @endif
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="col">
                                <div class="fs-14 mb-1 text-body"><strong>Intake Method:</strong>
                                    @if (!is_null($medicine->other_intake_method))
                                        <span class="label label-warning">{{ $medicine->other_intake_method }}</span>
                                    @else
                                        {{ $intake_method[$medicine->intake_method] }}
                                    @endif
                                </div>
                                <div class="fs-14 mb-1 text-body"><strong>Instruction:</strong>
                                    {{ !empty($medicine->instructions) ? $medicine->instructions : 'N/A' }}</div>
                                <div class="fs-14 mb-1 text-body"><strong>Supervised By:</strong>
                                    @if (!is_null($medicine->intake_supervised_other))
                                        <span class="label label-warning">{{ $medicine->intake_supervised_other }}</span>
                                    @else
                                        {{ $intake_guidedby[$medicine->intake_supervised_by] }}
                                    @endif
                                </div>
                                @if ($medicine->bi_daily_start_date != null)
                                    <div class="row">
                                        <div class="fs-14 mb-1 text-body col-auto"><strong>Start Date:</strong>
                                            {{ date('m-d-Y', strtotime($medicine->bi_daily_start_date)) }}
                                        </div>
                                        @if ($medicine->bi_daily_end_date != null)
                                            <div class="fs-14 mb-1 text-body col-auto"><strong>End Date:</strong>
                                                {{ date('m-d-Y', strtotime($medicine->bi_daily_end_date)) }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                @if ($medicine->is_discontinue == 1)
                                    <div class="row">
                                        <div class="fs-14 mb-1 text-body col-auto"><strong>Discontinue Note:</strong>
                                            {{ $medicine->discontinue_note }}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                        @php
                            $medicineTime = json_decode($medicine->time_id);
                            //dd($medicineTime);
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-hover no-margins" id="medicine_list">
                                <thead>

                                    <tr>

                                        <th style="width:250px">Date</th>
                                        @if (!in_array('6', $medicineTime))
                                            <th style="width:250px">Morning</th>
                                            <th style="width:250px">Afternoon</th>
                                            <th style="width:250px">Evening</th>
                                            <th style="width:250px">Night</th>
                                            <!-- <th style="width:250px">Description</th> -->
                                        @else
                                            <th style="width:250px">Time</th>
                                            <th style="width:250px">Reason</th>
                                            <th style="width:250px">Staff Note</th>
                                        @endif

                                    </tr>
                                </thead>

                                <tbody>
                                    @if (!$patient->getPatientLog->isEmpty())

                                        @foreach ($patient->getPatientLog as $log)
                                            @php
                                                $morningDetail = getPatientActivity($log->id, $medicine->id, 1);
                                                $afternoonDetail = getPatientActivity($log->id, $medicine->id, 2);
                                                $eveningDetail = getPatientActivity($log->id, $medicine->id, 3);
                                                $nightDetail = getPatientActivity($log->id, $medicine->id, 4);

                                                $adHocDetail = getPatientActivity($log->id, $medicine->id, 5);
                                                //$adHocDetail='';
                                                if (!empty($morningDetail->ad_hoc_time)) {
                                                    $adHocDetail = $morningDetail;
                                                }
                                                //dd($adHocDetail, $morningDetail, $afternoonDetail, $eveningDetail, $nightDetail);
                                                $morning = '';
                                                $afternoon = '';
                                                $night = '';
                                                $current_time = date('d-m-Y H:i');
                                                $current_date = 'd-m-Y';
                                                if (!empty($activity_time)) {
                                                    $morning = explode('-', $activity_time->morning_time);
                                                    $current_datetime_morning_end = date(
                                                        $log->report_date . ' ' . $morning[1],
                                                    );
                                                    $afternoon = explode('-', $activity_time->afternoon_time);
                                                    $current_datetime_afternoon_end = date(
                                                        $log->report_date . ' ' . $afternoon[1],
                                                    );
                                                    $night = explode('-', $activity_time->night_time);
                                                    $current_datetime_night_end = date(
                                                        $log->report_date . ' ' . $night[1],
                                                    );
                                                }
                                                $note = '';
                                                //dd($current_time,$current_datetime_morning_end,$current_datetime_afternoon_end,$current_datetime_night_end)
                                            @endphp

                                            @if (!in_array('6', $medicineTime))
                                                @if (!empty($morningDetail) || !empty($afternoonDetail) || !empty($eveningDetail) || !empty($nightDetail))
                                                    <tr>
                                                        <td>{{ date('F d Y', strtotime($log->report_date)) }}</td>
                                                        <td>
                                                            @if (
                                                                !empty($morningDetail) &&
                                                                    $morningDetail->activity_field_value->name &&
                                                                    (in_array('1', $medicineTime) || in_array('5', $medicineTime)))
                                                                @php
                                                                    $note_remark = $morningDetail->remark;
                                                                    $note_staff_note = $morningDetail->staff_note;

                                                                @endphp
                                                                {{ $morningDetail->activity_field_value->name }}

                                                                <p class="text-muted m-0"><strong>Mark By:</strong>
                                                                    {{ $morningDetail->added_by->name }}</p>
                                                            @else
                                                                @php
                                                                    if (
                                                                        !empty($activity_time) &&
                                                                        strtotime($current_time) >
                                                                            strtotime($current_datetime_morning_end)
                                                                    ) {
                                                                        echo '-';
                                                                    } else {
                                                                        echo '-';
                                                                    }
                                                                @endphp
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($afternoonDetail) && $afternoonDetail->activity_field_value->name && in_array('2', $medicineTime))
                                                                @php
                                                                    $note_remark = $afternoonDetail->remark;
                                                                    $note_staff_note = $afternoonDetail->staff_note;
                                                                @endphp
                                                                {{ $afternoonDetail->activity_field_value->name }}

                                                                <p class="text-muted m-0"><strong>Mark By:</strong>
                                                                    {{ $afternoonDetail->added_by->name }}</p>
                                                            @else
                                                                @php
                                                                    if (
                                                                        !empty($activity_time) &&
                                                                        strtotime($current_time) >
                                                                            strtotime($current_datetime_afternoon_end)
                                                                    ) {
                                                                        echo '-';
                                                                    } else {
                                                                        echo '-';
                                                                    }
                                                                @endphp
                                                            @endif
                                                        </td>
                                                        <td>

                                                            @if (isset($eveningDetail->activity_field_value->name) && in_array('3', $medicineTime))
                                                                @php
                                                                    $note_remark = $eveningDetail->remark;
                                                                    $note_staff_note = $eveningDetail->staff_note;
                                                                @endphp
                                                                {{ $eveningDetail->activity_field_value->name }}

                                                                <p class="text-muted m-0"><strong>Mark By:</strong>
                                                                    {{ $eveningDetail->added_by->name }}</p>
                                                            @else
                                                                @php
                                                                    if (
                                                                        !empty($activity_time) &&
                                                                        strtotime($current_time) >
                                                                            strtotime($current_datetime_night_end)
                                                                    ) {
                                                                        echo '-';
                                                                    } else {
                                                                        echo '-';
                                                                    }
                                                                @endphp
                                                            @endif
                                                        </td>

                                                        <td>

                                                            @if (isset($nightDetail->activity_field_value->name) && in_array('4', $medicineTime))
                                                                @php
                                                                    $note_remark = $nightDetail->remark;
                                                                    $note_staff_note = $nightDetail->staff_note;
                                                                @endphp
                                                                {{ $nightDetail->activity_field_value->name }}

                                                                <p class="text-muted m-0"><strong>Mark By:</strong>
                                                                    {{ $nightDetail->added_by->name }}</p>
                                                            @else
                                                                @php
                                                                    if (
                                                                        !empty($activity_time) &&
                                                                        strtotime($current_time) >
                                                                            strtotime($current_datetime_night_end)
                                                                    ) {
                                                                        echo '-';
                                                                    } else {
                                                                        echo '';
                                                                    }
                                                                @endphp
                                                            @endif
                                                        </td>
                                                        <!-- <td>
                                                             
                                                             <p class="text-muted m-0"><strong>Reason:</strong> {{ !empty($note_remark) ? $note_remark : 'N/A' }}</p>
                                                             
                                                             
                                                             <p class="text-muted m-0"><strong>Note:</strong> {{ !empty($note_staff_note) ? $note_staff_note : 'N/A' }}</p>
                                                             
                                                             </td> -->
                                                    </tr>
                                                @endif
                                            @endif
                                            @if (in_array('6', $medicineTime) && !empty($adHocDetail->ad_hoc_time))
                                                <tr>
                                                    <td>{{ date('F d Y', strtotime($log->report_date)) }}</td>
                                                    <td>{{ !empty($adHocDetail->ad_hoc_time) ? $adHocDetail->ad_hoc_time : '-' }}
                                                    </td>
                                                    <td>{{ !empty($adHocDetail->remark) ? $adHocDetail->remark : '-' }}
                                                    </td>
                                                    <td>{{ !empty($adHocDetail->staff_note) ? $adHocDetail->staff_note : '-' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script type="text/javascript">
        var patient_id = "{{ $patient->id }}";
        var medicine_list = '';
        $(document).ready(function() {
            // Upgrade button class name
            $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn btn-white btn-sm';

            $(document).ready(function() {
                @if ($patient->medicines->count() > 0)
                    var medicine_list = $('#medicine_list').DataTable({
                        pageLength: 10,
                        responsive: true,
                        lengthChange: false,
                        bFilter: false,
                        bSort: false,
                        "autoWidth": false
                    });
                    //console.log('medicine_list', medicine_list);
                @endif


            });
        });
    </script>
@endsection
