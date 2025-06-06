<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title")</title>
	<link rel="icon" href="{{ url('fav.png') }}">
    <link href="{{ asset('assets/css/app.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/css/plugins/iCheck/custom.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/css/animate.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/css/style.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/css/plugins/morris/morris-0.4.3.min.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
	<!--link href="{{ asset('assets/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet" /-->
	@yield("style")
	<style>
	.global-loader {
		background: #cfcfcf;
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100% !important;
		right: 0;
		bottom: 0;
		z-index: 99;
	}
	.global-loader img {max-width: 40px;}
	.relative {
		position: relative;
	}
	.form-loader {
		position: fixed;
		top: 0;
		left: 0;
		z-index: 5000;
		width: 100%;
		text-align: center;
		background: rgb(0,0,0,0.7);
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-wrap: wrap;
		color: #DCDCDC;
	}
	.active-sort {
		color: #339de9 !important;
		height: 1px;
	}
	
	.inactive-sort {
		color: #c3c8cb !important;
		height: 1px;
	}
	.ibox-content .table-responsive {min-height: 350px;}
	
</style>
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
      <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
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
      <script src="{{ asset('assets/js/plugins/chartJs/Chart.min.js')}}"></script>
	  
	  <script src="{{ asset('assets/js/plugins/sweetalert/aweetAlert.js') }}"></script>
      <!-- Jquery Validation script -->
      <script src="{{ asset('assets/js/plugins/validate/jquery.validate.min.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/validate/additional-methods.min.js')}}"></script>
      <script src="{{ asset('assets/js/module/common/url.js')}}"></script>
      <script src="{{ asset('assets/js/module/common.js')}}"></script>
	  <script src="{{ asset('assets/js/main.js')}}"></script>
      <!--script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
      <script src="https://unpkg.com/@ckeditor/ckeditor5-inspector@4.1.0/build/inspector.js"></script-->
	  <script>
			var BASE_URL = "{{ url('/') }}";
			var USER_ROLE = "{{ Auth::user()->role }}";
			var CONTENT_LOADING = "{{ asset('assets/img/loading.gif') }}";
	  </script>
      <!-- DataTables-->
      <!--script src="{{ asset('assets/js/plugins/dataTables/datatables.min.js')}}"></script-->
	  @yield("script")
	  <script>
        @if(Session::has('message'))
            var type = "{{ Session::get('type') }}";
            var msg = "{{ Session::get('message') }}";
            toastAlert(type, msg);
        @endif
      </script>	  
      <script>
			@if(Route::is('dashboard'))
          $(document).ready(function() {
             var d1 = [[1262304000000, 6], [1264982400000, 3057], [1267401600000, 20434], [1270080000000, 31982], [1272672000000, 26602], [1275350400000, 27826], [1277942400000, 24302], [1280620800000, 24237], [1283299200000, 21004], [1285891200000, 12144], [1288569600000, 10577], [1291161600000, 10295]];
              var d2 = [[1262304000000, 5], [1264982400000, 200], [1267401600000, 1605], [1270080000000, 6129], [1272672000000, 11643], [1275350400000, 19055], [1277942400000, 30062], [1280620800000, 39197], [1283299200000, 37000], [1285891200000, 27000], [1288569600000, 21000], [1291161600000, 17000]];
  
              var data1 = [
                  { label: "Data 1", data: d1, color: '#17a084'},
                  { label: "Data 2", data: d2, color: '#127e68' }
              ];
            //   $.plot($("#flot-chart1"), data1, {
            //       xaxis: {
            //           tickDecimals: 0
            //       },
            //       series: {
            //           lines: {
            //               show: true,
            //               fill: true,
            //               fillColor: {
            //                   colors: [{
            //                       opacity: 1
            //                   }, {
            //                       opacity: 1
            //                   }]
            //               },
            //           },
            //           points: {
            //               width: 0.1,
            //               show: false
            //           },
            //       },
            //       grid: {
            //           show: false,
            //           borderWidth: 0
            //       },
            //       legend: {
            //           show: false,
            //       }
            //   });
  
              var lineData = {
                  labels: ["January", "February", "March", "April", "May", "June", "July"],
                  datasets: [
                      {
                          label: "Example dataset",
                          backgroundColor: "rgba(26,179,148,0.5)",
                          borderColor: "rgba(26,179,148,0.7)",
                          pointBackgroundColor: "rgba(26,179,148,1)",
                          pointBorderColor: "#fff",
                          data: [48, 48, 60, 39, 56, 37, 30]
                      },
                      {
                          label: "Example dataset",
                          backgroundColor: "rgba(220,220,220,0.5)",
                          borderColor: "rgba(220,220,220,1)",
                          pointBackgroundColor: "rgba(220,220,220,1)",
                          pointBorderColor: "#fff",
                          data: [65, 59, 40, 51, 36, 25, 40]
                      }
                  ]
              };
  
              var lineOptions = {
                  responsive: true
              };
			  
              var ctx = document.getElementById("lineChart").getContext("2d");
              new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});
			  
			  
          });
		  @endif
      </script>

      <script>
        function CustomizationPlugin( editor ) {

        }

        /*ClassicEditor
            .create( document.querySelector( '#user_editor' ), {
            extraPlugins: [ CustomizationPlugin ]
        } )
            .then( newEditor => {
            window.editor = newEditor;
            // The following line adds CKEditor 5 inspector.
            CKEditorInspector.attach( newEditor, {
                isCollapsed: true
            } );
        } )
            .catch( error => {
            console.error( error );
        } );*/
      </script>

        <script>
            /*function saveContent(e, page) {
                e.preventDefault();
                var content = editor. getData();
                var showpage = $('#showpage').prop('checked');

                // Perform Ajax request to save content to the server
                $.ajax({
                    type: 'POST',
                    url: '{{ route('resource-update') }}', // Update the URL to your Laravel route
                    data: {
                        _token: '{{ csrf_token() }}',
                        content: content,
                        page_type: page,
                        showpage: showpage,
                        // You can add more data if needed
                    },
                    success: function(response) {
                        toastAlert(response.type, response.message);
                        // Handle success response
                    },
                    error: function(error) {
                        toastAlert(error.type, error.message);
                        // Handle error response
                        // console.error(error);
                    }
                });

            }*/
        </script>

</html>
