@extends('layouts.admin')

@section('title', 'Care Homes Detail')
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
					<a href="{{ route('homes.index') }}">Care Homes List</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Care Homes Detail</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox ">
					<!--div class="ibox-title d-flex">
						<h5>Care Homes Detail </h5>
					</div-->
					<div class="ibox-content">
						<div class="col-lg-12">
							<div class="widget-head-color-box navy-bg p-sm text-center">
								<div class="m-b-sm">
									<h2 class="font-bold no-margins">{{ $home->admin ? $home->admin->name : '' }}</h2>
									<p class="m-0"><strong>Email:</strong> {{ !is_null($home->email) ? $home->email : 'Not Added !!' }}</p>
									<p class="m-0"><strong>Phone number:</strong> {{ !is_null($home->contact_no) ? $home->contact_no : 'Not Added !!' }}</p>
								</div>
								<img src="{{ $home->image_path }}" class="rounded-circle circle-border m-b-md"  height="100px;" width="100px;" alt="care-home">
								<div>
									<span>{{ $staffs->count() }} Staff Members</span> |
									<!--span>{{ $home->capacity }} Patients Capacity</span-->
									<span>{{ $patients->count() }} Current Patients</span> 
								</div>
							</div>
							<div class="widget-text-box">
								<div class="px-3">
									<div class="mb-4 row">
										<div class="col-6">
											<div class="row">
												<div class="col-6 mb-4">
													<div class="border p-3 rounded-lg h-100 d-flex align-items-start">
														<span class="icon-span mr-2 fs-18 icon-small-span"><i class="fa fa-hospital-o" aria-hidden="true"></i></span>
														<div class="detail-box-div"><h3 class="font-bold mb-0"> Care Home Name</h3>
														<p class="m-0">{{ $home->name }}</p></div>
													</div>
												</div>
												<div class="col-6 mb-4">
													<div class="border p-3 rounded-lg h-100 d-flex align-items-start">
													<span class="icon-span mr-2 fs-18 icon-small-span"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
														<div class="detail-box-div">
														<h3 class="font-bold mb-0"> Home Address</h3>
														<p class="m-0">{{ $home->street }}, {{ $home->city }}, {{ $home->state }}, {{ $home->zip_code }}</p>
														</div>
													</div>
												</div>
												<div class="col-12">
													<div class="border p-3 rounded-lg h-100 d-flex align-items-start">
													<span class="icon-span mr-2 fs-18 icon-small-span"><i class="fa fa-tags"></i></span>
														<div class="detail-box-div w-100">
														<h3 class="font-bold mb-0"> Subscription Package</h3>
														<p class="mb-1">{{ $home->subscription ? $home->subscription->name : '' }}</p>
														<div class="d-flex justify-content-between w-100">
															<p class="m-0"><strong>Fixed Staff: </strong>{{$user_allowed != '' ? $user_allowed->user_allowed : 0}}</p>
															<p class="m-0"><strong>Addons Staff: </strong>{{$home->staff_capacity}}</p>
														</div>
														</div>
													</div>
												</div>
												<div class="col-12 mt-4">
													<div class="border p-3 rounded-lg d-flex align-items-start">
													<span class="icon-span mr-2 fs-18 icon-small-span"><i class="fa fa-home"></i></span>
													<div class="detail-box-div">
														<h3 class="font-bold mb-0"> About Home</h3>
														<p class="m-0">{{ $home->about }}</p></div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-6">
											<div class="ibox product-detail custom-pricing">
												<div class="ibox-content rounded-lg p-0">
													<div class="row">
														@if($home->subscription)
															<div class="col-md-12">
																<h2 class="font-bold m-b-xs px-4 py-2 mb-0">{{ $plan->name }}</h2>
																
																<div class="d-flex justify-content-between align-items-center px-4 py-1 border-bottom mb-3">
																	<h1 class="product-main-price mb-0">${{ number_format($plan->price, 0, '', '')}}<small class="text-muted">/Month</small> </h1>
																	<div class="d-flex align-items-center">
																		@if($home->subscription_status != 'pause' && $home->pause_at == null)
																			<button class="btn btn-primary float-right mr-2 py-1 pause_subscription" type="button" data-item-id="{{ $home->id }}" data-item-type="{{ $home->subscription->type }}" data-stripe-id="{{$home->subscription->stripe_id}}"> Pause</button>
																		@else
			 																<button class="btn btn-primary mr-2 mb-0 py-1 px-2 resume_subscription" type="button" data-item-id="{{ $home->id }}" data-item-type="{{ $home->subscription->type }}" data-stripe-id="{{$home->subscription->stripe_id}}"> Resume</button>

																			<button class="btn btn-primary mb-0 py-1 px-2 cancel_subscription" type="button" data-item-id="{{ $home->id }}" data-item-type="{{ $home->subscription->type }}" data-stripe-id="{{$home->subscription->stripe_id}}">Cancel</button>
																		@endif
																		{{-- <button class="btn btn-primary float-right mr-2 py-1">3 Add-Ons Applied</button> 
																		<button class="btn btn-primary mb-0 py-1 px-2" type="button">
																			<i class="fa fa-plus" aria-hidden="true"></i>
																		</button> --}}
																	</div>
																</div>
																
																<!--ul class="list-group">
																	<li class="list-group-item">
																		<p class="mb-0"><i class="fa fa-check-square-o"></i> Lorem ipsum unknown printer took a galley </p>
																	</li>
																	<li class="list-group-item ">
																		<p class="mb-0"><i class="fa fa-check-square-o"></i> The standard chunk of Lorem Ipsum</p>
																	</li>
																	<li class="list-group-item">
																		<p class="mb-0"><i class="fa fa-check-square-o"></i>  I belive that. Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
																	</li>
																	<li class="list-group-item">
																		<p class="mb-0"><i class="fa fa-check-square-o"></i> Lorem ipsum unknown printer took a galley </p>
																	</li>
																</ul-->
																{!!  $plan != '' ?  $plan->description : '' !!}
																<div class="text-right">
																	<div class="btn-group">
																		@php if($home->subscription)
																			$recurringDate = '';
																			// Add months, years and days
																			if($user_allowed->duration == 1){
																				$recurringDate = date('Y-m-d', strtotime('+1 month', strtotime($home->subscription->created_at)));
																			}elseif($user_allowed->duration == 2){
																				$recurringDate = date('Y-m-d', strtotime('+1 year', strtotime($home->subscription->created_at)));
																			}else{
																				$recurringDate = date('Y-m-d', strtotime('+1 day', strtotime($home->subscription->created_at)));
																			}
																		@endphp
																		<button class="btn btn-white btn-sm badge badge-warning py-1 mr-2 mb-2">Start Date: {{ $home->subscription->subscriptionPayment ? date('M d, Y', strtotime($home->subscription->subscriptionPayment->current_period_start)) : '' }}</button>
																		<button class="btn btn-white btn-sm badge badge-danger mr-2 mb-2">Renewal date: {{ $home->subscription->subscriptionPayment ? date('M d, Y', strtotime($home->subscription->subscriptionPayment->current_period_end)) : '' }}</button>
																	</div>
																</div>
															</div>
														@else
															<div class="col-md-12">
																<div class="border p-3 rounded-lg h-100">
																	<h2 class="font-bold m-b-xs">Subscription Package</h2>
																	<p class="m-0 label label-danger" style="width: 57%;">No Subscription Purchased</p>
																</div>
															</div>	
														@endif
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row mt-5 mb-0 no-gutters list-tabs">
								@php $active_list = Request::has('active_list') ? Request::get('active_list') : 'staff'; @endphp
								<div class="col-lg-6">
									<a class="btn btn-block list-buttons rounded-0 border-0 {{$active_list == 'staff' ? 'btn-primary' : 'btn-default'}}" href="javascript:;" onclick="showHideList('staff', this)">
										<i class="fa fa-users"></i> Staff List
									</a>
								</div>
								<div class="col-lg-6">
									<a class="btn btn-block list-buttons rounded-0 border-0 {{$active_list == 'patient' ? 'btn-primary' : 'btn-default'}}" href="javascript:;" onclick="showHideList('patient', this)">
										<i class="fa fa-users"></i> Patients List
									</a>
								</div>
							</div>
							@include('users.care-home-staff-list')
							@include('patients.care-home-patient-list')
						</div>
					</div>
				</div>
			</div>
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
				$('#staff_list').DataTable({
					pageLength: 10,
					responsive: true,
					lengthChange: false,
					bFilter: false,
					bSort: false, 
				});
				
				$('#patient_list').DataTable({
					pageLength: 10,
					responsive: true,
					lengthChange: false,
					bFilter: false,
					bSort: false, 
				});

			});
        });
		
		const showHideList = (list, obj) => {
			updateURL('active_list', list)
			$('.list-buttons').removeClass('btn-primary').addClass('btn-default');
			$(obj).removeClass('btn-default').addClass('btn-primary');
			if(list == 'staff'){
				$('#patient_list_div').addClass('d-none');
				$('#staff_list_div').removeClass('d-none');
			}else{
				$('#staff_list_div').addClass('d-none');
				$('#patient_list_div').removeClass('d-none');
			}
		}

		$(".pause_subscription").on("click", function(e) {
			var item_id = $(this).data('item-id');
			var item_type = $(this).data('item-type');
			var subscription_id = $(this).data('stripe-id');

			if(typeof item_type === "undefined"){
				item_type = 'data';
			}
			Swal.fire({
				text: "Are you sure you want to pause this "+ item_type +"?",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, Pause it!",
			}).then((result) => {
				if (result.value) {
					// ShowFormLoading();
					// If the user clicks "Yes, Pause it!", make the AJAX call
					$.ajax({
						url: '{{ route('pause-subscription') }}', // Replace with the actual URL of your controller action
						type: 'POST', // or 'GET' depending on your backend route definition
						data: {
							home_id: item_id,
							subscription_type: item_type,
							subscription_id:subscription_id
						},
						success: function (response) {
							// Handle the success response from the server
							console.log(response);
							if(response.status == 'success'){
								toastAlert(response.status, response.message);
								window.location.reload(true);
							}else{
								toastAlert(response.status, response.message);
							}
						},
						error: function (error) {
							// Handle any errors that occurred during the AJAX request
							console.error(error);
						}
					});
				}
			});
		});

		$(".resume_subscription").on("click", function(e) {
			var item_id = $(this).data('item-id');
			var item_type = $(this).data('item-type');
			var subscription_id = $(this).data('stripe-id');
			if(typeof item_type === "undefined"){
				item_type = 'data';
			}
			Swal.fire({
				text: "Are you sure you want to resume this "+ item_type +"?",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, Resume it!",
			}).then((result) => {
				if (result.value) {
					// ShowFormLoading();
					// If the user clicks "Yes, Pause it!", make the AJAX call
					$.ajax({
						url: '{{ route('resume-subscription') }}', // Replace with the actual URL of your controller action
						type: 'POST', // or 'GET' depending on your backend route definition
						data: {
							home_id: item_id,
							subscription_type: item_type,
							subscription_id:subscription_id
						},
						success: function (response) {
							// Handle the success response from the server
							console.log(response);
							if(response.status == 'success'){
								toastAlert(response.status, response.message);
								window.location.reload(true);
							}else{
								toastAlert(response.status, response.message);
							}
						},
						error: function (error) {
							// Handle any errors that occurred during the AJAX request
							console.error(error);
						}
					});
				}
			});
		});

		$(".cancel_subscription").on("click", function(e) {
			var pause_at = new Date('{{$home->pause_at}}');
			var subscription_period_end = new Date('{{$home->subscription->subscriptionPayment ? $home->subscription->subscriptionPayment->current_period_end : ""}}');
			// Calculate the difference in milliseconds
			var timeDifference = subscription_period_end - pause_at;

			// Convert milliseconds to days
			var daysDifference = Math.floor(timeDifference / (1000 * 60 * 60 * 24));

			if(daysDifference <= 1){
				daysDifference = daysDifference + " day";
			}else{
				daysDifference = daysDifference + " days";
			}

			console.log('Difference in days:', daysDifference);
			var item_id = $(this).data('item-id');
			var item_type = $(this).data('item-type');
			var subscription_id = $(this).data('stripe-id');
			if(typeof item_type === "undefined"){
				item_type = 'data';
			}
			Swal.fire({
				text: "Your " + item_type + " package has " + daysDifference + " left.\nAre you sure you want to cancel this subscription?",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, Cancel it!",
			}).then((result) => {
				if (result.value) {
					// ShowFormLoading();
					// If the user clicks "Yes, Pause it!", make the AJAX call
					$.ajax({
						url: '{{ route('cancel-subscription') }}', // Replace with the actual URL of your controller action
						type: 'POST', // or 'GET' depending on your backend route definition
						data: {
							home_id: item_id,
							subscription_type: item_type,
							subscription_id:subscription_id
						},
						success: function (response) {
							// Handle the success response from the server
							console.log(response);
							if(response.status == 'success'){
								toastAlert(response.status, response.message);
								window.location.reload(true);
							}else{
								toastAlert(response.status, response.message);
							}
						},
						error: function (error) {
							// Handle any errors that occurred during the AJAX request
							console.error(error);
						}
					});
				}
			});
		});
		
    </script>
@endsection