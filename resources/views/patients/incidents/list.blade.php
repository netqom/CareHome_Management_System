@extends('layouts.admin')

@section('title', 'Incident List')

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
                <strong>Incident List</strong>
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
                        
                       
                        <div class="col-12 col-md-6 col-lg-4  mb-3">
                            <label for="date">Date</label>
                            <div id="data_5">
                                @php
                                $start_date = Request::has('start_date') ? Request::get('start_date') : '';
                                $end_date = Request::has('end_date') ? Request::get('end_date') : '';
                                @endphp
                                <div class="input-daterange  d-flex" id="datepicker">
                                    <span class="cstm-cal">
                                    <i class="fa fa-calendar left" aria-hidden="true"></i> <input type="text" class="form-control-sm form-control"  name="start_date" id="start_date" value="{{ $start_date }}"></span>
                                    <span class="input-group-addon pl-3 pr-3">to</span>
                                    <span class="cstm-cal"><input type="text" class="form-control-sm form-control"  name="end_date" id="end_date" value="{{ $end_date }}"><i class="fa fa-calendar right" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>

                         <div class="col-12 col-md-6 col-lg-8">
                             <div class="d-flex justify-content-end">
                       
                        <button type="button" class="btn btn-primary mr-3" id="submit_filter">Search</button>
                        <button type="button" class="btn btn-default btn-sm" id="clear_filter"><i class="fa fa-refresh" aria-hidden="true"></i> Clear</button>
                    </div>
                         </div>
                      
                       




                    </div>
                    
                    
                   

                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Incident List </h5>
                </div>
                <div class="ibox-content relative rounded">
                    <div class="table-responsive">
                        <table class="table table-striped" id="data_list">
                            <thead>
                                <tr>
                                    <!--th><input type="checkbox" class="i-checks" name="input[]"></th-->
                                    <th>Id</th>
                                    <th>Patient Name</th>
                                    <th>Care Home</th>
                                    <th style="width: 300px">Title</th>
                                    <th>Reported By</th>
                                    <th>Date of Incident</th>
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
    var data_url = createURL('get-incident-list');
    $(document).ready(function() {
        getData();
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
        updateURL('start_date', $('#start_date').val());
        updateURL('end_date', $('#end_date').val());
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
        var data_url = "/get-incident-list/";
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