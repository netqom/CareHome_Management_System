@extends('layouts.front')
@section('content')
 <!-- banner start here -->

<style>
li.more {
    padding-top: 14px;
}
li.more:before {
    content: none !important;
}
</style>
	

<section class="banner" id="home" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100" data-aos-duration="1000">
   <div class="container">
	   <div class="row">
		   <div class="col-lg-10 offset-lg-1 col-md-12 col-md-12">
				<div class="banner-contet text-center white">
					<span class="fs-18 d-block pb-3">Best Solution To Documentation</span>
					<h1 class="white fw-600">We Make Documentation<br> Simple and Easy</h1>
					<span class="fs-16 d-block py-4">No employee should be dreading documentation after completing a task.</span>
					{{-- <p class="mb-4 mt-2"><a href="#" class="btn filled-btn">Free Demo <i class="fa fa-arrow-right ml-1"></i></a></p> --}}
					<img class="banner-img" src="{{ asset('assets/front-images/banner-img.png') }}" alt="DocRyt">
				</div>
		   </div>
	   </div>
   </div>
   <div class="svg-shape">
	 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
	  <path class="elementor-shape-fill" d="M0,6V0h1000v100L0,6z"></path>
	 </svg>
   </div>
</section>
<!-- banner ends here -->
<!-- about sec start here -->
<section class="about-sec">
	<div class="container">
		<div class="row align-items-center flex-column-reverse flex-md-row">
			<div class="col-lg-5 col-md-12 col-sm-12">
				<h2 class="dark-green fw-600">About DocRyt</h2>
				<p class="my-4">{!! $about_us->content !!}</p>
				<p class="my-4">{!! $home_page->content !!}</p>
				<a href="{{ route('about-us') }}" class="btn filled-btn mt-3">Read More <i class="fas fa-arrow-right"></i></a>
			</div>
			<div class="col-lg-7 col-md-12 col-sm-12 text-center">
				<div class="about-img relative">
					<img src="{{ asset('assets/front-images/about-img.png') }}">
					<img src="{{ asset('assets/front-images/about-img-bg.png') }}" class="about-bg">
				</div>
			</div>
		</div>
	</div>
</section>
<!-- about sec ends here -->
<!-- how it works start here -->
<section class="how-it-work" id="how" data-next-section="year-3333" data-aos="fade-bottom" data-aos-offset="100" data-aos-duration="1000">
   <div class="container">
	   <div class="row">
		   <div class="col-lg-12 col-md-12 col-sm-12">
			   <div class="step">
				  <h2 class="dark-green fw-600 text-center mb-5">How It Works</h2>
			   </div>
		   </div>
	   </div>
	   <div class=" row">
		   <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
			 <div class="step-box text-center">
				<span class="number">Step 01</span>
				<img src="{{ asset('assets/front-images/how-icon1.png') }}">
				<h3 class="dark-green py-3 uppercase fw-600">Buy Subscription</h3>
				<p>Choose from one of the 3 plans that we currently offer and subscribe. Choose yearly subscription to save some money. <br>We also offer special pricing for big institutions. Please Contact us.</p>
		   </div>
		   </div>
		   <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
			 <div class="step-box text-center">
				<span class="number">Step 02</span>
				<img src="{{ asset('assets/front-images/how-icon2.png') }}">
				<h3 class="dark-green py-3 uppercase fw-600">Setup Account</h3>
				<p>Account set-up is very straight forward and our dedicated staff are always available to help answer questions and provide support.</p>
		   </div>
		   </div>
		   <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
			 <div class="step-box text-center">
				<span class="number">Step 03</span>
				<img src="{{ asset('assets/front-images/how-icon3.png') }}">
				<h3 class="dark-green py-3 uppercase fw-600">Monitor Activities</h3>
				<p>Once your account is set up, you are all set to immidiately start enjoying the benefits that DocRyt provides.</p>
		   </div>
		   </div>
	   </div>
	</div>
</section>
<!-- how it works ends here -->
<!-- mobile section start here -->
<section class="mobile-app-sec text-center pb-5">
	 <div class="svg-shape">
	 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
	  <path class="elementor-shape-fill" d="M0,6V0h1000v100L0,6z"></path>
	 </svg>
   </div>
	<div class="container">
		<div class="row">
			<h2 class="text-center white w-100 fw-600">Powerful Tool for your business</h2>
			<p class="white text-center w-100">With its comprehensive features and user-friendly interface, our tool is poised to become an indispensable asset for your care home operations.</p>
			<ul class="numbers">
				<li><h2>450+</h2> <span>Subscriber Using Our Platform Per Month</span></li>
				<li><h2>100+</h2> <span>Nursing Homes Using Our Platform Per Month</span></li>
				<li><h2>1000+</h2> <span>Daily Reporting Getting update With Our Software</span></li>
				<li><h2>20+</h2> <span>We are Serving in More than 20+ Cities</span></li>
			</ul>
			<ul class="mob-app-images d-flex justify-content-center w-100 mt-4">
				<li class="mx-1"><img src="{{ asset('assets/front-images/app-img1.jpg') }}"></li>
				<li class="mx-1"><img src="{{ asset('assets/front-images/app-img2.jpg') }}"></li>
				<li class="mx-1"><img src="{{ asset('assets/front-images/app-img3.jpg') }}"></li>
				<li class="mx-1"><img src="{{ asset('assets/front-images/app-img4.jpg') }}"></li>
			</ul>
			<div class="app-buttons d-flex justify-content-center w-100 mt-5 mb-5">
				<a target="_blank" href="https://play.google.com/store/apps/details?id=com.rnhealthcare&pcampaignid=web_share" class="app-button mx-1"><img src="{{ asset('assets/front-images/google-play.png') }}"></a>
				<a href="javascript:void(0);" class="app-button mx-1"><img src="{{ asset('assets/front-images/app-store.png') }}"></a>
			</div>
		</div>
	</div>
	 <div class="svg-shape bottom-shape">
	 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
	  <path class="elementor-shape-fill" d="M0,6V0h1000v100L0,6z"></path>
	 </svg>
   </div>
</section>
<!-- mobile section start here -->
<!-- faq start here -->
<section class="plans-sec" id="pricing" id="how" data-next-section="year-3333" data-aos="fade-bottom" data-aos-offset="100" data-aos-duration="1000">
  <div class="container">
	  <div class="row">
		 <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
			 <h2 class="text-center fw-600 w-100 dark-green mt-5">Best plans, pay what you use</h2>
			 <p class="text-center w-100">Tailored care home management plans, optimized for your needs.<br>  Pay only for the services you use, ensuring cost-effective care.</p>
			 <div class="switch switch--horizontal">
			  <input id="radio-a" type="radio" name="first-switch" value="1" checked="checked" class="plans-radio-button"/>
			  <label for="radio-a">Monthly</label>
			  <input id="radio-b" type="radio" name="first-switch" value="2" class="plans-radio-button"/>
			  <label for="radio-b">Yearly</label><span class="toggle-outside"><span class="toggle-inside"></span></span>
			</div>
		 </div>
		 <div class="col-12">
		 <div id="monthly-plans-div" class="row">
		 @if(!$monthlyPlans->isEmpty())
		 @foreach ($monthlyPlans as $mPlan)
			 
		
		 <div class="col-lg-4 col-md-12 col-sm-12 col-12">
			 <div class="plan-single text-center">
				 <h6 class="green">{{ $mPlan->name }}</h6>
				 <!-- <span class="dark-green">Upto 2 Staff</span> -->
				 <hr>
				 <h2 class="green">${{ $mPlan->price}}<span class="dark-green fs-16">/{{ $mPlan->duration == 1 ? 'Month' : ($mPlan->duration == 2 ? 'Year' : 'Daily') }}</span></h2>
				 <span class="addon fs-14 white">Add on ${{$mPlan->addons_price}}/Staff</span>
				 
                            {!!  $mPlan ?  $mPlan->description : '' !!}
                             
                        
						 @if(Auth::check())
							@if(Auth::user()->role_id != 1)
								<a class="btn filled-btn mt-3" href="{{ route('register') }}">
									Start Now <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i>
								</a>
							@endif
						@else
							<a class="btn filled-btn mt-3" href="{{ route('register') }}">
								Start Now <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i>
							</a>
						@endif
			 </div>
		 </div>
		
		  @endforeach
		  @endif
		 </div>
		 <div id="yearly-plans-div"  class="row d-none">
		  @if(!$yearlyPlans->isEmpty())
		 @foreach ($yearlyPlans as $yPlan)
			 
		
		 <div class="col-lg-4 col-md-12 col-sm-12 col-12">
			 <div class="plan-single text-center">
				 <h6 class="green">{{ $yPlan->name }}</h6>
				 <!-- <span class="dark-green">Upto 2 Staff</span> -->
				 <hr>
				 <h2 class="green">${{ $yPlan->price}}<span class="dark-green fs-16">/{{ $yPlan->duration == 1 ? 'Month' : ($yPlan->duration == 2 ? 'Year' : 'Daily') }}</span></h2>
				 <span class="addon fs-14 white">Add on ${{$yPlan->addons_price}}/Staff</span>
				 
                            {!!  $yPlan ?  $yPlan->description : '' !!}
                             
                        
						 @if(Auth::check())
							@if(Auth::user()->role_id != 1)
								<a class="btn filled-btn mt-3" href="{{ route('register') }}">
									Start Now <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i>
								</a>
							@endif
						@else
							<a class="btn filled-btn mt-3" href="{{ route('register') }}">
								Start Now <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i>
							</a>
						@endif
			 </div>
		 </div>
		
		  @endforeach
		  @endif
		 </div>
		 </div>
	  </div>
   </div>
</section>
	  
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function () {
	$(document).on("change",".plans-radio-button",function(){
		var val = $(this).val();
		if(val==1)
		{
			$("#yearly-plans-div").addClass('d-none');
			$("#monthly-plans-div").removeClass('d-none');
			
		}else{
			$("#yearly-plans-div").removeClass('d-none');
			$("#monthly-plans-div").addClass('d-none');
		}
	});
});
</script>
@endsection
