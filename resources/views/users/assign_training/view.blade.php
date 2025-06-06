@extends('layouts.admin')

@section('title', 'View Assigned Training Detail')

@section('content')
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="{{ route('dashboard') }}">Home</a>
				</li>
				<li class="breadcrumb-item">
					<a href="{{ route('assign-training-to-staff.index', ['home_id' => $home_id,'staff_id' => $assined_training->user_id]) }}">Assigned Training</a>
				</li>
				<li class="breadcrumb-item active">
					<strong>View Assigned Training</strong>
				</li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox product-detail">
					<div class="ibox-content border rounded shadow">
						<div class="row">
							<div class="col-md-12">
								<a class="btn btn-white btn-sm" type="button" href="{{ route('assign-training-to-staff.index', ['home_id' => $home_id,'staff_id' => $assined_training->user_id]) }}"><i class="fa fa-arrow-circle-o-left"></i> Back</a>
								@if($assined_training->course_status!=5)
								<a class="btn btn-white btn-sm" type="button" href="javascript:void(0)" id="complete-training-btn"><i class="fa fa-tick"></i>Completed Training?</a>
								@else
								<a class="btn btn-white btn-sm" type="button" href="javascript:void(0)" ><i class="fa fa-tick"></i>Completed</a>
								@endif
								<div class="hr-line-dashed"></div>
							</div>	

							<div class="col-md-6">
								<h2 class="font-bold fs-18 mb-4 text-body">Assigned Training Detail</h2>
								<div class="ch-detail d-flex flex-wrap flex-sm-nowrap">
									<div class="mb-2 mr-4">
										<div class="position-relative ch-img text-center">
											
											<img src="{{ $assined_training->certificate_url }}" alt="certificate" class="img-fluid rounded-lg w-auto">
											<div class="position-absolute ch-online">
											</div>
										</div>
									</div>
									<div class="flex-grow-1">
										<h2 class="font-bold text-body fs-16">{{ $assined_training->course_detail->title }} <i class="fa fa-check-circle fs-18 text-navy"></i></h2>
										<div class="mb-4 ch-info">
											<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i class="fa fa-calendar-o fs-18 mr-1"></i> Start Date: {{$assined_training->start_date}}</a>
											<a href="#" class="align-items-center d-flex font-bold  mb-2 mr-4"><i class="fa fa-calendar-o mr-1"></i> Due Date: {{$assined_training->end_date}}</a>
											<div class="d-flex">
												<div class="align-items-center d-flex font-bold  mb-2 mr-4 text-muted">
													<i class="fa fa-check-circle  mr-1"></i> 
													@foreach($course_status as $key => $value)
														@if($assined_training->course_status === $key)
															{{$value}}
														@endif
													@endforeach
												</div>
											</div>
										</div>											
									</div>
								</div>
							</div>
							
							@if($assined_training->course_status == 3 && $assined_training->certificate == null)
								<div class="col-md-6">
									<h2 class="font-bold fs-18 mb-4 text-body">Upload Certificate</h2>
									<div class="ch-detail d-flex flex-wrap flex-sm-nowrap">
										<form class="w-100" method="POST" role="form" action="{{ route('upload-training-certificate') }}" id="uploadCertificate_Form" enctype="multipart/form-data">
											@csrf
											<input type="hidden" name="staff_id" value={{$assined_training->user_id}}>
											<input type="hidden" name="home_id" value={{$home_id}}>
											<input type="hidden" name="id" value={{$id}}>

											<div class="upload-certi mb-3">
												<img src="{{ asset('assets/img/certificate.png') }}" class="img-fluid" id="existing_certificate_image">
												<input type="file" class="form-control" name="certificate" id="certificate_image" accept="image/*"  style="display:none" onchange="handleFiles(this)">
											</div>
											
											
											<div class="form-group row">
												<div class="col-md-12 text-center">
													<button type="button" class="btn btn-primary btn-sm" id="upload_certificate_image"><i class="fa fa-pencil" aria-hidden="true"></i> Upload</button> 
													<button class="btn btn-sm btn-primary" type="submit" id="uploadCertificateForm">Save</button>
												</div>
											</div>
										</form>
									</div>									
								</div>
							@else
								<div class="col-md-6">
									<h2 class="font-bold fs-18 mb-4 text-body">Download Certificate</h2>
									<div class="ch-detail d-flex flex-wrap flex-sm-nowrap">
										<div class="download-certi-img position-relative text-center">											
											<img src="{{ asset('assets/img/certificate.png') }} " alt="certificate" class="h-100 img-fluid mx-auto rounded-lg">
											<a data-toggle="tooltip" data-placement="top" title="Download Certificate" href="{{$assined_training->certificate_url}}" download="{{$assined_training->certificate_url}}">
												<div class="position-absolute download-certi-icon">
													<i class="fa fa-download text-box"></i>
												</div>
											</a>
										</div>
									</div>									
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
			//change upload certificate image start
			$('#upload_certificate_image').click(function(){
				$('#certificate_image').trigger('click');
			})
			$('#complete-training-btn').click(function(){
				Swal.fire({
                        text: "This Training is Completed?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#f39c12",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No"
                    }).then((result) => {
						if (result.isConfirmed) {
            // Perform the AJAX request here
					$.ajax({
						url: '{{ route("complete-training-by-admin", ["id" => $assined_training->id]) }}', // Your endpoint here
						type: 'POST', // Or 'GET', depending on your endpoint
						data: {
							// Your data here
							training_id: '{{$assined_training->id}}', // Example data
							_token: '{{ csrf_token() }}' // Include CSRF token if required
						},
						success: function(response) {
							Swal.fire({
								text: "Training completed successfully!",
								icon: "success",
								confirmButtonText: "OK"
							}).then(() => {
								// Optional: Reload the page or redirect
								location.reload();
							});
						},
						error: function(xhr, status, error) {
							Swal.fire({
								text: "An error occurred. Please try again.",
								icon: "error",
								confirmButtonText: "OK"
							});
						}
					});
				}
                       
                    });
			})
			//change upload certificate image end
		})

		//preview upload certificate image on select Start
		const handleFiles = (input) => {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#existing_certificate_image').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}

		//validate Upload Training Certificate form
		$('#uploadCertificateForm').on('click',function(e){
			var fileInput = $('#certificate_image');
            var errorMessage = '';
            if (!fileInput || !fileInput[0].files || fileInput[0].files.length === 0) {
                errorMessage = 'Please select a certificate image.';
            } else {
                var file = fileInput[0].files[0];
                var fileType = file.type;
                if (fileType.split('/')[0] !== 'image') {
                    errorMessage = 'Please select an image file.';
                }
            }
            if (errorMessage) {
                toastAlert("error", errorMessage);
                event.preventDefault(); // Prevent form submission
            }

		})
    </script>
@endsection