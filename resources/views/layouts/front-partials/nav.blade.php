<section class="main-nav" data-next-section="year-3333" data-aos="fade-top" data-aos-offset="300" data-aos-duration="1000">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<nav class="navbar navbar-light navbar-expand-lg navbar-static bg-faded justify-content-between align-items-center" role="navigation">
					<a class="navbar-brand" href="{{ route('home') }}">
						{{-- <img src="{{ asset('assets/front-images/doc-ryt-web-logo-white.png') }}" alt=""> --}}
						<img src="{{ asset('assets/front-images/logo-white.svg') }}" alt="" style="width:70px; height:70px;">
					</a>
					<ul class="nav navbar-nav toggle-ul  ml-auto">
						<button class="navbar-toggler pull-xs-right" id="navbarSideButton" type="button">
							<img src="{{ asset('assets/front-images/menu.png') }}">
						</button>
					</ul>
					<div class="navbar-side" id="navbarSide">
						<button class="navbar-toggler pull-xs-right" id="close" type="button">
							<i class="fas fa-times"></i>
						</button>
						<ul class="navbar-nav">
							<li class="navbar-side-item">
								<a href="{{ route('home') }}" class="side-link {{ Route::current()->getName() == 'home' ? 'active' : '' }}">Home</a>
							</li>
							<li class="navbar-side-item">
								<a href="{{ route('about-us') }}" class="side-link {{ Route::current()->getName() == 'about-us' ? 'active' : '' }}">About Us</a>
							</li>
							<li class="navbar-side-item">
								<a href="{{ route('home', ['#how']) }}" class="side-link">How It Works</a>
							</li>
							<li class="navbar-side-item">
								<a href="{{ route('faq') }}" class="side-link {{ Route::current()->getName() == 'faq' ? 'active' : '' }}">FAQ</a>
							</li>
							<li class="navbar-side-item">
								<a href="{{ route('home', ['#pricing']) }}" class="side-link">Pricing</a>
							</li>
							<li class="navbar-side-item">
								<a href="{{ route('contact-us') }}" class="side-link {{ Route::current()->getName() == 'contact-us' ? 'active' : '' }}">Contact Us</a>
							</li>
						</ul>
					</div>
					<ul class="login-register d-flex mb-0">
						@if(!Auth::check())
							<li class="navbar-side-item">
								<a href="{{ route('login') }}" class="side-link filled-btn">Login</a>
							</li>
							<li class="navbar-side-item">
								<a href="{{ route('register') }}" class="side-link border-btn">Register</a>
							</li>
						@else
							<li class="navbar-side-item">
								<a href="{{ route('dashboard') }}" class="side-link filled-btn">Dashboard</a>
							</li>
						@endif
					</ul>
					<div class="overlay" style="display:none;"></div>
				</nav>
			</div>
		</div>
	</div>
</section>

        <!-- header ends here -->