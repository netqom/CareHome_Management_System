@extends('layouts.admin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>View Subscription Plan</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            @foreach($plans as $key => $value)
                @if($value->status == 1)
                <div class="col-md-4">
                    <div class="ibox">
                        <div class="ibox-content product-box">
                            <div class="product-imitation p-0 position-relative">
                                @if($value->subscription)
                                    <div class="badge badge-warning py-1 ml-2 mt-2 position-absolute" style="font-size: 12px;">
                                        <h6 class="m-0" style="font-size: 13px;font-weight: bold;">Start</h6>
                                        <p class="mb-0">{{ date_format($value->subscription->created_at, 'm-d-y')}}</p>
                                    </div>

                                    <div class="badge badge-danger ml-2 mt-2 position-absolute" style="right: 6px;font-size: 12px;">
                                        <h6 class="m-0" style="font-size: 13px;font-weight: bold;">Renewal date</h6>
                                        <?php if($value->subscription)
                                            $dateString = '';
                                            $dateString =  date_format($value->subscription->created_at, 'Y-m-d');

                                            $dateTime = new DateTime($dateString);
                                            
                                            // Add months, years and days
                                            if($value->duration == 1){
                                                $dateTime->modify('+1 month');
                                            }elseif($value->duration == 2){
                                                $dateTime->modify('+1 years');
                                            }else{
                                                $dateTime->modify('+1 day');
                                            }
                                            
                                            // Get the result as a string
                                            $recurringDate = $dateTime->format('m-d-y');
                                        ?>
                                        <p class="mb-0">
                                            {{$recurringDate}}
                                        </p>
                                    </div>
                                @endif
								<img class="w-100" src="{{ asset('assets/img/subscribe-img.jpg') }}" alt="subscription-img">
                            </div>
                            <div class="product-desc">
                                <span class="product-price " style="top: 0px;">
                                    ${{ number_format($value->price, 0, '', '')}}
                                </span>
                                <small class="text-muted">{{ $value->duration == 1 ? 'Monthly' : ($value->duration == 2 ? 'Yearly' : 'Daily') }}</small>
                                <a href="#" class="product-name"> {{ $value->name}} </a>



                                <div class="small m-t-xs">
                                    {{ $value->description}}
                                </div>
                                <div class="m-t text-left">
                                    @if($value->subscription && ($value->subscription->stripe_status == 'completed' || $value->subscription->stripe_status == 'active')) 
                                        <p class="badge badge-warning py-1 mt-2" style="font-size: 12px;">Active</p>
                                    @else
                                    <a href="{{ route('subscription-plan-show',[$home_id, $value->id]) }}" class="btn btn-sm btn-outline btn-primary" >Buy 
                                        {{-- <i class="fa fa-long-arrow-right"></i>  --}}
                                    </a>
                                    @endif
                                    @if($value->subscription && ($value->subscription->stripe_status == 'completed' || $value->subscription->stripe_status == 'active'))
                                        @if($value->addon_status == 1)
                                            <p class="mt-2"><strong>Per Staff Addons Price: </strong> ${{number_format($value->addons_price, 0, '', '')}}</p>
                                            <form method="POST" role="form" action="{{ route('subscription.staffAddOnSubscription', [$home_id, $value->id]) }}" id="addOnStaff">
                                                @csrf
                                                <div class="form-group row">
                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                        <input type="text" id="staff_capacity" name="add_on_staff_capacity" class="form-control" placeholder="Add Staff Addons" required>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <button type="button" class="btn btn-primary" id="addOn">Add</button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <p class="col-md-12 text-center mt-4">No Subscription Plan Found.</p>
                @endif
            @endforeach
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#addOn').on('click',function(e){
        e.preventDefault();
        var form = $('#addOnStaff');
		form.validate({
            rules: {
                add_on_staff_capacity: {
                    digits:true,
                    min: 1,   // Minimum value
                },
            }
        });
		if (form.valid()) {
			$('#addOnStaff').submit();
		}
    })
    </script>
@endsection