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
                    <strong>Edit Page</strong>
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
						<div class="ibox-content shadow border rounded">
							<form method="POST" role="form" action="{{ route('pages.update', $page->id) }}" id="pageForm">
								<div class="row">
							@csrf
								@method("PATCH")
								<div class="form-group col-sm-6 @error('page_id') has-error @enderror">
									<label class="col-form-label">Page</label>
									<div class="">
										<select class="form-control " name="page_id" required>
											@foreach($content_pages as $key => $value)
												@if($page->page_id == $key)
													<option value="{{ $key }}" {{ $page->page_id == $key ? 'selected' : ''}}>{{ $value }}</option>
												@endif
											@endforeach
										</select>
										@error('page_id')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
                                
								<div class="form-group col-sm-6 @error('status') has-error @enderror">
									<label class="col-form-label">Status</label>
									<div class="">
										<select class="form-control " name="status" required>
											<option value="">Select status</option>
											<option value="1" {{ $page->status == 1 ? 'selected' : ''}}>Active</option>
											<option value="0" {{ $page->status == 0 ? 'selected' : '' }}>In-Active</option>
										</select>
										@error('status')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="form-group col-12 @error('content') has-error @enderror">
                                    <label class="col-form-label">Content</label>
                                    <div class="">
                                        <textarea name="content" class="form-control" id="page_editor" rows="4" required>{{ $page->content }}</textarea>
										@error('content')
											<span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
										@enderror
                                    </div>
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
@endsection
@section('script')
	<script src="{{ asset('assets/js/plugins/summernote/summernote-bs4.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
			 $('#page_editor').summernote();
		});
    </script>
@endsection