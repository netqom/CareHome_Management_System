<?php
/*
*
*	This file contains the functions related to Patient activity report
*	Note : This file needs to be included in composer.json (autoload->files)
*		   
*
*/

if (! function_exists('getShiftLogField')) {
	function getShiftLogField($shift_id)
	{	
		//$activity_shift_id = $shift_id == 1 ? 'morning' : ($shift_id == 2 ? 'afternoon' : 'evening');
		$shiftNames = [
			1 => 'morning',
			2 => 'afternoon',
			3 => 'evening',
			4 => 'night',
			5=>'ad_hoc',
		];
		
		$activity_shift_id = $shiftNames[$shift_id] ?? 'unknown_shift';
		return \App\Models\PatientActivityField::with('subchilds')->where(['parent_id' => 0, 'slug' => $activity_shift_id])->get();
		//$morning_activity = \App\Models\PatientActivityField::with('subchilds')->where(['parent_id' => 0, 'slug' => $activity_shift_id])->get();
		//echo"<pre>";print_r($morning_activity);die;
	}
}

if (! function_exists('getLogFieldValue')) {
	function getLogFieldValue($shift_id, $log_id, $field_id)
	{
		
		//0=None,1=Input,2=Radio,3=Checkbox,4=Select,5=Multiselect,6=Time,7=TextArea
		$field = \App\Models\PatientActivityField::find($field_id);
		
		$form_value = \App\Models\PatientActivity::where(['log_id' => $log_id, 'field_id' => $field_id])->first();
		
		if($form_value){
			if($field->name != 'Activity'){
				$result = getFieldValue($field, $form_value);
				if(!is_null($form_value->remark) && $form_value->remark != '' && $result!=''){
					$result .= '<div class="acti-item remark"><strong class="text-navy mr-1">Remark:</strong>'.$form_value->remark.'</div>';
				}
				return $result;
			}else{
				
				$result = getActivityValue($shift_id, $form_value);
				// if(!is_null($form_value->remark) && $form_value->remark != ''){
				// 	$result .= ' <div class="acti-item remark"><strong class="text-navy mr-1">Remark:</strong>'.$form_value->remark.'</div>';
				// }
				return $result;
			}
		}
		return '';
	}
}
if (! function_exists('isActivityEmpty')) {
	function isActivityEmpty($shift_id, $log_id, $field_id)
	{	
		$field = \App\Models\PatientActivityField::with(['subchilds' => function($query) {
			
		}])
		->find($field_id);
		if($field->name != 'Activity')
		{
		$subchildIds = $field->subchilds->pluck('id')->toArray();
		if (empty($subchildIds)) {
			$subchildIds = [$field->id]; // Include field_id in subchildIds array
		}
		$form_value = \App\Models\PatientActivity::where('log_id', $log_id)
		->whereIn('field_id', $subchildIds) // Check field_id against subchild IDs
		->whereNotNull('field_value') // Ensure field_value is not null
		->where('field_value', '<>', '') // Ensure field_value is not empty
		->first();
		
		}else{
			$form_value = \App\Models\PatientActivityRemark::where(['log_id' => $log_id, 'shift_id' => $shift_id])->first();

		}
		if(!empty($form_value))
		{
			return true;
		}
		return false;
		
	
		
	}
}

if (! function_exists('getFieldValue')) {
	function getFieldValue($field, $form_value)
	{	
		if($field->name == 'Medicines'){
			if($field->type == 3 || $field->type == 5){
				$patient_id = \App\Models\PatientLog::where('id', $form_value->log_id)->pluck('patient_id');
				$medicines = \App\Models\PatientMedicine::where('patient_id', $patient_id)->get();
				$html = '';
				foreach($medicines as $key => $medicine){
					$ids_array = \App\Models\PatientActivity::where('field_id', $medicine->id)->where('shift_id', $form_value->shift_id)->where('log_id', $form_value->log_id)->first();
					if(!empty($ids_array)){
						$selected_values = \App\Models\PatientActivityFieldValue::where('id', $ids_array->field_value)->first();
					}else{
						$selected_values = '';
					}
					if($selected_values!=''){
						$medicine_icon = !is_null($field->icon) ? '<img src="'.asset($field->icon).'" alt="" title="activity-icon">' : '';
						$icon_image = !is_null($selected_values->icon) ? '<img src="'.asset($selected_values->icon).'" alt="" title="activity-icon" style="width: 16px;vertical-align: top;margin-top: 3px;"> ' : '';
						$remark = $ids_array->remark != '' ? '<div><span><strong class="text-navy mr-1">Remark:</strong>'.$ids_array->remark.'</span></div>' : '';
						$html .= '<div class="acti-item align-items-start flex-column h-auto w-100">
						<div class="mb-2">'.$medicine_icon.'<span>'. $medicine->name .'</span></div>
						<div class="mb-2">'.$icon_image.'<span>'.$selected_values->name.'</span></div>'.$remark.'</div>';
					}
				}
				
				return $html;
				// return implode(',', $selected_values);
			}
		}else{
			if($field->type == 1 || $field->type == 7){
				return '<div class="acti-item remark">'.$form_value->field_value.'</div>';
			}else if($field->type == 6){
				$selected_value = \App\Models\PatientActivityField::find($form_value->field_id);
				$icon_image = !is_null($selected_value->icon) ? '<img src="'.asset($selected_value->icon).'" alt="" title="activity-icon"> ' : '';
				if($field->parent_id == 2){
					$cls = 'flex-column flex-wrap h-auto';
					$div = '<div class="acti-item '.$cls.'"><div class="font-bold">'.$field->name.'</div><div>'.$icon_image.'<span>'.$form_value->field_value.'</span></div></div>';
				}else{
					$cls = '';
					$div = '<div class="acti-item '.$cls.'">'.$icon_image.'<span>'.$form_value->field_value.'</span></div>';
				}
				return $div;
				// return '<div class="acti-item">'.$icon_image.'<span>'.$form_value->field_value.'</span></div>';	
			}else if($field->type == 2 || $field->type == 4){
				$selected_value = \App\Models\PatientActivityFieldValue::find($form_value->field_value);
				$icon_image = !empty($selected_value->icon) ? '<img src="'.asset($selected_value->icon).'" alt="" title="activity-icon"> ' : '';
				if($selected_value){
					if($field->parent_id == 2){
						$cls = 'flex-column flex-wrap h-auto';
						$div = '<div class="acti-item '.$cls.'"><div class="font-bold">'.$field->name.'</div><div>'.$icon_image.'<span>'.$selected_value->name.'</span></div></div>';
					}else{
						$cls = '';
						$div = '<div class="acti-item '.$cls.'">'.$icon_image.'<span>'.$selected_value->name.'</span></div>';
					}
					return $div;
					// return '<div class="acti-item">'.$icon_image.'<span>'.$selected_value->name.'</span></div>';
				}else{
					return '<div class="acti-item"><span></span></div>';
				}
				
				//return $selected_values->name;
			}else if($field->type == 3 || $field->type == 5){
				$ids_array = explode(',', $form_value->field_value);
				$selected_values = \App\Models\PatientActivityFieldValue::whereIn('id', $ids_array)->get();
				$html = '';
				if($selected_values->count() > 0){
					foreach($selected_values as $selected_value){
						$icon_image = !is_null($selected_value->icon) ? '<img src="'.asset($selected_value->icon).'" alt="" title="activity-icon"> ' : '';
						if($field->parent_id == 2){
							$cls = 'flex-column flex-wrap h-auto';
							$div = '<div class="acti-item '.$cls.'"><div class="font-bold">'.$field->name.'</div><div>'.$icon_image.'<span>'.$selected_value->name.'</span></div></div>';
						}else{
							$cls = '';
							$div = '<div class="acti-item '.$cls.'">'.$icon_image.'<span>'.$selected_value->name.'</span></div>';
						}
						$html .= $div;
						// $html .= '<div class="acti-item">'.$icon_image.'<span>'.$selected_value->name.'</span></div>';
					}
				}
				return $html;
				//return implode(',', $selected_values);
			}
		}
	}
}

// if (! function_exists('getActivityValue')) {
// 	function getActivityValue($form_value)
// 	{	
// 		$ids_array = explode(',', $form_value->field_value);
// 		$selected_values = \App\Models\Activity::whereIn('id', $ids_array)->get();
// 		$html = '';
// 		if($selected_values->count() > 0){
// 			foreach($selected_values as $selected_value){
// 				$icon_image = !is_null($selected_value->icon) ? asset($selected_value->icon) : asset('assets/img/activity-icon/activity.png');
// 				$html .= '<div class="acti-item"><img src="'.$icon_image.'" alt="" title="activity-icon"><span>'.$selected_value->name.'</span></div>';
// 			}
// 		}
// 		return $html;
// 	}
// }


if (! function_exists('getActivityValue')) {
	function getActivityValue($shift_id, $form_value)
	{	
		// $ids_array = explode(',', $form_value->field_value);
		$ids_array = \App\Models\PatientActivityRemark::where(['log_id' => $form_value->log_id, 'shift_id' => $shift_id])->pluck('activity_id');
		
		$selected_values = \App\Models\Activity::whereIn('id', $ids_array)->get();
		$html = '';
		if($selected_values->count() > 0){
			foreach($selected_values as $selected_value){
				
				$patient_activity_remark = $ids_array = \App\Models\PatientActivityRemark::where(['log_id' => $form_value->log_id, 'shift_id' => $shift_id,'activity_id'=>$selected_value->id])->first();
				$status= ($patient_activity_remark->status==1)? 'Yes' : 'No';
				$icon_image = !is_null($selected_value->icon) ? asset($selected_value->icon) : asset('assets/img/activity-icon/activity.png');
				$html .= '<div class="acti-item align-items-start flex-column h-auto w-100"><div class="mb-2 acti-status"><img src="'.$icon_image.'" alt="" title="activity-icon"><span>'.
				$selected_value->name.' - <strong class="text-navy mr-1">'.$status.'</strong></span></div>';
				if(!empty($patient_activity_remark->remark))
				{
				$html .= '<div class="remark"><span><strong class="text-navy mr-1">Remark:</strong>'.$patient_activity_remark->remark.'</span></div>';
				}
				$html .='</div>';
				// $html .= '<div class="acti-item remark"><strong class="text-navy mr-1">Remark:</strong>'.$patient_activity_remark->remark.'</div>';
			}
		}
		return $html;
	}
}
if (! function_exists('getPatientActivity')) {
	function getPatientActivity($log_id,$med_id,$shift_id)
	{	
		return $ids_array = \App\Models\PatientActivity::where('field_id', $med_id)->where('shift_id', $shift_id)->where('log_id', $log_id)->first();
		
	}
}
if (! function_exists('getPatientActivityByLog')) {
	function getPatientActivityByLog($log_id)
	{	
		return $ids_array = \App\Models\PatientActivity::where('field_id', $med_id)->where('shift_id', $shift_id)->where('log_id', $log_id)->first();
		
	}
}
if (! function_exists('getMedLogByType')) {
	function getMedLogByType($patient_id,$year,$month,$med_array)
	{	
		return $logs = \App\Models\PatientLog:: leftJoin('patient_activities', 'patient_logs.id', '=', 'patient_activities.log_id')
		->leftJoin('users', 'users.id', '=', 'patient_activities.created_by')
		->leftJoin('users as updated_user', 'updated_user.id', '=', 'patient_activities.updated_by')
		->leftJoin('patient_medicines', 'patient_medicines.id', '=', 'patient_activities.field_id')
		->leftJoin('patient_activity_field_values', 'patient_activity_field_values.id', '=', 'patient_activities.field_value')
		->whereIn('patient_activities.field_id', $med_array)
		->where('patient_logs.patient_id', $patient_id)
		->where('patient_logs.log_type', 1)
		->whereYear('patient_logs.report_date', $year)
		->whereMonth('patient_logs.report_date', $month)
		->select('patient_logs.*','patient_logs.id as log_iddd', 'patient_activities.*','users.name as added_by','updated_user.name as update_by','patient_medicines.name as med_name','patient_medicines.dose' , 'patient_medicines.intake_method','patient_medicines.reason as med_reason','patient_activity_field_values.name as field_value_name','patient_medicines.time_other','patient_medicines.medicine_type_other','patient_medicines.time_id','patient_medicines.medicine_time','patient_medicines.id as med_id','patient_activities.created_at as activity_time','patient_medicines.instructions as instructions','patient_medicines.other_dose as other_dose','patient_medicines.is_discontinue', 'patient_medicines.discontinue_date','patient_activities.med_key as med_key')
		->get();
		
	}
}
/* if (! function_exists('getMedLogByType')) {
	function getMedLogByType($patient_id,$year,$month,$med_array)
	{	
		return $logs = \App\Models\PatientLog:: leftJoin('patient_activities', 'patient_logs.id', '=', 'patient_activities.log_id')
		->leftJoin('users', 'users.id', '=', 'patient_activities.created_by')
		->leftJoin('patient_medicines', 'patient_medicines.id', '=', 'patient_activities.field_id')
		->leftJoin('patient_activity_field_values', 'patient_activity_field_values.id', '=', 'patient_activities.field_value')
		->whereIn('patient_activities.field_id', $med_array)
		->where('patient_logs.patient_id', $patient_id)
		->where('patient_logs.log_type', 1)
		->whereYear('patient_logs.report_date', $year)
		->whereMonth('patient_logs.report_date', $month)
		->select('patient_logs.*','patient_logs.id as log_iddd', 'patient_activities.*','users.name as added_by','patient_medicines.name as med_name','patient_medicines.dose' , 'patient_medicines.intake_method','patient_medicines.reason as med_reason','patient_activity_field_values.name as field_value_name','patient_medicines.time_other','patient_medicines.medicine_type_other','patient_medicines.time_id','patient_medicines.medicine_time','patient_medicines.id as med_id','patient_activities.created_at as activity_time','patient_medicines.instructions as instructions','patient_medicines.other_dose as other_dose')
		->get();
		
	}
} */
if (! function_exists('getNoteMedLog')) {
	function getNoteMedLog($patient_id,$year,$month,$med_array)
	{	
		return $logs = \App\Models\PatientLog:: leftJoin('patient_activities', 'patient_logs.id', '=', 'patient_activities.log_id')
		->leftJoin('users', 'users.id', '=', 'patient_activities.created_by')
		->leftJoin('users as updated_user', 'updated_user.id', '=', 'patient_activities.updated_by')
		->leftJoin('users as noted_user', 'noted_user.id', '=', 'patient_activities.noted_by')
		->leftJoin('patient_medicines', 'patient_medicines.id', '=', 'patient_activities.field_id')
		->leftJoin('patient_activity_field_values', 'patient_activity_field_values.id', '=', 'patient_activities.field_value')
		->whereIn('patient_activities.field_id', $med_array)
		->where('patient_logs.patient_id', $patient_id)
		->where('patient_logs.log_type', 1)
		->whereYear('patient_logs.report_date', $year)
		->whereMonth('patient_logs.report_date', $month)
		->whereNotNull('patient_activities.staff_note')
    	->whereNotNull('patient_activities.note_time')
		->select('patient_logs.*','patient_logs.id as log_iddd','patient_activities.*','users.name as added_by','updated_user.name as update_by','noted_user.name as note_by','patient_medicines.name as med_name','patient_medicines.dose' , 'patient_medicines.intake_method','patient_medicines.reason as med_reason','patient_activity_field_values.name as field_value_name','patient_medicines.time_other','patient_medicines.medicine_type_other','patient_medicines.time_id','patient_medicines.medicine_time','patient_medicines.id as med_id','patient_activities.created_at as activity_time','patient_medicines.instructions as instructions','patient_medicines.other_dose as other_dose')
		->orderBy('patient_activities.created_at', 'asc')
		->get();
		
	}
}
if (! function_exists('getSingleMedLogByType')) {
	function getSingleMedLogByType($patient_id,$log,$med_array)
	{	
		return $logs = \App\Models\PatientLog:: leftJoin('patient_activities', 'patient_logs.id', '=', 'patient_activities.log_id')
		->leftJoin('users', 'users.id', '=', 'patient_activities.created_by')
		->leftJoin('users as updated_user', 'updated_user.id', '=', 'patient_activities.updated_by')
		->leftJoin('patient_medicines', 'patient_medicines.id', '=', 'patient_activities.field_id')
		->leftJoin('patient_activity_field_values', 'patient_activity_field_values.id', '=', 'patient_activities.field_value')
		->whereIn('patient_activities.field_id', $med_array)
		->where('patient_logs.patient_id', $patient_id)
		->whereDate('patient_logs.report_date', $log->report_date)
		->select('patient_logs.*','patient_activities.*','users.name as added_by','updated_user.name as update_by','patient_medicines.name as med_name','patient_medicines.dose' , 'patient_medicines.intake_method','patient_medicines.reason as med_reason','patient_activity_field_values.name as field_value_name','patient_medicines.time_other','patient_medicines.medicine_type_other','patient_medicines.time_id','patient_medicines.medicine_time','patient_activities.ad_hoc_time','patient_activities.created_at as activity_time','patient_medicines.instructions as instructions','patient_medicines.other_dose as other_dose','patient_medicines.id as med_id','patient_medicines.instructions as instructions','patient_medicines.is_discontinue', 'patient_medicines.discontinue_date','patient_activities.med_key as med_key')
		->get();
		
	}
}
if (! function_exists('getSingleNoteMedLog')) {
	function getSingleNoteMedLog($patient_id,$log,$med_array)
	{	
		return $logs = \App\Models\PatientLog:: leftJoin('patient_activities', 'patient_logs.id', '=', 'patient_activities.log_id')
		->leftJoin('users', 'users.id', '=', 'patient_activities.created_by')
		->leftJoin('users as updated_user', 'updated_user.id', '=', 'patient_activities.updated_by')
		->leftJoin('users as noted_user', 'noted_user.id', '=', 'patient_activities.noted_by')
		->leftJoin('patient_medicines', 'patient_medicines.id', '=', 'patient_activities.field_id')
		->leftJoin('patient_activity_field_values', 'patient_activity_field_values.id', '=', 'patient_activities.field_value')
		->whereIn('patient_activities.field_id', $med_array)
		->where('patient_logs.patient_id', $patient_id)
		->whereDate('patient_logs.report_date', $log->report_date)
		->whereNotNull('patient_activities.staff_note')
    	->whereNotNull('patient_activities.note_time')
		->select('patient_logs.*','patient_activities.*','users.name as added_by','updated_user.name as update_by','noted_user.name as note_by','patient_medicines.name as med_name','patient_medicines.dose' , 'patient_medicines.intake_method','patient_medicines.reason as med_reason','patient_activity_field_values.name as field_value_name','patient_medicines.time_other','patient_medicines.medicine_type_other','patient_medicines.time_id','patient_medicines.medicine_time','patient_activities.ad_hoc_time','patient_activities.created_at as activity_time','patient_medicines.instructions as instructions','patient_medicines.other_dose as other_dose','patient_medicines.id as med_id')
		->orderBy('patient_activities.created_at', 'asc')
		->get();
		
	}
}

if (! function_exists('getAdHocShiftLog')) {
	function getAdHocShiftLog($log_id)
	{	
		 
		return $result = Illuminate\Support\Facades\DB::table('patient_activities as pa1')
		->select('pa1.log_id', 'pa1.shift_id', 'pa1.created_at', 'pa1.created_by as ad_created_by', 'pa1.field_value as title', 'pa2.field_value as comment', 'pa1.field_id as title_field_id', 'pa2.field_id as comment_field_id','pa1.id as activity_id')
		->join('patient_activities as pa2', function ($join) {
			$join->on('pa1.log_id', '=', 'pa2.log_id')
				->on('pa1.shift_id', '=', 'pa2.shift_id')
				->on('pa1.created_at', '=', 'pa2.created_at')
				->on('pa1.created_by', '=', 'pa2.created_by');
		})
		->join('patient_activity_fields as paf_title', function ($join) {
			$join->on('pa1.field_id', '=', 'paf_title.id')
				->where('paf_title.slug', '=', 'ad-hoc-title');
		})
		->join('patient_activity_fields as paf_comment', function ($join) {
			$join->on('pa2.field_id', '=', 'paf_comment.id')
				->where('paf_comment.slug', '=', 'ad-hoc-comments');
		})
		->where(['pa1.log_id' => $log_id,'pa1.shift_id'=>5])->get();
		
	}
}



