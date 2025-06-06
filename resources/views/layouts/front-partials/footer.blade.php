<footer data-next-section="year-3333" data-aos="fade-top" data-aos-offset="10" data-aos-duration="1000">
	<div class="svg-shape bottom-shape">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
			<path class="elementor-shape-fill" d="M0,6V0h1000v100L0,6z"></path>
		</svg>
	</div>
	<div class="container">
		<div class="row">
			<div class="contact-box d-flex justify-content-between align-items-center" id="contact">
				<div class="contact-left text-left">
					<h2 class="white mb-0">Need support? Contact our team</h2>
					<span class="pl-0"><i class="far fa-clock"></i> Mon - Fri: 9am to 5pm</span>
				</div>
				<div class="contact-right text-center">
					<a href="tel:+121 456 8940" class="filled-btn"><i class="fa-solid fa-phone"></i> +1425-232-1892</a>
					<span class="d-block mt-2">Or Email Us: <a href="mailto:info@docryt.com">info@docryt.com</a></span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xl-12 col-lg-12 text-center">
				 <a href="{{ route('home') }}" class="logo-footer">
					{{-- <img src="{{ asset('assets/front-images/footer-logo.png') }}"> --}}
					{{-- <img src="{{ asset('assets/front-images/doc-ryt-web-logo.png') }}"> --}}
					<img src="{{ asset('assets/front-images/logo.svg') }}" style="width:70px; height:70px;">
				</a>
				 <ul class="navbar-footer">
					<li class="navbar-side-item">
						<a href="{{ route('privacy-policy') }}" class="side-link {{ Route::current()->getName() == 'privacy-policy' ? 'active' : '' }}">Privacy Policy</a>
					</li>
					{{-- <li class="navbar-side-item">
						<a href="{{ route('term-conditions') }}" class="side-link {{ Route::current()->getName() == 'term-conditions' ? 'active' : '' }}">Terms & Conditions</a>
					</li> --}}
				</ul>
				<ul class="social">
					<li><a href="#"><i class="fa fa-facebook-f" aria-hidden="true"></i></a></li>
					<li><a href="#" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
					<li><a href="#" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
					<li><a href="#" target="_blank"><i class="fa fa-youtube"></i></a></li>
				</ul>
		    </div>
			<div class="col-xl-12 col-lg-12 text-center">
			<hr>
				<p>Copyright {{ date('Y') }} DocRyt. All Rights Reserved.</p></div>
		  </div>
	</div>
</footer>
