@extends('layouts.admin')

@section('title', 'Users List')

@section('content')
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
        {{-- <div class="ibox-content m-b-sm border-bottom">
            <div class="row">
                <div class="col-md-12">
                    <form class="advanced-search" id="advanced-search">
                        <div class="form-row">

                             
                                <div class="col-12 col-md-4 col-lg-2  mb-3">
                                    <label for="homeName">Care Home Name</label>
                                    <select class="form-control select2_element w-100" name="home_name" id="home_filter">
                                        <option></option>
                                        @foreach ($homes as $homeKey => $home)
                                            <option value="{{ $homeKey }}">{{ $home }}</option>
                                        @endforeach
                                    </select>
                                </div>
                           
                            <div class="col-12 col-md-4 col-lg-2  mb-3">
                                <label for="staffName">Staff Name</label>
                                <select class="form-control select2_element w-100" name="staff_name" id="staff_filter" multiple>
                                    <option></option>
                                    @foreach ($staffs as $staffKey => $staff)
                                        <option value="{{ $staffKey }}">{{ $staff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            

                            <div class="col-12 col-md-4 col-lg-2  mb-3">
                                <label for="staffName">Status:</label>
                                <select class="form-control" name="staff_status" id="staff_status">
                                    
                                    <option value="active">Active</option>
                                    <option value="inactive" {{!empty(request()->input('staff_status')) && request()->input('staff_status') == 'inactive' ? 'selected' : ''}}>Inactive</option>
                                    <option value="archive" {{!empty(request()->input('staff_status')) && request()->input('staff_status') == 'archive' ? 'selected' : ''}}>Archive</option>
                                </select>
                            </div>
                        </div>
                        <div class="expandable" style="display: none">
                            <div class="form-row align-items-center">                                
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary mr-3" id="submit_filter">Search</button>
                            <button type="button" class="btn btn-default btn-sm" id="clear_filter"><i class="fa fa-refresh"
                                    aria-hidden="true"></i> Clear</button>
                        </div>

                    </form>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Care Home Admin</h5>
                        
                    </div>
                    <div class="ibox-content relative shadow border rounded">
                        <div class="table-responsive">
                            <table class="table table-striped" id="data_list">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <!-- <th>Action</th> -->
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
        var data_url = createURL('care-home-get-data');
        $(document).ready(function() {
            getData();
        });


        function selectintialize()
        {
            $("#staff_filter").select2({
            placeholder: "Select staff name",
            allowClear: true
            });

            $("#home_filter").select2({
            placeholder: "Select home name",
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
            //updateURL('client_name', $('#client_filter').val());
            updateURL('staff_name', $('#staff_filter').val());
            updateURL('home_name', $('#home_filter').val());
            updateURL('staff_status', $('#staff_status').val());
            //updateURL('manager_name', $('#manager_filter').val());
            updateURL('start_date', $('#start_date').val());
            updateURL('end_date', $('#end_date').val());
            updateURL('shift', $('#shift').val());
			updateURL('training_id', $('#training_id').val());
            // //updateURL('training_status', $('#training_status').val());
            // updateURL('client_availability', $('#client_availability').val());
            updateURL('task', $('#task').val());
            getData();
        })
        $(".add_staff_member").on("click", function(e) {
            e.preventDefault(); // Prevent the default form submission

            var home_id = $('#home_filter').val();
            
            // Perform the AJAX request
            if(home_id!='')
            {
            $.ajax({
                url: "{{ route('check-is-admin-add-staff') }}", // Replace with your endpoint URL
                method: 'POST', // Or 'POST' if needed
                data: { home_id: home_id },
                dataType:'json',
                success: function(response) {
                   if(response.type=='success')
                   {
                    var name = response.name; 

                    Swal.fire({
                        text: name + " staff limit exceeded. Please upgrade your plan or purchase Add-ons",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#f39c12",
                        confirmButtonText: "Add Addon",
                        cancelButtonText: "Upgrade Plan"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `/subscription-manage/${home_id}?add_ons=yes`;
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = `/subscription-manage/${home_id}?upgrade_downgrade_plan=yes`;
                        }
                    });
                    }else{
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
        }else{
            window.location.href = "{{ route('users.create') }}";
        }
        });
        

        $(document).on('click', '#clear_filter', function(e) {
            e.preventDefault();
            removeAllParamFromUrl();
            $('#advanced-search')[0].reset();
            selectintialize();
            getData();
        })
        selectintialize();
        $(document).on('change', '#home_filter', function(){
            var home_id = $(this).val();
            $.ajax({
                url: "{{ route('care-home-users') }}", // Replace with your endpoint URL
                method: 'POST', // Or 'POST' if needed
                data: { home_id: home_id },
                dataType:'json',
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

        $(document).on('click','.change_status', function(){
            var user_id = $(this).data('item-id');
            var status = $(this).data('status');
            var status_message = '';
            if(status == 1){
                status_message = 'Inactive';
            }else{
                status_message = 'Active';
            }
                    Swal.fire({
                        text: "Do you want to "+ status_message +" user?",
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
                                data: { user_id: user_id, status: status },
                                dataType:'json',
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
    </script>
@endsection
