@php
    $medicine_type = config('const.medicine_type');
    $medicine_time = config('const.medicine_time');
    $intake_method = config('const.medicine_intake_method');
    $intake_guidedby = config('const.medicine_intake_supervised_by');
    $medicine_frequency = config('const.medicine_frequency');
    $medicine_time_other   = config('const.medicine_time_other');
@endphp

<style>
    .time-badges span {
        min-width: 65px;
        display: inline-block;
        text-align: center;
        margin-right: 5px;
    }

    .btn-secondary {
        background-color: #ccc;
        border-color: #ccc;
    }
</style>

<div class="">
    <div class="ibox">
        <div class="ibox-title d-flex pl-0 align-items-center">
            <h5 class="mr-2 mb-0">Patient Medicine list</h5>
            @if (Auth::user()->role_id == 2 && $patient->discharged != 1 && $patient->deleted_at == null)
                <div class="ibox-tools">
                    <a href="{{ route('add-update-patients-medicine', ['patient_id' => $patient->id, 'id' => '0']) }}"
                        class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Medicine </a>
                </div>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover no-margins" id="medicine_list">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Frequency</th>
                        <th>Time</th>
                        <th>Medication Type</th>
                        <th>Intake Method</th>
                        <th>Supervised By</th>
                        <th>Dose</th>
                        @if (Auth::user()->role_id == 2)
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($patient->medicines as $key => $medicine)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td style="max-width:150px; word-wrap:break-word;">{{ $medicine->name }}</td>
                            <td>{{ $medicine->med_frequency ? $medicine_frequency[$medicine->med_frequency] : '' }}</td>
                            <td>
                                <div class="time-badges">
                                    @php
                                        $jsonData = [];
                                        $medicine_time2 = [];
                                        if ($medicine->time_id != 0) {
                                            $jsonData = json_decode($medicine->time_id, true);
                                        }
                                        if ($medicine->medicine_time != 0) {
                                            $medicine_time2 = json_decode($medicine->medicine_time, true);
                                        }
                                        $dose = '';
                                        $dose_type = '';
                                        if (strpos($medicine->dose, '__') !== false) {
                                            $doseData = explode('__', $medicine->dose);
                                            $dose1 = $doseData[0];
                                            $dose_type = $doseData[1];
                                            $dose = $dose1 . ' ' . $dose_type;
                                            if ($dose_type == 'other') {
                                                $dose = $medicine->other_dose;
                                            }
                                        } else {
                                            $dose = $medicine->dose;
                                            $dose_type = '';
                                        }

                                    @endphp
                                    @if ($jsonData !== null)
                                        @if (is_array($jsonData))
                                            <ul
                                                style="list-style-type: none; padding: 0; display: flex;">
                                                @foreach ($jsonData as $jd)
                                                    <li>
                                                        <!-- Display shift name -->
                                                        
                                                        <span>{{ ucwords($medicine_time[$jd]) }}</span>

                                                        <!-- Display first time under the shift name -->
                                                        @if (in_array($jd, [1, 2, 3, 4]))
                                                            @if (!empty($medicine_time2) && array_key_exists($jd, $medicine_time2))
                                                                <?php
                                                                $times = explode(',', $medicine_time2[$jd]);
                                                                $firstTime = $times[0]; // Get the first time slot
                                                                ?>
                                                                <ul style="list-style-type: none; padding-left: 0;">
                                                                    <li>
                                                                        <span class="label label-warning">
                                                                            {{ date('h:i A', strtotime($firstTime)) }}
                                                                        </span>
                                                                    </li>
                                                                </ul>
                                                            @endif
                                                        @elseif ($jd == 5)
                                                            @if (!empty($medicine_time2) && array_key_exists(5, $medicine_time2))
                                                                @php 
                                                                 $medicine_time_other_id = $medicine_time2[5];
                                                                 
                                                                @endphp
                                                                <ul style="list-style-type: none; padding-left: 0;">
                                                                    <li>
                                                                        <span class="label label-warning">{{$medicine_time_other[$medicine_time_other_id]}} </span>
                                                                    </li>
                                                                </ul>
                                                            @endif
                                                        @else
                                                            <ul style="list-style-type: none; padding-left: 0;">
                                                                <li>No times available</li>
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                            {{-- @foreach ($jsonData as $jd)
                                                <span>{{ $medicine_time[$jd] }}</span>
                                            @endforeach
                                            <br>
                                            @if (!empty($medicine_time2) && array_key_exists(1, $medicine_time2))
                                                <span
                                                    class="label label-warning">{{ date('h:i A', strtotime($medicine_time2[1])) }}</span>
                                            @endif
                                            @if (!empty($medicine_time2) && array_key_exists(2, $medicine_time2))
                                                <span
                                                    class="label label-warning">{{ date('h:i A', strtotime($medicine_time2[2])) }}</span>
                                            @endif
                                            @if (!empty($medicine_time2) && array_key_exists(3, $medicine_time2))
                                                <span
                                                    class="label label-warning">{{ date('h:i A', strtotime($medicine_time2[3])) }}</span>
                                            @endif
                                            @if (!empty($medicine_time2) && array_key_exists(4, $medicine_time2))
                                                <span
                                                    class="label label-warning">{{ date('h:i A', strtotime($medicine_time2[4])) }}</span>
                                            @endif --}}
                                        @else
                                            {{ $medicine_time[$medicine->time_id] }}
                                            <br>
                                            @if (!is_null($medicine->time_other) && $medicine->time_id == 4)
                                                <span
                                                    class="label label-warning">{{ date('h:i A', strtotime($medicine->time_other)) }}</span>
                                            @endif
                                        @endif
                                    @else
                                        {{ $medicine_time[$medicine->time_id] }}
                                        <br>
                                        @if (!is_null($medicine->time_other) && $medicine->time_id == 4)
                                            <span
                                                class="label label-warning">{{ date('h:i A', strtotime($medicine->time_other)) }}</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td>
                                {{ $medicine_type[$medicine->medicine_type] }} <br>
                                @if (!is_null($medicine->medicine_type_other))
                                    <span class="label label-warning">{{ $medicine->medicine_type_other }}</span>
                                @endif
                            </td>
                            <td>{{ $intake_method[$medicine->intake_method] }}</td>
                            <td>
                                {{ $intake_guidedby[$medicine->intake_supervised_by] }} <br>
                                @if (!is_null($medicine->intake_supervised_other))
                                    <span class="label label-warning">{{ $medicine->intake_supervised_other }}</span>
                                @endif
                            </td>
                            <td>{{ $dose }}</td>
                            @if (Auth::user()->role_id == 2)
                                <td class="text-dark text-nowrap">
                                    <a data-toggle="tooltip" data-placement="top" title=""
                                        href="{{ route('view-patient-medication-report', ['patient_id' => $patient->id, 'id' => $medicine->id]) }}"
                                        class="btn-primary btn btn-sm" data-original-title="View Patient Medicine"><i
                                            class="fa fa-eye" aria-hidden="true"></i></a>
                                    @if ($patient->deleted_at == null && $patient->discharged == 0)
                                        {{-- <a data-toggle="tooltip" data-placement="top" title="Edit Patient Medicine" href="{{ route('add-update-patients-medicine', ['patient_id' => $patient->id, 'id' => $medicine->id]) }}" class="btn-primary btn btn-sm" title="Edit Patient Medicine">
											<i class="fa fa-edit" aria-hidden="true"></i>
										</a>  --}}
                                        <form action="{{ route('delete-patients-medicine', $medicine->id) }}"
                                            method="post" id="delete_form_{{ $medicine->id }}" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <a data-toggle="tooltip" data-placement="top" href="javascript:;"
                                            class="btn-danger btn btn-sm confirm_delete" role="button"
                                            data-item-id="{{ $medicine->id }}" data-item-type="patient medicine"
                                            title="Delete Medicine"><i class="fa fa-trash" aria-hidden="true"></i></a>

                                        <a data-toggle="tooltip" data-placement="top" href="javascript:;"
                                            class="@if ($medicine->is_discontinue == 0) btn-danger @else btn-secondary @endif btn btn-sm discontinue_medicine"
                                            role="button" data-id="{{ $medicine->id }}" title="Discontinue Medicine"
                                            @if ($medicine->is_discontinue == 1) style="pointer-events: none;" @endif><i
                                                class="fa fa-ban" aria-hidden="true"></i></a>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="{{ Auth::user()->role_id == 2 ? 8 : 7 }}">No Data Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    /*$("#download_med_report").on("click",function(event){
		event.preventDefault()
		var form= document.getElementById('download_med_report_form');
		form.submit();
	})
    document.getElementById('med-year').addEventListener('change', function() {
        var year = this.value;
        var monthDropdown = document.getElementById('med-month');
        var selectedMonth = {{ $selectedMonth }};
        var selectedYear = {{ $selectedYear }};
        var totalMonths = 12;

        // Clear existing options
        monthDropdown.innerHTML = '';

        // If the selected year is the current year, start from the current month
        if (year == {{ $endYear }}) {
            totalMonths = {{ date('n') }};
        }
		if(year!=selectedYear)
		{
			selectedMonth=1;
		}

        // Add options for each month
        for (var i = selectedMonth; i <= totalMonths; i++) {
            var option = document.createElement('option');
            option.value = i;
            option.text = new Date(year, i - 1, 1).toLocaleString('default', { month: 'long' });
            monthDropdown.appendChild(option);
        }
    });*/
</script>
