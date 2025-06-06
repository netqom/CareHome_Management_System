@extends('layouts.admin')

@section('title', 'Manage Subscription')
@section("style")
<link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet">
<style>
	.wizard > div.content {
    background: #f3f3f3;
}
.wizard > div.content > .body {
    position: relative;
    width: 100%;
    height: auto;
}
.home-image-upload {
    position: relative;
}
.home-image-upload img {
    width: 100px;
	height: 100px;
	object-fit: cover;
}

.home-image-upload button {
    position: absolute;
    bottom: 0;
    right: 0;
    background: #3a3a3a;
    border: 0;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    color: #fff;
    box-shadow: 0px 0px 6px 1px #c3c3c3;
}
.payment-radio {
			opacity: 0;
			position: absolute;
			inset: 0;
			z-index: 99;
			cursor: pointer;
		}
    .payment-card {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
        border-color: transparent;
        border-width: 2px;
    }  
    .payment-radio:checked + .payment-card {
    border-color: #1ab394;
    border-width: 2px;
}
.steps-tab {
    position: relative;
    display: block;
    width: 100%;
}
.steps-tab > ul > li {
    width: 25%;
	display: block;
    padding: 0;
	float: left;
}
.steps-tab .current a {
    background: #1AB394;
    color: #fff;
    cursor: default;	    
}
.steps-tab .disabled a {
    background: #eee;
    color: #aaa;
    cursor: default;
}
.steps-tab a {
   display: block;
    width: auto;
    margin: 0 0.5em 0.5em;
    padding: 8px;
    text-decoration: none;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}
.map-div {
    height: 140px;
    border: 1px solid #e5e6e7;
    background: #e3e3e3;
}
.card-form {
	border: 1px solid #dcdcdc;
    padding: 7px;
	border-radius: 5px;
}

.active-tab {
    background-color: #18a689;
    border-color: #18a689;
	border-radius: 5px;
    color: #FFFFFF;
}

.inactive-tab {
    background: #eee;
	border-color: #e3e1e1;
	border-radius: 5px;
    color: #aaa;
}
.plan-item input[type="radio"] {
    width: 0;
    height: 0;
}
.plan-item input[type="radio"] label {cursor: pointer;}
.plan-item input[type="radio"]:checked + label .plan-single {
    background: #08a29e;
    margin-top: 50px;
}
.plan-item input[type="radio"]:checked + label .plan-single h6, 
.plan-item input[type="radio"]:checked + label .plan-singlespan, 
.plan-item input[type="radio"]:checked + label .plan-single h2, 
.plan-item input[type="radio"]:checked + label .plan-single li, 
.plan-item input[type="radio"]:checked + label .plan-single li::before {
    color: #fff !important;
}

.error-message {
    /* display: none; */
    width: 100%;
    margin-top: 0.25rem;
    font-size: 91%;
    color: #dc3545;
    text-align: start;
}
.disabled-plan {
    pointer-events: none;
    opacity: 0.6;
}
li.more {
    padding-top: 14px;
}
li.more:before {
    content: none !important;
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
					<a href="{{ route('homes.index') }}">Care Homes</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Manage Subscription</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox shadow border rounded ">
					<div class="ibox-content">
						<div class="ibox-content">
							<form id="careHomeManageSubscription" class="wizard-big" method="POST" action="{{ route('handle-subscription-manage') }}">
								@csrf
								<input type="hidden" name="home_id" id="home_id" value="{{ $home->id }}">
								<input type="hidden" name="new_subscription" id="new_subscription" value="{{$upgrade_price == 'yes' || $upgrade_downgrade_plan == 'yes' ? 'no' : 'yes' }}">
								<input type="hidden" name="email" value={{ $home->email ? $home->email : ""}}>
								<input type="hidden" name= "update_notification" value="{{ $upgrade_price == 'yes' ? 'yes' : 'no' }}">
								
								<fieldset class="" id="form_step_2">
									@if($upgrade_price == 'yes' || $upgrade_downgrade_plan == 'yes')
										<input type="hidden" name= "prev_plan_user_allowed" value="{{previousPlanUserAllowed($home->id)}}" id="prev_plan_user_allowed">
										<input type="hidden" name= "count_added_staff" value="{{ countAddedActiveStaff($home->id) }}" id="count_added_staff">
										<!--h2 class="border-bottomfont-normal mb-3 pb-2">Select Subscription Plan</h2-->
										<div class="row">
											<div class="col-lg-12 border-bottom">
												<div class="alert alert-warning">
													<label class=""> 
														<div class="icheckbox_square-green" style="position: relative;">
															<input type="checkbox" value="1" name="agreed_price_update" class="i-checks" style="position: absolute; opacity: 0;" id="agreed_price_update">
															<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
														</div>  
														{{ $upgrade_downgrade_plan == 'yes' ? "If you want to change the plan and ok with price just click and procceed" : 'If you want to go with same plan and ok with price just click and procceed' }}
													</label><br>
													<span class="text-info">Note: Any outstading amount will be adjusted in next invoice automatically</span>
												</div>
											</div>
										</div>
                                    @endif								
									<div class="justify-content-center row mb-5">
								
										 @foreach($plans as $key => $value)
										 	<?php 
												$get_discount = "";
												if(!$upgrade_price && !$upgrade_downgrade_plan && !$add_ons){
													$get_discount = getDiscountOnPlan($value->id); 
												}
											?>
											
											@if($value->status == 1)
											
													{{-- @if($add_ons && @$home->subscription_plan_id == @$value->id && @$home->getCareHomeSubscription->stripe_status == 'active' ) --}}
												<div class="col-md-4">
													@elseif(($upgrade_downgrade_plan))
													<div class="col-md-4">
														@else
														<div class="col-md-4 d-none">
														@endif
													<div class="plan-item {{(@$upgrade_downgrade_plan || $add_ons) && @$home->subscription_plan_id == @$value->id && @$home->getCareHomeSubscription->stripe_status == 'active' ? 'disabled-plan': ''}}">
											    		<input id="subscription_plan_{{ $value->id }}" type="radio" value="{{ $value->id }}" name="subscription_plan"  @if(($upgrade_price == 'yes' || $add_ons == "yes") && @$home->subscription_plan_id == @$value->id && @$home->getCareHomeSubscription->stripe_status == 'active') checked @elseif($upgrade_downgrade_plan == 'yes') checked @elseif(!$upgrade_downgrade_plan && !$upgrade_price && !$add_ons) checked @endif data-discount="{{$get_discount ? $get_discount->stripe_id : ''}}" data-user-allowed="{{$value->user_allowed}}">

														<label for="subscription_plan_{{ $value->id }}">
															<div class="plan-single text-center position-relative pt-4">
																<h2 class="green dis-block mt-4"><b>{{ $value->name }}</b></h2>
																@if($get_discount)
																	<span class="discount-tab">{{$get_discount->discount_type == 1 ? $get_discount->discount.'%' : '$'.$get_discount->discount}} off</span>
																@endif
																<hr>
																<h2 class="green">
																	{{-- ${{ number_format($value->price, 0, '', '')}} --}}
																	${{ $value->price }}
																	<span class="dark-green fs-16">/
																		{{ $value->duration == 1 ? 'Month' : ($value->duration == 2 ? 'Year' : 'Daily') }}
																	</span>
																</h2>
																<span class="addon fs-14 white">Add on ${{$value->addons_price}}/Staff</span>
																{{-- <span class="addon fs-14 white">Add on ${{number_format($value->addons_price, 0, '', '')}}/Staff</span> --}}
																{!!  $value ?  $value->description : '' !!}
																
															{{--<!-- 	<h3 class="text-white">Services Available</h3>
																<ul class="text-left p-0">
																	@if($value->permission->allow_geofencing == 1)
																		<li>Allow Geofencing</li>
																		<input type="hidden" name="subscription_plan_permission[allow_geofencing]" value="{{$value->permission->allow_geofencing}}">
																	@endif
																	@if($value->permission->allow_add_images == 1)
																		<li>Add Images</li>
																		<input type="hidden" name="subscription_plan_permission[allow_add_images]" value="{{$value->permission->allow_add_images}}">
																	@endif
																	@if($value->permission->allow_manage_med_report == 1)
																		<li>Manage Medicine Report</li>
																		<input type="hidden" name="subscription_plan_permission[allow_manage_med_report]" value="{{$value->permission->allow_manage_med_report}}">
																	@endif
																</ul> -->--}}
															</div>
													 	</label>
													</div>	
													@if(@$home->getCareHomeSubscription && $home->subscription_plan_id == $value->id && (@$home->getCareHomeSubscription->stripe_status == 'completed' || @$home->getCareHomeSubscription->stripe_status == 'active'))											 
														@if($value->addon_status == 1 && $add_ons == "yes")
															<div class="add-on">
																<p class="d-flex justify-content-between white border-bottom pb-2 mb-2"><span>Number of add-ons applied</span> <span><b></b></span></p>
																<div class="addon-form justify-content-between">
																	
																	@if(request()->get('type') == 'patient')
																		<div class="form-group row mb-0">
																			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																				<input type="number" id="patient_capacity" name="patient_capacity" class="form-control integer_no" placeholder="Add Patient Addons">
																				<span class="patient_capacity_err" style></span>
																			</div>
																		</div>
																	@else
																		<div class="form-group row mb-0">
																			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																				<input type="number" id="staff_capacity" name="staff_capacity" class="form-control integer_no" placeholder="Add Staff Addons">
																				<span class="staff_capacity_err" style></span>
																			</div>
																		</div>
																	@endif
																</div> 
															</div>
														@endif
													@endif
												</div>
											{{-- @endif --}}
										@endforeach
									</div>
									<input type="hidden" name="discount" id="discount" value="">
									<span class="text-danger m-t-xs @if($upgrade_price == 'yes' || $upgrade_downgrade_plan == 'yes') d-none @endif" id="subacription_plan_error"></span>
									<div class="hr-line-dashed"></div>
									
									<div class="row" id="stripe_card_form_div">
										<div class="col-lg-6">
											<div class="form-group">
												<label for="card_holder_name">Name</label>
												<input type="text" name="card_holder_name" id="card_holder_name" class="form-control rounded required" value=""
													placeholder="Name on the card" required>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label>Card details</label>
												<div class="card-form" id="card-element"></div>
												<span class="text-danger m-t-xs d-none" id="stripe_error"></span>
											</div>
										</div>
									</div>
									<div class="row @if($upgrade_price == 'yes' || $upgrade_downgrade_plan == "yes" || $add_ons == "yes") d-none @endif" id="coupon-div">
										<div class="col-lg-3"></div>
										<div class="col-lg-4">
											<div class="form-group">
												<label for="card_holder_name">Have Coupon ?</label>
												<input type="text" name="coupon_code" id="coupon_code" class="form-control rounded" value="" placeholder="Add Coupon Code Here">
												<span class="text-danger m-t-xs d-none" id="coupon_code_error"></span>
												<span class="text-info m-t-xs d-none" id="coupon_code_success"></span>
											</div>
										</div>
										<div class="col-lg-2">
											<div class="actions clearfix" id="verify_button_div">
												<button class="btn btn-primary mt-4" type="button" id="verify_coupon_code">Verify</button>
											</div>
											<div class="actions clearfix" id="verify_loader_button_div" style="display: none;">
												<button type="button" class="btn btn-primary" disabled>
													<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
													Wait ...
												</button>
											</div>
										</div>
										<div class="col-lg-3"></div>
									</div>	
									<div class="hr-line-dashed"></div>
									<div class="actions clearfix float-right" id="submit_button_div">
										<a class="btn btn-white mr-2 font-bold" href="{{ createCancelUrl(route('homes.index')) }}">Cancel</a>
										<a class="btn btn-primary font-bold" href="javascript:;" data-current-step="2" onclick="updateSubscripton()">Submit</a>
									</div>
									<div class="actions clearfix float-right" id="loader_button_div" style="display: none;">
										<div class="col-lg-12 col-m-12">
											<button type="button" class="btn btn-primary" disabled>
												<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
												Wait Processing...
											</button>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('script')
    <script src="https://kit.fontawesome.com/66517fe47a.js" crossorigin="anonymous"></script>
	<script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
		const stripe       = Stripe("{{ config('app.stripe_key') }}")
        //const elements     = stripe.elements()
        //const cardElement  = elements.create('card')
		const appearance = {theme: 'night'};
		const elements = stripe.elements({appearance})
	
		const cardElement = elements.create('card', {
		  style: {
			base: {
			  iconColor: '#000',
			  color: '#000',
			  fontWeight: '500',
			  fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
			  fontSize: '16px',
			  fontSmoothing: 'antialiased',
			  ':-webkit-autofill': {
				color: '#000',
			  },
			  '::placeholder': {
				color: '#000',
			  },
			},
			invalid: {
			  iconColor: '#dc3545',
			  color: '#dc3545',
			},
		  },
		})
        cardElement.mount('#card-element')
		cardElement.on('ready', function(event) {
			console.log('card element ready function');
		  // Handle ready event
		});
		cardElement.addEventListener('change', function(event) {
			var displayError = document.getElementById('card-errors');
			if (event.error) {
				if($('#stripe_error').hasClass('d-none')){
					$('#stripe_error').removeClass('d-none')
				}
				$('#stripe_error').text(event.error.message);
			} else {
				$('#stripe_error').addClass('d-none').text('');
			}
		});
		
		$(document).ready(function () {
			$('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });

			// Check if the checkbox is checked on page load
			if ($('input[type=radio][name=subscription_plan]').is(':checked')) {
				//$('#stripe_card_form_div').removeClass('d-none');
				var discount = $('input[type=radio][name=subscription_plan]:checked').data('discount');
				$('#discount').val(discount); // Set the value to the hidden input field

				if(discount != ''){
					$('#coupon-div').addClass('d-none');
				}
			}

			//plan radio button clicked
			$('input[type=radio][name=subscription_plan]').change(function() {
				var discount = this.getAttribute('data-discount');
				var upgrade_downgrade_plan = "{{$upgrade_downgrade_plan}}";
				var upgrade_price = "{{$upgrade_price}}";

				if(discount != '' || upgrade_downgrade_plan == "yes" || upgrade_price == "yes"){
					$('#coupon-div').addClass('d-none');
				//	$('#stripe_card_form_div').addClass('d-none');
				}else{
					$('#coupon-div').removeClass('d-none');
					//$('#stripe_card_form_div').removeClass('d-none');
				}

				$('#discount').val(discount);

				if (this.value != '') {
					$('#subacription_plan_error').addClass('d-none').text('');
				}else{
					if($('#subacription_plan_error').hasClass('d-none')){
						$('#subacription_plan_error').removeClass('d-none')
					}
					$('#subacription_plan_error').text('Please select a plan to procced.');
				}
			});

			$('#verify_coupon_code').click(function(){
				var coupon_code = $('#coupon_code').val();
				if(coupon_code == ''){
					$('#coupon_code_error').removeClass('d-none');
					$('#coupon_code_error').text('Coupon code is required');
					return false;
				}else{
					$('#coupon_code_error').addClass('d-none');
					$('#coupon_code_error').text('');
				}
				$('#verify_button_div').hide();
				$('#verify_loader_button_div').show();
				$.ajax({
					type: "GET",
					url: "{{ route('verify-coupons-code') }}",
					data: {coupon_code: coupon_code},
					success: function(response) {
						$('#verify_button_div').show();
						$('#verify_loader_button_div').hide();
						
						if(response.type == 'success'){
							$('#coupon_code_error').addClass('d-none');
							$('#coupon_code_error').text('');
							$('#coupon_code_success').removeClass('d-none');
							$('#coupon_code_success').text(response.message);
						}else{
							$('#coupon_code').val('');
							$('#coupon_code_success').addClass('d-none');
							$('#coupon_code_success').text('');
							$('#coupon_code_error').removeClass('d-none');
							$('#coupon_code_error').text(response.message);
						}	
					},
					error: function(err, xhr) {
						
					},
			   });
			});
		}) 
		
		const updateSubscripton = () => {
			if ($('input[type=radio][name=subscription_plan]').is(':checked')) {
				$('#subacription_plan_error').addClass('d-none').text('');

				var newSubscription = $('#new_subscription').val();
				//console.log(newSubscription);
				
				if(newSubscription === 'no'){
					//upgrade subscription price && upgrade - downgrade subscription
					if ($('#agreed_price_update').is(':checked')) {
						// Perform additional actions if checkbox is checked
						var prev_plan_user_allowed = $('#prev_plan_user_allowed').val();
						var user_allowed = parseInt($('input[type=radio][name=subscription_plan]:checked').data('user-allowed'));
						var count_added_staff = parseInt($('#count_added_staff').val());

						if(count_added_staff > 0 && (user_allowed < count_added_staff || user_allowed < prev_plan_user_allowed)){
							toastAlert('error', 'To purchase this Subscription you need only '+user_allowed+' Active member');
						}else{

							let form     = $('#careHomeManageSubscription')[0];
							let formData = new FormData(form);

							$('#submit_button_div').hide();
							$('#loader_button_div').show();
							$('.form-loader').css('display', 'flex');
							$.ajax({
								type: "POST",
								url: "{{ route('handle-subscription-manage') }}",
								data: formData,
								contentType: false,
								cache: false,
								processData:false,
								success: function(response) {
									$('#submit_button_div').show();
									$('#loader_button_div').hide();
									$('.form-loader').css('display', 'none');
									/*console.log("response====",response)
									return false;*/
									if(response.status == 'success'){
										toastAlert(response.status, response.message);
										//clear the form on submit
										$('#careHomeManageSubscription')[0].reset();
										window.location.href = BASE_URL + '/' + 'homes';
									}else{
										toastAlert(response.status, response.message);
									}
								},
								error: function(err, xhr) {
									console.log(err);
									
								},
							});
						}
					} else {
						console.log('Checkbox is not checked');
						toastAlert('error', 'Please checked the checkbox to proceed');
					}
				}else{
					
					// buy new subscription
					if ($('#staff_capacity').length) {
						var add_ons = $('#staff_capacity').val();
					}else{
						var add_ons = $('#patient_capacity').val();
					}
					//var add_ons = $('#staff_capacity').val();
					var card_holder_name = $('#card_holder_name').val();
					
					if(add_ons == ''){
						$('#staff_capacity').prop("required", true);
						$('.staff_capacity_err').text('Add on staff capacity field required.');
						$('.add-on').css({'padding-bottom':'25px', 'color':'red'});

						$('#patient_capacity').prop("required", true);
						$('.patient_capacity_err').text('Add on patient capacity field required.');
						$('.add-on').css({'padding-bottom':'25px', 'color':'red'});
					}else{
						$('#staff_capacity').prop("required", false);
						$('.staff_capacity_err').text('');
						$('#patient_capacity').prop("required", false);
						$('.patient_capacity_err').text('');
					}
					if(card_holder_name == ''){
						$('#card_holder_name').prop("required", true);
					
					}else{
						$('#card_holder_name').prop("required", false);
					}

					$('#submit_button_div').hide();
					$('#loader_button_div').show();
					$('.form-loader').css('display', 'flex');
					createStripeToken();
				}
			}else{
				if($('#subacription_plan_error').hasClass('d-none')){
					$('#subacription_plan_error').removeClass('d-none')
				}
				$('#subacription_plan_error').text('Please select a plan to procced.');
			}
		}
		
		const createStripeToken = () => {
				stripe.createPaymentMethod({ elements, params: {
				billing_details: {
						name: $('#card_holder_name').val(),
						email: $('#email').val(),
				},
				},
			})
			.then(function(result) {
				console.log('payment method result', result)
				stripeResponseHandler(result);
			});
		}
		
		const stripeResponseHandler = (response) => {
			//console.log('handle stripe response data', response)
			var $form = $('#careHomeManageSubscription');
			if (response.error) {
				$('#submit_button_div').show();
				$('#loader_button_div').hide();
				$('.form-loader').css('display', 'none');
				if($('#stripe_error').hasClass('d-none')){
					$('#stripe_error').removeClass('d-none')
				}
				$('#stripe_error').text(response.error.message);
			} else {
				var token = response.paymentMethod.id;
				//first check and remove any pre input element apened
				$("input[name=stripeToken]").remove();//apend new token
				$form.append($('<input type="hidden" name="stripeToken" />').val(token));
				submitFormData();
			}
		};
		
		function submitFormData() {
			let form     = $('#careHomeManageSubscription')[0];
			let formData = new FormData(form);
			$.ajax({
				type: "POST",
				url: "{{ route('handle-subscription-manage') }}",
				data: formData,
				contentType: false,
				cache: false,
				processData:false,
				success: function(response) {
					$('#submit_button_div').show();
					$('#loader_button_div').hide();
					$('.form-loader').css('display', 'none');
					if(response.status == 'success'){
						toastAlert(response.status, response.message);
						//clear the form on submit
						$('#careHomeManageSubscription')[0].reset();
						window.location.href = BASE_URL + '/' + 'homes';
					}else if(response.status == 'payment_action_required'){
						toastAlert(response.status, response.message);
						window.open(response.url, '_blank');
					}else{
						toastAlert(response.status, response.message);
					}
				},
				error: function(err, xhr) {
					//console.log(err);
					$('#submit_button_div').show();
					$('#loader_button_div').hide();
					$('.form-loader').css('display', 'none');
					if (err.responseJSON.message === undefined) {
						toastAlert("error", err.responseJSON);
					} else {
						toastAlert("error", err.responseJSON.message);
					}
					var errors = err.responseJSON.errors;
					$.each(errors, function(indexInArray, valueOfElement) {
						  var fieldName = "[name='" + indexInArray + "']";
    
						// Check if an error message already exists for the field
						if ($(fieldName).next(".payment-error").length === 0) {
							// Append the error message only if it doesn't already exist
							$(fieldName).after(
								'<div class="small text-danger payment-error">'+ valueOfElement[0] +'</div>'
							);
						}
					});
				},
			});
		};
    </script>
@endsection