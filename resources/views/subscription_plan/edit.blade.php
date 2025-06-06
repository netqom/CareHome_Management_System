@extends('layouts.admin')

@section('title', 'Edit Subscription Plan')
@section('style')
	  <link href="{{ asset('assets/css/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
      <style>
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
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
        .disable-checkbox {
            cursor: not-allowed;
        pointer-events: none;
        }
      </style>
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-7">
            <h2>Subscription Plans</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('subscription_plans.index') }}">Subscription Management</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Edit</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-5 text-right">
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
            <div class="ibox shadow border rounded ">
					<div class="row form-tabs no-gutters">
                    <div class="col-lg-6">
                    <a class="btn btn-block active-tab btn-lg header-tab rounded-0" href="javascript:void(0);" id="tab_header_1">Update Plan</a>
                </div>
                <div class="col-lg-6">
                    <a class="btn btn-block btn-lg header-tab rounded-0" href="javascript:void(0);" id="tab_header_2">Update Plan Permissions</a>
                </div>
						
					</div>

                <div class="ibox-content shadow border rounded">
                <div id="content_1" class="tab-content active">
                    <form method="POST" role="form" action="{{ route('subscription_plans.updateSubscription') }}" id="subscriptionForm">
                       <div class="row">
                    @csrf
                        <input type="hidden" name="id" value="{{ $plan_id}}">
                        <input type="hidden" name="stripe_product_id" value="{{$plan->stripe_product_id}}">
                        <input type="hidden" name="stripe_price_id" value="{{$plan->stripe_price_id}}">
                        <input type="hidden" name="duration" value="{{$plan->duration}}"> 
                        <input type="hidden" name="price" value="{{$plan->price}}">
                        <div class="form-group col-12 col-md-6 col-lg-3  @error('name') has-error @enderror">
                            <label class="col-form-label">Name</label>
                            <div class="">
                                <input type="text" name="name" placeholder="Name" class="form-control" required autocomplete="off" value="{{$plan->name}}">
                                @error('name')
                                    <span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-3 ">
                            <label class="col-form-label">Staff Allowed</label>
                            <div class="">
                                <input type="number" name="user_allowed" placeholder="User Allowed" class="form-control" required autocomplete="off" value="{{ $plan->user_allowed }}">
                            </div>
                        </div>

                        <div class="form-group col-12 col-md-6 col-lg-3 ">
                            <label class="col-form-label">Patient Allowed</label>
                            <div class="">
                                <input type="number" name="patient_allowed" placeholder="Patient Allowed" class="form-control" required autocomplete="off" value="{{ $plan->patient_allowed }}">
                            </div>
                        </div> 

						<div class="form-group col-12 col-md-6 col-lg-3 ">
                            <label class="col-form-label">Price</label>
                            <div class="">
                                <input type="text" name="plan_price" class="form-control" value="{{ $plan ? amountFormat($plan->price) : amountFormat(0.00) }}" readonly>
								<span class="form-text m-b-none text-warning">The Price Of Plan is imutabel you cannot update it.</span>
                            </div>
                        </div>
                        <div class="form-group col-12 ">
                            <label class="col-form-label">Description</label>
                            <div class="">
                                <textarea name="description" id="description" placeholder="Description" class="form-control">{{$plan->description}}</textarea>
                            </div>
                        </div>
							<div class="form-group col-12 col-md-4">
								<div class=" ">
									<label class="col-form-label">Addons status</label>
									<div class="">
										<div class="i-checks"><label> <input type="checkbox" name="addon_status" value="1" @if($plan->addon_status == 1) checked="" @endif> <i></i> Add Staff Addons </label></div>
									</div>
								</div>
							</div>
							<div class="form-group col-12 col-md-4">
								<div class="">
									<label class="col-form-label">Per Staff Addons Price</label>
									<div class="">
										<input type="text" name="addons_price" class="form-control" value="{{ amountFormat($plan->addons_price)}}" readonly>
										<span class="form-text m-b-none text-warning">The Price Of addons is imutabel you cannot update it.</span>
									</div>
								</div>
							</div>
                            <div class="form-group col-12 col-md-4">
								<div class="">
									<label class="col-form-label">Per Patient Addons Price</label>
									<div class="">
										<input type="text" name="patient_addons_price" class="form-control" value="{{ amountFormat($plan->patient_addons_price)}}" readonly>
										<span class="form-text m-b-none text-warning">The Price Of addons is imutabel you cannot update it.</span>
									</div>
								</div>
							</div>

                           <!--  <div class="form-group col-12 col-md-12">
								<div class="">
									<label class="col-form-label">Feature Permissions</label>
									<div class="">
                                        <label class="checkbox-inline i-checks mr-2"> <input type="checkbox" name="allow_geofencing" class="checkbox-toggle"  value="{{$plan->permission &&$plan->permission->allow_geofencing}}" @if($plan->permission &&$plan->permission->allow_geofencing) checked="true" @endif> <i></i> Allow Geofencing </label>
                                        <label class="checkbox-inline i-checks mr-2"> <input type="checkbox" name="allow_add_images" class="checkbox-toggle"  value="{{$plan->permission &&$plan->permission->allow_add_images}}" @if($plan->permission &&$plan->permission->allow_add_images) checked="true" @endif> <i></i> Allow Add Images </label>
                                        {{-- <label class="checkbox-inline i-checks mr-2"> <input type="checkbox" name="allow_cashflow" class="checkbox-toggle" value="{{$plan->permission && $plan->permission->allow_cashflow}}" @if($plan->permission &&$plan->permission->allow_cashflow) checked="true" @endif> <i></i> Allow Cashflow (Revenue & Expenses)</label> --}}
                                        {{-- <label class="checkbox-inline i-checks mr-2"><input type="checkbox" name="allow_chat" class="checkbox-toggle"   value="{{$plan->permission && $plan->permission->allow_chat}}" @if($plan->permission && $plan->permission->allow_chat) checked="true" @endif> <i></i> Allow Chat </label> --}}
                                        {{-- <label class="checkbox-inline i-checks mr-2"> <input type="checkbox" name="allow_manage_task" class="checkbox-toggle"  value="{{$plan->permission &&$plan->permission->allow_manage_task}}"  @if($plan->permission &&$plan->permission->allow_manage_task) checked="true" @endif> <i></i> Allow Manage Task </label> --}}
                                        <label class="checkbox-inline i-checks mr-2"> <input type="checkbox" name="allow_manage_med_report" class="checkbox-toggle"  value="{{$plan->permission && $plan->permission->allow_manage_med_report}}"  @if($plan->permission && $plan->permission->allow_manage_med_report) checked="true" @endif> <i></i> Allow Manage Medicine Report </label>
                                        
									</div>
								</div>
							</div> -->
                        {{-- <div class="form-group col-12 col-md-6 col-lg-4 ">
                            <label class="col-form-label">Duration</label>
                            <div class="">
                                <select class="form-control m-b" name="duration" required>
                                    <option value="">Select Duration</option>
                                    <option value="1" {{ $data->duration === 1 ? 'selected' : '' }}>Monthly</option>
                                    <option value="2" {{ $data->duration === 2 ? 'selected' : '' }}>Yearly</option>
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="form-group col-12 col-md-6 col-lg-4 ">
                            <label class="col-form-label">Price</label>
                            <div class="">
                                <input type="text" name="price" placeholder="Price" class="form-control valid_price" required autocomplete="off" value="{{$data->price}}">
                            </div>
                        </div> --}}
                        </div>
						<div class="hr-line-dashed"></div>
						<div class="form-group col-12">
							<div class="col-md-12 text-right">
								<a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('subscription_plans.index')) }}">Cancel</a>
								<button class="btn btn-sm btn-primary" type="submit" id="subscription-form">Save</button>
							</div>
						</div>
                    </form>
                    </div>
                    <div id="content_2" class="tab-content">
                    <form method="POST" role="form" action="{{ route('subscription_plans.update-subscription-permission') }}" id="subscriptionFormpermission">
                    @csrf   
                    <input type="hidden" name="plan_id" value="{{ $plan_id}}">
                    <div class="row">
                    <div class="col-12">
                    <div class="table-responsive">
							<table class="table table-striped" id="data_list">
								<thead>
								<tr>
                                <th>Features</th>
                                <th>Action</th>
                                </tr>
								</thead>
								<tbody>
                                    <tr>
                                        <td colspan="2" class="font-bold text-center">Can not update the following features because they are core functionalities of the website.</td>
                                    </tr>
                                    <tr>
                                        <td>Acess to web and mobile app</td>
                                        <td><label class="checkbox-inline i-checks disable-checkbox">
                                            <input type="checkbox" disabled name="" class="checkbox-toggle"checked="true"></label>
                                        </td>
                                    </tr>   
                                    <tr>
                                        <td>Manage Care Home</td>
                                        <td><label class="checkbox-inline i-checks disable-checkbox">
                                            <input type="checkbox" disabled name="" class="checkbox-toggle"checked="true"></label>
                                        </td>
                                    </tr>   
                                    <tr>
                                        <td>Manage Client and Staff</td>
                                        <td><label class="checkbox-inline i-checks disable-checkbox">
                                            <input type="checkbox" disabled name="" class="checkbox-toggle"checked="true"></label>
                                        </td>
                                    </tr>   
                                    <tr>
                                        <td>Daily Documentation</td>
                                        <td><label class="checkbox-inline i-checks disable-checkbox">
                                            <input type="checkbox" disabled name="" class="checkbox-toggle"checked="true"></label>
                                        </td>
                                    </tr>   
                                    <tr>
                                        <td>Manage Medicine</td>
                                        <td><label class="checkbox-inline i-checks disable-checkbox">
                                            <input type="checkbox" disabled name="" class="checkbox-toggle"checked="true"></label>
                                        </td>
                                    </tr>   
                                    <tr>
                                        <td colspan="2" class="font-bold text-center">Update the following features</td>
                                    </tr>
                                <tr>
                                        <td>Geofencing</td>
                                        <td><label class="checkbox-inline i-checks"> <input type="checkbox" name="allow_geofencing" class="checkbox-toggle"  value="{{$plan->permission &&$plan->permission->allow_geofencing}}" @if($plan->permission &&$plan->permission->allow_geofencing) checked="true" @endif></label></td>
                                    </tr>   
                                    <tr>
                                        <td>Add Images</td>
                                        <td><label class="checkbox-inline i-checks"> <input type="checkbox" name="allow_add_images" class="checkbox-toggle"  value="{{$plan->permission &&$plan->permission->allow_add_images}}" @if($plan->permission &&$plan->permission->allow_add_images) checked="true" @endif></label></td>
                                    </tr>    
                                    <tr>
                                        <td> Scheduling</td>
                                        <td><label class="checkbox-inline i-checks"> <input type="checkbox" name="manage_scheduling" class="checkbox-toggle"  value="{{$plan->permission &&$plan->permission->manage_scheduling}}" @if($plan->permission &&$plan->permission->manage_scheduling) checked="true" @endif></label></td>
                                    </tr> 
                                    <tr>
                                        <td>Chat</td>
                                        <td><label class="checkbox-inline i-checks"> <input type="checkbox" name="allow_chat" class="checkbox-toggle"  value="{{$plan->permission &&$plan->permission->allow_chat}}" @if($plan->permission &&$plan->permission->allow_chat) checked="true" @endif></label></td>
                                    </tr>    
                                    <tr>
                                        <td> Manage Task</td>
                                        <td><label class="checkbox-inline i-checks"> <input type="checkbox" name="allow_manage_task" class="checkbox-toggle"  value="{{$plan->permission &&$plan->permission->allow_manage_task}}" @if($plan->permission &&$plan->permission->allow_manage_task) checked="true" @endif></label></td>
                                    </tr> 
                                    <tr>
                                        <td> Manage Medicine Report</td>
                                        <td><label class="checkbox-inline i-checks"> <input type="checkbox" name="allow_manage_med_report" class="checkbox-toggle"  value="{{$plan->permission &&$plan->permission->allow_manage_med_report}}" @if($plan->permission &&$plan->permission->allow_manage_med_report) checked="true" @endif></label></td>
                                    </tr>    
                                    <tr>
                                        <td>Manage Cashflow (Revenue & Expenses)</td>
                                        <td><label class="checkbox-inline i-checks"> <input type="checkbox" name="allow_cashflow" class="checkbox-toggle"  value="{{$plan->permission &&$plan->permission->allow_cashflow}}" @if($plan->permission &&$plan->permission->allow_cashflow) checked="true" @endif></label></td>
                                    </tr>   
                                    <tr>
                                        <td> Assign Training</td>
                                        <td><label class="checkbox-inline i-checks"> <input type="checkbox" name="assign_training" class="checkbox-toggle"  value="{{$plan->permission &&$plan->permission->assign_training}}" @if($plan->permission &&$plan->permission->assign_training) checked="true" @endif></label></td>
                                    </tr>    
                                       
								</tbody>
							</table>
						</div>
                    
                    </div>
                    

                    </div>
                    <div class="hr-line-dashed"></div>
						<div class="form-group col-12">
							<div class="col-md-12 text-right">
								<a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('subscription_plans.index')) }}">Cancel</a>
								<button class="btn btn-sm btn-primary" type="submit" id="subscription-form">Save</button>
							</div>
						</div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
@section('script')
	<script src="{{ asset('assets/js/plugins/summernote/summernote-bs4.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
			 $('#description').summernote();

             $('.checkbox-toggle').on('ifChanged', function() {
                var isChecked = $(this).is(":checked") ? 1 : 0;
                $(this).val(isChecked);
            });
		});
    </script>
	<script>
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});
	</script>	
     <script>
        $(document).ready(function(){
            $('#tab_header_1').click(function(){
                $('.header-tab').removeClass('active-tab');
                $(this).addClass('active-tab');
                $('.tab-content').removeClass('active');
                $('#content_1').addClass('active');
            });

            $('#tab_header_2').click(function(){
                $('.header-tab').removeClass('active-tab');
                $(this).addClass('active-tab');
                $('.tab-content').removeClass('active');
                $('#content_2').addClass('active');
            });
        });
    </script>					
@endsection