<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth, File;
use  App\Models\StaffTraining;
use App\Events\CreateNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
class StaffAssignedTraining extends Model
{
	
    use HasFactory, SoftDeletes;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'user_id',
        'course_id',
        'course_status',
        'start_date',
        'end_date',
		'due_date',
        'certificate',
		'status',
		'created_by',
        'updated_by'
    ];
	
	protected $appends = ['course_detail', 'certificate_url', 'status_name'];
	
	public function getCourseDetailAttribute(){
        return StaffTraining::find($this->course_id);
    }
	
	public function staff()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
	
	public function added_by()
	{
		return $this->belongsTo(User::class, 'created_by', 'id');
	}
	
	
	public function getCertificateUrlAttribute(){
        if(!is_null($this->certificate)){
			if (file_exists( public_path($this->certificate))) {
				return asset($this->certificate);
			}
		}
		// return '';
		return asset('assets/img/certificate.png');
    }
	
	public function getStatusNameAttribute(){
		if($this->course_status!=5)
		{
       return config('const.course_status')[$this->course_status];
		}else{
			return 'Completed';
		}
    }
	
	public function getStaffCourseList($request)
	{
		$sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        return $this->where('staff_assigned_trainings.user_id', $request->staff_id)->where('status',1)
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                 return $q->orderBy('staff_assigned_trainings.' . $sort_field, $sort_order);
            });
	}
	
	public function addUpdateUserCourse($request)
	{
		
		$course_data = [
            'user_id'       => $request->staff_id,
            'course_id'     => $request->course_id,
            'course_status' => $request->course_status,
            'start_date'    => (isset($request->start_date))? date('Y-m-d', strtotime($request->start_date)) :  date('Y-m-d'),
            'end_date'      => (isset($request->end_date))? date('Y-m-d', strtotime($request->end_date)):  date('Y-m-d',strtotime($request->due_date)),
			'due_date'=> (isset($request->due_date))? date('Y-m-d',strtotime($request->due_date)) : date('Y-m-d', strtotime($request->end_date)),
            'status'        => $request->status,
			'updated_by'    => Auth::user()->id,
        ];
        if ($request->id != 0) {
            $course = $this->findOrFail($request->id);
            $courseData = $course->update($course_data);
			// call the event
			event(new CreateNotification('course_updated', $course));
			//$this->saveCourseCertificate($request, $courseData);
        } else {
            $course_data['created_by']        = Auth::user()->id;
            $course = $this->create($course_data);
			$course->save();
			// call the event
			event(new CreateNotification('course_assigned', $course));
			//$this->saveCourseCertificate($request, $course);
        }
		return true;
	}
	
	public function saveCourseCertificate($request)
	{
		$course = $this->findOrFail($request->id);
		if ($request->hasFile('certificate')) {
			if(!is_null($course->certificate)){
				$old_image = public_path($course->certificate);
				File::delete($old_image);
			}
			$file = $request->file('certificate');
			$extension = $file->getClientOriginalExtension();
			$file_name = "certificate_". uniqid()."." . $extension;
            $file->move(public_path('uploads/staff-training-certificate/'), $file_name);
			$course->update(['certificate' => "uploads/staff-training-certificate/".$file_name]);
			// call the event
			event(new CreateNotification('course_certificate_uploaded', $course));
			return true;
		}else{
			return false;
		}
		
	}
	
	public function deleteUserCourse($request)
	{
		$course = $this->find($request->id);
		$course->delete();
		return true;
	}
}
