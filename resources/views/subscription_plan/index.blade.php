@extends('layouts.admin')

@section('title', 'Subscription Plans List')

@section('content')
    
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-7">
            {{-- <h2>Subscription Plans</h2> --}}
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="#">Subscription Plans</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>List</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-5 text-right">
            {{-- <a href="{{ route('subscription_plans.getAddEdit', ['id' => null]) }}" class="btn btn-primary mt-4">Add</a> --}}
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
            <div class="ibox border rounded shadow">
                <div class="ibox-title d-flex">
                    <h5>Subscription Plan List</h5>
                    <div class="ibox-tools">
                        {{-- If you want to Sync you newly created stripe plans in your database, Please click on this button. --}}
                        {{-- <a href="{{ route('subscription_plans.create') }}" class="btn btn-primary btn-sm">Sync Plans</a> --}}
                        {{-- <a href="javascript:void(0);" class="btn btn-primary btn-sm" id="syncPlan">Sync Plans</a> --}}
                    </div> 
                </div>

                <div class="ibox-content relative">
                    <div class="table-responsive">
                        <table class="table table-striped" id="data_list">
                            <thead>
                            <tr>
                                {{-- <th><input type="checkbox" class="i-checks" name="input[]"></th> --}}
                                <th>S.No</th>
                                <th>Name</th>
                                <!--th>Description</th-->
                                <th>Duration</th>
                                <th>Price</th>
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
    <script type="text/javascript">
		var data_url = createURL('subscription-plans/get-data');
        $(document).ready(function() {
            getData();
        });
       
        // $('#syncPlan').on('click', function(){
        //     // Perform Ajax request to save stripe new plans
        //     addCardLoader('.ibox-content');
        //     $.ajax({
        //         url: createURL('subscription-plans/add'),
        //         type: "POST",
        //         // data: params,
        //     }).done(function(response) {
        //         if (response.type == 'error') {
        //             toastAlert(response.type, response.msg);
        //         } else {
		// 			removeCardLoader();
        //             toastAlert(response.type, response.msg);
        //             window.location.reload();
        //         }
        //     });
        // })
    </script>
@endsection