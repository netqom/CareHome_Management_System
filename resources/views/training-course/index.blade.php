@extends('layouts.admin')

@section('title', 'Training Course List')

@section('content')
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Training Course List</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox shadow border rounded">
					<div class="ibox-title align-items-center d-flex justify-content-between pr-3 flex-wrap">
						<h5>Training Course List </h5>
						<div class="align-items-center d-flex flex-wrap">
							<div class="align-items-center d-flex mr-2">
								<label class="mb-0 mr-2">Status:</label>
								<select class="form-control" name="course_status" id="course_status">
									<option value="active">Active</option>
									<option value="inactive" {{!empty(request()->input('course_status')) && request()->input('course_status') == 'inactive' ? 'selected' : ''}}>Inactive</option>
									<option value="archive" {{!empty(request()->input('course_status')) && request()->input('course_status') == 'archive' ? 'selected' : ''}}>Archive</option>
								</select>
							</div>
							{{-- <div class="ibox-tools"> --}}
								<a href="{{ route('add-update-training-course', $id='') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Course</a>
							{{-- </div> --}}
						</div>
					</div>
					<div class="ibox-content relative">
						<div class="table-responsive">
							<table class="table table-striped" id="data_list">
								<thead>
								<tr>
									<!--th><input type="checkbox" class="i-checks" name="input[]"></th-->
									<th>S.No</th>
                                    <th style="width: 200px;">Care Home Name</th>
									<th style="width: 200px;">Course Name</th>
									<th style="width: 300px;">Description</th>
									
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
		var data_url = createURL('get-training-course-list');
        $(document).ready(function() {
            getData();
        });

		//filter data based on status
		$(document).on('change', '#course_status', function(e) {
            updateURL('course_status', $(this).val());
            getData();
        })
    </script>
@endsection