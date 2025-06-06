@extends('layouts.admin')

@section('title', 'Edit Subscription Plan')
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
                    <a href="{{ route('subscription_plans.index') }}">Subscription Plans</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Create</strong>
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
                <div class="ibox-content">
                    <form method="POST" role="form" action="{{ route('subscription_plans.store') }}" id="subscriptionForm">
                        @csrf
                        <input type="hidden" name="id" value="{{ $plan_id}}">
                        <input type="hidden" name="product_id" value="{{$plan->product->id}}">
                        <input type="hidden" name="stripe_price_id" value="{{$plan->stripe_price_id}}">
                        <input type="hidden" name="duration" value="{{$plan->duration}}"> 
                        <input type="hidden" name="price" value="{{$plan->price}}">
                        <div class="form-group row @error('name') has-error @enderror">
                            <label class="col-lg-2 col-form-label">Name</label>
                            <div class="col-lg-10">
                                <input type="text" name="name" placeholder="Name" class="form-control" required autocomplete="off" value="{{$plan->name}}">
                                @error('name')
                                    <span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Description</label>
                            <div class="col-lg-10">
                                <textarea name="description" id="description" placeholder="Description" class="form-control">{{$plan->description}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Staff Allowed</label>
                            <div class="col-lg-10">
                                <input type="number" name="user_allowed" placeholder="User Allowed" class="form-control" required autocomplete="off" value="{{ $plan->user_allowed }}">
                            </div>
                        </div>
						<div class="form-group row">
                            <label class="col-lg-2 col-form-label">Price</label>
                            <div class="col-lg-10">
                                <input type="text" name="plan_price" class="form-control" value="{{ $plan ? amountFormat($plan->price) : amountFormat(0.00) }}" readonly>
								<span class="form-text m-b-none text-warning">The Price Of Plan is imutabel you cannot update it.</span>
                            </div>
                        </div>
						<div class="hr-line-dashed"></div>
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group row">
									<label class="col-lg-2 col-form-label">Addons status</label>
									<div class="col-lg-6">
										<div class="i-checks"><label> <input type="checkbox" name="addon_status" value="1" @if($plan->addon_status == 1) checked="" @endif> <i></i> Add Staff Addons </label></div>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group row">
									<label class="col-lg-2 col-form-label">Per Addons Price</label>
									<div class="col-lg-10">
										<input type="text" name="addons_price" class="form-control" value="{{ amountFormat($plan->addons_price)}}" readonly>
										<span class="form-text m-b-none text-warning">The Price Of addons is imutabel you cannot update it.</span>
									</div>
								</div>
							</div>
						</div>
                        {{-- <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Duration</label>
                            <div class="col-lg-10">
                                <select class="form-control m-b" name="duration" required>
                                    <option value="">Select Duration</option>
                                    <option value="1" {{ $data->duration === 1 ? 'selected' : '' }}>Monthly</option>
                                    <option value="2" {{ $data->duration === 2 ? 'selected' : '' }}>Yearly</option>
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Price</label>
                            <div class="col-lg-10">
                                <input type="text" name="price" placeholder="Price" class="form-control valid_price" required autocomplete="off" value="{{$data->price}}">
                            </div>
                        </div> --}}
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
	<script src="{{ asset('assets/js/plugins/summernote/summernote-bs4.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
			 $('#description').summernote();
		});
    </script>
	<script>
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});
	</script>							
@endsection