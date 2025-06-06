<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Patient;
use App\Models\User;
use App\Models\PatientActivityRemark;
use App\Models\PatientMedicineRemark;
use App\Models\PatientMedicine;
use App\Events\CreateNotification;
use Log;
use Carbon\Carbon;

class PatientActivity extends Model
{
    use HasFactory;
    protected $fillable = [
		'log_id', 
		'shift_id',
		'field_id',
		'field_value', 
		'remark',
		'staff_note',
		'note_time',
		'ad_hoc_time',
		'status', 
		'created_by', 
		'updated_by',
		'noted_by',
		'patient_id',
		'activity_id',
		'duration',
		'frequency',
		'recurrence',
		'description',
		'activity_perform_days',
		'med_time',
		'med_key',
		'reported_by',
		'is_from_app',
	];
	
	public function logs()
	{
		return $this->belongsTo(PatientLog::class, 'log_id', 'id');
	}
	
	public function activity_field()
	{
		return $this->belongsTo(PatientActivityField::class, 'field_id', 'id');
	}
	
	public function activity_field_value()
	{
		return $this->belongsTo(PatientActivityFieldValue::class, 'field_value', 'id');
	}
	
	public function added_by()
	{
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function addUpdateLogActivityWeb($request, $log)
	{
		
		$form_data = $request->form_data;
		if(count($form_data) > 0){
			
			foreach($form_data as $f_key => $f_data){
				//dd($request->all(), $log, $f_data);
				$activity_data = [
					'log_id'      => $log->id,
					'shift_id'    => $request->shift_id,
					'remark'      => (isset($f_data['remark']))? $f_data['remark']: '',
					'field_id'    => $f_key,
					'field_value' => $f_key == 8 ? implode(',', $f_data['field_value']) : $f_data['field_value'],
					'status'      => 1,
					'updated_by'  => Auth::user()->id,
				];
				//dd($activity_data);
				//$activity_data['created_by']  = Auth::user()->id;
				//$patient_log = $this->create($activity_data);
				if($request->is_call_ad_hoc=='true')
				{
					$activity_data['created_by']  = Auth::user()->id;
					$patient_log = $this->create($activity_data);
				}else{
				if(!isset($f_data['id']) || $f_data['id'] == 0){
					$activity_data['created_by']  = Auth::user()->id;
					$patient_log = $this->create($activity_data);
				}else{
					$patient_log = $this->findOrFail($f_data['id']);
					$patient_log->update($activity_data);
				}
			}
			}
			
		}
		if(!empty($request->activity_data)){
			$activityData = $request->activity_data;
			if (is_array($activityData)) {
				// foreach($request->activity_data as $activity_data){
				foreach($activityData as $activity_data){
					//dd($activity_data,$activityData);
					if(isset($activity_data['activity_id'])){
					PatientActivityRemark::updateOrCreate(['log_id' => $log->id, 'patient_id' => $log->patient_id , 'activity_id' => $activity_data['activity_id'],'shift_id' => $request->shift_id],['status' => isset($activity_data['status']) ? $activity_data['status'] : 0, 'remark' => isset($activity_data['remark']) ? $activity_data['remark'] : '']);
					}
				}
			}
			
		}

		if(!empty($request->medicine_data)){
			$medicine_data = json_decode($request->medicine_data);
			if (is_array($medicine_data)) {
			// foreach($request->medicine_data as $medicine_data){
				foreach($medicine_data as $medicine_data){
					PatientMedicineRemark::updateOrCreate(['log_id' => $log->id, 'patient_id' => $log->patient_id , 'medicine_id' => $medicine_data->medicine_id,'shift_id' => $request->shift_id],['shift_id' => $request->shift_id, 'status' => isset($medicine_data->field_value) ? $medicine_data->field_value : '', 'remark_comment' => isset($medicine_data->remark) ? $medicine_data->remark : null]);
				}
			}
		}
		/*if($request->id == 0){
			$activity_data['created_by']  = Auth::user()->id;
			$patient_log = $this->create($log_data);
		}else{
			$patient_log = $this->findOrFail($request->id);
			$patient_log->update($log_data);
		}*/
		return true;
	}
	
	public function addUpdateLogActivity($request, $log)
	{
		
		$form_data = json_decode($request->form_data, true);
		if(count($form_data) > 0){
			
			foreach($form_data as $f_data){
				$activity_data = [
					'log_id'      => $log->id,
					'shift_id'    => ($request->is_call_ad_hoc=='true')? 5: $request->shift_id,
					'remark'      => (isset($f_data['remark']))? $f_data['remark']: '',
					'field_id'    => $f_data['field_id'],
					'field_value' => $f_data['field_value'],
					'status'      => 1,
					'is_from_app'      => 1,
					'updated_by'  => Auth::user()->id,
				];
				//$activity_data['created_by']  = Auth::user()->id;
				//$patient_log = $this->create($activity_data);
				if($request->is_call_ad_hoc=='true')
				{
					$activity_data['created_by']  = Auth::user()->id;
					$patient_log = $this->create($activity_data);
				}else{
				if(!isset($f_data['id']) || $f_data['id'] == 0){
					$activity_data['created_by']  = Auth::user()->id;
					$patient_log = $this->create($activity_data);
				}else{
					$patient_log = $this->findOrFail($f_data['id']);
					$patient_log->update($activity_data);
				}
			}
			}
			
		}
		if(!empty($request->activity_data)){
			$activityData = json_decode($request->activity_data);
			if (is_array($activityData)) {
				// foreach($request->activity_data as $activity_data){
				foreach($activityData as $activity_data){
					PatientActivityRemark::updateOrCreate(['log_id' => $log->id, 'patient_id' => $log->patient_id , 'activity_id' => $activity_data->activity_id,'shift_id' => ($request->is_call_ad_hoc=='true')? 5: $request->shift_id],['shift_id' => ($request->is_call_ad_hoc=='true')? 5: $request->shift_id, 'status' => isset($activity_data->status) ? $activity_data->status : 0, 'remark' => isset($activity_data->remark) ? $activity_data->remark : '']);
				}
			}
			
		}

		if(!empty($request->medicine_data)){
			$medicine_data = json_decode($request->medicine_data);
			if (is_array($medicine_data)) {
			// foreach($request->medicine_data as $medicine_data){
				foreach($medicine_data as $medicine_data){
					PatientMedicineRemark::updateOrCreate(['log_id' => $log->id, 'patient_id' => $log->patient_id , 'medicine_id' => $medicine_data->medicine_id,'shift_id' => $request->shift_id],['shift_id' => $request->shift_id, 'status' => isset($medicine_data->field_value) ? $medicine_data->field_value : '', 'remark_comment' => isset($medicine_data->remark) ? $medicine_data->remark : null]);
				}
			}
		}
		/*if($request->id == 0){
			$activity_data['created_by']  = Auth::user()->id;
			$patient_log = $this->create($log_data);
		}else{
			$patient_log = $this->findOrFail($request->id);
			$patient_log->update($log_data);
		}*/
		return true;
	}
	public function addUpdateMedLogActivity($request, $log)
	{
		
		$form_data = json_decode($request->medicine_data, true);
		if($request->type==1)
		{
            $medicine_data = json_decode($request->medicine_data,true);
			$item=$this->where(['log_id' => $log->id,'shift_id'=> 5,'field_id' => $medicine_data[1]['field_value']])->first();
		
			if(!empty($item))
					{
						$item['id']=$item->id;
					}
			$activity_data = [
				'log_id'      => $log->id,
				'shift_id'    => 5,
				'ad_hoc_time'  => date('h:i:s a'),
				'remark'      => $medicine_data[2]['remark'],
				'field_id'    => $medicine_data[1]['field_value'],
				'field_value' => $medicine_data[2]['field_value'],
				'status'      => 1,
				'is_from_app'      => 1,
				'updated_by'  => Auth::user()->id,
			];
			/* if($item==null){ */
				$activity_data['created_by']  = Auth::user()->id;
				$patient_log = $this->create($activity_data);
			/* }else{
				$patient_log = $this->findOrFail($item['id']);
				$patient_log->update($activity_data);
			} */
			if (is_array($medicine_data)) {
				// foreach($request->medicine_data as $medicine_data){
				
						PatientMedicineRemark::updateOrCreate(['log_id' => $log->id, 'patient_id' => $log->patient_id , 'medicine_id' =>  $medicine_data[1]['field_value'],'shift_id' =>5],['shift_id' => 5, 'status' => $medicine_data[2]['field_value'], 'remark_comment' => isset($medicine_data[2]['remark'])? $medicine_data[2]['remark'] : null,'note_time' => date('h:i:s a')]);
					
				}


		}
		else{
			$result= $this->where(['log_id'=>$log->id,'shift_id'=>$request->shift_id])->first();
		
		/* if($result && $request->type!=2){
			return false;
		} */
	
		if(count($form_data) > 0){
			foreach($form_data as $f_data){
				 $staff_note=null;
				$remark=null;
				$note_time=null;
				$med_time_array=[];
				$item=[];
				if(isset($f_data['id']) && $f_data['id']!=0)
				{
				$item=$this->where(['id' => $f_data['id']])->first();
				
				\Log::info(['item====' => $item]);
					if(!empty($item))
					{
						
						$f_data['id']=$item->id;
						$staff_note=$item->staff_note;
						$remark=$item->remark;
						$note_time=$item->note_time;
					} 
				}
				$med_data=PatientMedicine::where('id',$f_data['field_id'])->first();
				$med_time_array=json_decode($med_data->time_id,true);
					if($request->type!=2)
					{
					if(!empty($med_time_array) && $med_data->time_id!=0 && isset($f_data['field_value']))
					{
						$activity_data = [
							'log_id'      => $log->id,
							'shift_id'    => $request->shift_id,
							'remark'      => ($f_data['remark'])? $f_data['remark']:$remark,
							'field_id'    => $f_data['field_id'],
							'field_value' => $f_data['field_value'],
							'med_key' 	=> $f_data['med_key'],
							'med_time' => ($f_data['med_time']!=null)? date('h:i',strtotime($f_data['med_time'])) : null,
							'status'      => 1,
							'is_from_app'      => 1,
							'updated_by'  => Auth::user()->id,
						];
					}else{
						$activity_data=[];
					}
					}
			   else{
				$activity_data = [
					'staff_note'  => ($f_data['staff_note'])? $f_data['staff_note']:$staff_note,
					'note_time'  => ($note_time==null)? date('h:i a') : $note_time,
					'noted_by'  => Auth::user()->id,
				];

			   }
			  
				
					
			   \Log::info(['med_log_ditemmmmmmmatacreated' => $activity_data,"f_datacreated======"=>$f_data,"itemmmm=======>",$item]);
			   if(!empty($activity_data))
			   {
				if($item!==NULL && !empty($item)){
					if($request->type==2)
					{
						if($item->staff_note==null)
						{
					$patient_log = $this->findOrFail($f_data['id']);
					$patient_log->update($activity_data);
						}
					}
				}else{
					
					$activity_data['created_by']  = Auth::user()->id;
					$patient_log = $this->create($activity_data);
				}
			}
			}
			
		}

		if(!empty($request->medicine_data && $request->type!=2)){
			$medicine_data = json_decode($request->medicine_data,true);
			if (is_array($medicine_data)) {
			// foreach($request->medicine_data as $medicine_data){
				foreach($medicine_data as $medicine_data){
					$item=PatientMedicineRemark::where(['log_id' => $log->id,'shift_id'=>  $request->shift_id,'medicine_id' => $medicine_data['field_id'],'patient_id' => $log->patient_id])->first();
					$staff_note=null;
				   $remark=null;
				   $note_time=null;
				
					if(!empty($item))
					{
						$staff_note=$item->staff_note;
						$remark=$item->remark_comment;
						$note_time=$item->note_time;
					}
					PatientMedicineRemark::updateOrCreate(['log_id' => $log->id, 'patient_id' => $log->patient_id , 'medicine_id' => $medicine_data['field_id'],'shift_id' => $request->shift_id],
					['shift_id' => $request->shift_id, 
					'status' => isset($medicine_data['field_value']) ? $medicine_data['field_value'] : '',
					'remark_comment'      => ($request->type==3)? $medicine_data['remark'] :$remark,
					'staff_note'  => ($request->type==2)? $medicine_data['remark'] : $staff_note,
					'note_time'  => ($request->type==2)? date('h:i:s a') : $note_time]);
				}
			}
		}
	}
		

		
		
		return true;
	}

	public function addUpdateMedLogActivityAdmin($request, $log)
	{
		//dd($request->all());
		
		$form_data = $request->medicine_data;
		\Log::info(['form_data' => $form_data]);
		
		if(count($form_data) > 0){
			foreach($form_data as $f_data){
				if(isset($f_data['medicine_id']) && !empty($f_data['medicine_id'])){
					
					if(isset($f_data['field_value']))
					{
						if($request->shift_id==5)
						{
							$activity_data = [
								'log_id'      => $log->id,
								'shift_id'    => $request->shift_id,
								'remark'      => !empty($f_data['remark'])? $f_data['remark']:NULL,
								'field_id'    => $f_data['medicine_id'],
								'field_value' => $f_data['field_value'],
								'med_time' 	  => !empty($f_data['med_time']) ? $f_data['med_time'] : NULL,
								'med_key' 	  => !empty($f_data['med_key']) ? $f_data['med_key'] : NULL,
								'status'      => 1,
								'updated_by'  => $request->created_by, //Auth::user()->id,
							];
					
							$activity_data['created_by']  = $request->created_by;//Auth::user()->id;
							$patient_log = $this->create($activity_data);
						}else{
							/*$item=$this->where(['id' => $f_data['id']])->first();*/
							// $item=$this->where(['shift_id'=>$request->shift_id,'log_id'=>$log->id,'field_id'=>$f_data['medicine_id'],'med_key'=>$f_data['med_key'], 'med_time'=>$f_data['med_time']])->first();
							$item=$this->where(['shift_id'=>$request->shift_id,'log_id'=>$log->id,'field_id'=>$f_data['medicine_id'],'med_key'=>$f_data['med_key']])->first();
							$activity_data = [
								'log_id'      => $log->id,
								'shift_id'    => $request->shift_id,
								'remark'      => !empty($f_data['remark'])? $f_data['remark']:NULL,
								'field_id'    => $f_data['medicine_id'],
								'field_value' => $f_data['field_value'],
								'med_time' 	  => !empty($f_data['med_time']) ? $f_data['med_time'] : NULL,
								'med_key' 	  => !empty($f_data['med_key']) ? $f_data['med_key'] : NULL,
								'status'      => 1,
							];
						
							if($item!==NULL && !empty($item)){
								if($item['field_value']!=$f_data['field_value'])
								{
								$activity_data['updated_by']  = $request->created_by;
								}else{
								$activity_data['updated_by']  = $item->updated_by;
								}
								\Log::info(['update_activity_data_web'=>$activity_data,"update_item"=>$item]);
								$patient_log = $this->findOrFail($item['id']);
								$patient_log->update($activity_data);
							}else{
								$activity_data['updated_by']  = $request->created_by;
								$activity_data['created_by']  = $request->created_by; //Auth::user()->id;
								\Log::info(['create_activity_data_web'=>$activity_data,"create_item"=>$item]);
								$patient_log = $this->create($activity_data);
							}
						}
					
					}
				}
				
			}
			
		}
		return true;
	}

	public function assignActivity($request){
		$patient = Patient::select('id','home_id','created_by')->find($request->patient_id);

		$activity_data = [
            'patient_id'      => $request->patient_id,
            'activity_id'     => $request->activity,
            'duration'        => isset($request->duration) && $request->duration != '' ? $request->duration : 0,
            'frequency'       => isset($request->frequency) && $request->frequency != '' ? $request->frequency : 0,
            'recurrence'      => $request->recurrence,
			'description'     => isset($request->description) && $request->description != '' ? $request->description : NULL,
			'updated_by'      => Auth::user()->id,
        ];

		if ($request->patient_activity_id != 0) {
            $patient_activity = $this->findOrFail($request->patient_activity_id);
            $patientActivityData = $patient_activity->update($activity_data);

				$patient_activity['home_id'] = $patient->home_id;
				$patient_activity['care_home_admin'] = $patient->added_by;
				// call the event
				event(new CreateNotification('activity_assign_to_patient', $patient_activity));
        } else {
            $activity_data['created_by']        = Auth::user()->id;
            $patient_activity = $this->create($activity_data);
			$patient_activity->save();
			
	
			$patient_activity['home_id'] = $patient->home_id;
			$patient_activity['care_home_admin'] = $patient->added_by;
			// call the event
			event(new CreateNotification('activity_assign_to_patient', $patient_activity));
			
        }
		return true;
	}	

	public function getActivityName(){
		return $this->hasOne(Activity::class, 'id', 'activity_id');
	}

	public function getPatientName(){
		return $this->hasOne(Patient::class, 'id', 'patient_id');
	}
	public function getMedicinesForNote($request,$shift_id,$log_id)
	{
		
		$current_date = Carbon::now()->format('Y-m-d');
		
		$medicineArray = PatientMedicine::where('patient_id',$request->patient_id)->pluck('id')->toArray();
		$data = $this->join('patient_medicines', 'patient_medicines.id', '=', 'patient_activities.field_id')
			->where('patient_activities.log_id',$log_id)
             ->whereIn('patient_activities.field_id', $medicineArray)
             ->whereDate('patient_activities.created_at', $current_date)
			 ->whereNull('patient_activities.staff_note')
            // ->where('patient_activities.shift_id', $shift_id)
			// ->groupBy('patient_activities.id')
			->orderBy('patient_activities.id','desc')
			 ->select('patient_medicines.*', \DB::raw("'select' as type_name"),'patient_activities.id as id', 'patient_activities.staff_note as staff_note')
             ->get();
			
			 return $data;
	}
	public function getPatientTakenNedicine($patient_id)
	{
		$current_date = Carbon::now()->format('Y-m-d');
		$medicineArray = PatientMedicine::where('patient_id',$patient_id)->pluck('id')->toArray();
		$data = $this->join('patient_medicines', 'patient_medicines.id', '=', 'patient_activities.field_id')
					->join('patient_logs','patient_logs.id','=', 'patient_activities.log_id')
					->leftJoin('patient_activity_field_values', 'patient_activity_field_values.id', '=', 'patient_activities.field_value')
					->where('patient_logs.log_type',1)
             ->whereIn('patient_activities.field_id', $medicineArray)
			 ->orderBy('patient_logs.report_date', 'desc')
			 ->select('patient_medicines.*', \DB::raw("'select' as type_name"),'patient_activities.id as id','patient_logs.report_date','patient_activities.shift_id as activity_shift','patient_activity_field_values.name as field_value_name','patient_activities.remark','patient_activities.staff_note','patient_activities.created_at as activity_time','patient_activities.note_time as note_time','patient_activities.updated_at as updated_activity_time','patient_activities.med_time as med_time', 'patient_activities.med_key as med_key')
             ->get();
			 return $data;
	}
}
