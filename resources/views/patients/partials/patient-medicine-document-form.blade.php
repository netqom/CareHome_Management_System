<!-- HTML -->
<style>
    .btn-outline-navy {
        color: #1ab394;
        border-color: #1ab394;
    }

    .btn-outline-navy:hover {
        color: #fff;
        background-color: #1ab394;
        border-color: #1ab394;
    }

    .pointer-event {
        cursor: pointer;
    }
</style>

<div class="accordion shadow" id="accordionExample">

    <div class="align-items-center bg-primary d-flex px-2 py-2 pointer-event" data-toggle="collapse"
        data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <h4 class="m-0">Add Doctor Details</h4>
        <i class="fa fa-chevron-down ml-auto mr-2 "></i>
    </div>
    <div id="collapseOne" class="collapse border border-1 p-3" aria-labelledby="headingOne"
        data-parent="#accordionExample">
        <div class="row" id="doctor_div">
            <div class="col-lg-3 col-lg">
                <div class="form-group">
                    <label for="doctor_name">Name *</label>
                    <input type="text" name="doctors[0][name]" placeholder="Name" class="form-control rounded"
                        id="doc_name_0">
                </div>
            </div>
            <div class="col-lg-3 col-lg">
                <div class="form-group">
                    <label for="doctor_email">Email *</label>
                    <input type="email" id="doctor_email_0" name="doctors[0][email]" placeholder="Email"
                        class="form-control validate_email rounded">
                </div>
            </div>
            <div class="col-lg-3 col-lg">
                <div class="form-group">
                    <label for="doctor_phone_number">Phone No *</label>
                    <input type="tel" name="doctors[0][phone]" placeholder="Phone No." class="form-control rounded"
                        id="doc_phn_0">
                </div>
            </div>
            <div class="col-lg-3 col-lg">
                <div class="form-group">
                    <label for="doctor_address">Address *</label>
                    <input type="text" name="doctors[0][address]" placeholder="Address" class="form-control rounded"
                        id="doc_address_0">
                </div>
            </div>
            <div class="col-lg-3 col-lg">
                <div class="form-group">
                    <label for="doctor_role">Designation *</label>
                    <input type="text" name="doctors[0][role]" placeholder="Role" class="form-control rounded"
                        id="doc_desig_0">
                </div>
            </div>
            <div class="col-lg-3 col-lg">
                <div class="form-group">
                    <label for="doctor_role">Fax Number *</label>
                    <input type="text" name="doctors[0][fax_number]" placeholder="Fax Number"
                        class="form-control rounded valid_fax dynamic_fax_input" id="fax_number_0">
                </div>
            </div>
        </div>
        <div id="more_doctors_div">
        </div>
        <div class="row">
            <div class="col-lg-12 text-right">
                <button type="button" class="btn btn-outline-navy font-bold" id="add_doctor_fields"><i
                        class="fa fa-plus"></i> Add More Doctors</button>
            </div>
        </div>
    </div>
</div>

{{-- <div class="hr-line-dashed"></div>
<div class="accordion shadow" id="accordionExample2">
<div class="align-items-center bg-primary d-flex px-2 py-2 pointer-event" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
    <h4 class="m-0">Add Medicine Details</h4>
    <i class="fa fa-chevron-down ml-auto mr-2 "></i>
</div>
<div id="collapseTwo" class="collapse border border-1 p-3" aria-labelledby="headingOne" data-parent="#accordionExample2">
<div class="row" id="medicine_div">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="name">Name</label>
            <input  type="text" name="medicines[0][medicine_name]" id="name" placeholder="Medicine Name" class="form-control rounded ">
        </div>
    </div>
    @php
   $other_medicine_type_div = 'd-none';
    @endphp
    <div class="col-lg-3">
        <div class="form-group">
            <label for="time">Medication-Type</label>
            <select name="medicines[0][medicine_type]" id="medicine_type" class="form-control rounded">
                <option value="">Select</option>
                @foreach ($medicine_type as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3 {{ $other_medicine_type_div }}" id="other_medicine_type_div">
        <div class="form-group">
            <label for="time">Medication-Type Other</label>
            <input type="text" name="medicines[0][medicine_type_other]" id="medicine_type_other"  placeholder="Medication Type Other" class="form-control rounded">
        </div>
    </div>
    @php
   $other_time_div = 'd-none';
    @endphp
    <div class="col-lg-3">
        <div class="form-group select-2-full">
            <label for="time">Time</label>
            <select name="medicines[0][time_id][]" id="time_id" class="form-control rounded med_time_select w-100" multiple="multiple">
                <option value="">Select</option>
                @foreach ($medicine_time as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3 {{ $other_time_div }}" id="other_time_div">
        <div class="form-group">
            <label for="time">Other Time</label>
            <div class="input-group clockpicker" data-autoclose="true">
                <span class="input-group-addon">
                    <span class="fa fa-clock-o"></span>
                </span>
                <input type="text" class="form-control" name="medicines[0][time_other]" id="time_other" placeholder="Medicine Other TIme" class="form-control rounded">
            </div>
        </div>
    </div>
      @php
   $other_intake_method_div = 'd-none';
    @endphp
    <div class="col-lg-3">
        <div class="form-group">
            <label for="time">Intake Method</label>
            <select name="medicines[0][intake_method]" id="intake_method" class="form-control rounded">
                <option value="">Select</option>
                @foreach ($intake_method as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3 {{ $other_intake_method_div }}" id="other_intake_method_div">
        <div class="form-group">
            <label for="time">Other Intake Method</label>
            <input type="text" class="form-control" value="" name="medicines[0][other_intake_method]" id="other_intake_method" placeholder="Other Intake Method" class="form-control rounded">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="time">Dose</label>
            <input type="text" name="medicines[0][dose]" id="dose" placeholder="Dose" class="form-control rounded">
        </div>
    </div>
    @php
   $other_intake_supervised_by_div = 'd-none';
    @endphp
    <div class="col-lg-3">
        <div class="form-group">
            <label for="time">Supervise by</label>
            <select name="medicines[0][intake_supervised_by]" id="intake_supervised_by" class="form-control rounded">
                <option value="">Select</option>
                @foreach ($intake_guidedby as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3 {{ $other_intake_supervised_by_div }}" id="other_intake_supervised_by_div">
        <div class="form-group">
            <label for="time">Other Supervise by</label>
            <input type="text" class="form-control" name="medicines[0][intake_supervised_other]" id="intake_supervised_other" placeholder="Intake Supervised By" class="form-control rounded">
        </div>
    </div>  
</div>
<div id="more_medicines_div">
</div>
<div class="row">    
    <div class="col-lg-12 text-right"> 
        <button type="button" class="btn btn-outline-navy font-bold" id="add_medicine_fields"><i class="fa fa-plus"></i> Add More Medical Details</button> 
        </div>
</div>
        </div>
</div> --}}

<div class="hr-line-dashed"></div>
<div class="accordion shadow" id="accordionExample3">
    <div class="align-items-center bg-primary d-flex px-2 py-2 pointer-event" data-toggle="collapse"
        data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
        <h4 class="m-0">Add Document Details</h4>
        <i class="fa fa-chevron-down ml-auto mr-2 "></i>
    </div>
    <div id="collapseThree" class="collapse border border-1 p-3" aria-labelledby="headingOne"
        data-parent="#accordionExample3">
        <div class="row" id="document_div">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="name">Title *</label>
                    <input type="text" name="doc[0][document_name]" id="name_0" placeholder="Document Title"
                        class="form-control rounded ">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group" id="data_1">
                    <label for="date">Date *</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="doc[0][date]" id="date_0" class="form-control"
                            placeholder="Date">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="name">Document *</label>
                    <input type="file" name="doc[0][file]" class="form-control rounded" id="file_0">
                </div>
            </div>
            @php
                $document_types = config('const.patient_document_type');
                $remark = 'd-none';
                $document_type = 1;
            @endphp
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="dose">Type</label>
                    <select class="form-control m-b" name="doc[0][type]" id="type_id">
                        @foreach ($document_types as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 {{ $remark }}" id="other_remark_div">
                <div class="form-group">
                    <label for="other_remark">Other Remark *</label>
                    <input type="text" class="form-control other_remark" name="doc[0][remark]" id="other_remark"
                        placeholder="Other Remark" class="form-control rounded required">
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="dose">Display to Staff</label>
                    <select class="form-control m-b display_to_staff" name="doc[0][display_to_staff]"
                        id="display_to_staff">
                        <option value="">Select Options</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                {{-- <div class="form-group select-2-full">
            <label for="dose">Select Staff</label>
            <select class="form-control m-b select_staffs" name="doc[0][staff_ids][]" id="staff_ids" multiple="multiple">
                @foreach ($staffs as $staffKey => $staff)
                <option value="{{ $staffKey }}">{{ $staff }}
                </option>
            @endforeach
            </select>
        </div> --}}
            </div>
        </div>
        <div id="more_document_div">
        </div>
        <div class="row">
            <div class="col-lg-12 text-right">
                <button type="button" class="btn btn-outline-navy font-bold" id="add_document_fields"><i
                        class="fa fa-plus"></i> Add More Documents</button>
            </div>
        </div>
    </div>
</div>
