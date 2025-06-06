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
                    <a href="{{ url()->previous() }}">Patient</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>{{ $id == 0 ? 'Create' : 'Update' }} Patient Document</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 dark-bg">
                <form method="POST" role="form"
                    action="{{ route('save-patients-document', ['patient_id' => $patient_id, 'id' => $id]) }}"
                    id="patient_document_Form" enctype="multipart/form-data">
                    @csrf
                    <div class="ibox">
                        <div class="ibox-content ff shadow border rounded">
                            <div class="ibox-content">
                                @php
                                    $document_types = config('const.patient_document_type');
                                    $remark = 'd-none';
                                    $document_type = 1;
                                    if ($document && $document->type == 2) {
                                        $remark = '';
                                        $document_type = 2;
                                    }

                                    $filename = isset($document->document_path)
                                        ? basename($document->document_path)
                                        : '';

                                @endphp

                                <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient_id }}">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="name" for="fileInput">Document *</label>
                                            <input type="file" name="doc[0][file]" class="form-control rounded"
                                                @if ($id == 0) required @endif id="fileInput">
                                            <small id="fileNameDisplay" class="text-white">{{ $filename }}</small>
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-4">
										<div class="form-group" id="data_1">
											<label for="date">Date *</label>
											<div class="input-group date">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" name="doc[0][date]" id="date" class="form-control" value="{{ $document ? $document->document_date : date('Y-m-d') }}" required>
											</div>
										</div>
									</div> --}}
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="name">Title *</label>
                                            <input type="text" name="doc[0][document_name]" id="name"
                                                placeholder="Document Title" value="{{ $document ? $document->name : '' }}"
                                                class="form-control rounded" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="dose">Type *</label>
                                            <select class="form-control m-b" name="doc[0][type]" id="type_id" required>
                                                @foreach ($document_types as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if ($key == $document_type) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-4 {{ $remark }}" id="other_remark_div">
                                        <div class="form-group">
                                            <label for="other_remark">Other Remark *</label>
                                            <input type="text" class="form-control" name="doc[0][remark]"
                                                value="{{ $document ? $document->remark : '' }}" id="other_remark"
                                                placeholder="Other Remark" class="form-control rounded">
                                        </div>
                                    </div>
                                    @php
                                        // $pre_staff_array=[];
                                        // $pre_staff_id = $document ? json_decode($document->staff_ids,true) : '';
                                        // if($pre_staff_id!=null)
                                        // {
                                        // 	if(is_array($pre_staff_id))
                                        // 	{
                                        // 		$pre_staff_array=$pre_staff_id;
                                        // 	}else{
                                        // 		$pre_staff_array[]=$pre_staff_id;
                                        // 	}
                                        // }
                                    @endphp
                                    <div class="col-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="dose">Display to Staff</label>
                                            <select class="form-control m-b" name="doc[0][display_to_staff]"
                                                id="display_to_staff">
                                                <option value="">Select Options</option>
                                                <option value="1"
                                                    {{ $document && $document->display_to_staff == 1 ? 'selected' : '' }}>
                                                    Yes</option>
                                                <option value="0"
                                                    {{ $document && $document->display_to_staff == 0 ? 'selected' : '' }}>
                                                    No</option>
                                            </select>
                                        </div>
                                        {{-- <div class="form-group select-2-full">
											<label for="dose">Select Staff</label>
											<select class="form-control m-b select_staffs" name="doc[0][staff_ids][]" id="staff_ids" multiple="multiple">
												@foreach ($staffs as $staffKey => $staff)
												<option value="{{ $staffKey }}"  @if (in_array($staffKey, $pre_staff_array)) selected @endif>{{ $staff }}
												</option>
											@endforeach
											</select>
										</div> --}}
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a class="btn btn-white btn-sm" type="button"
                                href="{{ createCancelUrl(route('patients.show', $patient_id)) }}">Cancel</a>
                            <button class="btn btn-sm btn-primary" type="submit" id="patientDocumentForm">Save</button>
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
        $(document).ready(function() {

            var existingFileName = "{{ $filename }}";
            if (existingFileName) {
                $('#fileNameDisplay').text(existingFileName); // Display the file name in the small tag
            }

            // Optional: Update the displayed filename when a new file is selected
            $('#fileInput').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $('#fileNameDisplay').text(fileName);
            });

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

            $('#type_id').change(function() {
                if ($(this).val() == 2) {
                    $('#other_remark_div').removeClass('d-none');
                    $('#other_remark').attr('required', true);
                } else {
                    $('#other_remark_div').addClass('d-none');
                    $('#other_remark').attr('required', false);
                }
            })
        })
    </script>
@endsection
