<h4 class="mb-3">Assign Activity to Patient</h4>
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="name">Activity Name *</label>
                <select class="form-control m-b" name="activity[]" id="activity_name">
                    <option value="">Choose Activity</option>
                    @foreach($activities as $activity)
                        <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="duration">Duration (in minutes)</label>
                <input type="text" name="duration[]" id="duration" placeholder="Duration" class="form-control rounded integer_no">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="contact_no">Frequency</label>
                <input type="text" name="frequency[]" id="frequency" placeholder="Frequency" class="form-control rounded">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="contact_no">Recurrence</label>
                <select class="form-control m-b activity-recurrence" name="recurrence[0]" id="0">
                    <option value="">Choose option</option>
                    @foreach($activity_recurrence as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 d-none" id="activity-perform_week_0">
									<label for="contact_no" class="col-form-label">Activity Perform (<span id="activity-perform-lbl">in week</span>)</label>
									@php
									$weekdays = config('const.week_days');
									@endphp
									<select class="form-control select-week-days w-100" id="week_days_select" name="activity_performance_day[]" multiple="multiple">
										@foreach($weekdays as $day)
										<option value="{{ $day}}">{{ $day}}</option>
										@endforeach
										
									</select>
									<!-- <div class="">
										<input type="number" name="activity_performance_day" id="activity_performance_day" class="form-control rounded" placeholder="2" min="1">

									</div> -->
								</div>
								<div class="col-lg-6 d-none" id="activity-perform_month_0">
									<label for="contact_no" class="col-form-label">Activity Perform (<span id="activity-perform-lbl">in month</span>)</label>
									<div class="input-group date month_date" id="specific-datepicker">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" name="activity_performance_month" id="selected_month_dates" placeholder="Select Month Dates" class="form-control" >  
									</div> 
									<!-- <div class="">
										<input type="number" name="activity_performance_day" id="activity_performance_day" class="form-control rounded" placeholder="2" min="1">

									</div> -->
								</div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control rounded" name="description[]" id="description" placeholder="Description" rows="4" autocomplete="off"></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 text-right">
            <button type="button" class="btn btn-success" id="addFields">+</button>
        </div>
    </div>
</div>
<div class="hr-line-dashed"></div>
<div class="actions clearfix float-right" id="submit_button_div">
    <a class="btn btn-white mr-2 font-bold" href="javascript:;" onclick="goToStep('2', '3')"><i class="fa fa-chevron-left"></i> Previous</a>
    <a class="btn btn-primary font-bold" href="javascript:;" data-current-step="3" onclick="validateFormStep(3)">Submit</a>
</div>
<div class="actions clearfix float-right" id="loader_button_div" style="display: none;">
    <div class="col-lg-12 col-m-12">
        <button type="button" class="btn btn-primary" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Wait Processing...
        </button>
    </div>
</div>