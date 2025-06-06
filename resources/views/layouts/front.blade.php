<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield("title")</title>
        <!-- Scripts -->
		<link rel="icon" href="{{ url('fav-icon.png') }}">
		<link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
		<link href="{{ asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
		<link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/css/aos.css')}}" />
	</head>
	<body class="home">
		@include('layouts.front-partials.nav')
		@yield('content')
		@include('layouts.front-partials.footer')
    </body> 
	<!-- Mainly scripts -->
      <script src="{{ asset('assets/js/jquery-3.1.1.min.js')}}"></script>
      <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
      <script src="{{ asset('assets/js/aos.js')}}"></script>
	  <script src="{{ asset('assets/js/plugins/validate/jquery.validate.min.js')}}"></script>
	  <script src="{{ asset('assets/js/main.js')}}"></script>
	  <script src="https://kit.fontawesome.com/66517fe47a.js" crossorigin="anonymous"></script>
	   @yield("script")
      <script>
		AOS.init({
			easing: 'ease-in-out-sine'
		});
	 </script>

	<script type="text/javascript">
		$(document).ready(function () {

			$('#navbarSideButton').on('click', function () {
				$('#navbarSide').addClass('reveal');
				$('.overlay').show();
			});

			$('.overlay').on('click', function () {
				$('#navbarSide').removeClass('reveal');
				$('.overlay').hide();
			});
			$('.side-link').on('click', function () {
				$('#navbarSide').removeClass('reveal');
				$('.overlay').hide();
			});
			$('#close').on('click', function () {
				$('#navbarSide').removeClass('reveal');
				$('.overlay').hide();
			});

		});
	</script>

<script type="text/javascript">
    $(window).bind('scroll', function () {
        if ($(window).scrollTop() > 50) {
            $('.main-nav').addClass('fixed');
        } else {
            $('.main-nav').removeClass('fixed');
        }
    });
</script>
</html>
