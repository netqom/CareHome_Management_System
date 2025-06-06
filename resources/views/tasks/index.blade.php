@extends('layouts.admin')

@section('title', 'Tasks Management List')

@section('content')
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Tasks Management List</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox ">
					<div class="ibox-title align-items-center d-flex justify-content-between pr-3 flex-wrap">
						<h5>Tasks List </h5>
						<div class="align-items-center d-flex flex-wrap">
							<div class="align-items-center d-flex mr-2">
								<label class="mb-0 mr-2">Status:</label>
								<select class="form-control" name="task_status" id="task_status">
									<option value="active">Active</option>
									<option value="archive" {{!empty(request()->input('task_status')) && request()->input('task_status') == 'archive' ? 'selected' : ''}}>Archive</option>
								</select>
								</div>
							{{-- <div class="ibox-tools"> --}}
								<a href="{{ route('add-update-tasks', $id='') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Task</a>
							{{-- </div> --}}
							</div>
					</div> 
					<div class="ibox-content relative rounded">
						<div class="table-responsive">
							<table class="table table-striped" id="data_list">
								<thead>
								<tr>
									<!--th><input type="checkbox" class="i-checks" name="input[]"></th-->
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">S.No</span> {!! checkSortOrder('id', 'id', 'desc') !!}</div></th> 
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">User</span></div></th>
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">Task For</span> {!! checkSortOrder('user_type') !!}</div></th>
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">Title</span> {!! checkSortOrder('title') !!}</div></th>
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">Start Date</span> {!! checkSortOrder('start_date') !!}</div></th>
									<th class="text-left sort-table-data" style="min-width: 90px;"><div class="align-items-baseline d-flex"><span class="mr-1">End Date</span> {!! checkSortOrder('end_date') !!}</div></th>
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">Task Type</span> {!! checkSortOrder('task_type') !!}</div></th>  
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
		var data_url = createURL('get-tasks-list');
        $(document).ready(function() {
            getData();
        });

		$(document).on('change', '#task_status', function(e) {
            updateURL('task_status', $(this).val());
            getData();
        })
    </script>
@endsection