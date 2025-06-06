@extends('layouts.admin')

@section('title', 'Training Course')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('training-course-list') }}">Training Course</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Edit Course</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 dark-bg">
                <form method="POST" role="form" action="{{ route('save-training-course') }}" id="trainingCourse_Form">
                    @csrf
                    <div class="ibox ">
                        <div class="ibox-title d-flex">
                            <h5>Edit Course</h5>
                        </div>

                        <div class="ibox-content">

                            <div class="row">

                                <input type="hidden" name="id" value={{ $get_course_detail->id }}>

                                <div class="form-group col-12 col-md-12 col-lg-6  @error('home_id') has-error @enderror">
                                    <label class="col-form-label">Care Home</label>
                                    <div class="">
                                        <select class="form-control" name="home_id" id="homeId" required>
                                            <option value="">Select Care Home</option>
                                            @foreach ($care_home as $home)
                                                <option value="{{ $home->id }}"
                                                    {{ $get_course_detail->home_id == $home->id ? 'selected' : '' }}>
                                                    {{ $home->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('home_id')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-12 col-md-12 col-lg-6  @error('title') has-error @enderror">
                                    <label class="col-form-label">Title</label>
                                    <div class="">
                                        <input type="text" name="title" placeholder="Title" class="form-control"
                                            required autocomplete="off" value="{{ $get_course_detail->title }}">
                                        @error('title')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-12 col-lg-6">
                                    <label class="col-form-label">Duration</label>
                                    <div class="">
                                        <input type="number" name="duration" id="duration" placeholder="Duration"
                                            class="form-control integer_no" required autocomplete="off"
                                            value="{{ $get_course_detail->duration }}">
                                    </div>
                                </div>
								{{-- <div class="form-group col-12 col-md-6 col-lg-6" id="due-date-div">
									<label class=" col-form-label" for="start_date">Due Date</label>
									<div class=" input-group date due_date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input type="text" name="due_date" value="{{ date('m/d/Y', strtotime($get_course_detail->due_date)) }}"
											id="due_date" placeholder="Due Date" class="form-control" required>
									</div>
								</div> --}}

                                <div class="form-group col-12 col-md-12 col-lg-6  @error('status') has-error @enderror">
                                    <label class="col-form-label">Status</label>
                                    <div class="">
                                        <select class="form-control" name="status" required>
                                            <option value="">Select status</option>
                                            <option value="1" {{ $get_course_detail->status == 1 ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="0" {{ $get_course_detail->status == 0 ? 'selected' : '' }}>
                                                In-Active</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-12  @error('description') has-error @enderror">
                                    <label class="col-form-label">Description</label>
                                    <div class="">
                                        <textarea class="form-control" name="description" placeholder="Description" s="3" required autocomplete="off">{{ $get_course_detail->description }}</textarea>
                                        @error('description')
                                            <span class="text-danger text-left d-block"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="redirectURL"
                                value="{{ createCancelUrl(route('training-course-list')) }}">


                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a class="btn btn-white btn-sm" type="button"
                                href="{{ createCancelUrl(route('training-course-list')) }}">Cancel</a>
                            <button class="btn btn-sm btn-primary" type="submit" id="trainingCourseForm">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="{{ asset('assets/js/module/training-course.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

        })
    </script>
@endsection
