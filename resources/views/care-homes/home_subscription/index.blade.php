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

    <section class="plans-sec ">
          <div class="container">
           
              <div class="justify-content-center row">
                 <div class="col-12">
                     <h2 class="text-center fw-600 w-100 dark-green mt-5">Best plans, pay what you use</h2>
                 </div>
                 @foreach($plans as $key => $value)
                 @if($value->status == 1)
                 <div class="col-lg-4 col-md-4 col-sm-6 col-12"> 
                     <div class="plan-single @if($value->subscription && ($value->subscription->stripe_status == 'completed' || $value->subscription->stripe_status == 'active')) active-plan @endif text-center"> 
                        <br>
                         <h2 class="green dis-block"><b>{{ $value->name }}</b></h2>
                         <hr>
                         <h2 class="green">${{ number_format($value->price, 0, '', '')}}<span class="dark-green fs-16">/{{ $value->duration == 1 ? 'Month' : ($value->duration == 2 ? 'Year' : 'Daily') }}</span></h2>

                         <span class="addon fs-14 white">Add on ${{number_format($value->addons_price, 0, '', '')}}/Staff</span>
                         {!!  $value ?  $value->description : '' !!}
                         @if($value->subscription && ($value->subscription->stripe_status == 'completed' || $value->subscription->stripe_status == 'active')) 
                        
                         <!--p class="badge badge-warning py-1 mt-2" style="font-size: 12px;">Active</p-->
						 @if($value->subscription)
							 @php if($value->subscription)
								$recurringDate = '';
								// Add months, years and days
								if($value->duration == 1){
									$recurringDate = date('Y-m-d', strtotime('+1 month', strtotime($value->subscription->created_at)));
								}elseif($value->duration == 2){
									$recurringDate = date('Y-m-d', strtotime('+1 year', strtotime($value->subscription->created_at)));
								}else{
									$recurringDate = date('Y-m-d', strtotime('+1 day', strtotime($value->subscription->created_at)));
								}
                             @endphp
							 <p class="d-flex justify-content-center start-renew-p flex-wrap mb-0">
								<span class="start-date w-100 mb-2 p-1 rounded"><b>Start Date:</b> {{ date('M d, Y', strtotime($value->subscription->subscriptionPayment->current_period_start)) }}</span> 
								<span class="renew-date w-100 mb-2 p-1 rounded"><b>Renew Date:</b> {{ date('M d, Y', strtotime($value->subscription->subscriptionPayment->current_period_end))}}</span>
							 </p>
						 @endif
                         @else
                         <a href="{{ route('subscription-plan-show',[$home_id, $value->id]) }}" class="btn filled-btn mt-3" >Buy Plan 
                            <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i> {{-- <i class="fa fa-long-arrow-right"></i>  --}}
                        </a>
                         {{-- <button class="btn filled-btn mt-3 disable-btn" disabled>Buy Plan <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></button> --}}
                         @endif
                     </div>
                         @if($value->subscription && ($value->subscription->stripe_status == 'completed' || $value->subscription->stripe_status == 'active'))
                             @if($value->addon_status == 1)
                        <div class="add-on">
                            <p class="d-flex justify-content-between white border-bottom pb-2 mb-2"><span>Number of add-ons applied</span> <span><b>04</b></span></p>
                            <div class="addon-form d-flex justify-content-between">
                                {{-- <input type="number" placeholder="Add More..."> 
                                <button class="d-flex align-items-center">
                                    <i class="fa fa-plus mr-2" aria-hidden="true"></i> Add</button> --}}

                                    <form method="POST" role="form" action="{{ route('subscription.staffAddOnSubscription', [$home_id, $value->id]) }}" id="addOnStaff">
                                        @csrf
                                        <div class="form-group row mb-0">
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                <input type="text" id="staff_capacity" name="add_on_staff_capacity" class="form-control" placeholder="Add Staff Addons" required>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <button type="button" class="btn btn-primary" id="addOn">Add</button>
                                            </div>
                                        </div>
                                    </form>
                            </div> 
                        </div>
                        @endif
                    @endif
                 </div>
                 
                 @else
                 <p class="col-md-12 text-center mt-4">No Subscription Plan Found.</p>
             @endif
         @endforeach
{{-- 
                 <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                     <div class="plan-single active-plan text-center">
                         <h2 class="green"><b>Essential Plan</b></h2>
                         <span class="dark-green">Upto 5 Staff</span>
                         <hr>
                         <h2 class="green">$200<span class="dark-green fs-16">/Month</span></h2>

                         <span class="addon fs-14 white">Add on $60/Staff</span>
                         <ul class="text-left">
                             <li>Upto 2 Staff</li>
                             <li>2 platform of your choice</li>
                             <li>10 GB Dedicated Hosting free</li>
                             <li>Unlimited updates</li>
                             <li>Live support</li>
                         </ul>

                         <button class="btn filled-btn mt-3">Buy Plan <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></button>
                     </div>

                     <div class="add-on">
                        <p class="d-flex justify-content-between white border-bottom pb-2 mb-2"><span>Number of add-ons applied</span> <span><b>04</b></span></p>
                        <div class="addon-form d-flex justify-content-between">
                            <input type="number" placeholder="Add More..."> <button class="d-flex align-items-center"><i class="fa fa-plus mr-2" aria-hidden="true"></i> Add</button>
                        </div>
                     </div>

                 </div>


                 <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                     <div class="plan-single text-center">
                         <h2 class="green"><b>Premium Plan</b></h2>
                         <span class="dark-green">Upto 10 Staff</span>
                         <hr>
                         <h2 class="green">$300<span class="dark-green fs-16">/Month</span></h2>

                         <span class="addon fs-14 white">Add on $60/Staff</span>
                         <ul class="text-left">
                             <li>Upto 2 Staff</li>
                             <li>2 platform of your choice</li>
                             <li>10 GB Dedicated Hosting free</li>
                             <li>Unlimited updates</li>
                             <li>Live support</li>
                         </ul>

                         <button class="btn filled-btn mt-3 disable-btn" disabled>Buy Plan <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></button>
                     </div>
                 </div> --}}

                 

              </div>
          </div>
      </section>

@endsection

@section('script')
<script src="https://kit.fontawesome.com/66517fe47a.js" crossorigin="anonymous"></script>
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