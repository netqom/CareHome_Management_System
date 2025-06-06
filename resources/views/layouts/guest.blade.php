<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield("title")</title>
		<link rel="icon" href="{{ url('fav-icon.png') }}">
        <!-- Fonts -->
		<link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
		<link href="{{ asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
		<link href="{{ asset('assets/css/plugins/iCheck/custom.css')}}" rel="stylesheet" />
		<link href="{{ asset('assets/css/animate.css')}}" rel="stylesheet" />
		<link href="{{ asset('assets/css/style.css')}}" rel="stylesheet" />
    </head>
	<body class="gray-bg">
		@yield('content')
		
	</body>
	<!-- Mainly scripts -->
	<script src="{{ asset('assets/js/jquery-3.1.1.min.js')}}"></script> 
	<script src="{{ asset('assets/js/popper.min.js')}}"></script> 
	<script src="{{ asset('assets/js/bootstrap.js')}}"></script> 
	<script src="{{ asset('assets/js/plugins/iCheck/icheck.min.js')}}"></script> 
	<script src="{{ asset('assets/js/plugins/validate/jquery.validate.min.js')}}"></script>
	 <script src="{{ asset('assets/js/main.js')}}"></script>
	<script>
	  $(document).ready(function(){
		  $('.i-checks').iCheck({
			  checkboxClass: 'icheckbox_square-green',
			  radioClass: 'iradio_square-green',
		  });
	  });
	</script>
</html>
