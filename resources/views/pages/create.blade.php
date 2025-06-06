@extends('layouts.admin')

@section('title', 'About Us')
@section('style')
	  <link href="{{ asset('assets/css/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
				 <li class="breadcrumb-item">
                    <a href="{{ route('pages.index') }}">Pages</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Create Page</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox ">
					<!-- <div class="ibox-title d-flex">
						<h5>Add Care Homes </h5>
					</div> -->
					<div class="ibox-content">
						<div class="ibox-content">
                            <form method="POST" role="form" action="{{ route('pages.store') }}" id="pageForm">
								@csrf
								<div class="form-group row @error('page_id') has-error @enderror">
									<label class="col-lg-2 col-form-label">Page</label>
									<div class="col-lg-10">
										<select class="form-control m-b" name="page_id" required>
											<option value="">Select Page</option>
											@foreach($content_pages as $key => $value)
												@if(!in_array($key, $existing_pages))
													<option value="{{ $key }}" {{ old('page_id') == $key ? 'selected' : ''}}>{{ $value }}</option>
												@endif
											@endforeach
										</select>
										@error('page_id')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
                                <div class="form-group row @error('content') has-error @enderror">
                                    <label class="col-lg-2 col-form-label">Content</label>
                                    <div class="col-lg-10">
                                        <textarea name="content" class="form-control" id="page_editor" rows="4" required></textarea>
										@error('content')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
                                    </div>
                                </div>
								<div class="form-group row @error('status') has-error @enderror">
									<label class="col-lg-2 col-form-label">Status</label>
									<div class="col-lg-10">
										@php
											$pre_seleted = 1;
											if(!is_null(old('status')) && old('status') == 0){
												$pre_seleted = 0;
											}
										@endphp
										<select class="form-control m-b" name="status" required>
											<option value="">Select status</option>
											<option value="1" {{ $pre_seleted == 1 ? 'selected' : ''}}>Active</option>
											<option value="0" {{ $pre_seleted == 0 ? 'selected' : '' }}>In-Active</option>
										</select>
										@error('status')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group row">
                                    <div class="col-md-12 text-right">
                                        <a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('pages.index')) }}">Cancel</a>
										<button class="btn btn-sm btn-primary" type="submit" id="page_Form">Save</button>
                                    </div>
                                </div>
                            </form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('script')
	<script src="{{ asset('assets/js/plugins/summernote/summernote-bs4.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
			 $('#page_editor').summernote();
		});
    </script>
@endsection