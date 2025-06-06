<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title")</title>
	<link rel="icon" href="{{ url('fav-icon.png') }}">
    <link href="{{ asset('assets/css/app.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/css/plugins/iCheck/custom.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/css/animate.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/css/style.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/css/plugins/morris/morris-0.4.3.min.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
	<!--link href="{{ asset('assets/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet" /-->
	@yield("style")
</head>

<body class="">
	<div class="form-loader" style="display: none;">
		<div class="text-center">
			<img id="loaderIcon" src="{{ asset('assets/img/loading.gif') }}" height="40px" width="40px" alt="..."/>
			<span class="ml-2 mr-3">  Wait...</span>
		</div>
	</div>
    <div id="wrapper">
		<!--- Sidebar Section --->
        @include('layouts.admin-partials.sidebar')
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
				<!--- Navigation Section ---> 
                @include('layouts.admin-partials.navigation')
            </div>
			<!--- Content Section --->
            @yield('content')
			<!--- Right Side Bar Section --->
            @include('layouts.admin-partials.right-sidebar')
			<!--- Foooter Section --->
            @include('layouts.admin-partials.footer')
        </div>
    </div>
</body>
<!-- Mainly scripts -->
      <script src="{{ asset('assets/js/jquery-3.1.1.min.js')}}"></script>
      <script src="{{ asset('assets/js/popper.min.js')}}"></script>
      <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
	  <script src="{{ asset('assets/js/plugins/iCheck/icheck.min.js')}}"></script> 
      <script src="{{ asset('assets/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
      <!-- Flot -->
      <!--script src="{{ asset('assets/js/plugins/flot/jquery.flot.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/jquery.flot.tooltip.min.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/jquery.flot.spline.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/jquery.flot.resize.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/jquery.flot.pie.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/jquery.flot.symbol.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/curvedLines.js')}}"></script-->
      <!-- Peity -->
      <!--script src="{{ asset('assets/js/plugins/peity/jquery.peity.min.js')}}"></script>
      <script src="{{ asset('assets/js/demo/peity-demo.js')}}"></script-->
      <!-- Custom and plugin javascript -->
      <script src="{{ asset('assets/js/inspinia.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/pace/pace.min.js')}}"></script>
	  <!-- Date range use moment.js same as full calendar plugin -->
	  <script src="{{ asset('assets/js/plugins/fullcalendar/moment.min.js') }}"></script>
	  <!-- Data picker -->
	  <script src="{{ asset('assets/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
      <!-- jQuery UI -->
      <!--script src="{{ asset('assets/js/plugins/jquery-ui/jquery-ui.min.js')}}"></script-->
      <!-- Jvectormap -->
      <!--script src="{{ asset('assets/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script-->
      <!-- Sparkline -->
      <!--script src="{{ asset('assets/js/plugins/sparkline/jquery.sparkline.min.js')}}"></script-->
      <!-- Sparkline demo data  -->
      <!--script src="{{ asset('assets/js/demo/sparkline-demo.js')}}"></script-->
      <!-- ChartJS-->
      {{-- <script src="{{ asset('assets/js/plugins/chartJs/Chart.min.js')}}"></script> --}}
	  <script src="{{ asset('assets/js/plugins/sweetalert/aweetAlert.js') }}"></script>
      <!-- Jquery Validation script -->
      <script src="{{ asset('assets/js/plugins/validate/jquery.validate.min.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/validate/additional-methods.min.js')}}"></script>
      <script src="{{ asset('assets/js/module/common/url.js')}}"></script>
      <script src="{{ asset('assets/js/module/common.js')}}"></script>
	  <script src="{{ asset('assets/js/main.js')}}"></script>
	  <script>
			var BASE_URL = "{{ url('/') }}";
			var USER_ROLE = "{{ Auth::user()->role }}";
			var CONTENT_LOADING = "{{ asset('assets/img/loading.gif') }}";
	  </script>
	  @yield("script")
	  @if(Session::has('message'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var type = "{{ Session::get('type') }}";
                var msg = "{{ Session::get('message') }}";
                toastAlert(type, msg);
                // Flush the session after displaying the message
                @php
                    Session::forget('message');
                    Session::forget('type');
                @endphp
            });
        </script>
    @endif
    <script>
        setInterval(function() {
           fetch("{{ route('chat-message-count') }}") // Adjust this route to your actual route for fetching chat count
           .then(response => response.json())
           .then(data => {
            //    document.getElementById('chat-count').innerText = data.count;
               // Optionally update the notification icon
               if (data.count > 0) {
                   document.getElementById('chat-count').innerHTML += '<i class="fa fa-circle position-absolute" style="font-size: 7px; color: #74f774 !important;"></i>';
               } else {
                //    document.getElementById('chat-count').innerHTML = data.count;
               }
           });
       }, 10000); // 10000 milliseconds = 10 seconds

   </script>
    <script src="https://kit.fontawesome.com/66517fe47a.js" crossorigin="anonymous"></script>
</html>
