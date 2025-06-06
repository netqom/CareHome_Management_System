<div class="">
    <div class="ibox">
        <div class="border-bottom d-flex ibox-title mb-3 pl-0">
            <h5>Patient Basic Information</h5>
            <div class="ibox-tools">
            </div>

        </div>

        <div class="row">
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-2">
                        <div class="font-bold fs-14 text-body">Patient Info:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->patient_info }}
                    </div>
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Address:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->address }}
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Emergency Contact Name:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->emergency_contact_name }}
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Per Day Cost:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->per_day_cost }}
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Emergency Contact:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->emergency_contact }}
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Initial Payment:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->initial_payment ? 'Yes' : 'No' }}
                    </div>
                </div>
            </div>
            @if ($patient->initial_payment)
                <div class="col">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="font-bold fs-14 text-body">Initial Payment:</div>
                        </div>
                        <div class="col-md-9 fs-14">
                            {{ $patient->inital_payment_amount }}
                        </div>

                    </div>
                </div>
            @endif
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Per Day Reserve Cost:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->per_day_reserve_cost }}
                    </div>
                </div>
            </div>
        </div>

        <div class="hr-line-dashed"></div>

        <h4 class="mb-3">Primary Care Person Information</h4>

        <div class="row">
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Name:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->care_person_name }}
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Phone No:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->care_person_contact }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Address:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->care_person_address }}
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Relationship with Patient:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->care_person_relation }}
                    </div>
                </div>
            </div>

        </div>
        <div class="hr-line-dashed"></div>

        <h4 class="mb-3">Biography Information</h4>

        <div class="row">
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Height:</div>
                    </div>
                    <div class="col-md-9 fs-14">

                        <?php
                        if ($patient->height_in_feet != null && $patient->height_in_feet != 'Feet') {
                            echo $patient->height_in_feet . ' feet ';
                        }
                        if ($patient->height_in_inch && $patient->height_in_inch != 'Inch') {
                            echo $patient->height_in_inch . ' inch';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Weight (in pounds):</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->weight }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Blood Group:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        {{ $patient->blood_group }}
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="font-bold fs-14 text-body">Sex:</div>
                    </div>
                    <div class="col-md-9 fs-14">
                        @php
                            if ($patient->gender == 'M') {
                                $gender = 'Male';
                            } elseif ($patient->gender == 'F') {
                                $gender = 'Female';
                            } elseif ($patient->gender == 'O') {
                                $gender = 'Other';
                            } else {
                                $gender = '';
                            }
                        @endphp
                        {{ $gender }}
                    </div>
                </div>
            </div>

        </div>



    </div>
</div>
