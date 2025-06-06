@extends('layouts.admin')

@section('title', 'Create/Update Patient Document')
@section('style')
	<link href="{{ asset('assets/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
@endsection
@section('content')
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item">
					<a href="{{ url()->previous() }}#tab7">Documents</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>{{$id == 0 ? 'Create' : 'Update'}} Document</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 dark-bg">
			<form method="POST" role="form" action="{{ route('save-home-document', ['home_id' => $home_id, 'id' => $id]) }}" id="home_document_Form" enctype="multipart/form-data">
			@csrf
				<div class="ibox ">
					<div class="ibox-content ff shadow border rounded">
						<div class="ibox-content">
							
								<input type="hidden" name="doc[0][home_id]" id="home_id" value="{{ $home_id }}">
								
								@php
								$staff_id = Request::query('staff_id'); // 'key' is the name of the query parameter

							    @endphp  
								<input type="hidden" name="doc[0][type]"  value="{{ ( Request::query('staff_id') && !empty(Request::query('staff_id')))? '2' : '1'}}">
								<input type="hidden" name="doc[0][staff_id]" id="staff_id" value="{{ Request::query('staff_id') }}">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="name">Title *</label>
											<input type="text" name="doc[0][name]" id="name" placeholder="Document Title" value="{{ $document ? $document->name : '' }}" class="form-control rounded required" required>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label for="name">Document *</label>
											<input type="file" name="doc[0][file]" class="form-control rounded valid_doc " @if($id == 0) required @endif>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="form-group">
											<label for="dose">Is Expiry Date Applicable *</label>
											<select class="form-control m-b required  is_expiry_applicable" name="doc[0][is_expiry_applicable]" id="is_expiry_applicable">
												<option value="">Select Options</option>
												<option value="1" {{ $document && $document->is_expiry_applicable==1? 'selected' : '' }}>Yes</option>
												<option value="0" {{ $document && $document->is_expiry_applicable==0? 'selected' : '' }}>No</option>
											</select>
										</div>
										
									</div>
									<div class="col-lg-6 is_expiry_field {{!$document || ($document && $document->is_expiry_applicable==0) ? 'd-none' : '' }}">
										<div class="form-group" id="data_1">
											<label for="date">Document Expiry Date * </label>
											<div class="input-group date">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" name="doc[0][expiry_date]" id="date" class="form-control expry_date_input bg-white" value="{{ $document ? $document->expiry_date : ''}}" readonly>
											</div>
										</div>
									</div>
									
									
									
										
									</div>
								</div>
							</div>
					</div>
					<div class="form-group row">
									<div class="col-md-12 text-right">
										<a class="btn btn-white btn-sm" type="button" href="{{ createCancelUrl(route('homes.show', $home_id)) }}">Cancel</a>
										<button class="btn btn-sm btn-primary" type="submit" id="hpmeDocumentForm">Save</button>
									</div>
								</div>
					</form>
				</div>
			</div>
		</div>
	
@endsection
@section('script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
	<script src="{{ asset('assets/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			
			$('.select_staffs').select2({
                placeholder: 'Select Staff',
            });

			var mem = $('#data_1 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
				format: "yyyy-mm-dd",
				todayHighlight: true
            });
			
			$('.is_expiry_applicabel').change(function(){
				if($(this).val() == 1){
					$('.expry_date_input').attr('required', true);
					$('.expry_date_input').addClass('required');
				}else{
					$('.expry_date_input').attr('required', false);
					$('.expry_date_input').removeClass('required');
				}
			})
		})
		$(document).on('change', '.is_expiry_applicable', function(){
			var data_check = $(this).val();
			if(data_check == '0'){
				$('.is_expiry_field').addClass('d-none');
			}else{
				$('.is_expiry_field').removeClass('d-none');
			}
		})
    </script>
@endsection