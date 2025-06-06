<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    @vite(['resources/css/app.css',
     'resources/css/bootstrap.min.css',
      'resources/font-awesome/css/font-awesome.css',
       'resources/css/animate.css', 
       'resources/css/style.css',
        'resources/css/plugins/morris/morris-0.4.3.min.css',
        'resources/css/plugins/dataTables/datatables.min.css'])
</head>

<body class="">
    <div id="wrapper">
        @include('layouts.sidebar')

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                @include('layouts.navigation')

            </div>


            <div class="wrapper wrapper-content">
                {{ $slot }}

            </div>
            <div id="right-sidebar">
                <div class="sidebar-container">
    
                    <ul class="nav nav-tabs navs-3">
                        <li>
                            <a class="nav-link active" data-toggle="tab" href="#tab-1"> Notes </a>
                        </li>
                        <li>
                            <a class="nav-link" data-toggle="tab" href="#tab-2"> Projects </a>
                        </li>
                        <li>
                            <a class="nav-link" data-toggle="tab" href="#tab-3"> <i class="fa fa-gear"></i> </a>
                        </li>
                    </ul>
    
                    <div class="tab-content">
    
    
                        <div id="tab-1" class="tab-pane active">
    
                            <div class="sidebar-title">
                                <h3> <i class="fa fa-comments-o"></i> Latest Notes</h3>
                                <small><i class="fa fa-tim"></i> You have 10 new message.</small>
                            </div>
    
                            <div>
    
                                <div class="sidebar-message">
                                    <a href="#">
                                        <div class="float-left text-center">
                                            <img alt="image" class="rounded-circle message-avatar" src="{{ asset('assets/img/a1.jpg')}}">
    
                                            <div class="m-t-xs">
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
    
                                            There are many variations of passages of Lorem Ipsum available.
                                            <br>
                                            <small class="text-muted">Today 4:21 pm</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="sidebar-message">
                                    <a href="#">
                                        <div class="float-left text-center">
                                            <img alt="image" class="rounded-circle message-avatar" src="{{ asset('assets/img/a2.jpg')}}">
                                        </div>
                                        <div class="media-body">
                                            The point of using Lorem Ipsum is that it has a more-or-less normal.
                                            <br>
                                            <small class="text-muted">Yesterday 2:45 pm</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="sidebar-message">
                                    <a href="#">
                                        <div class="float-left text-center">
                                            <img alt="image" class="rounded-circle message-avatar" src="{{ asset('assets/img/a3.jpg')}}">
    
                                            <div class="m-t-xs">
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            Mevolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                            <br>
                                            <small class="text-muted">Yesterday 1:10 pm</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="sidebar-message">
                                    <a href="#">
                                        <div class="float-left text-center">
                                            <img alt="image" class="rounded-circle message-avatar" src="{{ asset('assets/img/a4.jpg')}}">
                                        </div>
    
                                        <div class="media-body">
                                            Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the
                                            <br>
                                            <small class="text-muted">Monday 8:37 pm</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="sidebar-message">
                                    <a href="#">
                                        <div class="float-left text-center">
                                            <img alt="image" class="rounded-circle message-avatar" src="{{ asset('assets/img/a8.jpg')}}">
                                        </div>
                                        <div class="media-body">
    
                                            All the Lorem Ipsum generators on the Internet tend to repeat.
                                            <br>
                                            <small class="text-muted">Today 4:21 pm</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="sidebar-message">
                                    <a href="#">
                                        <div class="float-left text-center">
                                            <img alt="image" class="rounded-circle message-avatar" src="{{ asset('assets/img/a7.jpg')}}">
                                        </div>
                                        <div class="media-body">
                                            Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                                            <br>
                                            <small class="text-muted">Yesterday 2:45 pm</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="sidebar-message">
                                    <a href="#">
                                        <div class="float-left text-center">
                                            <img alt="image" class="rounded-circle message-avatar" src="{{ asset('assets/img/a3.jpg')}}">
    
                                            <div class="m-t-xs">
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            The standard chunk of Lorem Ipsum used since the 1500s is reproduced below.
                                            <br>
                                            <small class="text-muted">Yesterday 1:10 pm</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="sidebar-message">
                                    <a href="#">
                                        <div class="float-left text-center">
                                            <img alt="image" class="rounded-circle message-avatar" src="{{ asset('assets/img/a4.jpg')}}">
                                        </div>
                                        <div class="media-body">
                                            Uncover many web sites still in their infancy. Various versions have.
                                            <br>
                                            <small class="text-muted">Monday 8:37 pm</small>
                                        </div>
                                    </a>
                                </div>
                            </div>
    
                        </div>
    
                        <div id="tab-2" class="tab-pane">
    
                            <div class="sidebar-title">
                                <h3> <i class="fa fa-cube"></i> Latest projects</h3>
                                <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                            </div>
    
                            <ul class="sidebar-list">
                                <li>
                                    <a href="#">
                                        <div class="small float-right m-t-xs">9 hours ago</div>
                                        <h4>Business valuation</h4>
                                        It is a long established fact that a reader will be distracted.
    
                                        <div class="small">Completion with: 22%</div>
                                        <div class="progress progress-mini">
                                            <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                                        </div>
                                        <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="small float-right m-t-xs">9 hours ago</div>
                                        <h4>Contract with Company </h4>
                                        Many desktop publishing packages and web page editors.
    
                                        <div class="small">Completion with: 48%</div>
                                        <div class="progress progress-mini">
                                            <div style="width: 48%;" class="progress-bar"></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="small float-right m-t-xs">9 hours ago</div>
                                        <h4>Meeting</h4>
                                        By the readable content of a page when looking at its layout.
    
                                        <div class="small">Completion with: 14%</div>
                                        <div class="progress progress-mini">
                                            <div style="width: 14%;" class="progress-bar progress-bar-info"></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label label-primary float-right">NEW</span>
                                        <h4>The generated</h4>
                                        <!--<div class="small float-right m-t-xs">9 hours ago</div>-->
                                        There are many variations of passages of Lorem Ipsum available.
                                        <div class="small">Completion with: 22%</div>
                                        <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="small float-right m-t-xs">9 hours ago</div>
                                        <h4>Business valuation</h4>
                                        It is a long established fact that a reader will be distracted.
    
                                        <div class="small">Completion with: 22%</div>
                                        <div class="progress progress-mini">
                                            <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                                        </div>
                                        <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="small float-right m-t-xs">9 hours ago</div>
                                        <h4>Contract with Company </h4>
                                        Many desktop publishing packages and web page editors.
    
                                        <div class="small">Completion with: 48%</div>
                                        <div class="progress progress-mini">
                                            <div style="width: 48%;" class="progress-bar"></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="small float-right m-t-xs">9 hours ago</div>
                                        <h4>Meeting</h4>
                                        By the readable content of a page when looking at its layout.
    
                                        <div class="small">Completion with: 14%</div>
                                        <div class="progress progress-mini">
                                            <div style="width: 14%;" class="progress-bar progress-bar-info"></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label label-primary float-right">NEW</span>
                                        <h4>The generated</h4>
                                        <!--<div class="small float-right m-t-xs">9 hours ago</div>-->
                                        There are many variations of passages of Lorem Ipsum available.
                                        <div class="small">Completion with: 22%</div>
                                        <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                    </a>
                                </li>
    
                            </ul>
    
                        </div>
    
                        <div id="tab-3" class="tab-pane">
    
                            <div class="sidebar-title">
                                <h3><i class="fa fa-gears"></i> Settings</h3>
                                <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                            </div>
    
                            <div class="setings-item">
                        <span>
                            Show notifications
                        </span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example">
                                        <label class="onoffswitch-label" for="example">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                        <span>
                            Disable Chat
                        </span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" checked class="onoffswitch-checkbox" id="example2">
                                        <label class="onoffswitch-label" for="example2">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                        <span>
                            Enable history
                        </span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example3">
                                        <label class="onoffswitch-label" for="example3">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                        <span>
                            Show charts
                        </span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
                                        <label class="onoffswitch-label" for="example4">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                        <span>
                            Offline users
                        </span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example5">
                                        <label class="onoffswitch-label" for="example5">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                        <span>
                            Global search
                        </span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example6">
                                        <label class="onoffswitch-label" for="example6">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                        <span>
                            Update everyday
                        </span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example7">
                                        <label class="onoffswitch-label" for="example7">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="sidebar-content">
                                <h4>Settings</h4>
                                <div class="small">
                                    I belive that. Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                    And typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                    Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                </div>
                            </div>
    
                        </div>
                    </div>
    
                </div>
    
    
    
            </div>
      


            <div class="footer">
                <div class="float-right">
                    10GB of <strong>250GB</strong> Free.
                </div>
                <div>
                    <strong>Copyright</strong> Example Company &copy; 2014-2018
                </div>
            </div>

        </div>


    </div>
      <!-- Mainly scripts -->
      <script src="{{ asset('assets/js/jquery-3.1.1.min.js')}}"></script>
      <script src="{{ asset('assets/js/popper.min.js')}}"></script>
      <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
  
      <!-- Flot -->
      <script src="{{ asset('assets/js/plugins/flot/jquery.flot.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/jquery.flot.tooltip.min.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/jquery.flot.spline.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/jquery.flot.resize.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/jquery.flot.pie.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/jquery.flot.symbol.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/flot/curvedLines.js')}}"></script>
  
      <!-- Peity -->
      <script src="{{ asset('assets/js/plugins/peity/jquery.peity.min.js')}}"></script>
      <script src="{{ asset('assets/js/demo/peity-demo.js')}}"></script>
  
      <!-- Custom and plugin javascript -->
      <script src="{{ asset('assets/js/inspinia.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/pace/pace.min.js')}}"></script>
      <script src="{{ asset('assets/js/main.js')}}"></script>
  
      <!-- jQuery UI -->
      <script src="{{ asset('assets/js/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
  
      <!-- Jvectormap -->
      <script src="{{ asset('assets/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
  
      <!-- Sparkline -->
      <script src="{{ asset('assets/js/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
  
      <!-- Sparkline demo data  -->
      <script src="{{ asset('assets/js/demo/sparkline-demo.js')}}"></script>
  
      <!-- ChartJS-->
      <script src="{{ asset('assets/js/plugins/chartJs/Chart.min.js')}}"></script>

      <!-- Jquery Validation script -->

      <script src="{{ asset('assets/js/plugins/validate/jquery.validate.min.js')}}"></script>
      <script src="{{ asset('assets/js/plugins/validate/additional-methods.min.js')}}"></script>

      <!-- DataTables-->
      <script src="{{ asset('assets/js/plugins/dataTables/datatables.min.js')}}"></script>
     
      <script>
          $(document).ready(function() {
  
  
              var d1 = [[1262304000000, 6], [1264982400000, 3057], [1267401600000, 20434], [1270080000000, 31982], [1272672000000, 26602], [1275350400000, 27826], [1277942400000, 24302], [1280620800000, 24237], [1283299200000, 21004], [1285891200000, 12144], [1288569600000, 10577], [1291161600000, 10295]];
              var d2 = [[1262304000000, 5], [1264982400000, 200], [1267401600000, 1605], [1270080000000, 6129], [1272672000000, 11643], [1275350400000, 19055], [1277942400000, 30062], [1280620800000, 39197], [1283299200000, 37000], [1285891200000, 27000], [1288569600000, 21000], [1291161600000, 17000]];
  
              var data1 = [
                  { label: "Data 1", data: d1, color: '#17a084'},
                  { label: "Data 2", data: d2, color: '#127e68' }
              ];
              $.plot($("#flot-chart1"), data1, {
                  xaxis: {
                      tickDecimals: 0
                  },
                  series: {
                      lines: {
                          show: true,
                          fill: true,
                          fillColor: {
                              colors: [{
                                  opacity: 1
                              }, {
                                  opacity: 1
                              }]
                          },
                      },
                      points: {
                          width: 0.1,
                          show: false
                      },
                  },
                  grid: {
                      show: false,
                      borderWidth: 0
                  },
                  legend: {
                      show: false,
                  }
              });
  
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
      </script>
 
    {{-- @vite(
    ['resources/js/jquery-3.1.1.min.js',    
    'resources/js/plugins/jquery-ui/jquery-ui.min.js',
      'resources/js/popper.min.js',
      'resources/js/bootstrap.js',
      'resources/js/plugins/metisMenu/jquery.metisMenu.js',
      'resources/js/plugins/slimscroll/jquery.slimscroll.min.js',
      'resources/js/plugins/flot/jquery.flot.js',
      'resources/js/plugins/flot/jquery.flot.tooltip.min.js',
      'resources/js/plugins/flot/jquery.flot.spline.js',
      'resources/js/plugins/flot/jquery.flot.resize.js',
      'resources/js/plugins/flot/jquery.flot.pie.js',
      'resources/js/plugins/flot/jquery.flot.symbol.js',
      'resources/js/plugins/flot/curvedLines.js',
    //   'resources/js/plugins/peity/jquery.peity.min.js',
    //   'resources/js/demo/peity-demo.js',
    //   'resources/js/inspinia.js',
    //   'resources/js/plugins/pace/pace.min.js',
    //   'resources/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js',
    //   'resources/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
    //   'resources/js/plugins/sparkline/jquery.sparkline.min.js',
    //   'resources/js/demo/sparkline-demo.js',
    //   'resources/js/plugins/chartJs/Chart.min.js', 
    ]) --}}
 
</body>

</html>
