<div class="row" id="staff_list_div">
	<div class="col-lg-12">
		<div class="ibox ">
			<div class="ibox-title d-flex border-0 pl-0">
				<!--h5>Staff List </h5-->
				<form class="mr-2">
					<div class="position-relative table-search">
						<input type="text" placeholder="Search" class="form-control rounded pr-4" id="staff_search">
						<i class="fa fa-search"></i>
					</div>
				</form>
				<div class="ibox-tools">
					@if(Auth::user()->role_id == 2 && $home->subscription && ($home->subscription->stripe_status == 'completed' || $home->subscription->stripe_status == 'active'))
						<?php 
							// if($user_allowed){
							// 	$user_allowed =	$user_allowed->user_allowed;
							// }
							// $total_user_allowed = $user_allowed + $home->staff_capacity; 
						?>
						{{-- @if($total_user_allowed > count($staffs)) --}}
						 @if($home->deleted_at == NULL && checkUserAllowedCapacity($home->id) > countAddedStaff($home->id)) 
							<a data-toggle="tooltip" data-placement="top"   href="{{ route('users.create', ['home_id' => $home->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Staff Member </a>
							@else
							<a data-toggle="tooltip" data-placement="top"   href="javascript:void();" class="btn btn-primary btn-sm add_staff_member" data-name="{{$home->name}}" data-id="{{$home->id}}"><i class="fa fa-plus" aria-hidden="true"></i> Add Staff Member </a>
						 @endif 
					@endif
				</div>
			</div>
			<div class="">
				<div class="table-responsive relative">
					<table class="table" id="staff_list">
						<thead>
							<tr>
								<th class="border-0">S.No</th>
								<th class="border-0">Name</th>
								<th class="border-0">Role</th>
								<th class="border-0">Email</th>
								<th class="border-0">Phone No</th>
								<th class="border-0">Status</th>
								<th class="border-0">Action</th>
							</tr>
						</thead>
						<tbody>
							@if (!$staffs->isEmpty())
								@foreach ($staffs as $key => $record)
									<tr>
										<td>{{ $key + 1 }}</td>
										<td>{{ $record->name }}</td>
										<td>{{ $record->role_name->name }}</td>
										<td>{{ $record->email }}</td>
										<td class="text-nowrap">{{ $record->phone_number }}</td>
										<td>
											@php
												$cls_name = 'label-primary';
												$text = 'Active';
												if ($record->status == 0) {
													$text = 'Inactive';
													$cls_name = 'label-warning';
												}
											@endphp
											<span class="label {{ $cls_name }}"> {{ $text }}</span>
										</td>
										<td class="text-nowrap">
												<a  data-toggle="tooltip" data-placement="top" title="View Staff" href="{{ route('users.show', $record->id) }}" class="btn-primary btn btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
												@if($home->deleted_at == NULL && Auth::user()->role_id != 1)
													<a  data-toggle="tooltip" data-placement="top" title="Edit Staff" href="{{ route('users.edit', $record->id) }}" class="btn-warning btn btn-sm"><i class="fa fa-edit"></i></a>
													<a href="{{ route('assign-training-to-staff.index',['home_id' => $record->home_id,'staff_id' => $record->id]) }}" class="btn-warning btn btn-sm"  data-toggle="tooltip" data-placement="top"   title="Assign Training"><i class="fa fa-file-text-o"></i></a>
													<form action="{{ route('users.destroy', $record->id) }}" method="post" id="delete_form_{{ $record->id }}" class="d-none">
														@csrf
														@method("DELETE")
													</form>
													<a href="javascript:;"  data-toggle="tooltip" data-placement="top" title="Delete Staff" class="btn-danger btn btn-sm confirm_delete" role="button" data-item-id="{{ $record->id }}" data-item-type="user"><i class="fa fa-trash" aria-hidden="true"></i></a>
												@endif
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td class="text-warning text-center" colspan="7">No Data Found</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
				<div id="pagination-section"></div>
			</div>
		</div>
	</div>
</div>