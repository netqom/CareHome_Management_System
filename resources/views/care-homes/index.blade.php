@extends('layouts.admin')

@section('title', 'Care Homes List')

@section('content')
<style>
.sort-table-data {
	cursor: pointer;
}
</style>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Care Homes</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox shadow border rounded">
					<div class="ibox-title d-flex align-items-center justify-content-between pr-3 flex-wrap">
						<h5>Care Homes List </h5>
						<div class="align-items-center d-flex">
							<div class="align-items-center d-flex mr-2">
                                <label class="mb-0 mr-2">Status:</label>
                                <select class="form-control" name="care_home_status" id="care_home_status">
                                    <option value="active">Active</option>
                                    <option value="inactive" {{!empty(request()->input('care_home_status')) && request()->input('care_home_status') == 'inactive' ? 'selected' : ''}}>Inactive</option>
                                    <option value="archive" {{!empty(request()->input('care_home_status')) && request()->input('care_home_status') == 'archive' ? 'selected' : ''}}>Archive</option>
                                </select>
                            </div>
							@if(Auth::user()->role_id == 2)
								{{-- <div class="ibox-tools"> --}}
									<a href="{{ route('homes.create') }}" data-toggle="tooltip" data-placement="top"  class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Care Homes</a>
								{{-- </div> --}}
							@endif
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
						<div class="table-responsive">
							<table class="table table-striped" id="data_list">
								<thead>
								<tr>
									<!--th><input type="checkbox" class="i-checks" name="input[]"></th-->
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">S.No</span> {!! checkSortOrder('id', 'id', 'desc') !!}</div></th>
									
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">Care Home</span> {!! checkSortOrder('name') !!}</div></th>
									<th class="text-left"><div class="align-items-baseline d-flex"><span class="mr-1">Subscription Plan</span></div></th>
									@if(Auth::user()->role_id != 2)
									<th>Care Home Admin</th>
									@endif
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">Phone Number</span> {!! checkSortOrder('phone') !!}</div></th>
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">Address</span> {!! checkSortOrder('street') !!}</div></th>
									@if(Auth::user()->role_id != 2)
									<th>Total Staff / Limit Staff</th>
									<th>Total Patient</th>
									@endif
									<!-- <th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">City</span> {!! checkSortOrder('city') !!}</div></th>
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">State</span> {!! checkSortOrder('state') !!}</div></th>
									<th class="text-left sort-table-data" style="min-width: 90px;"><div class="align-items-baseline d-flex"><span class="mr-1">Zip Code</span> {!! checkSortOrder('zip_code') !!}</div></th> -->
									<th class="text-left sort-table-data"><div class="align-items-baseline d-flex"><span class="mr-1">Status</span> {!! checkSortOrder('status') !!}</div></th>
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
	
		var data_url = createURL('homes-get-data');
        $(document).ready(function() {
            getData();
        });

		$(document).on('change', '#care_home_status', function(e) {
            updateURL('care_home_status', $(this).val());
            getData();
        })
    </script>
@endsection