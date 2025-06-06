@extends('layouts.admin')

@section('title', 'Create Care Home')
@section('style')
    <link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet">
    <style>
        .wizard>div.content {
            background: #f3f3f3;
        }

        .wizard>div.content>.body {
            position: relative;
            width: 100%;
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

        .payment-radio:checked+.payment-card {
            border-color: #1ab394;
            border-width: 2px;
        }

        .steps-tab {
            position: relative;
            display: block;
            width: 100%;
        }

        .steps-tab>ul>li {
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

        .plan-item input[type="radio"] label {
            cursor: pointer;
        }

        .plan-item input[type="radio"]:checked+label .plan-single {
            background: #08a29e;
            margin-top: 50px;
        }

        .plan-item input[type="radio"]:checked+label .plan-single h6,
        .plan-item input[type="radio"]:checked+label .plan-singlespan,
        .plan-item input[type="radio"]:checked+label .plan-single h2,
        .plan-item input[type="radio"]:checked+label .plan-single li,
        .plan-item input[type="radio"]:checked+label .plan-single li::before {
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
                    <strong>Create Care Homes</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox shadow border rounded ">
                    <div class="row form-tabs no-gutters">
                        <div class="col-lg-4">
                            <a class="btn btn-block active-tab btn-lg header-tab rounded-0" href="javascript:;"
                                id="tab_header_1">General Information</a>
                        </div>
                        <div class="col-lg-4">
                            <a class="btn btn-block inactive-tab btn-lg header-tab rounded-0" href="javascript:;"
                                id="tab_header_2">Select Subscription Plan</a>
                        </div>
                        <div class="col-lg-4">
                            <a class="btn btn-block inactive-tab btn-lg header-tab rounded-0" href="javascript:;"
                                id="tab_header_3">Finished</a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="ibox-content">
                            <form id="careHomeCreate" class="wizard-big" method="POST" action="{{ route('homes.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <fieldset class="w-100" id="form_step_1">
                                    <!--h2 class="border-bottom font-normal mb-3 pb-2">General Information</h2-->
                                    <div class="row">
                                        <div class="col-lg-2 my-1">
                                            <div class="bg-info col-12 py-3 rounded-lg team-memberc text-center">
                                                <div class="form-group">
                                                    <h4 class="mb-3">Upload Home Image</h4>
                                                    <div class="d-inline-block home-image-upload">
                                                        <img alt="image" id="existing_home_image"
                                                            class="border border-dark rounded-circle me-2"
                                                            src="{{ asset('assets/img/care-home-dummy.jpg') }}">
                                                        <button type="button" id="select_home_image"><i
                                                                class="fa fa-pencil"></i></button>
                                                    </div>
                                                    <input type="file" class="form-control" name="image"
                                                        id="home_image" accept="image/*" style="display:none"
                                                        onchange="handleFiles(this)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="name">Name *</label>
                                                        <input type="text" name="name" id="name"
                                                            placeholder="Name" class="form-control rounded required">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="email">Email *</label>
                                                        <input type="email" name="email" id="email"
                                                            placeholder="Email"
                                                            class="form-control rounded required valid_email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="contact_no">Contact No *</label>
                                                        <input type="text" name="contact_no" id="contact_no"
                                                            placeholder="Contact No" class="form-control rounded required">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="about">About *</label>
                                                        <textarea class="form-control rounded required" name="about" id="about" placeholder="Info About Care Home"
                                                            rows="4" autocomplete="off"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="street">Street Name *</label>
                                                        <input type="text" name="street" id="street"
                                                            placeholder="Street Name"
                                                            class="form-control rounded required">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="city">City Name *</label>
                                                        <input type="text" name="city" id="city"
                                                            placeholder="City Name" class="form-control rounded required">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="state">State Name *</label>
                                                        <input type="text" name="state" id="state"
                                                            placeholder="State Name"
                                                            class="form-control rounded required">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="privacy_policy">Care Home Policy </label>
                                                        <input type="file"
                                                            class="form-control rounded valid_doc_req py-1 px-1"
                                                            name="privacy_policy" id="privacy_policy"
                                                            placeholder=" Policy of Care Home" rows="4"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="zip_code">Zip Code *</label>
                                                        <input type="number" name="zip_code" id="zip_code"
                                                            placeholder="Zip Code" class="form-control rounded required">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="status">Status *</label>
                                                        <select name="status" id="status"
                                                            class="form-control rounded required">
                                                            <option value="1" selected>Active</option>
                                                            <option value="0">In-Active</option>
                                                        </select>
                                                    </div>
                                                    {{-- <div class="form-group">
														<label for="term_conditions">Term & Conditions *</label>
														<input type="file"  class="form-control rounded valid_doc_req required py-1 px-1" name="term_conditions" id="term_conditions" placeholder="Term & Conditions of Care Home" rows="4" autocomplete="off"   required>
													</div> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="location_lat">Location Lat *</label>
                                                        <input type="text" name="location_lat" id="location_lat"
                                                            readonly class="form-control rounded required">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="location_long">Location Longitude *</label>
                                                        <input type="text" name="location_long" id="location_long"
                                                            readonly class="form-control rounded required">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="geofencing_radius">Geofencing Radius (Miles)</label>
                                                        <input type="number" name="geofencing_radius"
                                                            id="geofencing_radius" class="form-control rounded">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div id="map" class="map-div rounded"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="accordion shadow" id="accordionExample3">
                                        <div class="align-items-center bg-primary d-flex px-2 py-2 pointer-event"
                                            data-toggle="collapse" data-target="#collapseThree" aria-expanded="true"
                                            aria-controls="collapseThree">
                                            <h4 class="m-0">Add Document Details</h4>
                                            <i class="fa fa-chevron-down ml-auto mr-2 " aria-hidden="true"></i>
                                        </div>
                                        <div id="collapseThree" class="collapse border border-1 p-3"
                                            aria-labelledby="headingOne" data-parent="#accordionExample3">
                                            <div class="row" id="document_div">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name">Title *</label>
                                                        <input type="text" name="doc[0][name]" id="name_0"
                                                            placeholder="Document Title" class="form-control rounded ">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name">Document *</label>
                                                        <input type="file" name="doc[0][file]" id="file_0"
                                                            class="form-control rounded">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="dose">Is Expiry Date Applicable *</label>
                                                        <select class="form-control m-b"
                                                            name="doc[0][is_expiry_applicable]" id="is_expiry_applicable_0">
                                                            <option value="">Select Options</option>
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 d-none">
                                                    <div class="form-group" id="data_0">
                                                        <label for="date">Expiry Date *</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-calendar"
                                                                    aria-hidden="true"></i></span>
                                                            <input type="text" name="doc[0][expiry_date]"
                                                                id="date_0" class="form-control" placeholder="Date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="more_document_div">
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 text-right add_document_fields">
                                                    <button type="button" class="btn btn-outline-navy font-bold"
                                                        id="add_document_fields"><i class="fa fa-plus"
                                                            aria-hidden="true"></i> Add More Documents</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="actions clearfix float-right">
                                        <a class="btn btn-primary font-bold" href="javascript:;" data-current-step="1"
                                            role="menuitem" onclick="validateFormStep('1', '2')">Next <i
                                                class="fa fa-chevron-right"></i></a>
                                    </div>
                                </fieldset>
                                <fieldset class="d-none" id="form_step_2">
                                    <!--h2 class="border-bottom font-normal mb-3 pb-2">Select Subscription Plan</h2-->
                                    <div class="justify-content-center row mb-5">
                                        <div class="col-lg-12 col-md-12 col-sm-12">


                                            <div class="switch switch--horizontal">
                                                <input id="radio-a" type="radio" name="first-switch" value="1"
                                                    class="plans-radio-button" checked="checked">
                                                <label for="radio-a">Monthly</label>
                                                <input id="radio-b" type="radio" name="first-switch" value="2"
                                                    class="plans-radio-button">
                                                <label for="radio-b">Yearly</label><span class="toggle-outside"><span
                                                        class="toggle-inside"></span></span>
                                            </div>
                                        </div>
                                        <div id="monthly-plans-div" class="row">
                                            @if (!$monthlyPlans->isEmpty())
                                                @foreach ($monthlyPlans as $key => $value)
                                                    <?php $get_discount = getDiscountOnPlan($value->id); ?>

                                                    @if ($value->status == 1)
                                                        <div class="col-md-4">
                                                            <div class="plan-item">
                                                                <input id="subscription_plan_{{ $value->id }}"
                                                                    type="radio" value="{{ $value->id }}"
                                                                    name="subscription_plan"
                                                                    data-discount="{{ $get_discount ? $get_discount->stripe_id : '' }}">

                                                                <label for="subscription_plan_{{ $value->id }}">
                                                                    <div
                                                                        class="plan-single text-center position-relative pt-4">
                                                                        <h2 class="green dis-block mt-4">
                                                                            <b>{{ $value->name }}</b>
                                                                        </h2>
                                                                        @if ($get_discount)
                                                                            <span
                                                                                class="discount-tab">{{ $get_discount->discount_type == 1 ? $get_discount->discount . '%' : '$' . $get_discount->discount }}
                                                                                off</span>
                                                                        @endif
                                                                        <hr>
                                                                        <h2 class="green">
                                                                            {{-- ${{ number_format($value->price, 0, '', '')}} --}}
                                                                            ${{ $value->price }}
                                                                            <span class="dark-green fs-16">/
                                                                                {{ $value->duration == 1 ? 'Month' : ($value->duration == 2 ? 'Year' : 'Daily') }}
                                                                            </span>
                                                                        </h2>
                                                                        <span class="addon fs-14 white">Add on
                                                                            ${{ $value->addons_price }}/Staff</span>
                                                                        {{-- <span class="addon fs-14 white">Add on ${{number_format($value->addons_price, 0, '', '')}}/Staff</span> --}}
                                                                        {!! $value ? $value->description : '' !!}

                                                                    </div>
                                                                </label>
                                                            </div>

                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div id="yearly-plans-div" class="row d-none">
                                            @if (!$yearlyPlans->isEmpty())
                                                @foreach ($yearlyPlans as $key => $value)
                                                    <?php $get_discount = getDiscountOnPlan($value->id); ?>

                                                    @if ($value->status == 1)
                                                        <div class="col-md-4">
                                                            <div class="plan-item">
                                                                <input id="subscription_plan_{{ $value->id }}"
                                                                    type="radio" value="{{ $value->id }}"
                                                                    name="subscription_plan"
                                                                    data-discount="{{ $get_discount ? $get_discount->stripe_id : '' }}">

                                                                <label for="subscription_plan_{{ $value->id }}">
                                                                    <div
                                                                        class="plan-single text-center position-relative pt-4">
                                                                        <h2 class="green dis-block mt-4">
                                                                            <b>{{ $value->name }}</b>
                                                                        </h2>
                                                                        @if ($get_discount)
                                                                            <span
                                                                                class="discount-tab">{{ $get_discount->discount_type == 1 ? $get_discount->discount . '%' : '$' . $get_discount->discount }}
                                                                                off</span>
                                                                        @endif
                                                                        <hr>
                                                                        <h2 class="green">
                                                                            {{-- ${{ number_format($value->price, 0, '', '')}} --}}
                                                                            ${{ $value->price }}
                                                                            <span class="dark-green fs-16">/
                                                                                {{ $value->duration == 1 ? 'Month' : ($value->duration == 2 ? 'Year' : 'Daily') }}
                                                                            </span>
                                                                        </h2>
                                                                        <span class="addon fs-14 white">Add on
                                                                            ${{ $value->addons_price }}/Staff</span>
                                                                        {{-- <span class="addon fs-14 white">Add on ${{number_format($value->addons_price, 0, '', '')}}/Staff</span> --}}
                                                                        {!! $value ? $value->description : '' !!}

                                                                    </div>
                                                                </label>
                                                            </div>

                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <input type="hidden" name="discount" id="discount" value="">
                                    <span class="text-danger m-t-xs d-none" id="subacription_plan_error"></span>
                                    <div class="hr-line-dashed"></div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="card_holder_name">Name</label>
                                                <input type="text" name="card_holder_name" id="card_holder_name"
                                                    class="form-control rounded required" value=""
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
                                    <div class="row" id="coupon-div">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="card_holder_name">Have Coupon ?</label>
                                                <input type="text" name="coupon_code" id="coupon_code"
                                                    class="form-control rounded" value=""
                                                    placeholder="Add Coupon Code Here">
                                                <span class="text-danger m-t-xs d-none" id="coupon_code_error"></span>
                                                <span class="text-info m-t-xs d-none" id="coupon_code_success"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="actions clearfix" id="verify_button_div">
                                                <button class="btn btn-primary mt-4" type="button"
                                                    id="verify_coupon_code">Verify</button>
                                            </div>
                                            <div class="actions clearfix" id="verify_loader_button_div"
                                                style="display: none;">
                                                <button type="button" class="btn btn-primary" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
                                                    Wait ...
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-lg-3"></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="actions clearfix float-right" id="submit_button_div">
                                        <a class="btn btn-white mr-2 font-bold" href="javascript:;"
                                            onclick="goToStep('1', '2')"><i class="fa fa-chevron-left"></i> Previous</a>
                                        <a class="btn btn-primary font-bold" href="javascript:;" data-current-step="2"
                                            onclick="validateFormStep(2, 3)">Submit</a>
                                    </div>
                                    <div class="actions clearfix float-right" id="loader_button_div"
                                        style="display: none;">
                                        <div class="col-lg-12 col-m-12">
                                            <button type="button" class="btn btn-primary" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Wait Processing...
                                            </button>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="d-none" id="form_step_3">
                                    <!--h2 class="border-bottom font-normal mb-3 pb-2">Completed</h2-->
                                    <div class="d-none" id="complete_alert">
                                        <div class="alert alert-warning alert-dismissable">
                                            <button aria-hidden="true" data-dismiss="alert" class="close"
                                                type="button">×</button>
                                            <span id="complete_alert_msg"></a>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <h2 class="font-bold mt-5 text-center">
                                            <i class="fa fa-check-circle mb-3 text-navy" style="font-size: 50px;"></i><br>
                                            Thank You!
                                        </h2>
                                        <h3>Your Care Homes Added Successfully!</h3>
                                        <p>Now you can add your staff.</p>
                                        <div class="" id="view_care_home">

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
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        $("#monthly-plans-div").find("input[type='radio']").last().prop('checked', true);
        $(document).on("change", ".plans-radio-button", function() {
            var val = $(this).val();
            if (val == 1) {
                $("#yearly-plans-div").addClass('d-none');
                $("#monthly-plans-div").removeClass('d-none');
                // Select last checked radio button in monthly plans
                $("#monthly-plans-div").find("input[type='radio']:checked").prop('checked', false);
                $("#monthly-plans-div").find("input[type='radio']").last().prop('checked', true);
            } else {
                $("#yearly-plans-div").removeClass('d-none');
                $("#monthly-plans-div").addClass('d-none');
                // Select last checked radio button in yearly plans
                $("#yearly-plans-div").find("input[type='radio']:checked").prop('checked', false);
                $("#yearly-plans-div").find("input[type='radio']").last().prop('checked', true);
            }
        });
        const stripe = Stripe("{{ config('app.stripe_key') }}")
        //console.log("stripe key=======", stripe, "{{ config('app.stripe_key') }}");
        //const elements     = stripe.elements()
        //const cardElement  = elements.create('card')
        const appearance = {
            theme: 'night'
        };
        const elements = stripe.elements({
            appearance
        })

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
            //console.log('card element ready function');
            // Handle ready event
        });
        cardElement.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                if ($('#stripe_error').hasClass('d-none')) {
                    $('#stripe_error').removeClass('d-none')
                }
                $('#stripe_error').text(event.error.message);
            } else {
                $('#stripe_error').addClass('d-none').text('');
            }
        });

        function checkEmailExists(email, callback) {
            $.ajax({
                type: "POST",
                url: "{{ url('check-home-email-exsist') }}",
                data: {
                    email: email
                },
                success: function(response) {
                    //console.log(response.exsist, "jjj");
                    if (response.exsist == true) {
                        callback(false);
                    } else {
                        callback(true);
                    }
                },
                error: function(err, xhr) {
                    console.error('AJAX Error:', err, xhr);
                    callback(false); // Handle error
                },
            });
        }
        $(document).ready(function() {

            //map functionlaity start
            let map = new L.map('map', {
                center: [47.116386, -101.299591],
                zoom: 2
            });
            let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
            map.addLayer(layer);
            let marker = null;
            //When map is loaded set default marker
            map.whenReady(function(event) {
                marker = L.marker([47.116386, -101.299591], {
                    draggable: 'true'
                });
                $('#location_lat').val('47.116386');
                $('#location_long').val('-101.299591');
                marker.on('dragend', function(event) {
                    var marker = event.target;
                    var position = marker.getLatLng();
                    $('#location_lat').val(position.lat);
                    $('#location_long').val(position.lng);
                    marker.setLatLng(new L.LatLng(position.lat, position.lng), {
                        draggable: 'true'
                    });
                    map.panTo(new L.LatLng(position.lat, position.lng))
                });
                map.addLayer(marker);
            })
            //map functionlaity end
            //change care home image start
            $('#select_home_image').click(function() {
                $('#home_image').trigger('click')
            })
            //change care home image end

            // Check if the checkbox is checked on page load
            if ($('input[type=radio][name=subscription_plan]').is(':checked')) {
                var discount = $('input[type=radio][name=subscription_plan]:checked').data('discount');
                $('#discount').val(discount); // Set the value to the hidden input field

                if (discount != '') {
                    $('#coupon-div').addClass('d-none');
                }
            }

            //plan radio button clicked
            $('input[type=radio][name=subscription_plan]').change(function() {
                var discount = this.getAttribute('data-discount');

                if (discount != '') {
                    $('#coupon-div').addClass('d-none');
                } else {
                    $('#coupon-div').removeClass('d-none');
                }

                if (this.value != '') {
                    $('#discount').val(discount);
                    $('#subacription_plan_error').addClass('d-none').text('');
                } else {
                    if ($('#subacription_plan_error').hasClass('d-none')) {
                        $('#subacription_plan_error').removeClass('d-none')
                    }
                    $('#subacription_plan_error').text('Please select a plan to procced.');
                }
            });

            // Listen for when the accordion is shown (expanded)
            $('#collapseThree').on('shown.bs.collapse', function() {

                // Add the required attribute to the necessary fields
                $('#document_div input[type="text"]').addClass('required').attr('required', true);
                $('#document_div input[type="file"]').addClass('required').attr('required', true);
                $('#document_div select').addClass('required').attr('required', true);
                $('#more_document_div input[type="text"]').removeClass('required').addClass('required').attr('required', true);
                $('#more_document_div input[type="file"]').removeClass('required').addClass('required').attr('required', true);
                $('#more_document_div select').removeClass('required').addClass('required').attr('required', true);
            });

            addChangeListener(0);
            datePickerCall(0);
            // Optionally, you can remove the required attribute when the accordion is hidden (collapsed)
            $('#collapseThree').on('hidden.bs.collapse', function() {

                // Remove the required attribute
                $('#document_div input[type="text"]').removeClass('required').attr('required', false);
                $('#document_div input[type="text"]').val('');
                $('#document_div input[type="file"]').removeClass('required').attr('required', false);
                $('#document_div input[type="file"]').val('');
                $('#document_div select').removeClass('required').attr('required', false);

                $('#more_document_div input[type="text"]').removeClass('required').attr('required', false);
                $('#more_document_div input[type="text"]').val('');
                $('#more_document_div input[type="file"]').removeClass('required').attr('required', false);
                $('#more_document_div input[type="file"]').val('');
                $('#more_document_div select').removeClass('required').attr('required', false);
            });

            //form element key up remove error filed
            $('.required').keyup(function() {
                let element_id = $(this).attr('id');
                let element_type = $(this).attr('type');
                let element_val = $(this).val();
                var form_valid = true;
                if ($(this).val() != '') {

                    if (element_type == 'number') {
                        var valid_zip = IsValidZipCode(element_val);
                        if (!valid_zip) {
                            $(this).focus();
                            //remove if any erorr element exist
                            $('#' + element_id + '-error').remove();
                            //add new error element
                            let error_html = '<div id="' + element_id +
                                '-error" class="error-message">Please enter a valid zipcode..</div>';
                            $(this).after(error_html);
                            form_valid = false;
                        }
                    } else if (element_id == 'contact_no') {
                        var valid_contact = IsValidPhoneNumber(element_val);
                        if (!valid_contact) {
                            $(this).focus();
                            //remove if any erorr element exist
                            $('#' + element_id + '-error').remove();
                            //add new error element
                            let error_html = '<div id="' + element_id +
                                '-error" class="error-message">Please enter a valid phone number..</div>';
                            $(this).after(error_html);
                            form_valid = false;
                        }
                    }
                    if (element_type == 'email') {
                        var self = $(this);

                        // First, validate the email pattern
                        if (!IsEmail(element_val)) {
                            self.focus();
                            $('#' + element_id + '-error').remove();
                            let error_html = '<div id="' + element_id +
                                '-error" class="error-message">Please enter a valid email address.</div>';
                            self.after(error_html);
                            form_valid = false;
                        }
                         	if($(this).hasClass('valid_email'))
                        	{
                        		checkEmailExists(element_val, function(valid_email) {
                        			if (!valid_email) {
                        				self.focus();
                        				$('#' + element_id + '-error').remove();
                        				let error_html = '<div id="' + element_id + '-error" class="error-message">This email already exists.</div>';
                        				self.after(error_html);
                        				form_valid = false;
                        			}
                        		});
                        	} 
                    }
                    if (form_valid) {
                        let element_id = $(this).attr('id');
                        $('#' + element_id + '-error').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                }
            })
            $('input[name="privacy_policy"]').on('change', function() {
                var element = this;
                if (element.files.length === 0) {
                    $('#privacy_policy-error').remove();
                    //add new error element
                    let error_html =
                        '<div id="privacy_policy-error" class="error-message"This field is required.</div>';
                    $(this).after(error_html);
                    form_valid = false;
                }
                var fileName = element.files[0].name;
                var valid_document = validDocumentMime(fileName);
                if (!valid_document) {
                    $(this).focus();
                    //remove if any erorr element exist
                    $('#privacy_policy-error').remove();
                    //add new error element
                    let error_html =
                        '<div id="privacy_policy-error" class="error-message">Please select a valid document file (PDF, DOC, DOCX, or TXT).</div>';
                    $(this).after(error_html);
                    form_valid = false;
                }
            });
            $('input[name="term_conditions"]').on('change', function() {
                var element = this;
                if (element.files.length === 0) {
                    $('#term_conditions-error').remove();
                    //add new error element
                    let error_html =
                        '<div id="term_conditions-error" class="error-message">This field is required.</div>';
                    $(this).after(error_html);
                    form_valid = false;
                }
                var fileName = element.files[0].name;
                var valid_document = validDocumentMime(fileName);
                if (!valid_document) {
                    $(this).focus();
                    //remove if any erorr element exist
                    $('#term_conditions-error').remove();
                    //add new error element
                    let error_html =
                        '<div id="term_conditions-error" class="error-message">Please select a valid document file (PDF, DOC, DOCX, or TXT).</div>';
                    $(this).after(error_html);
                    form_valid = false;
                }
            });

            $('#verify_coupon_code').click(function() {
                var coupon_code = $('#coupon_code').val();
                if (coupon_code == '') {
                    $('#coupon_code_error').removeClass('d-none');
                    $('#coupon_code_error').text('Coupon code is required');
                    return false;
                } else {
                    $('#coupon_code_error').addClass('d-none');
                    $('#coupon_code_error').text('');
                }
                $('#verify_button_div').hide();
                $('#verify_loader_button_div').show();
                $.ajax({
                    type: "GET",
                    url: "{{ route('verify-coupons-code') }}",
                    data: {
                        coupon_code: coupon_code
                    },
                    success: function(response) {
                        $('#verify_button_div').show();
                        $('#verify_loader_button_div').hide();
                        if (response.type == 'success') {
                            $('#coupon_code_error').addClass('d-none');
                            $('#coupon_code_error').text('');
                            $('#coupon_code_success').removeClass('d-none');
                            $('#coupon_code_success').text(response.message);
                        } else {
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
        });
        //preview home image on select Start
        const handleFiles = (input) => {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#existing_home_image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        //create form validation object
        var _validator;
        $(function() {
            _validator = $("#careHomeCreate").validate();
        });
        //preview home image on select end
        const goToStep = (go_to, current) => {
            $('#form_step_' + current).addClass('d-none');
            $('#form_step_' + go_to).removeClass('d-none');

            $('#tab_header_' + current).removeClass('active-tab').addClass('inactive-tab');
            $('#tab_header_' + go_to).removeClass('inactive-tab').addClass('active-tab');
        }
        //validate form step start
        const validateFormStep = (step, next) => {
            var form_valid = true;
            var focus = '';
            $('#form_step_' + step).find('input, select, textarea').each(function() {

                let element_id = $(this).attr('id');
                let element_type = $(this).attr('type');
                let element_val = $(this).val();
				
                if ($(this).hasClass('required') && element_val == '') {
                    //focus first erroe element
                    if (focus == '') {
                        $(this).focus();
                        focus = 'yes';
                    }
                    //remove if any erorr element exist
                    $('#' + element_id + '-error').remove();
                    //add new error element
                    let error_html = '<div id="' + element_id +
                        '-error" class="error-message">This field is required.</div>';
                    $(this).after(error_html);
                    form_valid = false;
                } else if ($(this).hasClass('required') && element_id == 'zip_code') {
                    var valid_zip = IsValidZipCode(element_val);
                    if (!valid_zip) {
                        $(this).focus();
                        //remove if any erorr element exist
                        $('#' + element_id + '-error').remove();
                        //add new error element
                        let error_html = '<div id="' + element_id +
                            '-error" class="error-message">Please enter a valid zipcode..</div>';
                        $(this).after(error_html);
                        form_valid = false;
                    }
                } else if ($(this).hasClass('required') && element_id == 'contact_no') {

                    var valid_contact = IsValidPhoneNumber(element_val);
                    if (!valid_contact) {
                        $(this).focus();
                        //remove if any erorr element exist
                        $('#' + element_id + '-error').remove();
                        //add new error element
                        let error_html = '<div id="' + element_id +
                            '-error" class="error-message">Please enter a valid phone number..</div>';
                        $(this).after(error_html);
                        form_valid = false;
                    }
                } else if ($(this).hasClass('required') && element_id == 'privacy_policy' || element_id ==
                    'term_conditions') {

                    var element = this;

                    var fileName = element.files[0].name;
                    var valid_document = validDocumentMime(fileName);
                    if (!valid_document) {
                        $(this).focus();
                        //remove if any erorr element exist
                        $('#' + element_id + '-error').remove();
                        //add new error element
                        let error_html = '<div id="' + element_id +
                            '-error" class="error-message">Please select a valid document file (PDF, DOC, DOCX, or TXT).</div>';
                        $(this).after(error_html);
                        form_valid = false;
                    }
                } else if (element_id == 'email') {
                    var self = $(this);

                    // First, validate the email pattern
                    if (!IsEmail(element_val)) {
                        self.focus();
                        $('#' + element_id + '-error').remove();
                        let error_html = '<div id="' + element_id +
                            '-error" class="error-message">Please enter a valid email address.</div>';
                        self.after(error_html);
                        form_valid = false;
                    }
                     if($(this).hasClass('valid_email'))
                    {
                    	checkEmailExists(element_val, function(valid_email) {
                    		if (!valid_email) {
                    			self.focus();
                    			$('#' + element_id + '-error').remove();
                    			let error_html = '<div id="' + element_id + '-error" class="error-message">This email already exists test.</div>';
                    			self.after(error_html);
                    			form_valid = false;
                    		}
                    	});
                    } 
                }else{
					$('#' + element_id + '-error').remove();
				}

            });
            console.log("form_valid===", form_valid);

            if (form_valid) {
                if (step == 1) {
                    $('#form_step_' + step).addClass('d-none');
                    $('#form_step_' + next).removeClass('d-none');
                    $('#tab_header_' + step).removeClass('active-tab').addClass('inactive-tab');
                    $('#tab_header_' + next).removeClass('inactive-tab').addClass('active-tab');
                } else if (step == 2) {
                    $('#submit_button_div').hide();
                    $('#loader_button_div').show();
                    $('.form-loader').show();
                    createStripeToken();
                }
            }
        }
        const createStripeToken = () => {
            stripe.createPaymentMethod({
                    elements,
                    params: {
                        billing_details: {
                            name: $('#card_holder_name').val(),
                            email: $('#email').val(),
                        },
                    },
                })
                .then(function(result) {
                    //console.log('payment method result', result)
                    stripeResponseHandler(result);
                });
        }

        const stripeResponseHandler = (response) => {
            //console.log('handle stripe response data', response)
            var $form = $('#careHomeCreate');
            if (response.error) {
                $('#submit_button_div').show();
                $('#loader_button_div').hide();
                $('.form-loader').css('display', 'none');
                if ($('#stripe_error').hasClass('d-none')) {
                    $('#stripe_error').removeClass('d-none')
                }
                $('#stripe_error').text(response.error.message);
            } else {
                var token = response.paymentMethod.id;
                //first check and remove any pre input element apened
                $("input[name=stripeToken]").remove(); //apend new token
                $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                submitFormData();
            }
        };

        function submitFormData() {
            let form = $('#careHomeCreate')[0];
            let formData = new FormData(form);
            $.ajax({
                type: "POST",
                url: "{{ route('homes.store') }}",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $('#submit_button_div').show();
                    $('#loader_button_div').hide();
                    $('.form-loader').css('display', 'none');
                    if (response.status == 'success') {
                        toastAlert(response.status, response.message);
                        $('#form_step_2').addClass('d-none');
                        $('#form_step_3').removeClass('d-none');
                        $('#tab_header_2').removeClass('active-tab').addClass('inactive-tab');
                        $('#tab_header_3').removeClass('inactive-tab').addClass('active-tab');
                        //clear the form on submit
                        $('#careHomeCreate')[0].reset();
                        if (response.subscription_status == 'active') {
                            $('#view_care_home').html('<a href="' + response.url +
                                '" class="btn btn-primary mx-2"><i class="fa fa-plus" aria-hidden="true"></i> Add Staff</a>'
                            );
                        } else {
                            $('#complete_alert').removeClass('d-none');
                            $('#complete_alert_msg').text(response.info);
                            $('#view_care_home').html('<a href="' + response.url +
                                '" class="btn btn-primary mx-2">View Care Home</a>');
                        }
                    } else if (response.status == 'payment_action_required') {
                        toastAlert(response.status, response.message);
                        window.open(response.url, '_blank');
                    } else {
                        //alert('jhgg');
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
                        $("[name='" + indexInArray + "']").after(
                            '<div class="small text-danger payment-error">' + valueOfElement[0] +
                            '</div>'
                        );
                    });
                },
            });
        };
        var maxFieldsDoc = 10;
        var fieldCountDoc = 1;

        $("#add_document_fields").click(function() {
            if (fieldCountDoc < maxFieldsDoc) {
                var newFieldDoc = `
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Title *</label>
                                    <input type="text" name="doc[` + fieldCountDoc + `][name]" id="name" placeholder="Document Title" class="form-control rounded required" required>
                                </div>
                            </div>
							<div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Document *</label>
                                    <input type="file" name="doc[` + fieldCountDoc + `][file]" class="form-control rounded required" required>
                                </div>
                            </div>
							<div class="col-lg-6">
                                <div class="form-group">
                                    <label for="dose">Is Expiry Date Applicable *</label>
                                    <select class="form-control m-b required" name="doc[` + fieldCountDoc + `][is_expiry_applicable]" id="is_expiry_applicable_${fieldCountDoc}" required>
                                        <option value="">Select Options</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="col-lg-6 d-none">
                                <div class="form-group" id="data_${fieldCountDoc}">
                                    <label for="date">Expiry Date *</label>
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" name="doc[` + fieldCountDoc + `][date]" id="date_${fieldCountDoc}" class="form-control required" required placeholder="Expiry Date">
                                    </div>
                                </div>
                            </div>
                            
                           
                             
                            
                            <div class="col-lg-12 mb-4 text-right">
                                <button type="button" class="btn btn-outline-danger font-bold remove_doc"><i class="fa fa-trash"></i> Remove</button>
                            </div>
                        </div>
                    `;
                $("#more_document_div").append(newFieldDoc);
                datePickerCall(fieldCountDoc);
                addChangeListener(fieldCountDoc);
                fieldCountDoc++;
            } else {
                $(".add_document_fields").hide();
            }
        });
        $("#more_document_div").on("click", ".remove_doc", function() {
            $(this).closest(".row").remove();
            fieldCountDoc--;
        });

        function datePickerCall(count) {
            var mem = $(`#data_${count} .input-group.date`).datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
                startDate: new Date()
            });
        }

         function addChangeListener(count) {
            $(`#is_expiry_applicable_${count}`).on('change', function() {
                const expiryDateInput = $(`#date_${count}`);
				console.log(expiryDateInput,$(this).val())
                if ($(this).val() === '1') {
					expiryDateInput.closest('.form-group').parent().removeClass('d-none')
                    expiryDateInput.attr('required', 'required');
                } else {
					expiryDateInput.closest('.form-group').parent().addClass('d-none')
                    expiryDateInput.removeAttr('required');
                }
            });
        }
    </script>
@endsection
