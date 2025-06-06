@extends('layouts.admin')

@section('title', 'Dashboard')

@section('style')
    <link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <style>
        .map-div {
            height: 350px;
            border: 1px solid #e5e6e7;
            background: #e3e3e3;
        }
    </style>

@endsection
@section('content')
    <style>
        .m-b-md {
            margin-bottom: 10px;
        }

        .p-lg {
            padding: 10px;
        }
    </style>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Dashboard</strong>
                </li>
            </ol>
        </div>
    </div>

    @if (Auth::user()->role_id == 2)
        @if ($total_nursing_homes == 0)
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="alert alert-warning alert-dismissable mb-0">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        Please add nursing home to start you journey. <a class="alert-link"
                            href="{{ route('homes.create') }}">Click Here to get started</a>.
                    </div>
                </div>
            </div>
        @endif
        @if (!$subhomeEnd->isEmpty() && Auth::user()->role_id == 2)
            @foreach ($subhomeEnd as $key => $chome)
                @if ($chome->ends_at != null && $chome->stripe_status == 'canceled')
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="alert alert-warning alert-dismissable mb-0">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                Your subscription for {{ $chome->name }} has ended. Please purchase again to continue. <a
                                    class="alert-link" href="{{ route('subscription-manage', $chome->home_id) }}">Click Here
                                    to purchase</a>.
                            </div>
                        </div>
                    </div>
                @elseif ($chome->stripe_status != 'active')
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="alert alert-warning alert-dismissable mb-0">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                Your subscription for {{ $chome->name }} has been not active. Please purchase again to continue. <a
                                    class="alert-link" href="{{ route('subscription-manage', $chome->home_id) }}">Click Here
                                    to purchase</a>.
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

        @if ($subscription_notifications)
            <div class="mt-3">
                @foreach ($subscription_notifications as $key => $value)
                    <div class="alert alert-danger align-items-baseline d-flex" role="alert">
                        <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true"></i> {!! $value->message !!}
                    </div>
                @endforeach
            </div>
        @endif

        @if ($currently_working_users)
            <div class="row mt-3 mb-5">

                <div class="col-lg-12">
                    <div class="ibox recent-updates shadow">
                        <div class="ibox-title">
                            <h5>Feeds</h5>
                            <div class="ibox-tools"></div>
                        </div>
                        <div class="ibox-content">
                            <div>
                                <div class="feed-activity-list">
                                    @if ($notifications->count() > 0)
                                        @foreach ($notifications as $notification)
                                            <div class="feed-element">
                                                @if (
                                                    $notification->item_type != 'appointment_update' &&
                                                        $notification->item_type != 'appointment_add' &&
                                                        $notification->patient_id > 0)
                                                    <a class="float-left"
                                                        href="{{ route('patients.show', $notification->patient_id) }}">
                                                        <img alt="image" class="rounded-circle"
                                                            src="{{ getPatientImage($notification->patient_id) }}">
                                                    </a>
                                                @endif
                                                @if (
                                                    ($notification->item_type != 'patient_incident_added' ||
                                                        $notification->item_type != 'adhoc_medicine_notification') &&
                                                        $notification->staff_id > 0)
                                                    <a class="float-left"
                                                        href="{{ route('users.show', $notification->staff_id) }}">
                                                        <img alt="image" class="rounded-circle"
                                                            src="{{ getUserImage($notification->staff_id) }}">
                                                    </a>
                                                @endif
                                                <div class="media-body ">
                                                    <!--small class="float-right">{{ $notification->created_at->diffForHumans() }}</small-->
                                                    {!! $notification->message !!}. <br>
                                                    <small
                                                        class="text-muted">{{ date('l', strtotime($notification->created_at)) }}
                                                        {{ date('h:i a', strtotime($notification->created_at)) }} -
                                                        {{ date('m.d.Y', strtotime($notification->created_at)) }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="feed-element text-center">
                                            <p>No Feeds Found</p>
                                        </div>
                                    @endif
                                </div>
                                <a class="btn btn-primary btn-block m-t" href="{{ route('notifications.index') }}"><i
                                        class="fa fa-arrow-down"></i> View All</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 gg">
                    <div class="animated fadeInRight pt-0">
                        <div class="row">
                            <div class="col-lg-6 mb-2 mt-2">
                                <div class="h-100 widget style1 white-bg mt-0 stat-box-shadow radius-17" type="button"
                                    onclick="location.href = '{{ route('homes.index') }}';">
                                    <div class="row align-items-center">
                                        <div class="col text-left w-100">
                                            <h2 class="font-bold fs-18 green">{{ $total_nursing_homes }}</h2>
                                            <span class="fs-14"> Nursing Homes </span>
                                        </div>
                                        <div class="col-auto flex-shrink-0">
                                            <span class="icon-span"><i class="fa fa-hospital-o fa-3x"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2 mt-2">
                                <div class="h-100 widget style1 white-bg mt-0 stat-box-shadow radius-17" type="button"
                                    onclick="location.href = '{{ route('users.index') }}';">
                                    <div class="row align-items-center">
                                        <div class="col text-left w-100">
                                            <h2 class="font-bold fs-18 green">{{ $total_regisetered_users }}</h2>
                                            <span class="fs-14"> Users </span>
                                        </div>
                                        <div class="col-auto flex-shrink-0">
                                            <span class="icon-span"> <i class="fa fa-user-friends fa-3x"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2 mt-2">
                                <div class="widget style1 white-bg stat-box-shadow radius-17" type="button"
                                    onclick="location.href = '{{ route('last-week-revenue') }}';">
                                    <div class="row align-items-center">
                                        <div class="col text-left w-100">
                                            <h2 class="font-bold fs-18 green">{{ amountFormat($last_week_revenue) }}</h2>
                                            <span class="fs-14"> Last Week Earning </span>
                                        </div>
                                        <div class="col-auto flex-shrink-0">
                                            <span class="icon-span"> <i class="fa fa-money fa-3x"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2 mt-2">
                                <div class="widget style1 white-bg stat-box-shadow radius-17" type="button"
                                    onclick="location.href = '{{ route('revenues.index') }}';">
                                    <div class="row align-items-center">
                                        <div class="col text-left w-100">
                                            <h2 class="font-bold fs-18 green">{{ amountFormat($last_month_revenue) }}</h2>
                                            <span class="fs-14"> Last Month Earning </span>
                                        </div>
                                        <div class="col-auto flex-shrink-0">
                                            <span class="icon-span"> <i class="fa fa-money fa-3x"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-2 mt-2">
                                <div class="h-100 widget white-bg no-padding mt-0 stat-box-shadow radius-17"
                                    type="button" onclick="location.href = '{{ route('revenues.index') }}';">
                                    <div class="p-m">
                                        <div class="row align-items-center">
                                            <div class="col-9 text-left">
                                                <h3 class="font-bold no-margins fs-18 green">Total Earning </h3>
                                                <h1 class="m-xs fs-14">{{ amountFormat($total_revenue) }}</h1>
                                            </div>
                                            <div class="col-3">
                                                <span class="icon-span ml-auto"><i class="fa fa-line-chart fa-3x"
                                                        aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="ibox recent-updates shadow">
                        <div class="ibox-title">
                            <h5>Current Active Staff Member/Manager</h5>
                            <div class="ibox-tools"></div>
                        </div>
                        <div class="ibox-content">
                            <div>
                                <div class="feed-activity-list only-admin">
                                    @if ($currently_working_users->count() > 0)
                                        @foreach ($currently_working_users as $key => $value)
                                            <div class="feed-element">
                                                @if ($value->id)
                                                    <a class="float-left" href="{{ route('users.show', $value->id) }}">
                                                        <img alt="image" class="rounded-circle"
                                                            src="{{ getUserImage($value->id) }}">
                                                    </a>
                                                    <div class="media-body ">
                                                        @if (isset($value->care_home->name))
                                                            <a target="_blank"
                                                                href="{{ route('homes.show', $value->care_home->id) }}"
                                                                class="noti-username">{{ ucfirst($value->care_home->name) }}
                                                            </a>
                                                        @endif
                                                        staff
                                                        @if ($value->role_id == 4 && isset($value->care_home->name))
                                                            member
                                                        @else
                                                            manager
                                                        @endif
                                                        <a target="_blank" href="{{ route('users.show', $value->id) }}"
                                                            class="noti-username">{{ ucfirst($value->name) }} </a>
                                                        is active now.<br>
                                                        @if ($value->shift_id)
                                                            <small class="text-muted">{{ $value->shift_name }}</small>
                                                        @endif

                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="feed-element text-center">
                                            <p>No Active Staff Member/Manager Found</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @endif

    {{-- @if ($care_homes_data->count() > 0 && Auth::user()->role_id == 1) --}}
    @if (Auth::user()->role_id == 1)
        <div class="row mt-3 mb-3">
            <div class="col-lg-6 gg">
                <div class="animated fadeInRight pt-0">
                    <div class="row">
                        <div class="col-lg-6 mb-2 mt-2">
                            <div class="h-100 widget style1 white-bg mt-0 stat-box-shadow radius-17" type="button"
                                onclick="location.href = '{{ route('homes.index') }}';">
                                <div class="row align-items-center">
                                    <div class="col text-left w-100">
                                        <h2 class="font-bold fs-18 green">{{ $total_nursing_homes }}</h2>
                                        <span class="fs-14"> Nursing Homes </span>
                                    </div>
                                    <div class="col-auto flex-shrink-0">
                                        <span class="icon-span"><i class="fa fa-hospital-o fa-3x"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2 mt-2">
                            <div class="h-100 widget style1 white-bg mt-0 stat-box-shadow radius-17" type="button"
                                onclick="location.href = '{{ route('users.index') }}';">
                                <div class="row align-items-center">
                                    <div class="col text-left w-100">
                                        <h2 class="font-bold fs-18 green">{{ $total_regisetered_users }}</h2>
                                        <span class="fs-14"> Users </span>
                                    </div>
                                    <div class="col-auto flex-shrink-0">
                                        <span class="icon-span"> <i class="fa fa-user-friends fa-3x"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2 mt-2">
                            <div class="widget style1 white-bg stat-box-shadow radius-17" type="button"
                                onclick="location.href = '{{ route('revenues.index') }}';">
                                <div class="row align-items-center">
                                    <div class="col text-left w-100">
                                        <h2 class="font-bold fs-18 green">{{ amountFormat($last_week_revenue) }}</h2>
                                        <span class="fs-14"> Last Week Earning </span>
                                    </div>
                                    <div class="col-auto flex-shrink-0">
                                        <span class="icon-span"> <i class="fa fa-3x fa-money"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2 mt-2">
                            <div class="widget style1 white-bg stat-box-shadow radius-17" type="button"
                                onclick="location.href = '{{ route('revenues.index') }}';">
                                <div class="row align-items-center">
                                    <div class="col text-left w-100">
                                        <h2 class="font-bold fs-18 green">{{ amountFormat($last_month_revenue) }}</h2>
                                        <span class="fs-14"> Last Month Earning </span>
                                    </div>
                                    <div class="col-auto flex-shrink-0">
                                        <span class="icon-span"> <i class="fa fa-3x fa-money"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2 mt-2">
                            <div class="h-100 widget white-bg no-padding mt-0 stat-box-shadow radius-17" type="button"
                                onclick="location.href = '{{ route('revenues.index') }}';">
                                <div class="p-m">
                                    <div class="row align-items-center">
                                        <div class="col-9 text-left">
                                            <h3 class="font-bold no-margins fs-18 green">Total Earning </h3>
                                            <h1 class="m-xs fs-14">{{ amountFormat($total_revenue) }}</h1>
                                        </div>
                                        <div class="col-3">
                                            <span class="icon-span ml-auto"><i class="fa fa-line-chart fa-3x"
                                                    aria-hidden="true"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox recent-updates shadow">
                    <div class="ibox-title">
                        <h5>Feeds</h5>
                        <div class="ibox-tools"></div>
                    </div>
                    <div class="ibox-content">
                        <div>
                            <div class="feed-activity-list super-adm">
                                @if ($notifications->count() > 0)
                                    @foreach ($notifications as $notification)
                                        <div class="feed-element">
                                            @if (
                                                $notification->item_type != 'appointment_update' &&
                                                    $notification->item_type != 'appointment_add' &&
                                                    $notification->patient_id > 0)
                                                <a class="float-left"
                                                    href="{{ route('patients.show', $notification->patient_id) }}">
                                                    <img alt="image" class="rounded-circle"
                                                        src="{{ getPatientImage($notification->patient_id) }}">
                                                </a>
                                            @endif
                                            @if ($notification->item_type != 'patient_incident_added' && $notification->staff_id > 0)
                                                <a class="float-left"
                                                    href="{{ route('users.show', $notification->staff_id) }}">
                                                    <img alt="image" class="rounded-circle"
                                                        src="{{ getUserImage($notification->staff_id) }}">
                                                </a>
                                            @endif
                                            <div class="media-body ">
                                                <!--small class="float-right">{{ $notification->created_at->diffForHumans() }}</small-->
                                                {!! $notification->message !!}. <br>
                                                <small
                                                    class="text-muted">{{ date('l', strtotime($notification->created_at)) }}
                                                    {{ date('h:i a', strtotime($notification->created_at)) }} -
                                                    {{ date('m.d.Y', strtotime($notification->created_at)) }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="feed-element text-center">
                                        <p>No Feeds Found</p>
                                    </div>
                                @endif
                            </div>
                            <a class="btn btn-primary btn-block m-t" href="{{ route('notifications.index') }}"><i
                                    class="fa fa-arrow-down"></i> View All</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pb-5">
            <div class="col-lg-12 pb-4">
                <div id="map" class="map-div rounded"></div>
            </div>
        </div>
    @endif

@endsection
@section('script')
    <script src="https://kit.fontawesome.com/66517fe47a.js" crossorigin="anonymous"></script>

    @if ($care_homes_data->count() > 0 && Auth::user()->role_id == 1)
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                var baseURL = "{{ url('/') }}";
                let map = new L.map('map', {
                    center: [47.116386, -101.299591],
                    zoom: 2 // Adjust the zoom level as needed
                });
                let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
                map.addLayer(layer);

                // Initialize the marker cluster group
                let markersCluster = L.markerClusterGroup();

                let markers = [
                    @foreach ($care_homes_data as $key => $value)
                        {
                            coordinates: [{{ $value->location_lat }}, {{ $value->location_long }}],
                            name: '{{ $value->name }}',
                            img: '{{ !empty($value->image) ? url('/') . '/' . $value->image : url('/') . '/public/assets/img/nursing-icon-green.png' }}',
                            url: '{{ route('homes.show', $value->id) }}',
                            createdBy: '{{ getUserName($value->created_by) }}'
                        },
                    @endforeach
                ];
                // console.log('markers', markers);

                // Add markers to the cluster group
                markers.forEach(markerData => {
                    let marker = L.marker(markerData.coordinates);
                    marker.bindPopup(createPopup(markerData));

                    // Add the marker to the cluster group
                    markersCluster.addLayer(marker);
                });

                // Add the marker cluster group to the map
                map.addLayer(markersCluster);

                function createPopup(markerData) {
                    return '<div>' +
                        '<a href="' + markerData.url + '" target="_blank"><h2 class="fs-14 mt-0 mb-0">' + markerData
                        .name + '</h2></a>' +
                        '<p class="mt-0 mb-1">' + markerData.createdBy + '</p>' +
                        '<img src="' + markerData.img + '" width="100%" style="max-width: 165px;">' +
                        '</div>';
                }
            });


            // let markers = [
            //     @foreach ($care_homes_data as $key => $value)
            //         {
            //             coordinates: [{{ $value->location_lat }}, {{ $value->location_long }}],
            //             name: '{{ $value->name }}',
            //             img: '{{ !empty($value->image) ? url('/') . '/' . $value->image : url('/') . '/public/assets/img/nursing-icon-green.png' }}',
            //             url: '{{ route('homes.show', $value->id) }}',
            //             createdBy: '{{ getUserName($value->created_by) }}'
            //         },
            //     @endforeach
            //     // { coordinates: [47.116386, -101.299591]},
            //     // { coordinates: [48.8583736, 2.2922926]}, // Example of another marker
            //     // Add more markers as needed
            // ];
            // console.log('markers',markers)

            // // When map is loaded, set default markers
            // map.whenReady(function(event) {
            //     markers.forEach(markerData => {
            //         //console.log('markers',markerData)
            //         let marker = L.marker(markerData.coordinates, {
            //             draggable: markerData.draggable
            //         });

            //         marker.on('dragend', function(event) {
            //             var marker = event.target;
            //             var position = marker.getLatLng();
            //             marker.setLatLng(new L.LatLng(position.lat, position.lng), {
            //                 draggable: true
            //             });
            //             map.panTo(new L.LatLng(position.lat, position.lng));
            //         });
            //         // Unbind any existing click handlers
            //         //marker.off('click');
            //         // marker.on('click', createClickHandler(markerData));
            //         marker.on('click', function(event) {
            //             createPopup(markerData, event
            //             .latlng); // Create popup when marker is clicked
            //         });
            //         map.addLayer(marker);
            //     });
            // });

            // function createPopup(markerData, latlng) {
            //     var popupContent = '<div>' +
            //         '<a href="' + markerData.url + '" target="_blank"><h2 class="fs-14 mt-0 mb-0">' + markerData
            //         .name + '</h2></a>' +
            //         '<p class="mt-0 mb-1">' + markerData.createdBy + '</p>' +
            //         '<img src="' + markerData.img + '" width="100%" style="max-width: 165px;">' +
            //         '</div>';

            //     // Create a popup and bind it to the marker
            //     var popup = L.popup()
            //         .setLatLng(latlng)
            //         .setContent(popupContent)
            //         .openOn(map);

            //     // Close the popup when the close button is clicked
            //     popup.on('remove', function() {
            //         map.removeLayer(popup);
            //     });
            // }

            // function createClickHandler(markerData) {
            //     //var imgpath = baseURL+'/'+markerData.img;
            //     return function(event) {
            //         var popupContent = '<div>' +
            //             '<a href="' + markerData.url + '" target="_blank"><h2 class="fs-14 mt-0 mb-0">' +
            //             markerData
            //             .name + '</h2></a>' +
            //             '<p class="mt-0 mb-1">' + markerData.createdBy + '</p>' +
            //             '<img src="' + markerData.img +
            //             '" width="100%" style="max-width: 165px;">' +
            //             '</div>';

            //         this.bindPopup(popupContent).openPopup();
            //     };
            // }
        </script>
    @endif
@endsection
