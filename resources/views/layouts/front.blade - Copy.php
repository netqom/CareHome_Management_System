<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield("title")</title>
        <!-- Scripts -->
		<link rel="icon" href="{{ url('fav.png') }}">
		<link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
		<link href="{{ asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
		<link href="{{ asset('assets/css/animate.css')}}" rel="stylesheet" />
		<link href="{{ asset('assets/css/style.css')}}" rel="stylesheet" />
		<style>
		.default-height {
			min-height: calc(100vh - 175px);
		}

		body {
			height: auto !important;
		}
		.common-header {
			margin-top: 67px;
			text-align: center;
			margin-bottom: 0px !important;
		}
		</style>
	</head>
	<body id="page-top" class="landing-page no-skin-config"> 
		@include('layouts.front-partials.nav')
		@yield('content')
    </body> 
	<!-- Mainly scripts -->
      <script src="{{ asset('assets/js/jquery-3.1.1.min.js')}}"></script>
      <script src="{{ asset('assets/js/popper.min.js')}}"></script>
      <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
      <!-- Custom and plugin javascript -->
      <script src="{{ asset('assets/js/inspinia.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/pace/pace.min.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/wow/wow.min.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/validate/jquery.validate.min.js')}}"></script>
      <script>

        $(document).ready(function () {
    
            /*$('body').scrollspy({
                target: '#navbar',
                offset: 80
            });
    
            // Page scrolling feature
            $('a.page-scroll').bind('click', function(event) {
                var link = $(this);
                $('html, body').stop().animate({
                    scrollTop: $(link.attr('href')).offset().top - 50
                }, 500);
                event.preventDefault();
                $("#navbar").collapse('hide');
            });
        });
    
        var cbpAnimatedHeader = (function() {
            var docElem = document.documentElement,
                    header = document.querySelector( '.navbar-default' ),
                    didScroll = false,
                    changeHeaderOn = 200;
            function init() {
                window.addEventListener( 'scroll', function( event ) {
                    if( !didScroll ) {
                        didScroll = true;
                        setTimeout( scrollPage, 250 );
                    }
                }, false );
            }
            function scrollPage() {
                var sy = scrollY();
                if ( sy >= changeHeaderOn ) {
                    $(header).addClass('navbar-scroll')
                }
                else {
                    $(header).removeClass('navbar-scroll')
                }
                didScroll = false;
            }
            function scrollY() {
                return window.pageYOffset || docElem.scrollTop;
            }
            init();
    
        })();
    
        // Activate WOW.js plugin for animation on scrol
        new WOW().init();*/
    
    </script>
</html>
