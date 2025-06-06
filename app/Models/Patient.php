<?php

namespace App\Models;

use App\Events\CreateNotification;
use App\Mail\PatientDischargeMail;
use App\Models\Activity;
use App\Models\CareHome;
use App\Models\IncidentReport;
use App\Models\Notification;
use App\Models\PatientActivity;
use App\Models\PatientAppointment;
use App\Models\PatientDoctor;
use App\Models\PatientDocument;
use App\Models\PatientLog;
use App\Models\PatientMedicine;
use App\Models\User;
use Auth, File, Mail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'home_id',
        'name',
        'email',
        'phone',
        'emergency_contact_name',
        'emergency_contact',
        'patient_info',
        'address',
        'discharged',
        'discharged_request',
        'discharged_comment',
        'status',
        'initial_payment',
        'inital_payment_amount',
        'profile_image',
        'per_day_cost',
        'per_day_reserve_cost',
        'admission_date',
        'created_by',
        'updated_by',
        'care_person_name',
        'care_person_contact',
        'care_person_address',
        'care_person_relation',
        'height_in_feet',
        'height_in_inch',
        'weight',
        'gender',
        'blood_group',
        'preferences',
    ];

    protected $appends = ['profile_image_path'];

    public function getProfileImagePathAttribute()
    {
        $image = asset('assets/img/patient_dummy.png');
        if (!is_null($this->profile_image)) {
            if (file_exists(public_path($this->profile_image))) {
                $image = url($this->profile_image);
            }
        }
        return $image;
    }

    public function care_home()
    {
        // home table has user_id field that stores id of related user model
        return $this->belongsTo(CareHome::class, 'home_id', 'id')->withTrashed();
    }

    public function doctors()
    {
        return $this->hasMany(PatientDoctor::class, 'patient_id', 'id');
    }

    public function medicines()
    {
        return $this->hasMany(PatientMedicine::class, 'patient_id', 'id')->orderBy('created_at', 'desc');
    }

    public function documents()
    {
        return $this->hasMany(PatientDocument::class, 'patient_id', 'id');
    }
    public function appointment()
    {
        return $this->hasMany(PatientAppointment::class, 'patient_id', 'id')->orderBy('id', 'DESC');
    }

    public function notification()
    {
        return $this->hasMany(Notification::class, 'patient_id', 'id');
    }

    public function added_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function update_by()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function incidents()
    {
        return $this->hasMany(IncidentReport::class, 'patient_id', 'id')->orderBy('id', 'DESC');
    }

    public function getPatientListAll($request, $home_ids)
    {
        //dd($request->all());
        if (!empty($request->patient_status) && $request->patient_status == 'archive') {
            $data = $this->onlyTrashed()->whereIn('home_id', $home_ids);
            $data = $data->select('patients.*')
            /*  ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, email FROM patients GROUP BY email) AS latest_patients'), function ($join) {
            $join->on('patients.id', '=', 'latest_patients.id');
            }) */
                ->orderBy('patients.id', 'desc');
        } else {
            // $data = $this->whereIn('home_id', $home_ids)->where('status',1)->orderBy('id','desc');
            $data = $this->whereIn('home_id', $home_ids)->where('status', 1);
            if (!empty($request->patient_status) && $request->patient_status == 'discharge') {
                $data = $data->where('discharged', 1);
            } else {
                $data = $data->where('discharged', 0);
            }

            $data = $data->select('patients.*')
            /*  ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, email FROM patients GROUP BY email) AS latest_patients'), function ($join) {
            $join->on('patients.id', '=', 'latest_patients.id');
            }) */
                ->orderBy('patients.id', 'desc');
            //  ->get();

        }

        return $data;

    }

    public function addUpdatePatient($request)
    {
        $patient_data = [
            'name' => $request->name,
            'email' => $request->email,
            'home_id' => isset($request->home_id) && $request->home_id != '' ? $request->home_id : 0,
            'status' => 1, //$request->status,
            'initial_payment' => $request->initial_payment,
            'inital_payment_amount' => $request->inital_payment_amount,
            'patient_info' => isset($request->patient_info) && $request->patient_info != '' ? $request->patient_info : null,
            'address' => $request->address,
            'per_day_cost' => $request->per_day_cost,
            'per_day_reserve_cost' => $request->per_day_reserve_cost,
            'admission_date' => isset($request->admission_date) && $request->admission_date != '' ? date('Y-m-d', strtotime($request->admission_date)) : date('Y-m-d'),
            'phone' => $request->phone_number,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact' => $request->emergency_contact,
            'updated_by' => Auth::user()->id,
        ];
        if ($request->patient_id != 0) {
            $patient = $this->findOrFail($request->patient_id);
            $patient_data['email'] = $patient->email;
            if (isset($request->email)) {
                $patient_data['email'] = $request->email;
            }

            $patientData = $patient->update($patient_data);
            $this->savePatientImage($request, $patient);
            // call the event
            event(new CreateNotification('patient_updated', $patient));
        } else {
            $patient_data['care_person_name'] = $request->care_person_name;
            $patient_data['care_person_contact'] = $request->care_person_contact;
            $patient_data['care_person_address'] = $request->care_person_address;
            $patient_data['care_person_relation'] = $request->care_person_relation;
            $patient_data['height_in_feet'] = !empty($request->height_in_feet) ? $request->height_in_feet : null;
            $patient_data['height_in_inch'] = !empty($request->height_in_inch) ? $request->height_in_inch : null;
            $patient_data['weight'] = !empty($request->weight) ? $request->weight : null;
            $patient_data['gender'] = !empty($request->gender) ? $request->gender : null;
            $patient_data['blood_group'] = !empty($request->blood_group) ? $request->blood_group : null;
            $patient_data['preferences'] = !empty($request->preferences) ? $request->preferences : null;
            $patient_data['created_by'] = Auth::user()->id;

            $patient = $this->create($patient_data);
            $patient->save();
            $this->savePatientImage($request, $patient);
            $request->patient_id = $patient->id;
            if (!empty($request->doctors[0]['name'])) {
                $patientDoctor = new PatientDoctor();
                $patientDoctor->addDoctor($request);
            }
            if (!empty($request->medicines[0]['medicine_name'])) {
                $patientMedicine = new PatientMedicine();
                $patientMedicine->addMedicine($request);
            }
            if (!empty($request->doc[0]['document_name'])) {
                $patientDocument = new PatientDocument();
                $patientDocument->addDocument($request);
            }
            if (!empty($request->activity[0]['activity_name'])) {
                $patientActivity = new Activity();
                $patientActivity->addActivity($request);
            }
            // if (isset($request->activity) && is_array($request->activity)) {
            //     foreach ($request->activity as $key => $activityId) {
            //         $activityData = [
            //             'activity_id' => $activityId,
            //             'duration' => $request->duration[$key] ?? 0,
            //             'frequency' => $request->frequency[$key] ?? 0,
            //             'recurrence' => $request->recurrence[$key],
            //             'activity_perform_days' => $request->activity_performance_day[$key] ?? 0,
            //             'description' => $request->description[$key] ?? null,
            //         ];
            //         $this->assignActivity($request->patient_id, $activityData);
            //     }
            // }
            // call the event
            event(new CreateNotification('patient_created', $patient));
        }
        return true;
    }

    public function assignActivity($patientId, $activityData)
    {
        $patient = Patient::findOrFail($patientId);

        $activity = new PatientActivity($activityData);
        $activity->patient_id = $patientId;
        $activity->created_by = Auth::user()->id;
        $activity->updated_by = Auth::user()->id;

        $activity->save();

        $activity['home_id'] = $patient->home_id;
        $activity['care_home_admin'] = $patient->added_by;

        event(new CreateNotification('activity_assign_to_patient', $activity));
        return true;
    }

    public function dischargePatient($request)
    {
        $patient = $this->findOrFail($request->id);
        //$patientData = $patient->update(['discharged' => 1, 'discharged_comment' => $request->discharged_comment]);
        $patientData = $patient->update(['discharged_request' => 1, 'discharged_comment' => $request->comment]);
        // call the event

        //send admin notification email for patient discharge
        $care_home_admin_id = $patient->care_home ? $patient->care_home->user_id : 0;
        if ($care_home_admin_id > 0) {
            $admin = User::find($care_home_admin_id);
            if ($admin) {
                Mail::to($admin->email)->send(new PatientDischargeMail($patient));
            }
        }
        return true;
    }

    public function getPatientList($request)
    {
        $sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        return $this->where('home_id', $request->home_id)
            ->where('discharged', 0)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere("patients.name", "LIKE", "%{$search}%");
                });
            })
        /* ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, email FROM patients GROUP BY email) AS latest_patients'), function ($join) {
        $join->on('patients.id', '=', 'latest_patients.id');
        }) */
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                return $q->orderBy('patients.' . $sort_field, $sort_order);
            });
    }

    public function getTaskPatients($request)
    {
        return $this->where('status', 1)->where('home_id', $request->home_id)->select('id as key', 'name as value')->get();
    }

    public function getCareHomePatientds()
    {
        $house_ids = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();
        return $this->where('status', 1)->whereIn('home_id', $house_ids)
            ->where('discharged', 0)
        // ->where('deleted_at', null)
        /* ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, email FROM patients GROUP BY email) AS latest_patients'), function ($join) {
        $join->on('patients.id', '=', 'latest_patients.id');
        }) */
            ->get();
    }
    public function getCareHomePatients()
    {
        $user_id = Auth::id();
        $house_ids = CareHome::where('user_id', $user_id)
            ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, name,email FROM care_homes WHERE stripe_id IS NOT NULL GROUP BY email) AS latest_care_homes'), function ($join) {
                $join->on('care_homes.id', '=', 'latest_care_homes.id');
            })->pluck('care_homes.id')->toArray();
        return Patient::where('status', 1)
            ->whereIn('home_id', $house_ids)
            ->where('discharged', 0)
            ->whereNull('deleted_at')
        // ->withTrashed() // Include soft-deleted patients
        /* ->joinSub(function ($query) {
        $query->selectRaw('MAX(id) AS id, email')
        ->from('patients')
        ->groupBy('email');
        }, 'latest_patients', function ($join) {
        $join->on('patients.id', '=', 'latest_patients.id');
        }) */
            ->get();
    }

    public function savePatientImage($request, $patient)
    {
        if ($request->hasFile('profile_image')) {

            if (!is_null($patient->profile_image)) {
                $old_image = public_path($patient->profile_image);
                File::delete($old_image);
            }

            $file = $request->file('profile_image');
            $extension = $file->getClientOriginalExtension();
            $imageName = "patient_image_" . uniqid() . "." . $extension;
            $file->move(public_path('uploads/patient-profile/'), $imageName);
            $patient->update(['profile_image' => "uploads/patient-profile/" . $imageName]);
        }
    }

    public function updateDischargeStatus($request)
    {
        $patient = $this->find($request->patient_id);
        $patient->discharged = $request->action_data == 1 ? 1 : 0;
        $patient->discharged_request = 0;
        if ($request->action_data == 1) {
            event(new CreateNotification('patient_discharged', $patient));
        } else {
            event(new CreateNotification('reject_patient_discharge_request', $patient));
        }
        return $patient->save();
    }

    public function deletePatient($request)
    {
        $patient = $this->find($request->patient_id);
        // if(!is_null($patient->profile_image)){
        //     $old_image = public_path($patient->profile_image);
        //     File::delete($old_image);
        // }
        // //delete patient medicine list
        // PatientMedicine::where('patient_id', $patient->id)->delete();
        // //delete patient logs and its activity
        // $logs = PatientLog::where('patient_id', $patient->id)->get();
        // if($logs->count() > 0){
        //     foreach($logs as $log){
        //         //first delete log activity
        //         PatientActivity::where('log_id', $log->id)->delete();
        //         //find and delete log image save alpha
        //         $log_images = PatientLogImage::where('log_id', $log->id)->get();
        //         if($log_images->count() > 0){
        //             foreach($log_images as $log_image){
        //                 if(!is_null($log_image->image)){
        //                     $image = public_path($log_image->image);
        //                     File::delete($image);
        //                 }
        //                 $log_image->delete();
        //             }
        //         }
        //         //delete log
        //         $log->delete();
        //     }
        // }
        // //delete patient expense list
        // PatientExpense::where('patient_id', $patient->id)->delete();
        // //delete patient payments list
        // PatientPayment::where('patient_id', $patient->id)->delete();
        // //delete patient Notification
        // Notification::where('patient_id', $patient->id)->delete();
        // //delete patient document
        // $documents = PatientDocument::where('patient_id', $patient->id)->get();
        // if($documents->count() > 0){
        //     foreach($documents as $document){
        //         if(!is_null($document->document_path)){
        //             $old_image = public_path($document->document_path);
        //             File::delete($old_image);
        //         }
        //         $document->delete();
        //     }
        // }
        //delete the patient
        $patient->delete_request = 1; //();
        $patient->save();
        // call the event
        event(new CreateNotification('patient_delete_request', $patient));
        return true;
    }

    public function getPatientAssignedActivities()
    {
        return $this->hasMany(Activity::class, 'patient_id', 'id');
    }

    public function getPatientAssignedTasks()
    {
        return $this->hasMany(Tasks::class, 'patient_id', 'id');
    }

    public function getPatientMedicines($home_ids)
    {
        return $this->where('patients.status', 1)->whereIn('patients.home_id', $home_ids)
            ->withTrashed()
        /* ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, email FROM patients GROUP BY email) AS latest_patients'), function ($join) {
        $join->on('patients.id', '=', 'latest_patients.id');
        }) */
            ->leftJoin('patient_medicines', 'patients.id', '=', 'patient_medicines.patient_id')
            ->where('patient_medicines.status', 1)
            ->select('patients.*', 'patient_medicines.name as medicine_name', 'patient_medicines.id as medicine_id')
            ->orderBy('patient_medicines.id', 'desc')
            ->get();
    }

    public function getLatestPatientLog()
    {
        return $this->hasOne(PatientLog::class, 'patient_id', 'id')->latest();
    }
    public function getPatientLog()
    {
        return $this->hasMany(PatientLog::class, 'patient_id', 'id');
    }
}
