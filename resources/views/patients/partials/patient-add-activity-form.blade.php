<div class="hr-line-dashed"></div>
<div class="accordion shadow" id="accordionExample4">
    <div class="align-items-center bg-primary d-flex px-2 py-2 pointer-event" data-toggle="collapse"
        data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
        <h4 class="m-0">Add Activity Details</h4>
        <i class="fa fa-chevron-down ml-auto mr-2 "></i>
    </div>
    <div id="collapseFour" class="collapse border border-1 p-3" aria-labelledby="headingOne"
        data-parent="#accordionExample4">
        <div class="row" id="activity_div_extra">
            <div class="col-12 col-md-6 col-lg-3   @error('shift_id') has-error @enderror">
                <label class="col-form-label">Shift *</label>
                <div class="">
                    @foreach ($shifts as $key => $shift)
                        <label class="checkbox-inline i-checks mr-2">
                            <input type="checkbox" name="activity[0][activity_shift_id][]" value="{{ $key }}">
                            <i></i> {{ $shift }}
                        </label>
                    @endforeach
                    @error('shift_id')
                        <span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
                    @enderror
                    <div id="activity-0-error" class="error-message" style="display:none"></div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3   @error('name') has-error @enderror">
                <label class="col-form-label">Name *</label>
                <div class="">
                    <input type="text" name="activity[0][activity_name]" placeholder="Name" class="form-control"
                        required autocomplete="off" value="{{ old('name') }}" id="activity_name_0">
                    @error('name')
                        <span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3  ">
                <label for="duration" class="col-form-label">Duration (in minutes) *</label>
                <div class="">
                    <input type="number" name="activity[0][activity_duration]" id="activity_duration_0"
                        placeholder="Duration" class="form-control rounded valid_integer" min=1>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3  ">
                <label for="contact_no" class="col-form-label">Frequency *</label>
                <div class="">
                    <input type="number" name="activity[0][activity_frequency]" id="activity_frequency_0"
                        placeholder="Frequency" class="form-control rounded valid_integer" min=1>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3  ">
                <label for="contact_no" class="col-form-label">Recurrence *</label>
                <div class="">
                    <select class="form-control activity-recurrence" name="activity[0][activity_recurrence]"
                        id="0">
                        <option value="">Choose option</option>
                        @foreach ($activity_recurrence as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 d-none select_week_full" id="activity-perform_week_0">
                <label for="contact_no" class="col-form-label">Activity Perform (<span id="activity-perform-lbl">in
                        week</span>)</label>
                @php
                    $weekdays = config('const.week_days');
                @endphp
                <select class="form-control select-week-days-0 w-100" id="week_days_select-0"
                    name="activity[0][activity_performance_day][]" multiple="multiple">
                    @foreach ($weekdays as $day)
                        <option value="{{ $day }}">{{ $day }}</option>
                    @endforeach

                </select>
                <!-- <div class="">
                    <input type="number" name="activity_performance_day" id="activity_performance_day" class="form-control rounded" placeholder="2" min="1">

                </div> -->
            </div>
            <div class="col-12 col-md-6 col-lg-3 d-none" id="activity-perform_month_0">
                <label for="contact_no" class="col-form-label">Activity Perform (<span id="activity-perform-lbl">in
                        month</span>)</label>
                <div class="input-group date month_date specific-datepicker-0">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="activity[0][activity_performance_month]" id="selected_month_dates-0"
                        placeholder="Select Month Dates" class="form-control">
                </div>
                <!-- <div class="">
                    <input type="number" name="activity_performance_day" id="activity_performance_day" class="form-control rounded" placeholder="2" min="1">

                </div> -->
            </div>
            {{--  <div class="col-12 col-md-6 col-lg-3   d-none" id="activity-perform">
                <label for="contact_no" class="col-form-label">Activity Perform (<span id="activity-perform-lbl"></span>)</label>
                <div class="">
                    <input type="text" name="activity[0][activity_performance_day]" id="activity_performance_day" class="form-control rounded" placeholder="2">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3   @error('status') has-error @enderror">
                <label class="col-form-label">Status</label>
                <div class="">
                    @php
                        $pre_seleted = 1;
                        if (!is_null(old('status')) && old('status') == 0) {
                            $pre_seleted = 0;
                        }
                    @endphp
                    <select class="form-control m-b" name="activity[0][activity_status]" required>
                        <option value="">Select status</option>
                        <option value="1" {{ $pre_seleted == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $pre_seleted == 0 ? 'selected' : '' }}>In-Active</option>
                    </select>
                    @error('status')
                    <span class="text-danger text-left d-block" role="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div> --}}
            <div class="form-group col-12 col-lg-6 ">
                <label for="description" class="col-form-label">Description *</label>
                <div class="">
                    <textarea class="form-control rounded" name="activity[0][activity_description]" id="activity_description_0"
                        placeholder="Description" rows="4" autocomplete="off"></textarea>
                </div>
            </div>
        </div>
        <div id="more_activity_div"></div>
        <div class="row">
            <div class="col-lg-12 mb-4 text-right">
                <button type="button" class="btn btn-outline-navy font-bold" id="add_activity_fields"><i
                        class="fa fa-plus"></i> Add More Activity</button>
            </div>
        </div>
    </div>
</div>
