@extends('layouts.admin')

@section('title', 'Client Activity History List')

@section('content')
<style>
    .custom-select-2 .select2-container{width:100% !important;}
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Client Activity History List</strong>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">
            <div class="col-md-12">
                <form class="advanced-search" id="advanced-search">
                    <div class="form-row align-items-center">
                        <div class="col-12 col-md-3 col-lg-2  mb-3">
                            <label for="clientName">Care Home Name</label>
                            <div>
                            @php
                                $home_id = Request::has('home_name') ? Request::get('home_name') : '';
                               
                                @endphp
                            <select class="form-control select2_element w-100" name="home_name" id="home_filter">
                                <option></option>
                                @foreach ($homes as $homesKey => $home)
                                <option value="{{ $homesKey }}" {{$home_id==$homesKey? "selected" : ""}}>{{ $home }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-2  mb-3">
                            <label for="clientName">Client Name</label>
                            <div>
                            @php
                                $patient_id = Request::has('client_name') ? Request::get('client_name') : '';
                               
                                @endphp
                            <select class="form-control select2_element w-100" name="client_name" id="client_filter">
                                <option></option>
                                @foreach ($patients as $patientKey => $patient)
                                <option value="{{ $patientKey }}" {{$patient_id==$patientKey? "selected" : ""}}>{{ $patient }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                         <div class="col-12 col-md-3 col-lg-2  mb-3">
                            <label for="staffName">Staff Name</label>
                            @php
                                $staff_id = Request::has('staff_name') ? Request::get('staff_name') : '';
                               
                                @endphp
                            <select class="form-control select2_element" name="staff_name" id="staff_filter">
                                <option></option>
                                @foreach ($staffs as $staffKey => $staff)
                                <option value="{{ $staffKey }}" {{$staff_id==$staffKey? "selected" : ""}}>{{ $staff }}</option>
                                @endforeach
                            </select>
                        </div> 
                        <!--<div class="col-6 col-md-3 col-lg-2  mb-3">
                            <label for="managerName">Manager Name</label>
                            <select class="form-control select2_element" name="manager_name" id="manager_filter">
                                <option></option>
                                @foreach ($managers as $managerKey => $manager)
                                <option value="{{ $managerKey }}">{{ $manager }}</option>
                                @endforeach
                            </select>
                        </div>-->
                        <div class="col-12 col-md-3 col-lg-4  mb-3">
                            <label for="date">Date</label>
                            <div id="data_5">
                                @php
                                $start_date = Request::has('start_date') ? Request::get('start_date') : '';
                                $end_date = Request::has('end_date') ? Request::get('end_date') : '';
                                @endphp
                                <div class="input-daterange  d-flex" id="datepicker">
                                    <span class="cstm-cal w-100">
                                    <i class="fa fa-calendar left" aria-hidden="true"></i> <input type="text" class="form-control-sm form-control"  name="start_date" id="start_date" value="{{ $start_date }}"></span>
                                    <span class="input-group-addon pl-3 pr-3">to</span>
                                    <span class="cstm-cal w-100"><input type="text" class="form-control-sm form-control"  name="end_date" id="end_date" value="{{ $end_date }}"><i class="fa fa-calendar right" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-2 mb-3">
                            <label for="managerName">Shift</label>
                            @php
                                $shift_id = Request::has('shift') ? Request::get('shift') : '';
                               
                                @endphp
                            <select class="form-control" name="shift" id="shift">
                                <option value="">All</option>
                                @foreach(config('const.activity_shifts') as $shiftKey => $shift)
                                <option value="{{$shiftKey}}" {{$shift_id==$shiftKey? "selected" : ""}}>{{$shift}}</option>
                                @endforeach
                            </select>
                        </div>
                      



                    </div>
                    
                    <div class="expandable" style="display: none">
                        <div class="form-row align-items-center">
                            <div class="col-12 col-md-3 col-xl-2 mb-3">
                                @php
                                        $client_availability_id = Request::has('client_availability') ? Request::get('client_availability') : '';
                                       
                                        @endphp
                                        <label for="clientAvailability">Client Availability Status</label>
                                        <select class="form-control" name="client_availability" id="client_availability">
                                            <option value="">All</option>
                                            <option value="1"  {{$client_availability_id==1? "selected" : ""}}>Available</option>
                                            <option value="0"  {{$client_availability_id==0? "selected" : ""}}>Temporarily out of home</option>
        
                                        </select>
                                    </div>
        
                            <!-- <div class="col-6 col-md-3 col-xl-2 mb-3">
                                <label for="trainingStatus">Training Status</label>
                                <select class="form-control" name="training_status" id="training_status">
                                    <option></option>
                                    @foreach(config('const.course_status') as $trainingStatusKey => $trainingStatus)
                                    <option value="{{$trainingStatusKey}}">{{$trainingStatus}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3 col-xl-2 mb-3">
                                <label for="training_id">Training Name</label>
                                <select class="form-control select2_element" name="training_id" id="training_id">
                                    <option></option>
                                </select>
                            </div> -->
                            <div class="col-12 col-md-6 col-xl-3 mb-3 custom-select-2">
                                <label for="client_medicine">Activity Report</label>
                                @php
                                $client_activity_id = Request::has('client_activity') ? Request::get('client_activity') : '';
                               
                                @endphp
                                <select class="form-control select2_element" name="client_activity" id="client_activity">
                                    <option></option>
                                    @foreach($patientActivities as  $patientActivity)
                                    @php
                                 /*   $report_date=date('m-d-Y',strtotime($patientActivity->report_date));
                                    $shift_ids= json_decode($patientActivity->report_time,true);
                                    if(array_key_exists(1,$shift_ids))
                                    {
                                        $shiftName='Morning';
                                    }
                                    else if(array_key_exists(2,$shift_ids))
                                    {
                                        $shiftName='Afternoon';
                                    }
                                    else if(array_key_exists(3,$shift_ids))
                                    {
                                        $shiftName='Evening';
                                    }
                                    else if(array_key_exists(4,$shift_ids))
                                    {
                                        $shiftName='Night';
                                    }
                                    else if(array_key_exists(5,$shift_ids))
                                    {
                                        $shiftName='Ad-hoc';
                                    }*/
                                    @endphp
                                    <option value="{{$patientActivity->id}}" {{$client_activity_id==$patientActivity->id? "selected" : ""}}>
                                        {{$patientActivity->name}}
                                        
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-xl-3 mb-3 custom-select-2">
                                <label for="client_medicine">Assigned Activity</label>
                                @php
                                $assigned_activity_id = Request::has('assigned_activity') ? Request::get('assigned_activity') : '';
                               
                                @endphp
                                <select class="form-control select2_element" name="assigned_activity" id="assigned_activity">
                                    <option></option>
                                    @foreach($assignedActivities as  $assignedActivityKey => $assignedActivity)
                                    <option value="{{$assignedActivityKey}}" {{$assigned_activity_id==$assignedActivityKey? "selected" : ""}}>
                                        {{$assignedActivity}}
                                        
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-xl-3 mb-3 custom-select-2">
                            @php
                                $client_medicine_id = Request::has('client_medicine') ? Request::get('client_medicine') : '';
                               
                                @endphp
                                <label for="client_medicine">Prescribed Medication</label>
                                <select class="form-control select2_element" name="client_medicine" id="client_medicine">
                                    <option></option>
                                    @foreach($patientMedicines as $patientMedicineKey => $patientMedicine)
                                    <option value="{{$patientMedicineKey}}" {{$client_medicine_id==$patientMedicineKey? "selected" : ""}}>{{$patientMedicine}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3 mb-3 custom-select-2">
                            @php
                                $task_id = Request::has('task') ? Request::get('task') : '';
                               
                                @endphp
                                <label for="client_activity">Tasks</label>
                                <select class="form-control select2_element" name="task" id="task">
                                    <option></option>
                                    @foreach($tasks as $taskKey => $task)
                                    <option value="{{$taskKey}}" {{$taskKey==$task_id? "selected" : ""}}>{{$task}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary mr-3" id="expandBtn"><i class="fa fa-chevron-down mr-1"></i> Advanced filters</button>
                        <button type="button" class="btn btn-primary mr-3" id="submit_filter">Search</button>
                        <button type="button" class="btn btn-default btn-sm" id="clear_filter"><i class="fa fa-refresh" aria-hidden="true"></i> Clear</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Client Activity History List </h5>
                </div>
                <div class="ibox-content relative rounded">
                    <div class="table-responsive">
                        <table class="table table-striped" id="data_list">
                            <thead>
                                <tr>
                                    <!--th><input type="checkbox" class="i-checks" name="input[]"></th-->
                                    <th>Id</th>
                                    <th>Care Home Name</th>
                                    <th>Client Name</th>
                                    <th>Reported By</th>
                                    <th>Date of Report</th>
                                    <th>Time of Report</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div id="pagination-section"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
    var data_url = createURL('get-staff-activity-history-list');
    $(document).ready(function() {
        getData();
    });
    $("#home_filter").select2({
        placeholder: "Select home name",
        allowClear: true
    });
    $("#client_filter").select2({
        placeholder: "Select client name",
        allowClear: true
    });
    $("#client_medicine").select2({
        placeholder: "Select Medicine",
        allowClear: true
    });
    $("#client_activity").select2({
        placeholder: "Select Activity",
        allowClear: true
    });
    $("#assigned_activity").select2({
        placeholder: "Select Assigned Activity",
        allowClear: true
    });
    $("#task").select2({
        placeholder: "Select Task",
        allowClear: true
    });
    $("#staff_filter").select2({
        placeholder: "Select staff name",
        allowClear: true
    });
    $("#manager_filter").select2({
        placeholder: "Select manager name",
        allowClear: true
    });
    $(document).ready(function() {
        $('#expandBtn').click(function() {
            $('.expandable').slideToggle();
            $(this).toggleClass("open");
        });
    });
    $(document).on('click', '#submit_filter', function(e) {
        e.preventDefault();
        updateURL('home_name', $('#home_filter').val());
        updateURL('client_name', $('#client_filter').val());
        updateURL('staff_name', $('#staff_filter').val());
        updateURL('client_activity', $('#client_activity').val());
        updateURL('start_date', $('#start_date').val());
        updateURL('end_date', $('#end_date').val());
        updateURL('shift', $('#shift').val());
        updateURL('assigned_activity', $('#assigned_activity').val());
        updateURL('client_availability', $('#client_availability').val());
        updateURL('task', $('#task').val());
        updateURL('client_medicine', $('#client_medicine').val());
        getData();
    })
    $(document).on('click', '#clear_filter', function(e) {
        e.preventDefault();
        removeAllParamFromUrl();
        $('#advanced-search')[0].reset();
        $('.select2_element').val(null).trigger('change');
        getData();
    })
    $(document).on('change', '#staff_filter', function() {
        var staff_id = $(this).val();
        var data_url = "/get-staff-traning-list/" + staff_id;
        $.ajax({
            url: data_url,
            type: "GET",
        }).done(function(response) {
            if (response.type == 'error') {
                toastAlert(response.type, response.msg);
            } else {
                $('#training_id').html(response.html);
                $("#training_id").select2({
                    placeholder: "Select training name",
                    allowClear: true
                });
            }
        });
    })
</script>
@endsection