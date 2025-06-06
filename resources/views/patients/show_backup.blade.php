@extends('layouts.admin')

@section('title', 'Patient Detail')
@section('style')
	<link href="{{ asset('assets/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endsection
@section('content')
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
					<strong>Patient Detail</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
            <div class="col-lg-12">
			<div class="ibox">
			<div class="ibox-content shadow border rounded">
			<div class="row">
                    <div class="align-items-center col-md-12 d-flex flex-column flex-md-row bg-white">
						<div class="mr-4">
							<div class="profile-image navy-bg p-md text-center gg" style="width: 150px;">
								<img src="{{ $patient->profile_image_path }}" class="" alt="profile">
							</div>
						</div>
						<div class="">
							<h4>Care Home Name: {{ $patient->care_home ? $patient->care_home->name : '-' }}</h4>
							<h4>Name: {{ $patient->name }}</h4>
							<h4>Email: {{ $patient->email }}</h4>
							<h4>Phone No: {{ $patient->phone }}</h4>
							<h4>Admitted On: {{ date('d M, Y', strtotime($patient->admission_date)) }}</h4>
						</div>
					</div>
					</div>
					</div>
                </div>
            </div>
		</div>	
		<div class="row">
			@include('patients.partials.patient-medicine')
			@include('patients.partials.patient-document')
        </div>
	</div>	
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Upgrade button class name
			$.fn.dataTable.Buttons.defaults.dom.button.className = 'btn btn-white btn-sm';

			$(document).ready(function(){
				$('#medicine_list').DataTable({
					pageLength: 10,
					responsive: true,
					lengthChange: false,
					bFilter: false,
					bSort: false, 
				});
				
				$('#document_list').DataTable({
					pageLength: 10,
					responsive: true,
					lengthChange: false,
					bFilter: false,
					bSort: false, 
				});

			});
        });
    </script>
@endsection