@extends('layouts.guest')

@section('content')
<section class="email-verified">
<div class="container">
	<div class="row">
		<div class="col-12 text-center"><a class="logo-verified" href="#">
			{{-- <img src="{{asset('assets/front-images/doc-ryt-web-logo-white.png')}}" alt="DocRyt"> --}}
			<img src="{{asset('assets/front-images/logo-white.svg')}}" alt="DocRyt" style="width:70px; height:70px;">
		</a></div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-ms-12">
			<div class="verified-box">
			<div class="row align-items-center">
				<div class="col-lg-6 col-md-6 col-m-12">
					<h2>Email Verified</h2>
					<p>Your account has been verified. Please download & install mobile app to login.</p>
					<a href="#"><img src="{{asset('assets/front-images/google-play.png')}}"></a>
				</div>
				<div class="col-lg-6 col-md-6 col-m-12"><img class="mw-100" src="{{asset('assets/front-images/verified-img.png')}}"></div>
			</div>
		</div>
	</div>
	</div>
</div>
</section>

@endsection