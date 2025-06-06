<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth, File;
use App\Events\CreateNotification;
use App\Models\Patient;
use App\Models\Notification;
use App\Models\User;
use App\Models\PatientAppointmentRemark;
use App\Services\FirebaseService;

class PatientAppointment extends Model
{
     use HasFactory;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'patient_id',
        'title',
        'description',
        'address',
        'appointment_date',
        'appointment_time',
		'doctor_name',
		'other_doctor',
		'schedule_frequency',
		'other_schedule_frequency',
		'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
	protected $appends = ['app_schedule_name'];
	public function getAppScheduleNameAttribute(){
        $intake_guidedby = config('const.schdule_frequency');
		return $intake_guidedby[$this->schedule_frequency];
    }
	public function marked_by(){
		return $this->belongsTo(User::class, 'marked_by', 'id')->select('id','name','email'); 
	}

	public function patient_detail(){
		return $this->belongsTo(Patient::class, 'patient_id', 'id'); 
	}

    public function addaAppointment($request)
	{
		//dd($request->all());
		$patientHomeId = Patient::where('id', $request->patient_id)->select('home_id')->first();
		$getAllStaff = User::where(['home_id' => $patientHomeId->home_id, 'status' => 1, 'role_id' => 4])->pluck('id');

		$appointment['other_schedule_frequency']=null;
		if(!empty($request->appointment['other_schedule_frequency_week']) && $request->appointment['other_schedule_frequency_week'][0]!=null)
		{
			$appointment['other_schedule_frequency']=json_encode($request->appointment['other_schedule_frequency_week']);
		}
		if(!empty($request->appointment['other_schedule_frequency_month']) && $request->appointment['other_schedule_frequency_month'][0]!=null)
		{
			$appointment['other_schedule_frequency']=json_encode(explode(',',$request->appointment['other_schedule_frequency_month'][0]));
		}
		//dd($request->all(),$appointment['other_schedule_frequency']);
		$notificationData = [];
        $document_data = [
            'patient_id'        => $request->patient_id,
            'title'             => !empty($request->title) ? $request->title: NULL,
            'description'       => !empty($request->description) ? $request->description: NULL,
            'address'           => !empty($request->address) ? $request->address: NULL,
            'appointment_date'  => !empty($request->appointment_date) ? date('Y-m-d', strtotime($request->appointment_date)) : NULL,
            'appointment_time'  => !empty($request->appointment_time) ? $request->appointment_time : NULL,
			'doctor_name'       => !empty($request->doctor_name) ? $request->doctor_name : NULL,
			'other_doctor'       => !empty($request->other_doctor) ? $request->other_doctor : NULL,
			'schedule_frequency'           => !empty($request->appointment['schedule_frequency']) ? $request->appointment['schedule_frequency'] : NULL,
			'other_schedule_frequency'           => $appointment['other_schedule_frequency'],
            'created_by'        => Auth::user()->id,
            'updated_by'        => Auth::user()->id,
        ];
        $document = $this->updateOrCreate(['id' => $request->id],$document_data);
		if(!empty($request->id) || $request->id !=0){
			$item_type = 'appointment_update';
			$msg = 'Admin update appointment ('.$document->title.') for '. getPatientName($request->patient_id). ' staff ';
		}else{
			$item_type = 'appointment_add';
			$msg = 'Admin add appointment ('.$document->title.') for '. getPatientName($request->patient_id);
		}
		 foreach($getAllStaff as $key => $staff){
			$notificationData[$key]['home_id'] = $patientHomeId->home_id;
			$notificationData[$key]['patient_id'] = $request->patient_id;
			$notificationData[$key]['staff_id'] = $staff;
			$notificationData[$key]['item_id'] = $document->id;
			$notificationData[$key]['item_type'] = $item_type;
			if(!empty($request->id) || $request->id !=0){
				// $notificationData[$key]['message'] = 'Admin update appointment ('.$document->title.') for '. getPatientName($request->patient_id). ' staff '.getUserName($staff);
				$notificationData[$key]['message'] = 'Admin update appointment ('.$document->title.') for '. getPatientName($request->patient_id);
				$notificationData[$key]['app_message'] = 'Admin update appointment ('.$document->title.') for '. getPatientName($request->patient_id);
				$title='Update Appointment';
			}else{
				// $notificationData[$key]['message'] = 'Admin add appointment ('.$document->title.') for '. getPatientName($request->patient_id). ' staff '.getUserName($staff);
				$title='Add Appointment';
				$notificationData[$key]['message'] = 'Admin add appointment ('.$document->title.') for '. getPatientName($request->patient_id);
				$notificationData[$key]['app_message'] = 'Admin add appointment ('.$document->title.') for '. getPatientName($request->patient_id);
			}
			$notificationData[$key]['created_by'] = Auth::user()->id;
			$notificationData[$key]['updated_by'] = Auth::user()->id;
			$notificationData[$key]['created_at'] = now();
			$notificationData[$key]['updated_at'] = now();
			$firebaseService = new FirebaseService();
			$response = $firebaseService->sendNotification($staff, $title , $notificationData[$key]['app_message']);
			Notification::insert($notificationData);

		}
		
		
		
		return true;
	}
	public function appointed_doctor()
	{
		return $this->belongsTo(PatientDoctor::class, 'doctor_name', 'id');
	}

	public function patient_appointment_remarks()
    {
        return $this->hasMany(PatientAppointmentRemark::class, 'appointment_id', 'id');
    }
}
