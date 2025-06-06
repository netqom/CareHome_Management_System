<?php

namespace App\Models;

use App\Models\CareHome;
use App\Models\StaffAssignedTraining;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffTraining extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'home_id',
        'staff_id',
        'title',
        'description',
        'duration',
        'due_date',
        'training_status',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['care_home_detail', 'staff_detail'];

    public function getCareHomeDetailAttribute()
    {
        return CareHome::withTrashed()
            ->select('id', 'name', 'email', 'status', 'image')
            ->find($this->home_id);
    }

    public function getStaffDetailAttribute()
    {
        $staff = User::withTrashed()
            ->select('id', 'name', 'email', 'status')
            ->find($this->staff_id);

        return $staff ?: null;
    }

    public function addUpdateTrainingCourse($request)
    {
    
        $course_data = [
            'title' => $request->title,
            'description' => $request->description,
            'home_id' => isset($request->home_id) && $request->home_id != '' ? $request->home_id : 0,
            'staff_id' => isset($request->staff_id) && $request->staff_id != '' ? $request->staff_id : 0,
            'status' => isset($request->status) ? $request->status : 1,
            'duration' => $request->duration,
            'due_date' => date('Y-m-d', strtotime($request->due_date)),
            'updated_by' => Auth::user()->id,
        ];
        if ($request->id != 0) {
            $course = $this->findOrFail($request->id);
            $courseData = $course->update($course_data);
        } else {
            $course_data['created_by'] = Auth::user()->id;
            $course = $this->create($course_data);
            $course->save();
            $request->course_id = $course->id;
            $request->user_id = $request->staff_id;
            $request->course_status = $request->status;
            $request->status = 1;

            $assignedTraining = new StaffAssignedTraining();
            $assignedTraining->addUpdateUserCourse($request);
        }
        return true;
    }
    public function addUpdateTrainingCourseMobile($request)
    {
       
        $course_data = [
            'title' => $request->title,
            'description' => $request->description,
            'home_id' => isset($request->home_id) && $request->home_id != '' ? $request->home_id : 0,
            'staff_id' => isset($request->staff_id) && $request->staff_id != '' ? $request->staff_id : 0,
            'training_status' => $request->status,
            'duration' => $request->duration,
            'due_date' => date('Y-m-d', strtotime($request->due_date)),

            'updated_by' => Auth::user()->id,
        ];
        if ($request->id != 0) {
            $course = $this->findOrFail($request->id);
            $courseData = $course->update($course_data);
        } else {
            $course_data['created_by'] = Auth::user()->id;
            $course = $this->create($course_data);
            $course->save();
        }
        return true;
    }

    public function getTrainingCourseList($request)
    {
        $sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        return $this->where('home_id', $request->home_id)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere("staff_trainings.title", "LIKE", "%{$search}%");
                });
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                return $q->orderBy('staff_trainings.' . $sort_field, $sort_order);
            });
    }

    public function getAllTrainingCourseList($request)
    {
        $sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        $course_status = isset($request->course_status) ? $request->course_status : 'active';
        $home_ids = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();

        return $this->whereIn('home_id', $home_ids)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere("staff_trainings.title", "LIKE", "%{$search}%");
                });
            })
            ->when($course_status != '', function ($q) use ($course_status) {
                if ($course_status == 'inactive') {
                    return $q->where('staff_trainings.status', 0);
                } else if ($course_status == 'archive') {
                    return $q->withTrashed()->whereNotNull('staff_trainings.deleted_at');
                } else {
                    return $q->where('staff_trainings.status', '1');
                }
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                return $q->orderBy('staff_trainings.' . $sort_field, $sort_order);
            });
    }

    public function deleteTrainingCourse($request)
    {
        $course = $this->find($request->id);
        $course->delete();
        StaffAssignedTraining::where('course_id', $request->id)->delete();

        return true;
    }
}
