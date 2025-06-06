@extends('layouts.admin')

@section('title', 'Patients List')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Patients</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        {{-- <div class="ibox-content m-b-sm border-bottom">
            
        </div> --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox shadow border rounded ">
                    <div class="align-items-center d-flex ibox-title justify-content-between pr-3 flex-wrap">
                        <h5>Patient List </h5>
                        @if (Auth::user()->role_id == 2)
                            <div class="align-items-center d-flex">
                                <div class="align-items-center d-flex mr-2">
                                <label class="mb-0 mr-2">Status:</label>
                                <select class="form-control" name="patient_status" id="patient_status">
                                    <option value="active">Active</option>
                                    <option value="discharge" {{!empty(request()->input('patient_status')) && request()->input('patient_status') == 'discharge' ? 'selected' : ''}}>Discharge</option>
                                    <option value="archive" {{!empty(request()->input('patient_status')) && request()->input('patient_status') == 'archive' ? 'selected' : ''}}>Archive</option>
                                </select>
                                </div>
                                <a href="{{ route('patients.create') }}" class="btn btn-primary flex-shrink-0"><i class="fa fa-plus"
                                        aria-hidden="true"></i> Add Patient</a>
                            </div>
                        @endif
                    </div>
                    <div class="ibox-content relative">
                        <div class="table-responsive">
                            <table class="table table-striped" id="data_list">
                                <thead>
                                    <tr>
                                        <!--th><input type="checkbox" class="i-checks" name="input[]"></th-->
                                        <th>S.No</th>
                                        <th>Care Home</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone No</th>
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
        var data_url = createURL('patients-get-data');
        $(document).ready(function() {
            getData();
        });
       
        
        $(document).on('change', '#patient_status', function(e) {
            updateURL('patient_status', $(this).val());
            getData();
        })
        // $(document).on('click', '#clear_filter', function(e) {
        //     e.preventDefault();
        //     removeAllParamFromUrl();
        //     $('#advanced-search')[0].reset();
        //     $('.select2_element').val(null).trigger('change');
        //     getData();
        // })
        $(document).on('click', '.restore_patient', function(){
            var home_id = $(this).data('home-id');
            var patient_id = $(this).data('item-id');
            $.ajax({
                url: "{{ route('check-is-admin-add-patient') }}", // Replace with your endpoint URL
                method: 'POST', // Or 'POST' if needed
                data: { home_id: home_id, patient_id: patient_id },
                dataType:'json',
                success: function(data) {
                    if(data.status && data.status == 'failed'){
                            Swal.fire({
                                text: "Patient limit exceeded. Please upgrade your plan or purchase Add-ons",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#f39c12",
                                confirmButtonText: "Add Addon",
                                cancelButtonText: "Upgrade Plan"
                            }).then((result) => {
                                if (result.isConfirmed) {
                               
                                    window.location.href = `/subscription-manage/${home_id}?add_ons=yes&type=patient`;
                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    window.location.href = `/subscription-manage/${home_id}?upgrade_downgrade_plan=yes&type=patient`;
                                }
                            });
                        }else{
                        getData();
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
