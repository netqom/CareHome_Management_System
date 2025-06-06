@extends('layouts.admin')

@section('title', 'HomePage Content')

@section('content') 
   
    <div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>Pages</strong>
				</li>
			</ol>
		</div>
	</div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox shadow rounded border">
					<div class="ibox-title">
						<h5>Pages List </h5>
						@if($pages->count() < 2)
							<div class="ibox-tools">
								<a href="{{ route('pages.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Page</a>
							</div>
						@endif
					</div>
                    <div class="ibox-content relative">
                        <div class="table-responsive">
                            <table class="table table-striped" id="page_list">
                                <thead>
                                <tr>
                                    {{-- <th><input type="checkbox" class="i-checks" name="input[]"></th> --}}
                                    <th>S.No</th>
                                    <th>Page</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
									@if($pages->count() > 0)
										@foreach($pages as $key => $page)
											<tr>
												<td>{{ $key + 1 }}</td>
												<td>{{ $content_pages[$page->page_id] }}</td>
												<td>
													@php
														$cls_name = 'label-primary';
														$text = 'Active';
														if ($page->status == 0) {
															$text = 'Inactive';
															$cls_name = 'label-warning';
														}
													@endphp
													<span class="label {{ $cls_name }}"> {{ $text }}</span>
												</td>
												<td><a href="{{ route('pages.edit', $page->id) }}" class="btn-warning btn btn-sm"><i class="fa fa-edit"></i></a></td>
											</tr>
										@endforeach
									@else
										<tr>
											<td class="text-warning text-center" colspan="5">No Data Found</td>
										</tr>
									@endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection