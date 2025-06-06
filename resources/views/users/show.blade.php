@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                @php
                    $previousUrl = URL::previous();
                    $path = parse_url($previousUrl, PHP_URL_PATH);
                    $pathInArray = explode('/', $path);
                @endphp
                @if (in_array('homes', $pathInArray))
                    <li class="breadcrumb-item">
                        <a href="{{ url()->previous() }}#tab2">Staff List</a>
                    </li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ url()->previous() }}">Users</a>
                    </li>
                @endif
                <li class="breadcrumb-item active">
                    <strong>View User Details</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox product-detail border rounded shadow">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-12">
                                <a class="btn btn-white btn-sm" type="button"
                                    href="{{ createCancelUrl(route('users.index')) }}"><i
                                        class="fa fa-arrow-circle-o-left"></i> Back</a>
                                <div class="hr-line-dashed"></div>
                            </div>

                            {{-- <div class="align-items-center col-md-6 d-flex flex-column flex-md-row">
									<div class="mr-4">
										@if ($user->role_id == 2)
											<div class="profile-image navy-bg p-lg text-center" style="width: 200px;">
												<img src="{{ asset('assets/img/care-home-dummy.jpg') }}" class="m-b-md" alt="care-home">
											</div>
										@else
											<div class="profile-image navy-bg p-lg text-center" style="width: 200px;">
												<img src="{{ $user->care_home ? $user->care_home->image_path : '' }}" class="m-b-md" alt="care-home">
											</div>
										@endif
									</div>
                                    <div class="">
										@if ($user->role_id != 2)
											<h4>Care Home Name: {{ $user->care_home ? $user->care_home->name : '-' }}</h4>
										@endif
										<h4>Name: {{ $user->name }}</h4>
										<h4>Role: {{ $user->role_name ? $user->role_name->name : '-' }}</h4>
										<h4>Email: {{ $user->email }}</h4>
										<h4>Phone No: {{ $user->phone_number }}</h4>
										@if ($user->role_id == 4)
											<h4>shift: {{ $user->shift_id > 0 ? getWorkShiftName($user->shift_id) : '-' }}</h4>
										@endif
									</div>
                                </div> --}}

                            <div class="col-lg-6 col-md-12">
                                @if ($user->role_id == 2)
                                    <h2 class="font-bold fs-18 mb-4 text-body">Care Home admin Detail</h2>
                                @elseif($user->role_id == 1)
                                    <h2 class="font-bold fs-18 mb-4 text-body">Super admin Detail</h2>
                                @else
                                    <h2 class="font-bold fs-18 mb-4 text-body">Staff Detail</h2>
                                @endif
                                <div class="ch-detail d-flex flex-wrap flex-sm-nowrap">
                                    <div class="mb-2 mr-4">
                                        <div class="position-relative ch-img">
                                            <!-- @if ($user->role_id == 4)
												<img src="{{ asset('assets/img/staff-pic.png') }}" alt="staff" class="img-fluid rounded-lg">
											@elseif($user->role_id == 3)
												<img src="{{ asset('assets/img/manager-pic.png') }}" alt="staff" class="img-fluid rounded-lg">
											@elseif($user->role_id == 2)
												<img src="{{ asset('assets/img/manager-pic.png') }}" alt="staff" class="img-fluid rounded-lg">
    										@endif -->
                                            <img src="{{ $user->image_path }}" alt="profile" class="img-fluid rounded-lg">
                                            <div
                                                class="position-absolute {{ $user->status == 1 && $user->deleted_at == null ? 'ch-online' : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h2 class="font-bold text-body fs-16">{{ $user->name }}</h2>
                                        <div class="mb-4 ch-info">
                                            <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                    class="fa fa-mobile-phone fs-18 mr-1"></i>
                                                {{ $user->phone_number }}</a>
                                            <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                    class="fa fa-envelope  mr-1"></i> {{ $user->email }} </a>
                                            <div class="d-flex">
                                                <div class="align-items-center d-flex font-bold  mb-2 mr-4 text-muted">
                                                    <i class="fa fa-user-circle  mr-1"></i>
                                                    {{ $user->role_name ? $user->role_name->name : '-' }}
                                                </div>
                                                @if ($user->role_id == 4)
                                                    <div class="align-items-center d-flex font-bold  mb-2 mr-4 text-muted">
                                                        <i class="fa fa-clock-o  mr-1"></i>
                                                        {{ $user->shift_id > 0 ? getWorkShiftName($user->shift_id) : '-' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($user->role_id != 2 && $user->role_id != 1)
                                <div class="col-lg-6 col-md-12">
                                    <h2 class="font-bold fs-18 mb-4 text-body">Connected with Care Home</h2>
                                    <div class="ch-detail d-flex flex-wrap flex-sm-nowrap">
                                        <div class="mb-2 mr-4">
                                            <div class="position-relative ch-img">
                                                <a href="{{ route('homes.show', $user->care_home->id) }}">
                                                    <img src="{{ $user->care_home->image_path }}" alt="care-home"
                                                        class="img-fluid rounded-lg">
                                                    <div class="position-absolute ch-online">
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <a href="{{ route('homes.show', $user->care_home->id) }}">
                                                <h2 class="font-bold text-body fs-16">
                                                    {{ $user->care_home ? $user->care_home->name : '-' }} </h2>
                                            </a>
                                            <div class="d-flex flex-wrap mb-4 ch-info">
                                                <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                        class="fa fa-mobile-phone fs-18 mr-1"></i>
                                                    {{ $user->care_home ? $user->care_home->contact_no : '-' }}</a>
                                                <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                        class="fa fa-user-circle  mr-1"></i>
                                                    {{ count($user->care_home->staff_users) }} Staff Members |
                                                    {{ count($user->care_home->patients) }} Current Patients</a>
                                                <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                        class="fa fa-envelope  mr-1"></i>
                                                    {{ $user->care_home ? $user->care_home->email : '-' }}</a>
                                                <a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i
                                                        class="fa fa-map-marker fs-14 mr-1"></i>

                                                    {{ $user->care_home ? $user->care_home->street . ', ' . $user->care_home->city . ', ' . $user->care_home->state . ', ' . $user->care_home->zip_code : '' }}
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if ($user->role_id != 2)
                        <div class="col-md-12 d-flex align-items-start flex-column flex-md-row bg-white">
                            <ul class="nav ch-tabs">
                                <li class="nav-item">
                                    <a class="font-bold  py-3 px-2 mr-4 nav-link active patient-tab"
                                        data-tab-name="activity" id="tab1-tab" data-toggle="tab" href="#tab1">Assigned
                                        Tasks List</a>
                                </li>
                                <li class="nav-item">
                                    <a class="font-bold  py-3 px-2 mr-4 nav-link patient-tab" data-tab-name="document"
                                        id="tab2-tab" data-toggle="tab" href="#tab2">Document list</a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            @if ($user->role_id != 2)
                <div class="col-lg-12">
                    <div class="ibox-content">

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab1">
                                @include('users.partials.assigned-tasks-to-user')
                            </div>
                            <div class="tab-pane fade" id="tab2">
                                @include('users.partials.document-list')
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            function showTabFromHash() {
                var hash = window.location.hash;
                if (hash) {
                    $('.nav-link[href="' + hash + '"]').tab('show');
                }
            }

            // Show the correct tab when the page loads
            showTabFromHash();

            // Show the correct tab when the hash changes (e.g., user clicks a link)
            $(window).on('hashchange', function() {
                showTabFromHash();
            });

            // Update the URL hash when a tab is clicked
            $('.nav-link').on('click', function() {
                window.location.hash = $(this).attr('href');
            });
        })
    </script>
@endsection
