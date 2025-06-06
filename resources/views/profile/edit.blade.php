@extends('layouts.admin')

@section('title', 'Update Profile')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Profile</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
                <div class="ibox ">
                    <div class="shadow border rounded">
                        <div class="py-12">
                            <div class="mt-3 responsive-scroll-x">
                                <ul class="nav ch-tabs">
                                    <li class="nav-item">
                                        <a class="font-bold py-3 px-2 mr-2 nav-link patient-tab active"
                                            data-tab-name="profile" id="profile-tab" data-toggle="tab"
                                            href="#profile">Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="font-bold py-3 px-2 mr-2 nav-link patient-tab"
                                            data-tab-name="password-update" id="password-update-tab" data-toggle="tab"
                                            href="#password-update">Update Password</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content ">
                                <div class="tab-pane fade active show" id="profile">
                                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                        <div class="max-w-xl">
                                            @include('profile.partials.update-profile-information-form')
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="password-update">
                                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                        <div class="max-w-xl">
                                            @include('profile.partials.update-password-form')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
