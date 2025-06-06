<?php

namespace App\Models;

use App\Events\CreateNotification;
use App\Models\CareHome;
use App\Models\Patient;
use App\Models\PatientActivityRemark;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'home_id',
        'shift_id',
        'name',
        'status',
        'created_by',
        'updated_by',
        'patient_id',
        'duration',
        'frequency',
        'recurrence',
        'activity_performance_day',
        'description',
        'type',
    ];

    // public function addUpdateActivity($request, $id)
    // {
    //     /*if ($id != 0) {
    //         $activity_data = [
    //             'shift_id'     => $request->shift_id,
    //             'name'         => $request->name,
    //             'status'       => $request->status,
    //             'updated_by'   => Auth::user()->id,
    //         ];
    //         $activity = $this->findOrFail($id);
    //         $activityData = $activity->update($activity_data);
    //     } else {
    //         $shifts = $request->shift_id;
    //         foreach($shifts as $value){
    //             $activity_data = [
    //                 'shift_id'     => $value,
    //                 'name'         => $request->name,
    //                 'status'       => $request->status,
    //                 'created_by'   => Auth::user()->id,
    //                 'updated_by'   => Auth::user()->id,
    //             ];
    //             $activity = $this->create($activity_data);
    //         }
    //     }
    //     return true;*/
    //     $activity_data = [
    //         'shift_id'     => implode(',', $request->shift_id),
    //         'name'         => $request->name,
    //         'home_id'        => $request->home_id ? $request->home_id : 0,
    //         'patient_id'   => $request->patient_id ? implode(',', $request->patient_id): null,
    //         'duration'     => $request->duration,
    //         'frequency'    => $request->frequency,
    //         'recurrence'   => $request->recurrence,
    //         'activity_performance_day' => $request->activity_performance_day,
    //         'description'  => $request->description,
    //         'status'       => $request->status,
    //         'updated_by'   => Auth::user()->id,
    //         'type'           => $request->home_id && $request->patient_id ? '2' : '1', //Activity for all patient or specific patient
    //     ];
    //     if ($id != 0) {
    //         $activity = $this->findOrFail($id);
    //         $activityData = $activity->update($activity_data);

    //         $activity['care_home_admin'] = Auth::user();
    //         event(new CreateNotification('activity_schedule_update', $activity));
    //     }else{
    //         $activity_data['created_by'] = Auth::user()->id;
    //         $activity = $this->create($activity_data);

    //         $activity['care_home_admin'] = Auth::user();

    //         event(new CreateNotification('activity_schedule_create', $activity));
    //     }
    //     return true;
    // }

    public function addUpdateActivity($request, $id)
    {

        if ($request->type == 1) {
            $request->patient_id = [];
        }
        $activity_data = [
            'shift_id' => implode(',', $request->shift_id),
            'name' => $request->name,
            'home_id' => $request->type == 2 && $request->home_id ? $request->home_id : 0, //$request->home_id ? $request->home_id : 0,
            'duration' => $request->duration,
            'frequency' => $request->frequency,
            'recurrence' => $request->recurrence,
            'activity_performance_day' => $request->activity_performance_day,
            'description' => $request->description,
            'updated_by' => Auth::user()->id,
            'type' => $request->type, //$request->home_id && $request->patient_id ? '2' : '1', //Activity for all patient or specific patient
        ];
        if ($id != 0) {
            $activity = $this->findOrFail($id);
            $activity_data['status'] = $request->status;
            $activityData = $activity->update($activity_data);

            $activity['care_home_admin'] = Auth::user();
            event(new CreateNotification('activity_schedule_update', $activity));
        } else {
            $patients = $request->patient_id;
            if ($patients) {
                foreach ($patients as $value) {
                    $activity_data['patient_id'] = $value;
                    $activity_data['status'] = 1;
                    $activity_data['created_by'] = Auth::user()->id;
                    $activity = $this->create($activity_data);

                    $activity['care_home_admin'] = Auth::user();

                    event(new CreateNotification('activity_schedule_create', $activity));
                }
            } else {
                $activity_data['patient_id'] = 0;
                $activity_data['created_by'] = Auth::user()->id;
                $activity = $this->create($activity_data);

                $activity['care_home_admin'] = Auth::user();

                event(new CreateNotification('activity_schedule_create', $activity));
            }
        }
        return true;
    }

    public function addActivity($request)
    {
        foreach ($request->activity as $activityData) {
            if (!empty($activityData['activity_name'])) {
                if ($request->recurrence == 2 || $activityData['activity_recurrence'] == 2) {
                    $activityData['activity_performance_day'] = json_encode($activityData['activity_performance_day']);
                } else {
                    $activityData['activity_performance_day'] = json_encode(explode(',', $activityData['activity_performance_month']));
                }
                if ($request->id == 0) {
                    $activity_data = [
                        'shift_id' => isset($activityData['activity_shift_id']) ? implode(',', $activityData['activity_shift_id']) : '',
                        'name' => $activityData['activity_name'],
                        'home_id' => isset($request->home_id) && $request->home_id != '' ? $request->home_id : 0,
                        'patient_id' => $request->patient_id,
                        'duration' => $activityData['activity_duration'],
                        'frequency' => $activityData['activity_frequency'],
                        'recurrence' => $activityData['activity_recurrence'],
                        'activity_performance_day' => $activityData['activity_performance_day'],
                        'description' => $activityData['activity_description'],
                        'status' => 1,
                        'updated_by' => Auth::user()->id,
                        'type' => isset($request->type) && $request->type != '' ? $request->type : '1',
                    ];
                    $activity_data['created_by'] = Auth::user()->id;
                    $activity = $this->create($activity_data);
                    $activity['care_home_admin'] = Auth::user();
                    event(new CreateNotification('activity_schedule_create', $activity));

                } else {

                    $activity_data = [
                        'shift_id' => isset($activityData['activity_shift_id']) ? implode(',', $activityData['activity_shift_id']) : '',
                        'name' => $activityData['activity_name'],
                        'home_id' => $request->home_id ? $request->home_id : 0,
                        'patient_id' => $request->patient_id,
                        'duration' => $activityData['activity_duration'],
                        'frequency' => $activityData['activity_frequency'],
                        'recurrence' => $activityData['activity_recurrence'],
                        'activity_performance_day' => $activityData['activity_performance_day'],
                        'description' => $activityData['activity_description'],
                        'status' => $activityData['activity_status'],
                        'updated_by' => Auth::user()->id,
                        'type' => isset($request->type) && $request->type != '' ? $request->type : '1',
                    ];
					
                    $activity = $this->findOrFail($request->id);
                    $activityDat = $activity->update($activity_data);
                    $activity['care_home_admin'] = Auth::user();
                    event(new CreateNotification('activity_schedule_update', $activity));
                    // $activityDat = $medicine->update($activity_data);
                }
            }
        }
        return true;
    }

    public function getActivityList($request)
    {
        $role_id = Auth::user()->role_id;
        $home_ids = CareHome::where('created_by', Auth::user()->id)->withTrashed()->pluck('id')->toArray();
        $super_admin_ids = User::where('role_id', 1)->pluck('id')->toArray();
        $sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        $activity_status = isset($request->activity_status) ? $request->activity_status : 'active';
        // return $this->when(Auth::user()->role_id == 2, function($q){
        //             return $q->where('activities.created_by', Auth::user()->id);
        //     })
        return $this->where(function ($query) use ($super_admin_ids, $role_id, $activity_status, $home_ids) {
            if ($role_id != 1 && $activity_status == 'active') {
                $query->where('activities.created_by', Auth::user()->id)
                    ->orWhereIn('activities.created_by', $super_admin_ids);
            } else if ($role_id != 1 && ($activity_status == 'archive' || $activity_status == 'inactive')) {
                $query->where('activities.created_by', Auth::user()->id);
            }
        })
            ->where(function ($query) use ($home_ids, $role_id, $activity_status, $super_admin_ids) {
                // Add a condition to check if the activity is related to a patient in a nursing home owned by the current admin
                if ($role_id != 1 && $activity_status == 'active') {
                    $query->whereIn('activities.home_id', $home_ids)->orWhere('activities.home_id', 0);
                }
            })
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere("activities.name", "LIKE", "%{$search}%");
                });
            })
            ->when($activity_status != '', function ($q) use ($activity_status) {
                if ($activity_status == 'inactive') {
                    return $q->where('activities.status', 0);
                } else if ($activity_status == 'archive') {
                    return $q->withTrashed()->whereNotNull('activities.deleted_at');
                } else {
                    return $q->where('activities.status', '1');
                }
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                return $q->orderBy('activities.' . $sort_field, $sort_order);
            });
    }

    public function patient_detail()
    {
        return $this->hasOne(Patient::class, 'id', 'patient_id')->withTrashed();
    }

    public function patient_activity_remark()
    {
        return $this->hasOne(PatientActivityRemark::class, 'activity_id', 'id');
    }
}
