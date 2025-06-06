<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Patient;
use App\Models\PatientMedicine;
use App\Models\PatientDocument;
use App\Models\Expense;
use App\Models\IncidentReport;
use App\Models\PatientLog;
use App\Models\PatientLogImage;
use App\Models\Activity;
use App\Models\PatientActivity;
use App\Models\ActivityTime;
use App\Models\PatientActivityField;
use App\Models\PatientAppointment;
use App\Models\PatientActivityRemark;
use App\Models\PatientMedicineRemark;
use App\Models\PatientAppointmentRemark;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Str, Arr, Auth;
use Carbon\Carbon;
use App\Events\CreateNotification;
use App\Models\Notification;
use App\Models\PatientActivityFieldValue;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PatientsController extends Controller
{
	private $patients;
	private $activity;
	private $patient_medicines;
	private $patient_documents;
	private $expenses;
	private $patient_logs;
	private $patient_activity;
	private $patient_log_images;
	private $patient_activity_fields;
	private $patient_activity_fields_value;
	private $activity_time;
	private $patient_activity_remarks;
	private $patient_appointments;
	private $users;
	private $patient_medicine_remarks;
	private $patient_incident_report;
	private $patient_appointment_remarks;
	
	/**
     * Constructor Instance
     */
	public function __construct(Patient $patients, PatientMedicine $patient_medicines, PatientDocument $patient_documents, 
	Expense $expenses, PatientLog $patient_logs, PatientLogImage $patient_log_images, Activity $activity, 
	PatientActivityField $patient_activity_fields, PatientActivity $patient_activity, ActivityTime $activity_time, PatientActivityRemark $patient_activity_remarks, PatientAppointment $patient_appointments,User $users, PatientMedicineRemark $patient_medicine_remarks, IncidentReport $patient_incident_report, PatientAppointmentRemark $patient_appointment_remarks,PatientActivityFieldValue $patient_activity_fields_value)
	{
		$this->patients                = $patients;
		$this->patient_medicines       = $patient_medicines;
		$this->patient_documents       = $patient_documents;
		$this->expenses        		   = $expenses;
		$this->patient_logs            = $patient_logs;
		$this->patient_log_images      = $patient_log_images;
		$this->activity                = $activity;
		$this->patient_activity        = $patient_activity;
		$this->patient_activity_fields = $patient_activity_fields;
		$this->patient_activity_fields_value = $patient_activity_fields_value;
		$this->activity_time           = $activity_time;
		$this->patient_activity_remarks= $patient_activity_remarks;
		$this->patient_appointments    = $patient_appointments;
		$this->users = $users;
		$this->patient_medicine_remarks = $patient_medicine_remarks;
		$this->patient_incident_report = $patient_incident_report;
		$this->patient_appointment_remarks = $patient_appointment_remarks;
	}
    
	/************************************************************
	********Patients*********
	***********************************************/
	
    public function addUpdatePatients(Request $request)
    {
       //Validate data
        $validator = Validator::make($request->all(), [
			'home_id' => 'required',
            'name' => 'required',
            //'email' => 'required|email|unique:patients,email',
            'address' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'emergency_contact' => 'required',
            'per_day_cost' => 'required',
            'admission_date' => 'required',
            'status' => 'required',
            'initial_payment' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()], 200);
        }
		
        $result = $this->patients->addUpdatePatient($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Patient added successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
    }
	
	public function deletePatients(Request $request)
	{
		$result = $this->patients->deletePatient($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Patient delete request sent successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}

	public function getTaskPatientsList(Request $request)
    {
		$patients = $this->patients->getTaskPatients($request);
		return response()->json([
			'status' => 'success',
			'list' => $patients,
		]);
	}
	
	public function getPatientsList(Request $request)
    {
		//$data =  $this->patients->paginate(request()->all());
		$data = $this->patients->getPatientList($request);
		//echo"<pre>";print_r($data->get());die;
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
		$pagination = $this->prepareApiPaginationData($request, $records, $total_count);
		return response()->json([
                'status' => 'success',
                'list' => $records,
				'pagination' => $pagination,
            ]);
    }
	
	public function getPatientByID(Request $request)
	{
		$data =  $this->patients->find($request->id);
		$data->admission_date=date('m-d-Y',strtotime($data->admission_date));
		return response()->json([
                'status' => 'success',
                'user' => $data,
				'user' => $data,
				'incident' => [
					['id' => 1, 'incident_reason' => '2test', 'report_to_nine' => '1', 'report_to_rcs' => '0', 'report_to_manager' => '1'],
					['id' => 2, 'incident_reason' => 'lorem ipsum', 'report_to_nine' => '1', 'report_to_rcs' => '1', 'report_to_manager' => '0' ],
					['id' => 3, 'incident_reason' => 'tetss', 'report_to_nine' => '0', 'report_to_rcs' => '1', 'report_to_manager' => '1'],
					['id' => 4, 'incident_reason' => '2tetst', 'report_to_nine' => '0', 'report_to_rcs' => '0', 'report_to_manager' => '0'],
				]
            ]);
	}
	
	/************************************************************
	********Patient Medicines*********
	***********************************************/
	
	public function getPatientsMedicineList(Request $request)
	{
		$data = $this->patient_medicines->getMedicineList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
		$pagination = $this->prepareApiPaginationData($request, $records, $total_count);
		$medicine_time_other   = config('const.medicine_time_other');
		return response()->json([
                'status' => 'success',
                'list' => $records,
				'pagination' => $pagination,
				'medicine_time_other'=>$medicine_time_other
            ]);
	}
	
	public function getMedicineFormDate(Request $request)
	{
		$home_id = $this->patients->where('id', $request->patient_id)->pluck('home_id');
		$medicine_type   = config('const.medicine_type');
		$dose_type   = config('const.dose_type');
		$medicine_time   = config('const.medicine_time');
		$intake_method   = config('const.medicine_intake_method');
		$intake_guidedby = config('const.medicine_intake_supervised_by');
		$med_frequency = config('const.medicine_frequency');
		$medicine_time_other = config('const.medicine_time_other');
		$activity_shifts = config('const.activity_shifts');
		$times      = $this->activity_time->where('home_id', $home_id)->first();
		$log_times = config('const.log_times');
		if($times){
			$log_times = [1 => $times->morning_time, 2 => $times->afternoon_time, 3 => $times->evening_time,4=>$times->night_time];
		}else{
			$log_times = $log_times;
		}
		$activity_time_range = [];
		foreach ($log_times as $key => $time) {
			$time_range = $log_times[$key];
		
			$times = explode(' - ', $time_range);
			$start_time = $times[0];
			$end_time = $times[1];
		
			$start_time_parts = explode(':', $start_time);
			$activity_start_hour = $start_time_parts[0];
			$activity_start_min = $start_time_parts[1];
		
			$end_time_parts = explode(':', $end_time);
			$activity_end_hour = $end_time_parts[0];
			$activity_end_min = $end_time_parts[1];
		
			array_push($activity_time_range, [
				"start_hour" => (int)$activity_start_hour,
				"start_min" => (int)$activity_start_min,
				"end_hour" => (int)$activity_end_hour,
				"end_min" => (int)$activity_end_min,
				"disabled" => true,
				"shift_name" => $activity_shifts[$key],
				"key" => $key,
				"time_pop_up" => false,
				"time" => null,
				"additionalTimes"=>[
					["time" => null,
					"popup"=>false,
					"boxkey"=>0
					]
				],
			]);
		}
		
		$med_frequency_customOrder=['1', '5', '2', '3', '4'];
		return response()->json(['status' => 'success', 'message' => '', 'medicine_type' => $medicine_type, 'medicine_time' => $medicine_time, 'intake_method' => $intake_method, 'intake_guidedby' => $intake_guidedby,'dose_type'=>$dose_type,'med_frequency'=>$med_frequency,'med_frequency_customOrder'=>$med_frequency_customOrder,'activity_time_range'=>$activity_time_range,'medicine_time_other'=>$medicine_time_other], 200);
	}
	
	public function addUpdatePatientsMedicine(Request $request)
	{
		//echo"<pre>";print_r($request->all());die;
		\Log::info(["request Medicine====>"=>$request->all()]);
		$result = $this->patient_medicines->addUpdateMedicine($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Patient medicine added/updated successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}

	public function getPatientsAppointments(Request $request)
	{
		//$data = [];
		$data = $this->patient_appointments->with('marked_by','patient_appointment_remarks')->where('patient_id', $request->patient_id);
		if(!empty($request->start_date) &&  !empty($request->end_date)){
			$data = $data->whereBetween('appointment_date', [$request->start_date, $request->end_date]);
		}
		//dd($request->status);
		if($request->status == '0' || $request->status == '1'){
			$data = $data->where('status', $request->status);
		}else{
			$data = $data->where('status', NULL);
		}
		$data->orderBy('appointment_date','asc');
		$total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
		$records->each(function ($record) {
			$record->patient_appointment_remarks = $record->patient_appointment_remarks;
			if($record->doctor_name!='other')
			{
				$record->doctor_name = $record->appointed_doctor ? $record->appointed_doctor->name : null;
			}else{
				$record->doctor_name = $record->other_doctor ? $record->other_doctor : null;
			}
			$today = \Carbon\Carbon::today()->toDateString();
			$record->disabled = false;

			// Check if today's date exists in any of the appointment_date entries in patient_appointment_remarks array
			foreach ($record->patient_appointment_remarks as $remark) {
				if (isset($remark['appointment_date']) && $remark['appointment_date'] == $today) {
					$record->disabled = true;
					break; // No need to continue checking once we find today's date
				}
			}
			$record->makeHidden(['appointed_doctor']);
		});

		if(!$records->isEmpty())
			{
			foreach ($records as $act) {
				$records = $records->filter(function ($act) {
					$keepRecord = true;
					
					if ($act->schedule_frequency == 2) { // Weekly
						$currentDay = strtoupper(date('l'));
						$expectedDays = json_decode($act->other_schedule_frequency, true);
						$expectedDays = is_array($expectedDays) ? array_map('strtoupper', $expectedDays) : [];
						if (!in_array($currentDay, $expectedDays)) {
							$keepRecord = false;
						}
					} 
					
					else if ($act->schedule_frequency == 4) { // Monthly
						$currentDateOfMonth = date('j');
						$expectedDays = json_decode($act->other_schedule_frequency, true);
						$expectedDays = is_array($expectedDays) ? $expectedDays : [];
						if (!in_array($currentDateOfMonth, $expectedDays)) {
							$keepRecord = false;
						}
					} 
					
					else if ($act->schedule_frequency == 5) { // Specific Date
						$currentDateOfMonth = date('Y-m-d');
						if ($currentDateOfMonth !== $act->appointment_date) {
							$keepRecord = false;
						}
					}
					
					return $keepRecord;
				});
			}
			$records= $records->values();
		}
		$pagination = $this->prepareApiPaginationData($request, $records, $total_count);

		return response()->json([
                'status' => 'success',
                'list' => $records,
				'pagination' => $pagination,
            ]);




		return response()->json(['status' => 'success', 'message' => 'Appointment get successfully', 'data' => $data], 200);
	}

	public function getPatientsAction(Request $request)
	{
		$data = [];
		// $appointmentData = $this->patient_appointments->where('id', $request->id)->update([
		// 	'status' => $request->type,
		// 	'reason' => !empty($request->remark) ? $request->remark : NULL,
		// 	'marked_by' => !empty($request->marked_by) ? $request->marked_by : NULL,
		// ]);

		// Check if a Appointment Remark exists for the appointment ID and current date
		$appointmentRemark = $this->patient_appointment_remarks->where('appointment_id', $request->id)
		->whereDate('created_at', Carbon::today())
		->first();
		
		if (!$appointmentRemark) {
			PatientAppointmentRemark::create([
				'appointment_id' => $request->id,
				'appointment_date' => date('Y-m-d'),
				'status' => $request->type,
				'remark' => !empty($request->remark) ? $request->remark : NULL,
				'marked_by' => !empty($request->marked_by) ? $request->marked_by : NULL,
			]);
		}else{
			$appointmentRemark->update([
				// 'appointment_id' => $request->id,
				'appointment_date' => date('Y-m-d'),
				'status' => $request->type,
				'remark' => !empty($request->remark) ? $request->remark : NULL,
				'marked_by' => !empty($request->marked_by) ? $request->marked_by : NULL,
			]);
		}
			
		return response()->json(['status' => 'success', 'message' => 'Appointment status updated'], 200);
	}
	
	public function deletePatientsMedicine(Request $request)
	{
		$result = $this->patient_medicines->deleteMedicine($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Patient medicine deleted successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}
	
	/************************************************************
	********Patient Documents*********
	***********************************************/
	
	public function getPatientsDocumentList(Request $request)
	{
		$document_type = config('const.patient_document_type');
		$data = $this->patient_documents->getDocumentList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
		$pagination = $this->prepareApiPaginationData($request, $records, $total_count);
		return response()->json([
                'status' => 'success',
				'document_type' => $document_type,
                'list' => $records,
				'pagination' => $pagination,
            ]);
	}
	
	public function addUpdatePatientsDocument(Request $request)
	{
		//echo"<pre>";print_r($request->all());die;
		$result = $this->patient_documents->addUpdateDocument($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Patient document added/updated successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}
	
	public function deletePatientsDocument(Request $request)
	{
		$result = $this->patient_documents->deleteDocument($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Patient document deleted successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}
	
	/************************************************************
	********Patient Expenses*********
	***********************************************/
	
	public function addUpdatePatientsExpense(Request $request)
	{
		$result = $this->expenses->addUpdateExpense($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Patient Expense added/updated successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}

	public function getPatientsExpenseList(Request $request)
	{
		//$result = $this->expenses->where('patient_id', $request->patient_id)->get();
		$expense_type = config('const.expense_type');
		$data = $this->expenses->getExpenseListByID($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
		$pagination = $this->prepareApiPaginationData($request, $records, $total_count);
		return response()->json([
                'status' => 'success',
				'expense_type' => $expense_type,
                'list' => $records,
				'pagination' => $pagination,
            ]);
	}

	public function deletePatientsExpense(Request $request)
	{
		$result = $this->expenses->deleteExpense($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Patient Expense deleted successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}
	/************************************************************
	********Patient Logs*********
	***********************************************/
	
	public function getShiftLogTimeInfo(Request $request)
	{
		$activity_shifts  = config('const.activity_shifts');
		$times            = $this->activity_time->where('home_id', Auth::user()->home_id)->first();
		$log_times = config('const.log_times');
		if($times){
			$log_times = [1 => $times->morning_time, 2 => $times->afternoon_time, 3 => $times->evening_time,4=>$times->night_time];
		}else{
			$log_times = $log_times;
		}
		return response()->json(['status' => 'success', 'log_times' => $log_times, 'activity_shifts' => $activity_shifts,'timezone'=>'America/Los_Angeles'], 200);
	}
	
	/*public function getPatientsLogForm(Request $request)
	{
		$check = $this->patient_logs->where('patient_id', $request->id)->whereDate('report_date', '=', date('Y-m-d'))->get();
		$form_data = $log_data = $log_images = '';
		if($check->count() == 0){
			$shift_schedule = config('const.shift_schedule');
			$form_format = [];
			foreach($shift_schedule as $shiftkey => $schedule){
				$shift_data = [];
				foreach($schedule as $key => $sch){
					$data['label']      = $sch[0];
					$data['value']      = '';
					$data['message']    = '';
					$data['field_type'] = $sch[1];
					$data['icon']       = asset('assets/img/activity-icon/'.$sch[2]);
					$shift_data[] = $data;
				}
				$form_format[$shiftkey] = $shift_data;
			}
			$form_data = json_encode($form_format);
		}else{
			$form_data = $check[0]->report;
			$log_data = collect($check->toArray())->transform(fn ($item) => Arr::except($item, 'report'));
			$log_images = $this->patient_log_images->where('log_id', $check[0]->id)->get();
		}
		$shift_id = $request->shift_id;
		$activities = $this->activity->whereRaw('FIND_IN_SET('.$shift_id.',shift_id)')->where('status', 1)->get();
		return response()->json(['status' => 'success', 'log_images' => $log_images, 'form_data' => $form_data, 'data' => $log_data != '' ? $log_data[0] : '', 'activities' => $activities], 200);
	}*/
	
	public function getPatientsLogForm(Request $request)
	{
		
		$super_admin_ids = $this->users->where('role_id',1)->pluck('id')->toArray();
		$home_id = $this->patients->where('id', $request->id)->pluck('home_id');
		$log = $this->patient_logs->where('patient_id', $request->id)->where('log_type',0)->whereDate('report_date', '=', date('Y-m-d'))->first();
	
		$shift_id = $request->shift_id;
		$times      = $this->activity_time->where('home_id', $home_id)->first();
		$log_times = config('const.log_times');
		if($times){
			$log_times = [1 => $times->morning_time, 2 => $times->afternoon_time, 3 => $times->evening_time,4=>$times->night_time];
		}else{
			$log_times = $log_times;
		}
		$shift_time=array_key_exists($shift_id,$log_times);
		// $shift_id=checkTimezone($log_times);
		
		//$activity_shift_id = $shift_id == 1 ? 1 : ($shift_id == 2 ? 20 : 32);
		$shiftNames = [
			1 => 'morning',
			2 => 'afternoon',
			3 => 'evening',
			4 => 'night'
		];
		
		$activity_shift_id = $shiftNames[$shift_id] ?? 'unknown_shift';
		$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => $activity_shift_id])->get()->toArray();
		$log_id = $log ? $log->id : 0;
		$submited_form     = $this->patient_activity->where(['shift_id' => $shift_id, 'log_id' => $log_id])->get();
		/*if($log){
			$activity_form = $this->patient_activity->where(['shift_id' => $shift_id, 'log_id' => $log->id])->get();
			if($activity_form->count() == 0){
				$activity_form    = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'id' => $activity_shift_id])->get()->toArray();
			}
		}else{
			$activity_form    = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'id' => $activity_shift_id])->get()->toArray();
		}*/
	/* 	foreach($activity_form['0']['subchilds'] as $key => $subchild){
			
			if($subchild['slug'] == 'medication' || $subchild['slug'] == 'Medication')
			{
				 foreach($subchild['subchilds'] as $key1 => $subchild1){
					if($subchild1['slug'] == 'medicines'){
						$options = $subchild1['options'];
						foreach($patient_medicines as $key2 => $patient_medicine){
							$patient_medicines[$key2]['options'] = $options;
						}
					}
				} 
			}
		} */
		/* Remove Medication in acrivity tab */
		$time_range=$log_times[$shift_id];

		list($start_time, $end_time) = explode(' - ', $time_range);

		list($activity_start_hour, $activity_start_min) = explode(':', $start_time);
		list($activity_end_hour, $activity_end_min) = explode(':', $end_time);
      $activity_time_range=[
		"start_hour" => (int)$activity_start_hour,
		"start_min" => (int)$activity_start_min,
		"end_hour" => (int)$activity_end_hour,
		"end_min" =>(int)$activity_end_min,
	  ];
		$patient_medicines = [];
		foreach ($activity_form[0]['subchilds'] as $key => $subchild) {
			
			if (strcasecmp($subchild['slug'], 'medication') === 0) {
				unset($activity_form[0]['subchilds'][$key]);
			}
			
		}
		
		$activity_form[0]['subchilds'] = array_values($activity_form[0]['subchilds']);
		/* end Remove Medication in acrivity tab */
		
		$activities  = $this->activity->whereRaw('(home_id = 0 OR home_id = ?)', [$home_id])
			->whereRaw('(patient_id = 0 OR patient_id = ?)', [$request->id])
			->whereRaw('FIND_IN_SET('.$shift_id.',shift_id)')
			->where(function ($query) use ($super_admin_ids) {
				$query->where('created_by', Auth::user()->parent_id)
					->orWhereIn('created_by', $super_admin_ids);
			})
			->where('status', 1)->get();
			if(!$activities->isEmpty())
			{
			foreach ($activities as $act) {
				if($act->recurrence==2)
				{
			$currentDay = strtolower(date('l'));
			$expectedDays = json_decode($act->activity_performance_day, true);
			$expectedDays = array_map('strtoupper', $expectedDays);
			if (is_array($expectedDays)) {
			if (!in_array(strtoupper($currentDay), $expectedDays)) {
				$activities = $activities->filter(function ($item) use ($act) {
					return $item->id != $act->id;
				});
			}
		}
				}
					
				else if ($act->recurrence == 3) { // Monthly
					$currentDateOfMonth = date('j');
					$expectedDays = json_decode($act->activity_performance_day, true);
					if (is_array($expectedDays)) {
					if (!in_array($currentDateOfMonth, $expectedDays)) {
						$activities = $activities->filter(function ($item) use ($act) {
							return $item->id != $act->id;
						});
					}
				}
				}
			}
			$activities= $activities->values();
		}
		$activities_remarks = $this->patient_activity_remarks->where('log_id', $log_id)->where('shift_id',$shift_id)->get();
		$patient_medicines_remarks = $this->patient_medicine_remarks->where('log_id', $log_id)->where('shift_id',$shift_id)->get();
		return response()->json(['status' => 'success', 'activity_form' => $activity_form, 'activities' => $activities, 'submited_form' => $submited_form, 'log' => $log, 'patient_medicines' => $patient_medicines, 'activities_remarks' => $activities_remarks, 'patient_medicines_remarks' => $patient_medicines_remarks,"activity_time_range"=>$activity_time_range]);
	}
	public function getPatientsAdHocForm(Request $request)
	{
		$super_admin_ids = $this->users->where('role_id',1)->pluck('id')->toArray();
		$home_id = $this->patients->where('id', $request->id)->pluck('home_id');
		$log = $this->patient_logs->where('patient_id', $request->id)->where('log_type',0)->whereDate('report_date', '=', date('Y-m-d'))->first();
		$shift_id = $request->shift_id;
		//$activity_shift_id = $shift_id == 1 ? 1 : ($shift_id == 2 ? 20 : 32);
		$shiftNames = [
			1 => 'morning',
			2 => 'afternoon',
			3 => 'evening',
			4 => 'night'
		];
		
		$activity_shift_id = $shiftNames[$shift_id] ?? 'unknown_shift';
		$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => 'ad_hoc'])->get()->toArray();
		
		$log_id = $log ? $log->id : 0;
		$submited_form     = $this->patient_activity->where(['shift_id' => $shift_id, 'log_id' => $log_id])->get();
		
		/* Remove Medication in acrivity tab */
		foreach ($activity_form[0]['subchilds'] as $key => $subchild) {
			if (strcasecmp($subchild['slug'], 'medication') === 0) {
				unset($activity_form[0]['subchilds'][$key]);
			}
		}
		
		$activity_form[0]['subchilds'] = array_values($activity_form[0]['subchilds']);
		/* end Remove Medication in acrivity tab */
	
		$activities_remarks = $this->patient_activity_remarks->where('log_id', $log_id)->where('shift_id',5)->get();
		$patient_medicines_remarks = $this->patient_medicine_remarks->where('log_id', $log_id)->where('shift_id',5)->get();
		return response()->json(['status' => 'success', 'activity_form' => $activity_form, 'submited_form' => $submited_form, 'log' => $log, 'activities_remarks' => $activities_remarks, 'patient_medicines_remarks' => $patient_medicines_remarks]);
	}
	
	public function addUpdatePatientsLog(Request $request)
	{
		//echo"<pre>";print_r($request->all());die;
		$result = $this->patient_logs->addUpdateLog($request);
		// if(!$result){
		// 	return response()->json(['status' => 'success', 'message' => 'This activity already exist.'], 200);
		// }
		if($result){
			$activity = $this->patient_activity->addUpdateLogActivity($request, $result); 
			if($activity){
				return response()->json(['status' => 'success', 'message' => 'Patient log added/updated successfully'], 200);
			}else{
				return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
			}
		}else{
			    return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}


	}
	public function addUpdateMedLog(Request $request)
	{
		//echo"<pre>";print_r($request->all());die;
		
		$result = $this->patient_logs->addUpdateMedLog($request);
		
		if($result){
			$activity = $this->patient_activity->addUpdateMedLogActivity($request, $result);
			
			if($activity){
				return response()->json(['status' => 'success', 'message' => 'Patient log added/updated successfully'], 200);
			}else{
				return response()->json(['status' => 'error', 'message' => 'Medicine report already exist.'], 200);
			}
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}

	public function getPatientsLogList(Request $request)
	{
		$shift_schedule = config('const.shift_schedule');
		$current_time = Carbon::now()->format('H:i');
		$currentTimeCarbon = Carbon::createFromFormat('H:i', $current_time);
		$home_id = $this->patients->where('id', $request->patient_id)->pluck('home_id');
		$times      = $this->activity_time->where('home_id', $home_id)->first();
		$log_times = config('const.log_times');
		if($times){
			$log_times = [1 => $times->morning_time, 2 => $times->afternoon_time, 3 => $times->evening_time,4=>$times->night_time];
		}else{
			$log_times = $log_times;
		}
		$activity_time_exist = false;
		foreach($log_times as $key => $time){
			list($start, $end) = explode(' - ', $time);
			$startCarbon = Carbon::createFromFormat('H:i', $start);
			$endCarbon = Carbon::createFromFormat('H:i', $end);
			
			if ($currentTimeCarbon->between($startCarbon, $endCarbon)) {
				$activity_time_exist = true;
				break;
			}
		}
		$data = $this->patient_logs->getLogList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
		$pagination = $this->prepareApiPaginationData($request, $records, $total_count);
		
		// $get_shift = ;
		return response()->json([
                'status' => 'success',
				'shift_schedule' => $shift_schedule,
                'list' => $records,
				'pagination' => $pagination,
				'activity_time_exist'=> $activity_time_exist,
            ]);
	}
	public function getPatientsMedLogList(Request $request)
	{
		//$shift_schedule = config('const.shift_schedule');
		$current_time = Carbon::now()->format('H:i');
		$currentTimeCarbon = Carbon::createFromFormat('H:i', $current_time);
		$home_id = $this->patients->where('id', $request->patient_id)->pluck('home_id');
		$times      = $this->activity_time->where('home_id', $home_id)->first();
		$log_times = config('const.log_times');
		if($times){
			$log_times = [1 => $times->morning_time, 2 => $times->afternoon_time, 3 => $times->evening_time,4=>$times->night_time];
		}else{
			$log_times = $log_times;
		}
		$shift_id=0;
		$activity_time_exist = false;
		foreach($log_times as $key => $time){
			list($start, $end) = explode(' - ', $time);
			$startCarbon = Carbon::createFromFormat('H:i', $start);
			$endCarbon = Carbon::createFromFormat('H:i', $end);
			
			if ($currentTimeCarbon->between($startCarbon, $endCarbon)) {
				$activity_time_exist = true;
				$shift_id=$key;
				break;
			}
		}
		
		$adHocMedData= $this->patient_medicines->getAdHocMedicines($request);
		$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => 'ad_hoc'])->get()->toArray();
		foreach($activity_form['0']['subchilds'] as $key => $subchild){
			
			if($subchild['slug'] == 'medication' || $subchild['slug'] == 'Medication')
			{
				foreach($subchild['subchilds'] as $key1 => $subchild1){
					if($subchild1['slug'] == 'medicines'){
						$options = $subchild1['options'];
						foreach($adHocMedData as $key2 => $patient_medicine){
							$adHocMedData[$key2]['options'] = $options;
						}
					}
				}
			}
		}
		$data = $this->patient_logs->getMedLogList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
		$pagination = $this->prepareApiPaginationData($request, $records, $total_count);
		
		// $get_shift = ;
		return response()->json([
                'status' => 'success',
			//	'shift_schedule' => $shift_schedule,
                'list' => $records,
				'pagination' => $pagination,
				'activity_time_exist'=> $activity_time_exist,
				'note_med_list'=>[],
				'ad_hoc_med_list'=>$adHocMedData,
            ]);
	}
	public function getPatientsMedListByType(Request $request)
	{
		
		$current_date = Carbon::now()->format('Y-m-d');
		$current_time = Carbon::now()->format('H:i');
		$currentTimeCarbon = Carbon::createFromFormat('H:i', $current_time);
		$home_id = $this->patients->where('id', $request->patient_id)->pluck('home_id');
		$times      = $this->activity_time->where('home_id', $home_id)->first();
		$log_times = config('const.log_times');
		if($times){
			$log_times = [1 => $times->morning_time, 2 => $times->afternoon_time, 3 => $times->evening_time,4=>$times->night_time];
		}else{
			$log_times = $log_times;
		}
		$exsistshift_id=0;
		$activity_time_exist = false;
		foreach($log_times as $key => $time){
			list($start, $end) = explode(' - ', $time);
			$startCarbon = Carbon::createFromFormat('H:i', $start);
			$endCarbon = Carbon::createFromFormat('H:i', $end);
			
			if ($currentTimeCarbon->between($startCarbon, $endCarbon)) {
				$activity_time_exist = true;
				$exsistshift_id=$key;
				\Log::info(['exsistshift_id' => $exsistshift_id]);
				break;
			}
		}
		$shift_id = $exsistshift_id;

		\Log::info(['shift_id' => $shift_id]);
		$log = $this->patient_logs->where('patient_id', $request->patient_id)->where('log_type',1)->whereDate('report_date', '=', date('Y-m-d'))->first();
		$log_id = $log ? $log->id : 0;
	
		$patient_medicines_remarks = $this->patient_medicine_remarks->where('log_id', $log_id)->where('shift_id',$shift_id)->get();
		
		if($shift_id!=0)
		{
			$shiftNames = [
				1 => 'morning',
				2 => 'afternoon',
				3 => 'evening',
				4 => 'night',
				5 => 'ad_hoc'
			];
			
			$activity_shift_id = $shiftNames[$exsistshift_id] ?? 'unknown_shift';
			$shiftName=getShiftName($exsistshift_id);
		}
		if($request->type==1)
		{
			
			$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => 'ad_hoc'])->get()->toArray();
			
			$patient_medicines = $this->getMedicineByType($request,1,$shift_id);
			$patient_medicines->each(function ($record) {
				if (strpos($record->dose, '__') !== false) {
												
					$doseData = explode('__', $record->dose);
					$dose = $doseData[0];
					$dose_type = $doseData[1];
					if($dose_type=='other')
					{
						$dose_dis=$dose.' '.$record->other_dose;
					}else{
						$dose_dis=$dose.' '.$dose_type;
					}
				} else {
					$dose_dis = $record->dose;
					
				}
				$record->name = $record->name ? $record->name.' '.$dose_dis : null;
			});
			$options=[];
		 	foreach($activity_form['0']['subchilds'] as $key => $subchild){
				if($subchild['slug'] == 'medication' || $subchild['slug'] == 'Medication')
				{
					foreach($subchild['subchilds'] as $key1 => $subchild1){
						if($subchild1['slug'] == 'medicines'){
							
							$options = $subchild1['options'];
							
						}
					}
				}
			} 



			$newArray = [
				[
					'id' => 1,
					'name' => 'Medicine Name',
					'type_name' => 'Select',
					'options' => []
				],
				[
					'id' => 2,
					'name' => 'Status',
					'type_name' => 'Select',
					'options' => (!$patient_medicines->isEmpty())? $options:[],
				],
				/* [
					'id' => 3,
					'name' => 'Time',
					"icon"=> "assets/img/activity-icon/clock-icon.png",
					'type_name' => 'Time',
					'options' => []
				] */
			];

			if(!$patient_medicines->isEmpty())
			{
				
				foreach($patient_medicines as $key=> $med)
				{
					$newArray[0]['options'][] =  $med;
				}
			}else{
				$newArray=[];
			}
			
			
			return response()->json([
                'status' => 'success',
				'patient_medicines'=>$newArray,
            ]);
		}else if($request->type==2)
		{
			if($shift_id!=0)
		{
			$submited_form     = $this->patient_activity->where(['log_id' => $log_id])->whereNull('staff_note')->select('*',\DB::raw("1 as remark_required"))->get();
			$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => $activity_shift_id])->get()->toArray();
			
			$patient_medicines= $this->patient_activity->getMedicinesForNote($request,$shift_id,$log_id);
			$patient_medicines->each(function ($record) {
				if (strpos($record->dose, '__') !== false) {
												
					$doseData = explode('__', $record->dose);
					$dose = $doseData[0];
					$dose_type = $doseData[1];
					if($dose_type=='other')
					{
						$dose_dis=$dose.' '.$record->other_dose;
					}else{
						$dose_dis=$dose.' '.$dose_type;
					}
				} else {
					$dose_dis = $record->dose;
					
				}
				$record->name = $record->name ? $record->name.' '.$dose_dis : null;
			});
			if(!$patient_medicines->isEmpty())
			{
				foreach($activity_form['0']['subchilds'] as $key => $subchild){
					
					if($subchild['slug'] == 'medication' || $subchild['slug'] == 'Medication')
					{
						foreach($subchild['subchilds'] as $key1 => $subchild1){
							if($subchild1['slug'] == 'medicines'){
								
								$options = collect($subchild1['options'])->map(function($item) {
									$item['remark'] = 1;
									return $item;
								});
								foreach($patient_medicines as $key2 => $patient_medicine){
									$patient_medicines[$key2]['options'] = $options;
								}
							}
						}
					}
				}
			}else{
				$patient_medicines=[];
			}
			return response()->json([
				'status' => 'success',
				'patient_medicines' => $patient_medicines,
				'submited_form'=>$submited_form,
				//'patient_medicines_remarks'=>$patient_medicines_remarks,
			//	'log' => $log,
			    'shift_name'=>$shiftName.' Shift',
				'shift_time'=>$log_times[$exsistshift_id],
				'activity_time_exist'=> true,
				'shift_id'=>$exsistshift_id,
				
			]);
		}
			
		}
		else if($request->type==3){
			if($shift_id!=0)
		{
		
			$submited_form     = $this->patient_activity->where(['log_id' => $log_id,"shift_id"=>$shift_id])->select('*',\DB::raw("1 as remark_required"),\DB::raw("CONCAT(field_id, '_', med_key) as new_id"))->get();
			$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => $activity_shift_id])->get()->toArray();
			
			$patient_medicines = $this->getMedicineByType($request,3,$shift_id);
		
			
			$patient_medicines->each(function ($record) use ($submited_form,$shift_id) {
				$record->disable = false;
			
				// Check if there's a matching entry in the submitted form
				foreach ($submited_form as $submitted) {
					if ($submitted->field_id == $record->id && $submitted->med_key == $record->med_key && $submitted->shift_id == $shift_id) {
						$record->disable = true;
						break;
					}
				}

				if (strpos($record->dose, '__') !== false) {
												
					$doseData = explode('__', $record->dose);
					$dose = $doseData[0];
					$dose_type = $doseData[1];
					if($dose_type=='other')
					{
						$dose_dis=$dose.' '.$record->other_dose;
					}else{
						$dose_dis=$dose.' '.$dose_type;
					}
				} else {
					$dose_dis = $record->dose;
					
				}
				$record->name = $record->name ? $record->name.' '.$dose_dis : null;
			});
			foreach($activity_form['0']['subchilds'] as $key => $subchild){
				
				if($subchild['slug'] == 'medication' || $subchild['slug'] == 'Medication')
				{
					foreach($subchild['subchilds'] as $key1 => $subchild1){
						if($subchild1['slug'] == 'medicines'){
							$options = $subchild1['options'];
							foreach($patient_medicines as $key2 => $patient_medicine){
								$patient_medicines[$key2]['options'] = $options;
							}
						}
					}
				}
			}
			
			return response()->json([
				'status' => 'success',
				'patient_medicines' => $patient_medicines,
				'submited_form'=>$submited_form,
			//	'patient_medicines_remarks'=>$patient_medicines_remarks,
				//'log' => $log,
				'shift_name'=>$shiftName.' Shift',
				'shift_time'=>$log_times[$exsistshift_id],
				'activity_time_exist'=> $activity_time_exist,
				'shift_id'=>$exsistshift_id,
				
			]);
		}

		}
	if($shift_id==0)
	{
		return response()->json([
			'status' => 'success',
			'activity_time_exist'=> false,
			'shift_id'=>$exsistshift_id,
			
		]);

	}
	
		
		
		
	}

	public function getPatientsMultiMedListByType(Request $request)
	{
		$current_date = Carbon::now()->format('Y-m-d');
		$current_time = Carbon::now()->format('H:i');
		$currentTimeCarbon = Carbon::createFromFormat('H:i', $current_time);
		$home_id = $this->patients->where('id', $request->patient_id)->pluck('home_id');
		$times      = $this->activity_time->where('home_id', $home_id)->first();
		$log_times = config('const.log_times');
		if($times){
			$log_times = [1 => $times->morning_time, 2 => $times->afternoon_time, 3 => $times->evening_time,4=>$times->night_time];
		}else{
			$log_times = $log_times;
		}
		$exsistshift_id=0;
		$activity_time_exist = false;
		foreach($log_times as $key => $time){
			list($start, $end) = explode(' - ', $time);
			$startCarbon = Carbon::createFromFormat('H:i', $start);
			$endCarbon = Carbon::createFromFormat('H:i', $end);
			
			if ($currentTimeCarbon->between($startCarbon, $endCarbon)) {
				$activity_time_exist = true;
				$exsistshift_id=$key;
				\Log::info(['exsistshift_id' => $exsistshift_id]);
				break;
			}
		}
		$shift_id = $exsistshift_id;

		\Log::info(['shift_id' => $shift_id]);
		$log = $this->patient_logs->where('patient_id', $request->patient_id)->where('log_type',1)->whereDate('report_date', '=', date('Y-m-d'))->first();
		$log_id = $log ? $log->id : 0;
	
		$patient_medicines_remarks = $this->patient_medicine_remarks->where('log_id', $log_id)->where('shift_id',$shift_id)->get();
		
		if($shift_id!=0)
		{
			$shiftNames = [
				1 => 'morning',
				2 => 'afternoon',
				3 => 'evening',
				4 => 'night',
				5 => 'ad_hoc'
			];
			
			$activity_shift_id = $shiftNames[$exsistshift_id] ?? 'unknown_shift';
			$shiftName=getShiftName($exsistshift_id);
		}
		if($shift_id!=0)
		{
		
			$submited_form     = $this->patient_activity->where(['log_id' => $log_id,"shift_id"=>$shift_id])->select('*',\DB::raw("1 as remark_required"),\DB::raw("CONCAT(field_id, '_', med_key) as new_id"))->get();
			$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => $activity_shift_id])->get()->toArray();
			
			$patient_medicines = $this->getMultiMedicineByType($request,3,$shift_id);
		
			$records = $patient_medicines->items();
			$pagination = [
				'total' => $patient_medicines->total(),
				'current_page' => $patient_medicines->currentPage(),
				'per_page' => $patient_medicines->perPage(),
				'last_page' => $patient_medicines->lastPage(),
				'from' => $patient_medicines->firstItem(),
				'to' => $patient_medicines->lastItem(),
			];
			//dd($submited_form);
			$patient_medicines->each(function ($record) use ($submited_form,$shift_id) {
				$med_value = [];
			
				// Check if there's a matching entry in the submitted form
				foreach ($submited_form as $submitted) {
					if ($submitted->field_id == $record->id && $submitted->shift_id == $shift_id) {
						$val=$this->patient_activity_fields_value->where('id',$submitted->field_value)->select('name')->first();
						$med_value[$submitted->med_key] = $val->name;
						
					}
				}
				$record->med_value=$med_value;

				if (strpos($record->dose, '__') !== false) {
												
					$doseData = explode('__', $record->dose);
					$dose = $doseData[0];
					$dose_type = $doseData[1];
					if($dose_type=='other')
					{
						$dose_dis=$dose.' '.$record->other_dose;
					}else{
						$dose_dis=$dose.' '.$dose_type;
					}
				} else {
					$dose_dis = $record->dose;
					
				}
				$record->name = $record->name ? $record->name.' ('.$dose_dis.')' : null;
			});
			
			$medicine_time_other   = config('const.medicine_time_other');
			return response()->json([
				'status' => 'success',
				'list' => $records,
				'pagination' => $pagination,
				'submited_form'=>$submited_form,
			//	'patient_medicines_remarks'=>$patient_medicines_remarks,
				//'log' => $log,
				'shift_name'=>$shiftName,
				'shift_time'=>$log_times[$exsistshift_id],
				'activity_time_exist'=> $activity_time_exist,
				'shift_id'=>$exsistshift_id,
				'medicine_time_other'=>$medicine_time_other
				
			]);
		}else{
			return response()->json([
			'status' => 'success',
			'list' => [],
			'shift_id'=>$exsistshift_id,
			]);
		}
	}
	public function getPatientsMedDetailById(Request $request)
	{
		$current_date = Carbon::now()->format('Y-m-d');
		$current_time = Carbon::now()->format('H:i');
		$currentTimeCarbon = Carbon::createFromFormat('H:i', $current_time);
		$home_id = $this->patients->where('id', $request->patient_id)->pluck('home_id');
		$times      = $this->activity_time->where('home_id', $home_id)->first();
		$log_times = config('const.log_times');
		if($times){
			$log_times = [1 => $times->morning_time, 2 => $times->afternoon_time, 3 => $times->evening_time,4=>$times->night_time];
		}else{
			$log_times = $log_times;
		}
		$exsistshift_id=0;
		$activity_time_exist = false;
		foreach($log_times as $key => $time){
			list($start, $end) = explode(' - ', $time);
			$startCarbon = Carbon::createFromFormat('H:i', $start);
			$endCarbon = Carbon::createFromFormat('H:i', $end);
			
			if ($currentTimeCarbon->between($startCarbon, $endCarbon)) {
				$activity_time_exist = true;
				$exsistshift_id=$key;
				\Log::info(['exsistshift_id' => $exsistshift_id]);
				break;
			}
		}
		$shift_id = $exsistshift_id;

		\Log::info(['shift_id' => $shift_id]);
		$log = $this->patient_logs->where('patient_id', $request->patient_id)->where('log_type',1)->whereDate('report_date', '=', date('Y-m-d'))->first();
		$log_id = $log ? $log->id : 0;
	
		$patient_medicines_remarks = $this->patient_medicine_remarks->where('log_id', $log_id)->where('shift_id',$shift_id)->get();
		
		if($shift_id!=0)
		{
			$shiftNames = [
				1 => 'morning',
				2 => 'afternoon',
				3 => 'evening',
				4 => 'night',
				5 => 'ad_hoc'
			];
			
			$activity_shift_id = $shiftNames[$exsistshift_id] ?? 'unknown_shift';
			$shiftName=getShiftName($exsistshift_id);
		}
		
		
		
			$submited_form     = $this->patient_activity->where(['log_id' => $log_id,"shift_id"=>$shift_id,"field_id"=>$request->id])->select('*',\DB::raw("1 as remark_required"),\DB::raw("CONCAT(field_id, '_', med_key) as new_id"))->get();
			$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => $activity_shift_id])->get()->toArray();
			
			$patient_medicines = $this->patient_medicines->where('id', $request->id)->select('*', DB::raw("'select' as type_name"))->get();
			
			$finalResult = collect();
	
			$medicine_time_other   = config('const.medicine_time_other');
			foreach ($patient_medicines as $medicine) {
				
				$medicine_time =$medicine->medicine_time;
				if($medicine_time!=0 && $medicine_time!=null && $shift_id!=5)
			{
				$time_ids = json_decode($medicine->time_id,true);
				if(in_array('5',$time_ids))
				{
					$medicine_time =json_decode($medicine->medicine_time,true);
					$otherkey=$medicine_time[5];
					$mtime=$medicine_time_other[$otherkey];
					
					$medicine->med_time=$mtime;
					$medicine->med_key=1;
					$medicine->new_id=$medicine->id.'_1';
					$finalResult->push($medicine);
					
				}
				else if(in_array($shift_id,$time_ids))
				{
				$medicine_time =json_decode($medicine->medicine_time,true);
				$times = strpos($medicine_time[$shift_id], ',') !== false ? explode(',', $medicine_time[$shift_id]) : [$medicine_time[$shift_id]];
				
				$med_key = 1;
				foreach ($times as $time) {
					$medicine_clone = clone $medicine;
					$medicine_clone->med_key = $med_key;
					$medicine_clone->new_id = $medicine->id.'_'.$med_key;
					$medicine_clone->med_time = date('h:i A',strtotime($time));
					$finalResult->push($medicine_clone);
					$med_key++;
				}
				}
			}
		}
		
		$patient_medicines =$finalResult->values();
		$popup_btn_disable = false;
		$matchedCount = 0;
		$totalRecords = $patient_medicines->count();
		
			$patient_medicines->each(function ($record) use ($submited_form,$shift_id,&$matchedCount) {
				$record->disable = false;
			
				// Check if there's a matching entry in the submitted form
				foreach ($submited_form as $submitted) {
					if ($submitted->field_id == $record->id && $submitted->med_key == $record->med_key && $submitted->shift_id == $shift_id) {
						$record->disable = true;
						$matchedCount++;
						break;
					}
				}

				if (strpos($record->dose, '__') !== false) {
												
					$doseData = explode('__', $record->dose);
					$dose = $doseData[0];
					$dose_type = $doseData[1];
					if($dose_type=='other')
					{
						$dose_dis=$dose.' '.$record->other_dose;
					}else{
						$dose_dis=$dose.' '.$dose_type;
					}
				} else {
					$dose_dis = $record->dose;
					
				}
				$record->name = $record->name ? $record->name.' '.$dose_dis : null;
			});
			foreach($activity_form['0']['subchilds'] as $key => $subchild){
				
				if($subchild['slug'] == 'medication' || $subchild['slug'] == 'Medication')
				{
					foreach($subchild['subchilds'] as $key1 => $subchild1){
						if($subchild1['slug'] == 'medicines'){
							$options = $subchild1['options'];
							foreach($patient_medicines as $key2 => $patient_medicine){
								$patient_medicines[$key2]['options'] = $options;
							}
						}
					}
				}
			}
			$user = Auth::user();
		$user['check_staff_shift'] = false;
		if ($matchedCount === $totalRecords) {
			$popup_btn_disable = true;
		}
			return response()->json([
				'status' => 'success',
				'patient_medicines' => $patient_medicines,
				'submited_form'=>$submited_form,
			//	'patient_medicines_remarks'=>$patient_medicines_remarks,
				//'log' => $log,
				'shift_name'=>$shiftName.' Shift',
				'shift_time'=>$log_times[$exsistshift_id],
				'activity_time_exist'=> $activity_time_exist,
				'shift_id'=>$exsistshift_id,
				'popup_btn_disable'=>$popup_btn_disable,
				
			]);
		
	
		
	}
	
	public function getPatientLogByID(Request $request)
	{
	//	dd($request->all());
		$data =  $this->patient_logs->find($request->id);
		
		$super_admin_ids = $this->users->where('role_id',1)->pluck('id')->toArray();
		$shift_schedule = config('const.shift_schedule');
		$report_times= json_decode($data->report_time,true);
		$outHome=false;
		$is_report_exist=true;
		if(count($report_times)==1)
		{
			$keys = array_keys($report_times);
			$key = $keys[0];
		$single_report_time=$report_times[$key];
		$activityData = explode('__', $single_report_time);
		if(count($activityData) > 1){
			$outHome = true;
			$is_report_exist=false;
		}
	}
		
		$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0])->get()->toArray();
		$adhocactivity_form     = $this->patient_activity_fields->with(['adhocsubchilds' => function ($query) use($request) {
			$query->join('patient_activities', 'patient_activity_fields.id', '=', 'patient_activities.field_id')
			->where('patient_activities.log_id', $request->id);
		}])->where(['parent_id' => 0,'slug'=>'ad_hoc'])->get()->toArray();
		foreach ($adhocactivity_form[0]['adhocsubchilds'] as $key => $subchild) {
			if (strcasecmp($subchild['slug'], 'medication') === 0) {
				unset($adhocactivity_form[0]['adhocsubchilds'][$key]);
			}
		}
		
		
		$filtered_adhocsubchilds = array_filter($adhocactivity_form[0]['adhocsubchilds'], function($subchild) {
			return strcasecmp($subchild['slug'], 'medication') !== 0;
		});
		//dd($adhocactivity_form);
		$submited_form     = $this->patient_activity->where(['log_id' => $data->id])->get();
		// $activities       = $this->activity->where('status', 1)->get();

		$patient_medicines =[];
		/* foreach($activity_form['0']['subchilds'] as $key => $subchild){
			
			if($subchild['slug'] == 'medication' || $subchild['slug'] == 'Medication')
			{
				foreach($subchild['subchilds'] as $key1 => $subchild1){
					if($subchild1['slug'] == 'medicines'){
						$options = $subchild1['options'];
						foreach($patient_medicines as $key2 => $patient_medicine){
							$patient_medicines[$key2]['options'] = $options;
						}
					}
				}
			}
		} */
		/*remove medication element */
		foreach ($activity_form as $key => &$activity) {
			if ($activity['parent_id'] == 0 && $activity['slug'] == 'ad_hoc') {
				$activity['subchilds'] = $filtered_adhocsubchilds;
			}
			// Collect indices of 'medication' subchilds to remove
			$medicationIndices = [];
			foreach ($activity['subchilds'] as $subKey => $subchild) {
				if (strtolower($subchild['slug']) == 'medication') {
					$medicationIndices[] = $subKey;
				}
			}
			// Remove 'medication' subchilds
			foreach ($medicationIndices as $index) {
				unset($activity['subchilds'][$index]);
			}
			// Re-index the subchilds array to ensure the keys are consecutive integers
			$activity['subchilds'] = array_values($activity['subchilds']);
		}
		unset($activity); 
		
		
		
		$activities  = $this->activity->whereRaw('(home_id = 0 OR home_id = ?)', [$data->patient->home_id])
			->whereRaw('(patient_id = 0 OR patient_id = ?)', [$data->patient_id])
			// ->whereRaw('FIND_IN_SET('.$shift_id.',shift_id)')
			->where(function ($query) use ($super_admin_ids) {
				$query->where('created_by', Auth::user()->parent_id)
					->orWhereIn('created_by', $super_admin_ids);
			})
			->where('status', 1)->get();
		$activities_remarks = $this->patient_activity_remarks->where('log_id', $request->id)->get();
		$patient_medicines_remarks = $this->patient_medicine_remarks->where('log_id', $request->id)->get();
		
		$activitiesShift1 = [];
		$activitiesShift2 = [];
		$activitiesShift3 = [];
		$activitiesShift4 = [];

		foreach ($activities as $activity) {
			$shiftIds = explode(',', $activity->shift_id);
			// Check if the activity's shift_id includes 1 or 2
			if (in_array(1, $shiftIds)) {
				$activitiesShift1[] = $activity;
			}
			if (in_array(2, $shiftIds)) {
				$activitiesShift2[] = $activity;
			}

			if (in_array(3, $shiftIds)) {
				$activitiesShift3[] = $activity;
			}
			if (in_array(4, $shiftIds)) {
				$activitiesShift4[] = $activity;
			}
		}

		
		return response()->json([
                'status' => 'success',
				'shift_schedule' => $shift_schedule,
                'activities' => $activities,
				'activities_shift_1' => $activitiesShift1,
				'activities_shift_2' => $activitiesShift2,
				'activities_shift_3' => $activitiesShift3,
				'activities_shift_4' => $activitiesShift4,
                'log' => $data,
                'log_form' => $activity_form,
                'log_data' => $submited_form,
				'patient_medicines' => $patient_medicines,
				'activities_remarks' => $activities_remarks,
				'patient_medicines_remarks' => $patient_medicines_remarks,
				'is_outhome'=>$outHome,
				'is_report_exist'=>$is_report_exist,
            ]);
	}
	public function getPatientLogByIDShift(Request $request)
	{
	//	dd($request->all());
		$data =  $this->patient_logs->find($request->id);
		
		$super_admin_ids = $this->users->where('role_id',1)->pluck('id')->toArray();
		$shift_schedule = config('const.shift_schedule');
		//$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0])->get()->toArray();
		$shift_id=$request->shift_id;
		$report_times= json_decode($data->report_time,true);
		$outHome=false;
		if(array_key_exists($shift_id,$report_times))
		{
		$single_report_time=$report_times[$shift_id];
		$activityData = explode('__', $single_report_time);
		if(count($activityData) > 1){
			$outHome = true;
		}
	}
		$shiftNames = [
			1 => 'morning',
			2 => 'afternoon',
			3 => 'evening',
			4 => 'night',
			5=>'ad_hoc'
		];
		
		$activity_shift_id = $shiftNames[$shift_id] ?? 'unknown_shift';
		if($shift_id==0)
		{
			$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0])->get()->toArray();
		}else{
		$activity_form     = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => $activity_shift_id])->get()->toArray();
		}
		$adhocactivity_form     = $this->patient_activity_fields->with(['adhocsubchilds' => function ($query) use($request) {
			$query->join('patient_activities', 'patient_activity_fields.id', '=', 'patient_activities.field_id')
			->where('patient_activities.log_id', $request->id);
		}])->where(['parent_id' => 0,'slug'=>'ad_hoc'])->get()->toArray();
		foreach ($adhocactivity_form[0]['adhocsubchilds'] as $key => $subchild) {
			if (strcasecmp($subchild['slug'], 'medication') === 0) {
				unset($adhocactivity_form[0]['adhocsubchilds'][$key]);
			}
		}
		
		
		$filtered_adhocsubchilds = array_filter($adhocactivity_form[0]['adhocsubchilds'], function($subchild) {
			return strcasecmp($subchild['slug'], 'medication') !== 0;
		});
		//dd($adhocactivity_form);
		if($request->shift_id==0)
		{
		$submited_form     = $this->patient_activity->where(['log_id' => $data->id])->get();
		}else{
			$submited_form     = $this->patient_activity->where(['log_id' => $data->id,'shift_id'=>$request->shift_id])->get();
		}
		// $activities       = $this->activity->where('status', 1)->get();

		/*$patient_medicines = $this->patient_medicines->where('patient_id', $data->patient_id)->get();
		 foreach($activity_form['0']['subchilds'] as $key => $subchild){
			
			if($subchild['slug'] == 'medication' || $subchild['slug'] == 'Medication')
			{
				foreach($subchild['subchilds'] as $key1 => $subchild1){
					if($subchild1['slug'] == 'medicines'){
						$options = $subchild1['options'];
						foreach($patient_medicines as $key2 => $patient_medicine){
							$patient_medicines[$key2]['options'] = $options;
						}
					}
				}
			}
		} */
		/*remove medication element */
		foreach ($activity_form as $key => &$activity) {
			if ($activity['parent_id'] == 0 && $activity['slug'] == 'ad_hoc') {
				$activity['subchilds'] = $filtered_adhocsubchilds;
			}
			// Collect indices of 'medication' subchilds to remove
			$medicationIndices = [];
			foreach ($activity['subchilds'] as $subKey => $subchild) {
				if (strtolower($subchild['slug']) == 'medication') {
					$medicationIndices[] = $subKey;
				}
			}
			// Remove 'medication' subchilds
			foreach ($medicationIndices as $index) {
				unset($activity['subchilds'][$index]);
			}
			// Re-index the subchilds array to ensure the keys are consecutive integers
			$activity['subchilds'] = array_values($activity['subchilds']);
		}
		unset($activity); 
		
		
		
		$activities  = $this->activity->whereRaw('(home_id = 0 OR home_id = ?)', [$data->patient->home_id])
			->whereRaw('(patient_id = 0 OR patient_id = ?)', [$data->patient_id])
			// ->whereRaw('FIND_IN_SET('.$shift_id.',shift_id)')
			->where(function ($query) use ($super_admin_ids) {
				$query->where('created_by', Auth::user()->parent_id)
					->orWhereIn('created_by', $super_admin_ids);
			})
			->where('status', 1)->get();
		$activities_remarks = $this->patient_activity_remarks->where('log_id', $request->id)->get();
		/*$patient_medicines_remarks = $this->patient_medicine_remarks->where('log_id', $request->id)->get();*/
		
		$activitiesShift1 = [];
		$activitiesShift2 = [];
		$activitiesShift3 = [];
		$activitiesShift4 = [];

		foreach ($activities as $activity) {
			$shiftIds = explode(',', $activity->shift_id);
			// Check if the activity's shift_id includes 1 or 2
			if (in_array(1, $shiftIds)) {
				$activitiesShift1[] = $activity;
			}
			if (in_array(2, $shiftIds)) {
				$activitiesShift2[] = $activity;
			}

			if (in_array(3, $shiftIds)) {
				$activitiesShift3[] = $activity;
			}
			if (in_array(4, $shiftIds)) {
				$activitiesShift4[] = $activity;
			}
		}

		if(!$submited_form->isEmpty() && $outHome==false)
		{
			$is_report_exist=true;
		}else{
			$is_report_exist=false;
		}
		return response()->json([
                'status' => 'success',
				'shift_schedule' => $shift_schedule,
                'activities' => $activities,
				'activities_shift_1' => $activitiesShift1,
				'activities_shift_2' => $activitiesShift2,
				'activities_shift_3' => $activitiesShift3,
				'activities_shift_4' => $activitiesShift4,
                'log' => $data,
                'log_form' => $activity_form,
                'log_data' => $submited_form,
				/*'patient_medicines' => $patient_medicines,*/
				'activities_remarks' => $activities_remarks,
				/*'patient_medicines_remarks' => $patient_medicines_remarks,*/
				'is_report_exist'=>$is_report_exist,
				'is_outhome'=>$outHome,
            ]);
	}
	
	public function downloadLogReport(Request $request)
	{
		
		$log = $this->patient_logs->find($request->id);
		\Log::info(['requestdata'=>$log]);
		if($log->log_type==0){
			$pdf = Pdf::loadView('patients.activity-detail-pdf', compact('log'));
			$name_slug = Str::slug($log->patient->name, '-');
			$file_name = $name_slug.'-'.$log->report_date.'.pdf';
			// Ensure directory exists
			$directory = public_path('uploads/patient-log-report/');
			File::ensureDirectoryExists($directory);
			$pdf->save($directory . $file_name);
			// $pdf->save(public_path('uploads/patient-log-report/'.$file_name));
			return response()->json([
					'status' => 'success',
					'file_url' => asset('uploads/patient-log-report/'.$file_name),
				]);
		}elseif($log->log_type==1){
            $patient = $this->patients->withTrashed()->find($log->patient_id);
			\Log::info(['patient'=>$patient]);
			$year=date('Y',strtotime($log->report_date));
			$month=date('m',strtotime($log->report_date));
			$lastDayOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();
			$totalDays = Carbon::createFromDate($year, $month, 1)->diffInDays($lastDayOfMonth) + 1;
			$medicineArray = $this->patient_medicines->where('patient_id',$log->patient_id)->whereRaw('NOT JSON_CONTAINS(time_id, \'["6"]\')')->pluck('id')->toArray();
			$adhocmedicineArray = $this->patient_medicines->where('patient_id',$log->patient_id)->whereRaw('JSON_CONTAINS(time_id, \'["6"]\')')->pluck('id')->toArray();
			$all_med = $this->patient_medicines->where('patient_id',$log->patient_id)->pluck('id')->toArray();
			$logs= getSingleMedLogByType($log->patient_id,$log,$medicineArray);
		   
			$adhoclogs =  getSingleMedLogByType($log->patient_id,$log,$adhocmedicineArray);
			$allMedLog=getSingleNoteMedLog($log->patient_id,$log,$all_med);
			$result = [];
    $adHocresult = [];
    $html = ''; // Initialize the HTML string

    foreach ($logs as $log) {     
		if ($log['med_key'] == null) {
			$med_key = 1;
		} else {
			$med_key = $log['med_key'];
		}  
		$result['data'][$log['med_id']][$log['shift_id'] . '_' . $med_key][date('j', strtotime($log['report_date']))] = $log;
  
        // Store medicine name in result array
		$result['med'][$log['med_id']]['id'] = $log['log_iddd'];
		$result['med'][$log['med_id']]['name'] = $log['med_name'];
		$result['med'][$log['med_id']]['med_id'] = $log['med_id'];
		$result['med'][$log['med_id']]['dose'] = $log['dose'];
		$result['med'][$log['med_id']]['instructions'] = $log['instructions'];
		$result['med'][$log['med_id']]['other_dose'] = $log['other_dose'];
		$result['med'][$log['med_id']]['intake_method'] = $log['intake_method'];
		$result['med'][$log['med_id']]['reason'] = $log['med_reason'];
		$result['med'][$log['med_id']]['medicine_type_other'] = $log['medicine_type_other'];
		$result['med'][$log['med_id']]['time_id'] = $log['time_id'];
		$result['med'][$log['med_id']]['medicine_time'] = $log['medicine_time'];
		$result['med'][$log['med_id']]['report_time'] = $log['report_time'];
		$result['med'][$log['med_id']]['activity_time'] = $log['activity_time'];
		$result['med'][$log['med_id']]['is_discontinue'] = $log['is_discontinue'];
		$result['med'][$log['med_id']]['discontinue_date'] = $log['discontinue_date'];
		$result['med'][$log['med_id']]['med_time'] = $log['med_time'];
		$result['med'][$log['med_id']]['med_key'] = $log['med_key'];
    }
	
   
    foreach ($adhoclogs as $log) {       
        $adHocresult['data'][$log['med_id']][$log['id']][date('j', strtotime($log['report_date']))] = $log;
  
        // Store medicine name in adHocresult array
        $adHocresult['med'][$log['med_id']]['name'] = $log['med_name'];
        $adHocresult['med'][$log['med_id']]['dose'] = $log['dose'];
		$adHocresult['med'][$log['med_id']]['other_dose'] = $log['other_dose'];
        $adHocresult['med'][$log['med_id']]['instructions'] = $log['instructions'];
        $adHocresult['med'][$log['med_id']]['intake_method'] = $log['intake_method'];
        $adHocresult['med'][$log['med_id']]['reason'] = $log['med_reason'];
        $adHocresult['med'][$log['med_id']]['medicine_type_other'] = $log['medicine_type_other'];
        $adHocresult['med'][$log['med_id']]['time_id'] = $log['time_id'];
        $adHocresult['med'][$log['med_id']]['medicine_time'] = $log['medicine_time'];
		$adHocresult['med'][$log['med_id']]['report_time'] = $log['report_time'];
    }
	$medicine_time_other   = config('const.medicine_time_other');
			$pdf = Pdf::loadView('patients.patient-medication-report-single-pdf', compact('log','patient','adHocresult','result','allMedLog','medicine_time_other','totalDays'));
			$name_slug = Str::slug($log->patient->name, '-');
			$file_name = $name_slug.'-'.$log->report_date.'.pdf';
			\Log::info(['file_name'=>$file_name]);
			// Ensure directory exists
			$directory = public_path('uploads/patient-log-report/');
			File::ensureDirectoryExists($directory);
			$pdf->save($directory . $file_name);
			// $pdf->save(public_path('uploads/patient-log-report/'.$file_name));
			return response()->json([
					'status' => 'success',
					'file_url' => asset('uploads/patient-log-report/'.$file_name),
				]);
		}
		else{
			return response()->json([
					'status' => 'error',
					'message' => 'Patient log not found with resource id you mentioned',
				]);
		}
	}

	public function deletePatientlog(Request $request)
	{
		$result = $this->patient_logs->deleteLog($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Patient log deleted successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}

	public function dischargePatient(Request $request)
	{
		$result = $this->patients->dischargePatient($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Patient discharge request sent successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}	

	public function getPatientsIncidentList(Request $request)
	{
		//$result = $this->expenses->where('patient_id', $request->patient_id)->get();
		$data = $this->patient_incident_report->getIncidentListByPatientID($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
		$pagination = $this->prepareApiPaginationData($request, $records, $total_count);
		return response()->json([
                'status' => 'success',
                'list' => $records,
				'pagination' => $pagination,
            ]);
	}
	public function addUpdatePatientsIncident(Request $request)
	{
		//echo"<pre>";print_r($request->all());die;
		$result = $this->patient_incident_report->addUpdateData($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Incident added successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}

	public function deleteIncident(Request $request)
	{
		$result = $this->patient_incident_report->deletIncident($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Incident deleted successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}
	/*public function getMedicineByType($request,$type,$shift_id)
	{
		$report_date= date('Y-m-d');
		// $patient_medicines = $this->patient_medicines->where('patient_id', $request->patient_id);
		$patient_medicines = $this->patient_medicines->where('patient_id', $request->patient_id)->whereDate("created_at", '<=',$report_date);
		
			if ($type == 1) {
				$patient_medicines->whereRaw('JSON_CONTAINS(time_id, \'["6"]\')');
			} else {
				$patient_medicines->where(function ($query) use ($shift_id) {
					$query->whereRaw('JSON_CONTAINS(time_id, \'["' . $shift_id . '"]\')')
						->orWhereRaw('JSON_CONTAINS(time_id, \'["5"]\')');
				});
			}
			$patient_medicines->where(function($query) {
				$query->where('is_discontinue', 0);
			});

			$patient_medicines->select('*', DB::raw("'select' as type_name"));

			$result = $patient_medicines->get();
			
			// Apply conditions based on the med_frequency for each patient medicine record
			foreach ($result as $medicine) {
				$med_frequency = $medicine->med_frequency;

				if ($med_frequency == 2) { // Weekly
					$currentDay = strtolower(date('l'));

					$expectedDays = json_decode($medicine->other_med_frequency, true);
					// Ensure case-insensitivity for day comparison
					$expectedDays = array_map('strtoupper', $expectedDays); // Convert expected days to uppercase
					if (is_array($expectedDays)) {
					if (!in_array(strtoupper($currentDay), $expectedDays)) {
						$result = $result->filter(function ($item) use ($medicine) {
							return $item->id != $medicine->id;
						});
					}
				}
				} else if ($med_frequency == 3) { // Bi-Weekly
					$currentDay = strtolower(date('l'));
					$weekNumber = ceil(date('j') / 7);
					$isOddWeek = $weekNumber % 2 == 1;

					if ($isOddWeek) {
						$expectedDays = json_decode($medicine->other_med_frequency, true);
						$expectedDays = array_map('strtoupper', $expectedDays); // Convert expected days to uppercase
						if (is_array($expectedDays)) {
						if (!in_array(strtoupper($currentDay), $expectedDays)) {
							$result = $result->filter(function ($item) use ($medicine) {
								return $item->id != $medicine->id;
							});
						}
					}
					}
				}else if ($med_frequency == 4) { // Monthly
					$currentDateOfMonth = date('j');
					$expectedDays = json_decode($medicine->other_med_frequency, true);
					if (is_array($expectedDays)) {
					if (!in_array($currentDateOfMonth, $expectedDays)) {
						$result = $result->filter(function ($item) use ($medicine) {
							return $item->id != $medicine->id;
						});
					}
				}
				}
				 else if($med_frequency == 5)
				{
					 $currentDay = Carbon::now()->startOfDay();
					 $currentDate=	$currentDay->format('Y-m-d');
				 	 $dateArray=$this->generateBiDailyMedicationDates($medicine->bi_daily_start_date,$medicine->bi_daily_end_date);
					 
				  	if (!in_array($currentDate, $dateArray)) {
						$result = $result->filter(function ($item) use ($medicine) {
							return $item->id != $medicine->id;
						});
					}

				} 
			}

			return $result->values();


	}*/

	public function getMedicineByType($request, $type, $shift_id)
{
    $patient_medicines = $this->patient_medicines->where('patient_id', $request->patient_id);
   
    if ($type == 1) {
        $patient_medicines->whereRaw('JSON_CONTAINS(time_id, \'["6"]\')');
    } else {
        $patient_medicines->where(function ($query) use ($shift_id) {
            $query->whereRaw('JSON_CONTAINS(time_id, \'["' . $shift_id . '"]\')')
                ->orWhereRaw('JSON_CONTAINS(time_id, \'["5"]\')');
        });
    }
	$patient_medicines->where(function($query) {
		$query->where('is_discontinue', 0);
	});

    $patient_medicines->select('*', DB::raw("'select' as type_name"));

    $result = $patient_medicines->get();

    
    // Apply conditions based on the med_frequency for each patient medicine record
    foreach ($result as $medicine) {
        $med_frequency = $medicine->med_frequency;
        $currentDay = strtolower(date('l'));
        $weekNumber = ceil(date('j') / 7);
        $isOddWeek = $weekNumber % 2 == 1;
        $currentDateOfMonth = date('j');
        $currentDate = Carbon::now()->startOfDay()->format('Y-m-d');
        $expectedDays = json_decode($medicine->other_med_frequency, true);
        
        switch ($med_frequency) {
            case 2: // Weekly
                $expectedDays = array_map('strtoupper', $expectedDays);
                if (is_array($expectedDays) && !in_array(strtoupper($currentDay), $expectedDays)) {
                    $result = $result->filter(fn($item) => $item->id != $medicine->id);
                }
                break;
            case 3: // Bi-Weekly
                $expectedDays = json_decode($medicine->other_med_frequency, true);
              
                $dateArray = $this->generateBiWeeklyMedicationDates($medicine->bi_daily_start_date, $medicine->bi_daily_end_date,$expectedDays);
              
                if (!in_array($currentDate, $dateArray)) {
                    $result = $result->filter(function ($item) use ($medicine) {
                        return $item->id != $medicine->id;
                    });
                }
                break;
            case 4: // Monthly
                if (is_array($expectedDays) && !in_array($currentDateOfMonth, $expectedDays)) {
                    $result = $result->filter(fn($item) => $item->id != $medicine->id);
                }
                break;
            case 5: // Bi-Daily
                $dateArray = $this->generateBiDailyMedicationDates($medicine->bi_daily_start_date, $medicine->bi_daily_end_date);
                if (!in_array($currentDate, $dateArray)) {
                    $result = $result->filter(fn($item) => $item->id != $medicine->id);
                }
                break;
        }
    }

    // Add med_key and med_time to the results
  
	
    $finalResult = collect();
	
	
    foreach ($result as $medicine) {
		
		$medicine_time =$medicine->medicine_time;
		if($medicine_time!=0 && $medicine_time!=null && $shift_id!=5)
	{
		$time_ids = json_decode($medicine->time_id,true);
		if(in_array('5',$time_ids))
		{
			$medicine_time =json_decode($medicine->medicine_time,true);
			if(in_array($shift_id,$medicine_time))
			{
			$medicine->med_time=null;
			$medicine->med_key=1;
			$medicine->new_id=$medicine->id.'_1';
			$finalResult->push($medicine);
			}
		}
		else if(in_array($shift_id,$time_ids))
		{
        $medicine_time =json_decode($medicine->medicine_time,true);
		$times = strpos($medicine_time[$shift_id], ',') !== false ? explode(',', $medicine_time[$shift_id]) : [$medicine_time[$shift_id]];
		
        $med_key = 1;
		foreach ($times as $time) {
			$medicine_clone = clone $medicine;
			$medicine_clone->med_key = $med_key;
			$medicine_clone->new_id = $medicine->id.'_'.$med_key;
			$medicine_clone->med_time = date('h:i A',strtotime($time));
			$finalResult->push($medicine_clone);
			$med_key++;
		}
		}
    }else{
			$medicine->med_time=null;
			$medicine->med_key=1;
			$medicine->new_id=$medicine->id.'_1';
			$finalResult->push($medicine);
	}
}

    return $finalResult->values();
}
public function getMultiMedicineByType($request, $type, $shift_id)
{
    $patient_medicines = $this->patient_medicines->where('patient_id', $request->patient_id);

    if ($type == 1) {
        $patient_medicines->whereRaw('JSON_CONTAINS(time_id, \'["6"]\')');
    } else {
        $patient_medicines->where(function ($query) use ($shift_id) {
            $query->whereRaw('JSON_CONTAINS(time_id, \'["' . $shift_id . '"]\')')
                ->orWhereRaw('JSON_CONTAINS(time_id, \'["5"]\')');
        });
    }
    $patient_medicines->where(function($query) {
        $query->where('is_discontinue', 0);
    });

    $patient_medicines->select('*', DB::raw("'select' as type_name"));

    $result = $patient_medicines->get();

    // Apply conditions based on the med_frequency for each patient medicine record
    foreach ($result as $medicine) {
        $med_frequency = $medicine->med_frequency;
        $currentDay = strtolower(date('l'));
        $weekNumber = ceil(date('j') / 7);
        $isOddWeek = $weekNumber % 2 == 1;
        $currentDateOfMonth = date('j');
        $currentDate = \Carbon\Carbon::now()->startOfDay()->format('Y-m-d');
        $expectedDays = json_decode($medicine->other_med_frequency, true);

        switch ($med_frequency) {
            case 2: // Weekly
                $expectedDays = array_map('strtoupper', $expectedDays);
                if (is_array($expectedDays) && !in_array(strtoupper($currentDay), $expectedDays)) {
                    $result = $result->filter(fn($item) => $item->id != $medicine->id);
                }
                break;
            case 3: // Bi-Weekly
				$expectedDays = json_decode($medicine->other_med_frequency, true);
              
                $dateArray = $this->generateBiWeeklyMedicationDates($medicine->bi_daily_start_date, $medicine->bi_daily_end_date,$expectedDays);
			
                if (!in_array($currentDate, $dateArray)) {
                    $result = $result->filter(function ($item) use ($medicine) {
                        return $item->id != $medicine->id;
                    });
                }
                break;
            case 4: // Monthly
                if (is_array($expectedDays) && !in_array($currentDateOfMonth, $expectedDays)) {
                    $result = $result->filter(fn($item) => $item->id != $medicine->id);
                }
                break;
            case 5: // Bi-Daily
                $dateArray = $this->generateBiDailyMedicationDates($medicine->bi_daily_start_date, $medicine->bi_daily_end_date);
                if (!in_array($currentDate, $dateArray)) {
                    $result = $result->filter(fn($item) => $item->id != $medicine->id);
                }
                break;
        }
    }

    // Add med_key and med_time to the results
    $finalResult = collect();

    foreach ($result as $medicine) {
        $medicine_time = $medicine->medicine_time;
        if ($medicine_time != 0 && $medicine_time != null && $shift_id != 5) {
            $time_ids = json_decode($medicine->time_id, true);
            if (in_array('5', $time_ids)) {
                $medicine_time = json_decode($medicine->medicine_time, true);
                if (in_array($shift_id, $medicine_time)) {
                    $medicine->med_time = null;
                    $medicine->med_key = 1;
                    $medicine->new_id = $medicine->id . '_1';
                    $finalResult->push($medicine);
                }
            } else if (in_array($shift_id, $time_ids)) {
                $finalResult->push($medicine);
            }
        }
    }

    // Pagination
    $page = $request->page;
    $perPage = 200;

    $paginatedResult = $this->paginateCollection($finalResult, $perPage, $page);
	
    return $paginatedResult;
}

protected function paginateCollection(Collection $collection, $perPage, $page)
{
    $total = $collection->count();
    $items = $collection->slice(($page - 1) * $perPage, $perPage)->values();

    return new LengthAwarePaginator($items, $total, $perPage, $page, [
        'path' => LengthAwarePaginator::resolveCurrentPath(),
    ]);
}


	public function generateBiDailyMedicationDates($start_date, $end_date)
	{
		
		$medication_dates = [];
		$start_date = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
		if ($end_date) {
			$end_date = Carbon::createFromFormat('Y-m-d', $end_date)->startOfDay();
		} else {
			$currentDay = Carbon::now()->startOfDay();
			$end_date = $currentDay->copy()->addDays(4);
		}
		$current_date = $start_date;
		while ($current_date <= $end_date) {
			
			$medication_dates[] = $current_date->format('Y-m-d');
			$current_date->addDays(2);
		}
		return $medication_dates;
	}
	public function generateBiWeeklyMedicationDates($start_date, $end_date, $expectedDays)
    {
        $medication_dates = [];
        $start_date = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
    
        if ($end_date) {
            $end_date = Carbon::createFromFormat('Y-m-d', $end_date)->startOfDay();
        } else {
            $end_date = Carbon::now()->startOfDay()->addDays(60); // Assuming 60 days for bi-weekly cycles
        }
    
        $current_date = $start_date;
    
        while ($current_date <= $end_date) {
            $currentDayOfWeek = strtolower($current_date->format('l'));
    
            if (in_array(ucfirst($currentDayOfWeek), $expectedDays)) {
                $medication_dates[] = $current_date->format('Y-m-d');
                $current_date->addWeeks(2); // Move to the same day, two weeks later
            } else {
                $current_date->addDay(); // Move to the next day
            }
        }
    
        return $medication_dates;
    }
	// public function sendIncidentReportNotification(){
	// 	$activity_form  = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0])->get()->toArray();
	// 	$incidents =[];
	// 	foreach ($activity_form as $item) {
	// 		foreach($item['subchilds'] as  $subItem){
	// 			if ($subItem['name'] === 'Incidents') {
	// 				foreach($subItem['subchilds'] as  $subChildItem){
	// 					if($subChildItem['name'] === 'Reported'){
	// 						foreach($subChildItem['options'] as $option)
	// 						if ($option['name'] === 'Yes') {
	// 							$incidents[] = $option;
	// 						}
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}
	// 	$current_date = Carbon::now()->toDateString();
	// 	$log_data = $this->patient_logs->where('report_date', $current_date)->get();

	// 	$log_data->transform(function ($item) {
	// 		$itemArray = $item->toArray();
	// 		unset($itemArray['images']);
	// 		return $itemArray;
	// 	});		
	// 	foreach($log_data as $data){
	// 		$patient = $this->patients->find($data['patient_id']);
	// 		foreach($incidents as $incident){
	// 			$submited_form  = $this->patient_activity->where(['log_id' => $data['id'], 'field_id' => $incident['field_id']])->first();
	// 			// echo  "<pre>"; print_r($submited_form->toArray());die;
	// 			if($submited_form){
	// 				$notificationData['home_id'] = $patient->home_id;
	// 				$notificationData['patient_id'] = $data['patient_id'];
	// 				$notificationData['staff_id'] = $submited_form->created_by;
	// 				$notificationData['item_id'] = $submited_form->id;
	// 				$notificationData['item_type'] = 'patient_incident_report';
	// 				// $notificationData['message'] = 'Add the incident report of patient '.$patient->name.'.';
	// 				$notificationData['app_message'] = 'Add the incident report of patient '.$patient->name.'.';
	// 				// $notificationData['created_by'] = Auth::user()->id;
	// 				// $notificationData['updated_by'] = Auth::user()->id;
	// 				$notificationData['created_at'] = now();
	// 				$notificationData['updated_at'] = now();
	// 				Notification::insert($notificationData);
	// 			}
	// 		}
	// 	}
	// 	return response()->json(['status' => 'success', 'message' => 'success'], 200);
	// }
	
	public function discontinuePatientsMedicine(Request $request){	

		//Validate data
        $validator = Validator::make($request->all(), [
			'note' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()], 200);
        }

		$result =  $this->patient_medicines->where('id', $request->id)->update(['is_discontinue' => 1,'discontinue_note' => $request->note, 'discontinue_date' => date('Y-m-d')]);

		if($result){
			return response()->json(['status' => 'success', 'message' => 'Patient medicine discontinue successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}

	}

}
