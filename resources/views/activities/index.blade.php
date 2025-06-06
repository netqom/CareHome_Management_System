@extends('layouts.admin')

@section('title', 'Activities List')

@section('content')
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Activities</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox shadow border rounded">					
					<div class="align-items-center ibox-title d-flex justify-content-between pr-3 flex-wrap">
						<h5>Activities List </h5>
						<div class="align-items-center d-flex flex-wrap">
							<div class="align-items-center d-flex mr-2">
								<label class="mb-0 mr-2">Status:</label>
								<select class="form-control" name="activity_status" id="activity_status">
									<option value="active">Active</option>
									<option value="inactive" {{!empty(request()->input('activity_status')) && request()->input('activity_status') == 'inactive' ? 'selected' : ''}}>Inactive</option>
									<option value="archive" {{!empty(request()->input('activity_status')) && request()->input('activity_status') == 'archive' ? 'selected' : ''}}>Archive</option>
								</select>
							</div>
							{{-- <div class="ibox-tools"> --}}
								<a href="{{ route('activities.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> 
								Add Activity</a>
							{{-- </div> --}}
						</div>
					</div>
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
					<div class="ibox-content relative">
						<div class="table-responsive">
							<table class="table table-striped" id="data_list">
								<thead>
								<tr>
									<!--th><input type="checkbox" class="i-checks" name="input[]"></th-->
									<th>S.No</th>
									<th>Activity</th>
									<th>Shifts</th>
									<th>Status</th>
									
									<th class="activity_action_column {{empty(request()->input('activity_status')) || request()->input('activity_status') != 'archive' ? '' : 'd-none'}}">Action</th>
									
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
	    var data_url = createURL('activities-get-data');
        $(document).ready(function() {
            getData();
        });


		//filter data based on status
		$(document).on('change', '#activity_status', function(e) {
            updateURL('activity_status', $(this).val());
			if($(this).val() == 'archive'){
				$('.activity_action_column').addClass('d-none')
			}else{
				$('.activity_action_column').removeClass('d-none')
			}
            getData();
        })
    </script>
@endsection