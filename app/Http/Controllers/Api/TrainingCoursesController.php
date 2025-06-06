<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\StaffTraining;
use App\Models\StaffAssignedTraining;

class TrainingCoursesController extends Controller
{
	
	private $users;
	private $training_courses;
	private $staff_courses;
	
	/**
     * Constructor Instance
     */
	public function __construct(User $users, StaffTraining $training_courses, StaffAssignedTraining $staff_courses)
	{
		$this->users            = $users;
		$this->training_courses = $training_courses;
		$this->staff_courses    = $staff_courses;
	}
	
	public function addUpdateTrainingCourse(Request $request)
    {
       //Validate data
        $validator = Validator::make($request->all(), [
			'home_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'status' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()], 200);
        }
		
        $result = $this->training_courses->addUpdateTrainingCourseMobile($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Training Course added successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
    }

    public function getTrainingCoursesList(Request $request)
    {
		$data = $this->training_courses->getTrainingCourseList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $pagination = $this->prepareApiPaginationData($request, $records, $total_count);
		$course_status = config('const.course_status');
	    return response()->json([
                'status' => 'success',
                'list' => $records,
				'course_status' => $course_status,
				'pagination' => $pagination,
            ]);
    }
	
	public function deleteTrainingCourse(Request $request)
	{
		$result = $this->training_courses->deleteTrainingCourse($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Training Course deleted successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}
	
	public function assignCourseToStaff(Request $request)
	{
		//Validate data
        $validator = Validator::make($request->all(), [
			'staff_id'      => 'required',
            'course_id'     => 'required',
            'course_status' => 'required',
            'status'        => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()], 200);
        }
		
        $result = $this->staff_courses->addUpdateUserCourse($request);
		$message = $request->id != 0 ? 'Training data updated successfully' : 'Training assigned to staff successfully';
		if($result){
			return response()->json(['status' => 'success', 'message' => $message], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}
	
	public function uploadTrainingCertificate(Request $request)
	{
		\Log::info(['hhhhhhhhhhhhh=====>' => $request->all()]);
		$result = $this->staff_courses->saveCourseCertificate($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Training certificate uploaded successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}
	
	public function getStaffAssignedCoursesList(Request $request)
    {
		$data = $this->staff_courses->getStaffCourseList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
		\Log::info(["trainingrecords====="=>$records]);
        $pagination = $this->prepareApiPaginationData($request, $records, $total_count);
	    return response()->json([
                'status' => 'success',
                'list' => $records,
				'pagination' => $pagination,
            ]);
    }
	
	public function deleteStaffCourse(Request $request)
	{
        $result = $this->staff_courses->deleteUserCourse($request);
		if($result){
			return response()->json(['status' => 'success', 'message' => 'Assigned training course deleted successfully'], 200);
		}else{
			return response()->json(['status' => 'error', 'message' => 'Some error occured please try again'], 200);
		}
	}

	
}