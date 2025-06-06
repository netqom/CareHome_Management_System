@extends('layouts.front')
@section('content')
<!-- banner start here -->
<section class="banner inner-banner" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100" data-aos-duration="1000">
   <div class="container">
	   <div class="row">
		   <div class="col-lg-10 offset-lg-1 col-md-12 col-md-12">
				<div class="banner-contet text-center white">
					{{-- <span class="fs-18 d-block pb-3">Information</span> --}}
					<h1 class="white fw-600">About Us</h1>
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
<section class="about-sec pt-100">
	<div class="container">
		<div class="row align-items-center flex-column-reverse flex-md-row">
			<div class="col-lg-5 col-md-6 col-sm-12">
				<h2 class="dark-green fw-600">About DocRyt</h2>
				<p class="my-4">{!! $page->content !!}</p>
				{{-- <div class="list-about d-flex">
					<i class="fas fa-chevron-circle-right dark-green mr-2"></i>
						<div class="about-inner-text"><h3 class="dark-green">Detailed Documentation</h3>
						<p>Lorem Ipsum is simply dummy text of the printing and types etting industry lorem Ipsum has been the industrys.</p>
					</div>
				</div>
				<div class="list-about d-flex">
					<i class="fas fa-chevron-circle-right dark-green mr-2"></i>
						<div class="about-inner-text"><h3 class="dark-green">Detailed Documentation</h3>
						<p>Lorem Ipsum is simply dummy text of the printing and types etting industry lorem Ipsum has been the industrys.</p>
					</div>
				</div> --}}
			</div>
			<div class="col-lg-7 col-md-6 col-sm-12 text-center">
				<div class="about-img relative">
					<img src="{{ asset('assets/front-images/about-img.png') }}">
					<img src="{{ asset('assets/front-images/about-img-bg.png') }}" class="about-bg">
				</div>
			</div>
		</div>
	</div>
</section>
<!-- about sec ends here -->
<section class="advantages-sec mt-5 pt-5">
  <div class="container">
	<hr>
	  <div class="row">
		 <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
			 <h2 class="text-center fw-600 w-100 dark-green mt-5">Advantages of DocRyt</h2>
			 <p class="text-center w-100">Certainly! Here are some key advantages of implementing a Care Home Management Tool:</p>
		 </div>
		 <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
			 <div class="advantage-single">
				 <img src="{{ asset('assets/front-images/advantage-icon1.png') }}" alt="advantage-icon">
				 <h5 class="dark-green">Daily Activity Management</h5>
			 </div>
		 </div>
		 <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
			 <div class="advantage-single">
				 <img src="{{ asset('assets/front-images/advantage-icon2.png') }}" alt="advantage-icon">
				 <h5 class="dark-green">View specific details of each client</h5>
			 </div>
		 </div>
		 <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
			 <div class="advantage-single">
				 <img src="{{ asset('assets/front-images/advantage-icon3.png') }}" alt="advantage-icon">
				 <h5 class="dark-green">Staff management</h5>
			 </div>
		 </div>
		 <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
			 <div class="advantage-single">
				 <img src="{{ asset('assets/front-images/advantage-icon4.png') }}" alt="advantage-icon">
				 <h5 class="dark-green">Medicine intake tracking and recording</h5>
			 </div>
		 </div>
		 <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
			 <div class="advantage-single">
				 <img src="{{ asset('assets/front-images/advantage-icon5.png') }}" alt="advantage-icon">
				 <h5 class="dark-green"> Meal Tracking and menu integration</h5>
			 </div>
		 </div>
		 <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
			 <div class="advantage-single">
				 <img src="{{ asset('assets/front-images/advantage-icon6.png') }}" alt="advantage-icon">
				 <h5 class="dark-green">Communicate with the Staff</h5>
			 </div>
		 </div>
	  </div>
  </div>
</section>
@endsection
