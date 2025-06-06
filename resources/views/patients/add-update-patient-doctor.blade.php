@extends('layouts.admin')

@section('title', 'Create Patient Doctor')
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
                    <strong>Create Patient Doctor</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 dark-bg">
                <form method="POST" role="form"
                    action="{{ route('save-patients-doctor', ['patient_id' => $patient_id, 'id' => $id]) }}"
                    id="patient_doctor_Form" enctype="multipart/form-data">
                    @csrf
                    <div class="ibox ">
                        <div class="ibox-content ff shadow border rounded">
                            <div class="ibox-content">

                                <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient_id }}">
                                <fieldset class="w-100">

                                    <div class="row" id="doctor_div">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="doctor_name">Name *</label>
                                                <input type="text" name="doctors[0][name]" placeholder="Name"
                                                    class="form-control rounded required"
                                                    value="{{ $doctor ? $doctor->name : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="doctor_email">Email *</label>
                                                <input type="email" name="doctors[0][email]" placeholder="Email"
                                                    class="form-control rounded required"
                                                    value="{{ $doctor ? $doctor->email : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="doctor_phone_number">Phone No *</label>
                                                <input type="tel" name="doctors[0][phone]" placeholder="Phone No."
                                                    class="form-control rounded required"
                                                    value="{{ $doctor ? $doctor->phone : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="doctor_address">Address * </label>
                                                <input type="text" name="doctors[0][address]" placeholder="Address"
                                                    class="form-control rounded required"
                                                    value="{{ $doctor ? $doctor->location : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="doctor_role">Designation *</label>
                                                <input type="text" name="doctors[0][role]" placeholder="Role"
                                                    class="form-control rounded required"
                                                    value="{{ $doctor ? $doctor->doctor_role : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="doctor_role">Fax Number *</label>
                                                <input type="text" name="doctors[0][fax_number]" placeholder="Fax Number"
                                                    class="form-control rounded valid_fax required"
                                                    value="{{ $doctor ? $doctor->fax_number : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a class="btn btn-white btn-sm" type="button"
                                href="{{ createCancelUrl(route('patients.show', $patient_id)) }}">Cancel</a>
                            <button class="btn btn-sm btn-primary" type="submit" id="patientDoctorForm">Save</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
