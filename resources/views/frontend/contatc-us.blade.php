@extends('layouts.front')
@section('content')
 <!-- banner start here -->
<section class="banner inner-banner" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100" data-aos-duration="1000">
   <div class="container">
	   <div class="row">
		   <div class="col-lg-10 offset-lg-1 col-md-12 col-md-12">
				<div class="banner-contet text-center white">
					 {{-- <span class="fs-18 d-block pb-3">Let's Lalk</span> --}}
					<h1 class="white fw-600">Contact Us</h1>
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
<!-- contact sec start here -->
<section id="contact" class="contact">
  <div class="container">
	<div class="section-title">
	  <h2 data-aos="fade-up" class="dark-green text-center w-100 fw-600">Have Any Query ?</h2>
	  <p data-aos="fade-up" class="aos-init aos-animate text-center col-md-8 mx-auto">{!! $page->content !!}</p>
	</div>
	<div class="row justify-content-center">

	{{--   <div class="col-xl-3 col-lg-4 mt-4 aos-init aos-animate" data-aos="fade-up">
		<div class="info-box">
		 <i class="fas fa-map-marker-alt"></i>
		  <h3 class="dark-green">Our Address</h3>
		  <p>A108 Adam Street, New York, NY 535022</p>
		</div>
	  </div> --}}
	  <div class="col-xl-3 col-lg-4 mt-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
		<div class="info-box text-center border py-3 px-2 h-100 rounded">
		  <i class="fas fa-envelope m-auto"></i>
		  <h3 class="dark-green mt-2">Email Us</h3>
		  <p class="mb-0"><a href="mailto:info@docryt.com" class="green">info@docryt.com</a><br><a href="mailto:contact@docryt.com" class="green">support@docryt.com</a></p>
		</div>
	  </div>
	  <div class="col-xl-3 col-lg-4 mt-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
		<div class="info-box text-center border py-3 px-2 h-100 rounded">
		<i class="fa-solid fa-phone m-auto"></i>
		 
		  <h3 class="dark-green mt-2">Call Us</h3>
		  <p class="mb-0"><a href="tel:+1425-232-1892" class="green">+1425-232-1892</a><br>
			{{-- <a href="tel:+121 456 8942" class="green">+1425-232-1892</a> --}}
		  </p>
		</div>
	  </div>
	</div>
	<div class="row justify-content-center aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
	  <div class="col-xl-9 col-lg-12 mt-4">
		<form class="contact-form" id="contactUsForm" method="post">
		@csrf
			<div class="form-row">
				<div class="col-md-6 form-group">
					<input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
					<div class="validate"></div>
				</div>
				<div class="col-md-6 form-group">
					<input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
					<div class="validate"></div>
				</div>
			</div>
			<div class="form-group">
				<input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
				<div class="validate"></div>
			</div>
			<div class="form-group">
				<textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
				<div class="validate"></div>
			</div>
			<div class="mb-3">
				<div class="alert alert-dismissible fade show" role="alert" id="alert_message" style="display: none;">
				  <span id="messsage_text"></span>
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				  </button>
				</div>
			</div>
			<div class="text-center" id="submit_button_div">
				<button type="button" class="filled-btn" id="save_contact_us" data-submit-url="{{ route('save-contact-us') }}">Send Message</button>
			</div>
			<div class="text-center" id="loader_button_div" style="display: none;">
				<button type="button" class="filled-btn" disabled>
					<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
					Wait Processing...
				</button>
			</div>
		</form>
	  </div>
	</div>
  </div>
</section>
<!-- contact sec ends here -->
{{-- @include('layouts.front-partials.footer') --}}
@endsection
