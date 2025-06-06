@extends('layouts.admin')

@section('title', 'Care Homes Detail')
@section('style')
<link href="{{ asset('assets/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
<style>
	.dataTables_filter {
		display: none;
	}
	.cursor-auto{
		cursor:auto;
	}
</style>
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
<!-- @if(Auth::user()->role_id == 2)
        @if($currently_working_users)
            <div class="mt-3">
                @foreach($currently_working_users as $key => $value)
                    <div class="alert alert-danger align-items-baseline d-flex" role="alert">
                       {{$value->care_home->name}} staff member {{$value->name}} is active now @if($value->role_id == 4) in {{$value->shift_name}}@endif.                    
                    </div>
                @endforeach
            </div>
        @endif
    @endif -->
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox ">
				<div class="ibox-content pb-0 shadow border rounded">
					<div class="ch-detail d-flex flex-wrap flex-sm-nowrap">
						<div class="mb-2 mr-4">
							<div class="position-relative ch-img">
								{{-- <img
									src="https://preview.keenthemes.com/metronic8/demo1/assets/media/avatars/300-1.jpg"
									alt="image" class="img-fluid rounded-lg"> --}}
								<img src="{{ $home->image_path }}" alt="care-home" class="img-fluid rounded-lg">
								<div class="position-absolute {{ $home->status == 1 ? 'ch-online' : ''}}">
								</div>
							</div>
						</div>
						<div class="flex-grow-1">
							<h2 class="font-bold text-body">{{ $home->name }} <i
									class="fa fa-check-circle fs-18 text-navy"></i></h2>
							<div class="d-flex flex-wrap mb-4 ch-info">
								<a href="tel:{{ !is_null($home->contact_no) ? $home->contact_no : '' }}"
									class="align-items-center d-flex font-bold  mb-2 mr-2"><i
										class="fa fa-mobile-phone fs-18 mr-1"></i>
									{{ !is_null($home->contact_no) ? $home->contact_no : 'Not Added !!' }}</a>
								<span style="font-weight: var(--fa-style,900); margin-right: 7px; color: #8d8d8d;">
									|
								</span>
								<a href="mailto:{{ !is_null($home->email) ? $home->email : '' }}"
									class="align-items-center d-flex font-bold  mb-2 mr-2"><i
										class="fa fa-envelope  mr-1"></i>
									{{ !is_null($home->email) ? $home->email : 'Not Added !!' }}</a>
								<span style="font-weight: var(--fa-style,900); margin-right: 7px; color: #8d8d8d;">
									|
								</span>
								<a href="javascript:void(0);" class="align-items-center d-flex font-bold  mb-2 mr-4 cursor-auto"><i
										class="fa fa-user-circle  mr-1"></i> {{ $staffs->count() }} Staff Members </a>
								<span style="font-weight: var(--fa-style,900); margin-right: 7px; color: #8d8d8d;">
									|
								</span>
								<a href="javascript:void(0);" class="align-items-center d-flex font-bold  mb-2 mr-4 cursor-auto">
										<i
										class="fa fa-bed  mr-1"></i>{{ $patients->count() }} Current Patients</a>
								<span style="font-weight: var(--fa-style,900); margin-right: 7px; color: #8d8d8d;">
									|
								</span>
								<a href="javascript:void(0);" class="align-items-center d-flex font-bold  mb-2 mr-4 cursor-auto"><i
										class="fa fa-calendar mr-1"></i> {{ date('F d,Y ', strtotime($home->created_at))}}</a>

							</div>
							<div class="d-flex flex-wrap ch-stats">
								<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
									<div class="d-flex align-items-center">
										<div class="counted font-bold fs-16 text-body"><i class="fa fa-usd text-navy"
												aria-hidden="true"></i></i>
											{{ $home->subscription ? $home->subscription->type : 'No Subscription'}}
										</div>
									</div>
									<div class="font-bold  text-muted">Subscription Package</div>
								</div>
								<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
									<div class="d-flex align-items-center">
										<div class="counted font-bold fs-16 text-body"><i class="fa fa-user text-navy"
												aria-hidden="true"></i>
											{{$user_allowed != '' ? $user_allowed->user_allowed : 0}}
										</div>
									</div>
									<div class="font-bold  text-muted">Fixed Staff</div>
								</div>
								<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
									<div class="d-flex align-items-center">
										<div class="counted font-bold fs-16 text-body">
											<i class="fa fa-user-plus text-navy" aria-hidden="true"></i>
											{{$home->staff_capacity}}
										</div>
									</div>
									<div class="font-bold  text-muted">Addons Staff</div>
								</div>
							  @if($home->privacy_policy!='')	
								<div class="border mb-3 mr-3 px-3 py-2 rounded ch-stats-item">
									<a href="{{url('/').'/'.$home->privacy_policy}}" class="align-items-center d-flex font-bold mb-2 mr-4 cursor-auto" style="cursor:pointer" download>	
									<div class="d-flex align-items-center">
										<div class="counted font-bold fs-16 text-body">
											@php $icon = getFileIcon($home->privacy_policy); @endphp
											   {!!$icon['fileIcon']!!} 
										</div>
									</div>
									<div class="font-bold  text-muted">
										 Privacy Policy
                                     </div>
									</a> 
								</div>
							  @endif	
							</div>
						</div>
					</div>
					<div class="mt-3 responsive-scroll-x">
						<ul class="nav ch-tabs">
							<li class="nav-item">
								<a class="font-bold  py-3 px-2 mr-4 nav-link active patient-tab" id="tab1-tab"
									data-toggle="tab" href="#tab1">Overview</a>
							</li>
							@if($home->status == 1)
								<li class="nav-item">
									<a class="font-bold  py-3 px-2 mr-4 nav-link patient-tab" id="tab7-tab"
										data-toggle="tab" href="#tab7">Document</a>
								</li>
								<li class="nav-item">
									<a class="font-bold  py-3 px-2 mr-4 nav-link patient-tab" id="tab2-tab"
										data-toggle="tab" href="#tab2">Staff List</a>
								</li>
								<li class="nav-item">
									<a class="font-bold  py-3 px-2 mr-4 nav-link patient-tab" id="tab3-tab"
										data-toggle="tab" href="#tab3">Patient List</a>
								</li>
							@endif
							<li class="nav-item">
								<a class="font-bold  py-3 px-2 mr-4 nav-link patient-tab" id="tab4-tab"
									data-toggle="tab" href="#tab4">Subscribed Details</a>
							</li>
							<li class="nav-item">
								<a class="font-bold  py-3 px-2 mr-4 nav-link patient-tab" id="tab5-tab"
									data-toggle="tab" href="#tab5">Revenue</a>
							</li>
							<li class="nav-item">
								<a class="font-bold  py-3 px-2 mr-4 nav-link patient-tab" id="tab6-tab"
									data-toggle="tab" href="#tab6">Expenses</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="ibox-content mt-3 shadow border rounded">
					<div class="tab-content mt-2">
						<div class="tab-pane fade show active" id="tab1">
							<h3 class="text-body">About Home</h3>
							<p>{{ $home->about }}</p>
							@if($home->deleted_at == null && $home->status == 1)
								<div class="">
									@include('care-homes.partials.current-active-user-list')
								</div>
							@endif
						</div>
						<div class="tab-pane fade" id="tab2">
							<div class="">
								@include('users.care-home-staff-list')
							</div>
						</div>
						<div class="tab-pane fade" id="tab3">
							<div class="">
								@include('patients.care-home-patient-list')
							</div>
						</div>
						<div class="tab-pane fade" id="tab4">
							@include('care-homes.partials.subscription-details')
						</div>
						<div class="tab-pane fade" id="tab5">
							<div class="">
								@include('care-homes.partials.revenue-list')
							</div>
						</div>
						<div class="tab-pane fade" id="tab6">
							<div class="">
								@include('care-homes.partials.expense-list')
							</div>
						</div>
						<div class="tab-pane fade" id="tab7">
							<div class="">
								@include('care-homes.partials.document-list')
							</div>
						</div>
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
    $(".download-ele").on("click",function(){
      alert('sd');
	});
	$(document).ready(function () {
		function showTabFromHash() {
			var hash = window.location.hash;
			if (hash) {
				$('.nav-link[href="' + hash + '"]').tab('show');
			}
		}

		// Show the correct tab when the page loads
		showTabFromHash();

		// Show the correct tab when the hash changes (e.g., user clicks a link)
		$(window).on('hashchange', function () {
			showTabFromHash();
		});

		// Update the URL hash when a tab is clicked
		$('.nav-link').on('click', function () {
			window.location.hash = $(this).attr('href');
		});
		if (window.location.hash) {
			var activeTabId = window.location.hash.substring(1);
			if ($('#' + activeTabId).length > 0) {
				$('[data-toggle="tab"]').removeClass('active');
				$('a[href="#' + activeTabId + '"]').addClass('active');
				$('.tab-pane').removeClass('show active')
				$('#' + activeTabId).addClass('show active')
			}
		}

		// Upgrade button class name
		$.fn.dataTable.Buttons.defaults.dom.button.className = 'btn btn-white btn-sm';
		@if (!$staffs->isEmpty())
			var staff_list = $('#staff_list').DataTable({
				pageLength: 10,
				responsive: true,
				lengthChange: false,
				searching: true,
				bFilter: false,
				bSort: false,
			});

			$('#staff_search').on('keyup', function () {
				staff_list.search($(this).val()).draw();
			});
		@endif
		@if (!$patients->isEmpty())
			var patient_list = $('#patient_list').DataTable({
				pageLength: 10,
				responsive: true,
				lengthChange: false,
				searching: true,
				bFilter: false,
				bSort: false,
			});

			$('#patient_search').on('keyup', function () {
				patient_list.search($(this).val()).draw();
			});
		@endif
		@if(!$revenue->isEmpty())
			$('#revenue_list').DataTable({
				pageLength: 10,
				responsive: true,
				lengthChange: false,
				bFilter: false,
				bSort: false,
			});
		@endif
		@if (!$patient_expenses->isEmpty())
			$('#expense_list').DataTable({
				pageLength: 10,
				responsive: true,
				lengthChange: false,
				bFilter: false,
				bSort: false,
			});
		@endif
		@if (!$subscription_invoices->isEmpty())
			$('#invoices_table').DataTable({
				pageLength: 10,
				responsive: true,
				lengthChange: false,
				bFilter: false,
				bSort: false,
			});
		@endif
	});

	const showHideList = (list, obj) => {
		updateURL('active_list', list)
		$('.list-buttons').removeClass('btn-primary').addClass('btn-default');
		$(obj).removeClass('btn-default').addClass('btn-primary');
		if (list == 'staff') {
			$('#patient_list_div').addClass('d-none');
			$('#staff_list_div').removeClass('d-none');
		} else {
			$('#staff_list_div').addClass('d-none');
			$('#patient_list_div').removeClass('d-none');
		}
	}

	$(".pause_subscription").on("click", function (e) {
		var item_id = $(this).data('item-id');
		var item_type = $(this).data('item-type');
		var subscription_id = $(this).data('stripe-id');

		if (typeof item_type === "undefined") {
			item_type = 'data';
		}
		Swal.fire({
			text: "Are you sure you want to pause " + item_type + "?",
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
						subscription_id: subscription_id
					},
					success: function (response) {
						// Handle the success response from the server
						console.log(response);
						if (response.status == 'success') {
							toastAlert(response.status, response.message);
							window.location.reload(true);
						} else {
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
	$(".add_staff_member").on("click", function (e) {
		id = $(this).data('id');
		name = $(this).data('name');
		Swal.fire({
			text: name + " staff limit exceeded. Please upgrade your plan or purchase Add-ons",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#f39c12",
			confirmButtonText: "Add Addon",
			cancelButtonText: "Upgrade Plan",
			//footer: '<button id="upgradeButton" class="swal2-confirm swal2-styled" style="background-color: #f39c12;">Upgrade Plan</button>',
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = "{{ route('subscription-manage', ['id' => $home->id, 'add_ons' => 'yes']) }}";
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				window.location.href = "{{ route('subscription-manage', ['id' => $home->id, 'upgrade_downgrade_plan' => 'yes']) }}";
			}
		})
	});

	$(".resume_subscription").on("click", function (e) {
		var item_id = $(this).data('item-id');
		var item_type = $(this).data('item-type');
		var subscription_id = $(this).data('stripe-id');
		if (typeof item_type === "undefined") {
			item_type = 'data';
		}
		Swal.fire({
			text: "Are you sure you want to resume this " + item_type + "?",
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
						subscription_id: subscription_id
					},
					success: function (response) {
						// Handle the success response from the server
						console.log(response);
						if (response.status == 'success') {
							toastAlert(response.status, response.message);
							window.location.reload(true);
						} else {
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

	$(".cancel_subscription").on("click", function (e) {
		// var pause_at = new Date('{{$home->pause_at}}');
		var pause_at = new Date();
		var subscription_period_end = new Date('{{$home->subscription && $home->subscription->subscriptionPayment ? $home->subscription->subscriptionPayment->current_period_end : ""}}');
		// Calculate the difference in milliseconds
		var timeDifference = subscription_period_end - pause_at;

		// Convert milliseconds to days
		var daysDifference = Math.floor(timeDifference / (1000 * 60 * 60 * 24));

		if (daysDifference <= 1) {
			daysDifference = daysDifference + " day";
		} else {
			daysDifference = daysDifference + " days";
		}

		console.log('Difference in days:', daysDifference);
		var item_id = $(this).data('item-id');
		var item_type = $(this).data('item-type');
		var subscription_id = $(this).data('stripe-id');
		if (typeof item_type === "undefined") {
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
						subscription_id: subscription_id
					},
					success: function (response) {
						// Handle the success response from the server
						console.log(response);
						if (response.status == 'success') {
							toastAlert(response.status, response.message);
							window.location.reload(true);
						} else {
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

	$(".open-close-row").on('click', function () {
		var item_id = $(this).data('item-id');
		console.log('item_id', item_id)
		$('#info_row_' + item_id).toggle();
	})
	$(document).on('click', '.patient_capacity_check', function () {

		var home_id = "{{$home->id}}";
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
	})
</script>
@endsection