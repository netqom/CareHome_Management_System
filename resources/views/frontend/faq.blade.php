@extends('layouts.front')
@section('content')
<!-- banner start here -->
<section class="banner inner-banner" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100"
	data-aos-duration="1000">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 offset-lg-1 col-md-12 col-md-12">
				<div class="banner-contet text-center white">
					{{-- <span class="fs-18 d-block pb-3">Any Query?</span> --}}
					<h1 class="white fw-600">FAQs</h1>
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
<!-- faq start here -->
<section class="faq-sec" id="faq" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="     0"
	data-aos-duration="1000">
	<div class="container">
		<div class="accordion aos-item aos-init aos-animate" data-aos="fade-in" id="membership">
			<div class="row row-md-reverse align-items-center">
				<div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
					<h2 class="dark-green fw-600 mb-4 text-center">Frequently <span class="stroke">Asked</span>
						Questions</h2>
					<p class="text-center">Curious about docRyt? We've got you covered! Here are the top questions
						our customers frequently<br> ask about our software.</p>
					<div class="accordion" id="faq">
						@foreach($faqs as $key => $faq)
						<div class="card" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100"
							data-aos-duration="1000">
							<div class="card-header" id="faqhead{{$key}}">
								<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse"
									data-target="#faq{{$key}}" aria-expanded="false" aria-controls="faq{{$key}}"><span><img
											src="{{ asset('assets/front-images/q-icon.png') }}" alt="Q"> {{$faq->question}}</span></a>
							</div>
							<div id="faq{{$key}}" class="collapse" aria-labelledby="faqhead{{$key}}" data-parent="#faq" style="">
								<div class="card-body">
									{{$faq->answer}}
								</div>
							</div>
						</div>
						@endforeach
						{{-- <div class="card" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100"
							data-aos-duration="1000">
							<div class="card-header" id="faqhead2">
								<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse"
									data-target="#faq2" aria-expanded="true" aria-controls="faq2"><span><img
											src="{{ asset('assets/front-images/q-icon.png') }}" alt="Q"> What platforms does ?</span></a>
							</div>
							<div id="faq2" class="collapse" aria-labelledby="faqhead2" data-parent="#faq">
								<div class="card-body">
									Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
									Ipsum has. been the industrys standard dummy text ever since the when an unknown
									printer took a galley of type and scrambled it to make a type specimen book. It
									has survived not only five cen turies but also the leap into electronic
									typesetting, remaining essentially unchanged.
								</div>
							</div>
						</div>
						<div class="card" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100"
							data-aos-duration="1000">
							<div class="card-header" id="faqhead3">
								<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse"
									data-target="#faq3" aria-expanded="true" aria-controls="faq3"><span><img
											src="{{ asset('assets/front-images/q-icon.png') }}" alt="Q"> CWhat platforms does ?</span></a>
							</div>
							<div id="faq3" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
								<div class="card-body">
									Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
									Ipsum has. been the industrys standard dummy text ever since the when an unknown
									printer took a galley of type and scrambled it to make a type specimen book. It
									has survived not only five cen turies but also the leap into electronic
									typesetting, remaining essentially unchanged.
								</div>
							</div>
						</div>
						<div class="card" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100"
							data-aos-duration="1000">
							<div class="card-header" id="faqhead3">
								<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse"
									data-target="#faq4" aria-expanded="true" aria-controls="faq4"><span><img
											src="{{ asset('assets/front-images/q-icon.png') }}" alt="Q"> What platforms does ?</span></a>
							</div>
							<div id="faq4" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
								<div class="card-body">
									Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
									Ipsum has. been the industrys standard dummy text ever since the when an unknown
									printer took a galley of type and scrambled it to make a type specimen book. It
									has survived not only five cen turies but also the leap into electronic
									typesetting, remaining essentially unchanged.
								</div>
							</div>
						</div>
						<div class="card" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100"
							data-aos-duration="1000">
							<div class="card-header" id="faqhead3">
								<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse"
									data-target="#faq5" aria-expanded="true" aria-controls="faq5"><span><img
											src="{{ asset('assets/front-images/q-icon.png') }}" alt="Q"> What platforms does ?</span></a>
							</div>
							<div id="faq5" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
								<div class="card-body">
									Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
									Ipsum has. been the industrys standard dummy text ever since the when an unknown
									printer took a galley of type and scrambled it to make a type specimen book. It
									has survived not only five cen turies but also the leap into electronic
									typesetting, remaining essentially unchanged.
								</div>
							</div>
						</div>
						<div class="card" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100"
							data-aos-duration="1000">
							<div class="card-header" id="faqhead3">
								<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse"
									data-target="#faq6" aria-expanded="true" aria-controls="faq6"><span><img
											src="{{ asset('assets/front-images/q-icon.png') }}" alt="Q"> What platforms does ?</span></a>
							</div>
							<div id="faq6" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
								<div class="card-body">
									Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
									Ipsum has. been the industrys standard dummy text ever since the when an unknown
									printer took a galley of type and scrambled it to make a type specimen book. It
									has survived not only five cen turies but also the leap into electronic
									typesetting, remaining essentially unchanged.
								</div>
							</div>
						</div>
						 <div class="card" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100"
							data-aos-duration="1000">
							<div class="card-header" id="faqhead3">
								<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse"
									data-target="#faq7" aria-expanded="true" aria-controls="faq7"><span><img
											src="{{ asset('assets/front-images/q-icon.png') }}" alt="Q"> What platforms does ?</span></a>
							</div>
							<div id="faq7" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
								<div class="card-body">
									Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
									Ipsum has. been the industrys standard dummy text ever since the when an unknown
									printer took a galley of type and scrambled it to make a type specimen book. It
									has survived not only five cen turies but also the leap into electronic
									typesetting, remaining essentially unchanged.
								</div>
							</div>
						</div>
						<div class="card" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100"
							data-aos-duration="1000">
							<div class="card-header" id="faqhead3">
								<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse"
									data-target="#faq8" aria-expanded="true" aria-controls="faq8"><span><img
											src="{{ asset('assets/front-images/q-icon.png') }}" alt="Q"> What platforms does ?</span></a>
							</div>
							<div id="faq8" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
								<div class="card-body">
									Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
									Ipsum has. been the industrys standard dummy text ever since the when an unknown
									printer took a galley of type and scrambled it to make a type specimen book. It
									has survived not only five cen turies but also the leap into electronic
									typesetting, remaining essentially unchanged.
								</div>
							</div>
						</div>
						<div class="card" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100"
							data-aos-duration="1000">
							<div class="card-header" id="faqhead3">
								<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse"
									data-target="#faq9" aria-expanded="true" aria-controls="faq9"><span><img
											src="{{ asset('assets/front-images/q-icon.png') }}" alt="Q"> What platforms does ?</span></a>
							</div>
							<div id="faq9" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
								<div class="card-body">
									Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
									Ipsum has. been the industrys standard dummy text ever since the when an unknown
									printer took a galley of type and scrambled it to make a type specimen book. It
									has survived not only five cen turies but also the leap into electronic
									typesetting, remaining essentially unchanged.
								</div>
							</div>
						</div>
						<div class="card" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="100"
							data-aos-duration="1000">
							<div class="card-header" id="faqhead3">
								<a href="#" class="btn btn-header-link collapsed" data-toggle="collapse"
									data-target="#faq10" aria-expanded="true" aria-controls="faq10"><span><img
											src="{{ asset('assets/front-images/q-icon.png') }}" alt="Q"> What platforms does ?</span></a>
							</div>
							<div id="faq10" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
								<div class="card-body">
									Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem
									Ipsum has. been the industrys standard dummy text ever since the when an unknown
									printer took a galley of type and scrambled it to make a type specimen book. It
									has survived not only five cen turies but also the leap into electronic
									typesetting, remaining essentially unchanged.
								</div>
							</div>
						</div> --}}
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
