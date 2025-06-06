@extends('layouts.admin')

@section('title', 'Expenses List')

@section('content')
    <style>
        .tab-active {
            background: #1ab394;
            color: #fff;
            border-color: transparent;
        }

        .flex-auto {
            flex: auto;
        }

        form#expenseForm select,
        form#expenseForm input {
            font-size: 13px;
            padding-right: 15px;
        }
    </style>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Expenses List</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        @include('expenses.expense-create-update')
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox shadow border rounded">
                    <div class="ibox-title d-flex justify-content-between pr-3 align-items-center flex-wrap">
                        <h5 class="mb-0 mt-2">Expenses List </h5>
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="form-group mb-0 mt-2 mr-2" id="data_5">
                                @php
                                    $start_date = Request::has('start_date') ? Request::get('start_date') : '';
                                    $end_date = Request::has('end_date') ? Request::get('end_date') : '';
                                @endphp
                                <div class="input-daterange d-flex" id="datepicker">
                                    <span class="cstm-cal">
                                        <i class="fa fa-calendar left" aria-hidden="true"></i>
                                        <input type="text" class="form-control-sm form-control" name="start_date"
                                            id="start_date" value="{{ $start_date }}"></span>
                                    <span class="input-group-addon pl-3 pr-3">to</span>
                                    <span class="cstm-cal">
                                        <i class="fa fa-calendar left" aria-hidden="true"></i>
                                        <input type="text" class="form-control-sm form-control" name="end_date"
                                            id="end_date" value="{{ $end_date }}"></span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm mr-2 mt-2" id="set_date_range"><i
                                    class="fa fa-filter" aria-hidden="true"></i> Filter</button>
                            <button type="button" class="btn btn-default btn-sm mr-2 mt-2" id="clear_date_range"><i
                                    class="fa fa-refresh" aria-hidden="true"></i> Clear</button>
                        </div>
                    </div>
                    <div class="ibox-content relative">
                        <!--div class="row">
               <div class="col-sm-5 m-b-xs"><select class="form-control-sm form-control input-s-sm inline">
                <option value="0">Option 1</option>
                <option value="1">Option 2</option>
                <option value="2">Option 3</option>
                <option value="3">Option 4</option>
               </select>
               </div>
               <div class="col-sm-4 m-b-xs">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                 <label class="btn btn-sm btn-white ">
                  <input type="radio" name="options" id="option1" autocomplete="off" checked> Day
                 </label>
                 <label class="btn btn-sm btn-white active">
                  <input type="radio" name="options" id="option2" autocomplete="off"> Week
                 </label>
                 <label class="btn btn-sm btn-white">
                  <input type="radio" name="options" id="option3" autocomplete="off"> Month
                 </label>
                </div>
               </div>
               <div class="col-sm-3">
                <div class="input-group"><input placeholder="Search" type="text" class="form-control form-control-sm"> <span class="input-group-append"> <button type="button" class="btn btn-sm btn-primary">Go!
                </button> </span></div>

               </div>
              </div-->
                        <div class="table-responsive relative">
                            <table class="table table-striped accordion" id="data_list">
                                <thead>
                                    <tr>
                                        <!--th><input type="checkbox" class="i-checks" name="input[]"></th-->
                                        <th>S.No</th>
                                        <th>Care Home</th>
                                        <th>Type</th>
                                        <th>User/Patient Name</th>
                                        <th>Expense Type</th>
                                        <th>Amount</th>
                                        <th></th>
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
        var data_url = createURL('patient-expenses-get-data');
        $("#home_filter").select2({
            placeholder: "Select care home",
            allowClear: true
        });
        $(document).ready(function() {
            getData();
            $(document).on('click', '.open-close-row', function() {
                var item_id = $(this).data('item-id');
                console.log('item_id', item_id)
                $('#info_row_' + item_id).toggle();
            })
            $(document).on('change', '#home_filter', function() {
                $('#user_type').val('');
                $('#patient_list_div').addClass('d-none');
                $('#user_list_div').addClass('d-none');
            })
            $('#user_type').change(function() {
                var home_id = $('#home_filter').val();
                var actionRoute = '';
                if ($(this).val() == 1) {
                    $('#user_list_div').addClass('d-none');
                    $('#patient_list_div').removeClass('d-none');
                    $.ajax({
                        url: "{{ route('care-home-patients') }}", // Replace with your endpoint URL
                        method: 'POST', // Or 'POST' if needed
                        data: {
                            home_id: home_id
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#patient_id').html(response.html);
                        },
                        error: function(xhr, status, error) {
                            // Handle any errors
                            console.error("An error occurred: ", error);
                        }
                    });
                } else {
                    $('#patient_list_div').addClass('d-none');
                    $('#user_list_div').removeClass('d-none');
                    $.ajax({
                        url: "{{ route('care-home-users') }}", // Replace with your endpoint URL
                        method: 'POST', // Or 'POST' if needed
                        data: {
                            home_id: home_id
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#user_id').html(response.html);
                        },
                        error: function(xhr, status, error) {
                            // Handle any errors
                            console.error("An error occurred: ", error);
                        }
                    });
                }

            })
        });
    </script>
@endsection
