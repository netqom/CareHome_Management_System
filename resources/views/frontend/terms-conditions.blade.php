@extends('layouts.front')
@section('content')
<!-- banner start here -->
<section class="banner inner-banner" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100" data-aos-duration="1000">
   <div class="container">
	   <div class="row">
		   <div class="col-lg-10 offset-lg-1 col-md-12 col-md-12">
				<div class="banner-contet text-center white">
					{{-- <span class="fs-18 d-block pb-3">Resources</span> --}}
					<h1 class="white fw-600">DocrytTerms of Use</h1>
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
<!-- terms sec start here -->
<section class="terms-privacy">
	<div class="container">
		<div class="row">
			{!! $page->content !!}
		</div>
	</div>
</section>
<!-- terms sec start here -->
@endsection
