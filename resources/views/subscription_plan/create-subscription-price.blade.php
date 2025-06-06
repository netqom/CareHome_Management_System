@extends('layouts.admin')

@section('title', 'Subscription Plan Price')
@section('style')
	  <link href="{{ asset('assets/css/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
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
                    <strong>Subscription Price</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-5 text-right">
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
            <div class="ibox ">
            {{--<div class="ibox-title">
                    <h5>Edit Subscription Plan</h5>
                     <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div> 
                </div>--}}

                <div class="ibox-content">
                    <form method="POST" role="form" action="{{ route('subscription_plans.update-subscription-price') }}" id="subscriptionForm">
                        @csrf
                        <input type="hidden" name="id" value="{{ $plan_id}}">
                        <input type="hidden" name="product_id" value="{{$plan->stripe_product_id}}">
                        <input type="hidden" name="plan_name" value="{{$plan->name}}">
                        <input type="hidden" name="duration" value="{{$plan->duration}}">
                        <div class="row">
                            <div class="form-group col-12 col-md-4 col-lg-4">
                                <label class=" col-form-label">Price</label>
                                <div class="">
                                    {{-- <input type="text" name="plan_price" class="form-control" value="{{ $plan ? amountFormat($plan->price) : amountFormat(0.00) }}" readonly>
                                    <span class="form-text m-b-none text-warning">The Price Of Plan is imutabel you cannot update it.</span> --}}

                                    <input type="text" name="price" placeholder="Price" class="form-control valid_price" required autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <div class="">
                                    <label class="col-form-label">Per Staff Addons Price</label>
                                    <div class="">
                                        <input type="text" name="addons_price" class="form-control" value="" required autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <div class="">
                                    <label class="col-form-label">Per Patient Addons Price</label>
                                    <div class="">
                                        <input type="text" name="patient_addons_price" class="form-control" value="" required autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="hr-line-dashed"></div>
						<div class="form-group row">
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
@endsection
@section('script')
	<script>
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});
	</script>							
@endsection