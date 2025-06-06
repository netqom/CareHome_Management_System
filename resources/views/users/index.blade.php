@extends('layouts.admin')

@section('title', 'Users List')

@section('content')

    <style>
        .ibox .label {
            font-size: 12px;
        }
    </style>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Users</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox-content m-b-sm border-bottom">
            <div class="row">
                <div class="col-md-12">
                    <form class="advanced-search" id="advanced-search">
                        <div class="form-row">

                            {{-- @if (Auth::user()->role_id == 2)  --}}
                            <div class="col-12 col-md-4 col-lg-2  mb-3">
                                <label for="home_filter">Care Home Name</label>
                                <select class="form-control select2_element w-100" name="home_name" id="home_filter">
                                    <option value=""></option>
                                    @foreach ($homes as $homeKey => $home)
                                        <option value="{{ $homeKey }}">{{ $home }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- @endif --}}
                            <div class="col-12 col-md-4 col-lg-2  mb-3">
                                <label for="staff_filter">Staff Name</label>
                                <select class="form-control select2_element w-100" name="staff_name" id="staff_filter"
                                    multiple>
                                    <option value=""></option>
                                    @foreach ($staffs as $staffKey => $staff)
                                        <option value="{{ $staffKey }}">{{ $staff }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-4 col-lg-2  mb-3">
                                <label for="staff_status">Status:</label>
                                <select class="form-control" name="staff_status" id="staff_status">
                                    <option value="">All</option>
                                    <option value="active"
                                        {{ !empty(request()->input('staff_status')) && request()->input('staff_status') == 'active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="inactive"
                                        {{ !empty(request()->input('staff_status')) && request()->input('staff_status') == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                    <option value="archive"
                                        {{ !empty(request()->input('staff_status')) && request()->input('staff_status') == 'archive' ? 'selected' : '' }}>
                                        Archive</option>
                                </select>
                            </div>

                            {{-- <div class="col-12 col-md-4 col-lg-4  mb-3">
                                <label for="date">Date</label>
                                <div id="data_5">
                                    @php
                                        $start_date = Request::has('start_date') ? Request::get('start_date') : '';
                                        $end_date = Request::has('end_date') ? Request::get('end_date') : '';
                                    @endphp
                                    <div class="input-daterange d-flex" id="datepicker">
                                        <span class="cstm-cal">
                                            <i class="fa fa-calendar left" aria-hidden="true"></i> <input type="text" class="form-control-sm form-control" 
                                            name="start_date" id="start_date" value="{{ $start_date }}">
                                        </span>
                                        <span class="input-group-addon pl-3 pr-3">to</span>
                                        <span class="cstm-cal">
                                            <i class="fa fa-calendar left" aria-hidden="true"></i>
                                            <input type="text" class="form-control-sm form-control" 
                                            name="end_date" id="end_date" value="{{ $end_date }}">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2  mb-3">
                                <label for="managerName">Shift</label>
                                <select class="form-control" name="shift" id="shift">
                                    <option></option>
                                    @foreach (config('const.work_shifts') as $shiftKey => $shift)
                                        <option value="{{ $shiftKey }}">{{ $shift }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2  mb-3">
                                <label for="training_id">Training Name</label>
                                <div>
                                    <select class="form-control select2_element w-100" name="training_id" id="training_id">
                                        <option></option>
                                        @foreach ($staffTrainingDatas as $staffTrainingDataKey => $staffTrainingData)
                                            <option value="{{ $staffTrainingDataKey }}">{{ $staffTrainingData }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2  mb-3 custom-select-2">
                                <label for="client_activity">Tasks</label>
                                <div>
                                <select class="form-control select2_element w-100" name="task" id="task">
                                    <option></option>
                                    @foreach ($tasks as $taskKey => $task)
                                        <option value="{{ $taskKey }}" >{{ $task }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div> --}}
                        </div>
                        <div class="expandable" style="display: none">
                            <div class="form-row align-items-center">
                                <!-- <div class="col-6 col-md-3 col-xl-2 mb-3">
                                            <label for="trainingStatus">Training Status</label>
                                            <select class="form-control" name="training_status" id="training_status">
                                                <option></option>
                                                @foreach (config('const.course_status') as $trainingStatusKey => $trainingStatus)
    <option value="{{ $trainingStatusKey }}">{{ $trainingStatus }}</option>
    @endforeach
                                            </select>
                                        </div> -->
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <!-- <button type="button" class="btn btn-secondary mr-3" id="expandBtn"><i
                                                    class="fa fa-chevron-down mr-1"></i> Advanced filters</button> -->
                            <button type="button" class="btn btn-primary mr-3" id="submit_filter">Search</button>
                            <button type="button" class="btn btn-default btn-sm" id="clear_filter"><i class="fa fa-refresh"
                                    aria-hidden="true"></i> Clear</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title @if (Auth::user()->role_id == 1) d-none @endif">
                        <h5>Users List </h5>
                        @if (Auth::user()->role_id == 1)
                            <div class="ibox-tools">
                                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"
                                        aria-hidden="true"></i> Add User</a>
                            </div>
                        @endif

                        @if (Auth::user()->role_id == 2)
                            {{-- <div class="d-flex mb-3 mb-md-0 mx-auto text-center">
                            <p class="px-2 m-0"><strong>Added Staff</strong>
                            <span class="d-block text-success" id="added_staff_count">3</span>
                            </p> 
                            <p>/</p>
                            <p class="px-2 m-0"><strong>Limit Staff</strong>
                            <span class="d-block text-danger" id="limit_staff_count">3</span>
                            </p>
                        </div> --}}
                            <div class="ibox-tools">
                                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm add_staff_member"><i
                                        class="fa fa-plus" aria-hidden="true"></i> Add Staff Member</a>
                            </div>
                        @endif
                    </div>
                    <div class="ibox-content relative shadow border rounded">
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
                        <div class="table-responsive">
                            <table class="table table-striped" id="data_list">
                                <thead>
                                    <tr>
                                        <!--th><input type="checkbox" class="i-checks" name="input[]"></th-->
                                        <th>S.No</th>
                                        <th>Care Home</th>
                                        <th>Staff Name</th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <th>Phone No</th>
                                        @if (Auth::user()->role_id == 2)
                                            <th>Shift</th>
                                        @endif
                                        <th>Status</th>
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
        var data_url = createURL('users-get-data');
        $(document).ready(function() {
            getData();
            //getStaffCountData();
        });


        /* function getStaffCountData(){
            var home_id = $('#home_filter').val();
            $.ajax({
                url: "{{ route('check-is-admin-add-staff') }}", // Replace with your endpoint URL
                method: 'POST', // Or 'POST' if needed
                data: { home_id: home_id },
                dataType:'json',
                success: function(response) {
                   if(response.type=='success')
                   {
                    $('#limit_staff_count').text(response.staff_limit);
                    if(response.staff_limit == response.added_staff)
                    $('#added_staff_count').text(response.added_staff);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    console.error("An error occurred: ", error);
                }
            });
        } */


        function selectintialize() {
            $("#staff_filter").select2({
                placeholder: "Select staff name",
                allowClear: true
            });

            $("#home_filter").select2({
                placeholder: "Select home name",
                allowClear: true
            });
            $("#staff_status").select2({
                placeholder: "All",
                allowClear: true
            });
        }


        $("#training_id").select2({
            placeholder: "Select Training",
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
           if($('#staff_filter').val()=='' && $('#home_filter').val()=='' && $('#staff_status').val()=='')
           { 
            toastAlert('error', 'Please enter a value to search for.');
           }else{
                //updateURL('client_name', $('#client_filter').val());
                updateURL('staff_name', $('#staff_filter').val());
                updateURL('home_name', $('#home_filter').val());
                updateURL('staff_status', $('#staff_status').val());
                //updateURL('manager_name', $('#manager_filter').val());
                //updateURL('start_date', $('#start_date').val());
                //updateURL('end_date', $('#end_date').val());
                //updateURL('shift', $('#shift').val());
                //updateURL('training_id', $('#training_id').val());
                //updateURL('training_status', $('#training_status').val());
                //updateURL('client_availability', $('#client_availability').val());
                //updateURL('task', $('#task').val());
                getData();
           } 
        })
        $(".add_staff_member").on("click", function(e) {
            e.preventDefault(); // Prevent the default form submission

            var home_id = $('#home_filter').val();

            // Perform the AJAX request
            if (home_id != '') {
                $.ajax({
                    url: "{{ route('check-is-admin-add-staff') }}", // Replace with your endpoint URL
                    method: 'POST', // Or 'POST' if needed
                    data: {
                        home_id: home_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.type == 'success') {
                            var name = response.name;

                            Swal.fire({
                                text: name +
                                    " staff limit exceeded. Please upgrade your plan or purchase Add-ons",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#f39c12",
                                confirmButtonText: "Add Addon",
                                cancelButtonText: "Upgrade Plan"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        `/subscription-manage/${home_id}?add_ons=yes`;
                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    window.location.href =
                                        `/subscription-manage/${home_id}?upgrade_downgrade_plan=yes`;
                                }
                            });
                        } else {
                            window.location.href = `/users/create?home_id=${home_id}`;
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors
                        console.error("An error occurred: ", error);
                        Swal.fire({
                            text: "An error occurred while processing your request. Please try again.",
                            icon: "error"
                        });
                    }
                });
            } else {
                window.location.href = "{{ route('users.create') }}";
            }
        });


        $(document).on('click', '#clear_filter', function(e) {
            e.preventDefault();
            removeAllParamFromUrl();
            $('#advanced-search')[0].reset();
            $('#staff_filter').val(null).trigger('change');
            $('#home_filter').val(null).trigger('change');
            $('#staff_status').val(null).trigger('change');
            selectintialize();
            getData();
        })
        selectintialize();
        $(document).on('change', '#home_filter', function() {
            var home_id = $(this).val();
            $.ajax({
                url: "{{ route('care-home-users') }}", // Replace with your endpoint URL
                method: 'POST', // Or 'POST' if needed
                data: {
                    home_id: home_id
                },
                dataType: 'json',
                success: function(response) {
                    $('#staff_filter').html(response.html);
                    $("#staff_filter").select2({
                        placeholder: "Select staff name",
                        allowClear: true
                    });
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    console.error("An error occurred: ", error);
                }
            });
        })

        $(document).on('click', '.change_status', function() {
            var user_id = $(this).data('item-id');
            var status = $(this).data('status');
            var status_message = '';
            if (status == 1) {
                status_message = 'Inactive';
            } else {
                status_message = 'Active';
            }
            Swal.fire({
                text: "Do you want to " + status_message + " user?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#f39c12",
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('change-user-status') }}", // Replace with your endpoint URL
                        method: 'POST', // Or 'POST' if needed
                        data: {
                            user_id: user_id,
                            status: status
                        },
                        dataType: 'json',
                        success: function(response) {
                            getData();
                        },
                        error: function(xhr, status, error) {
                            // Handle any errors
                            console.error("An error occurred: ", error);
                        }
                    });
                }
            });
        })

        $(document).on('click', '.restore_staff', function() {
            var home_id = $(this).data('home-id');
            var staff_id = $(this).data('item-id');
            $.ajax({
                url: "{{ route('check-is-admin-add-staff-restore') }}", // Replace with your endpoint URL
                method: 'POST', // Or 'POST' if needed
                data: {
                    home_id: home_id,
                    staff_id: staff_id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.type == 'success') {
                        var name = response.name;

                        Swal.fire({
                            text: name +
                                " staff limit exceeded. Please upgrade your plan or purchase Add-ons",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#f39c12",
                            confirmButtonText: "Add Addon",
                            cancelButtonText: "Upgrade Plan"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href =
                                    `/subscription-manage/${home_id}?add_ons=yes`;
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                window.location.href =
                                    `/subscription-manage/${home_id}?upgrade_downgrade_plan=yes`;
                            }
                        });
                    } else {
                        getData();
                        Swal.fire({
                            text: "Staff restored successfully!",
                            icon: "success"
                        });

                        //window.location.href = `/users/create?home_id=${home_id}`;
                    }
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    console.error("An error occurred: ", error);
                    Swal.fire({
                        text: "An error occurred while processing your request. Please try again.",
                        icon: "error"
                    });
                }
            });
        })
    </script>
@endsection
