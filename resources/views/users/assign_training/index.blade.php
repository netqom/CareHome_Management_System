@extends('layouts.admin')

@section('title', 'Assign Training List')

@section('content')
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Assigned Training</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox ">
					<div class="ibox-title">
						<h5>Assigned Training </h5>
                        <div class="ibox-tools">
							@if($user_detail->deleted_at == null && $user_detail->deleted_at == '')
                           	 	<a href="{{ route('assign-training-to-staff.create', ['home_id' => $home_id,'id' => $staff_id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Assign Training</a>
							@endif
                        </div>
					</div>
					<div class="ibox-content relative rounded">
						<div class="table-responsive">
							<table class="table table-striped" id="data_list">
								<thead>
								<tr>
									<!--th><input type="checkbox" class="i-checks" name="input[]"></th-->
									<th>S.No</th>
									<th>Course Name</th>
									<th>Course Status</th>
									<th>Start Date</th>
									<th>Due Date</th>
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
		var data_url = createURL('staff-training-course-get-data');
        $(document).ready(function() {
            getData();
        });
    </script>
@endsection