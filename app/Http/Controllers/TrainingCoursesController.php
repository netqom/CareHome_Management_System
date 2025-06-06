<?php

namespace App\Http\Controllers;

use App\Models\CareHome;
use App\Models\StaffAssignedTraining;
use App\Models\StaffTraining;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrainingCoursesController extends Controller
{
    private $users;
    private $training_courses;
    private $staff_courses;
    private $care_home;

    /**
     * Constructor Instance
     */
    public function __construct(User $users, StaffTraining $training_courses, StaffAssignedTraining $staff_courses, CareHome $care_home)
    {
        $this->users = $users;
        $this->training_courses = $training_courses;
        $this->staff_courses = $staff_courses;
        $this->care_home = $care_home;
    }

    /** Get Training Course List */

    public function viewTraningCourseList()
    {

        return view('training-course.index');
    }

    public function getTrainingCoursesList(Request $request)
    {
        $data = $this->training_courses->getAllTrainingCourseList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $view = 'training-course.partials.list-table-body';

        $html = view($view, compact('records'))->render();
        $pagination = $this->preparePaginationData($request, $records, $total_count);

        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination]);
    }

    public function addUpdateTrainingCourse($id = 0)
    {
        // $care_home = $this->care_home->where('user_id', Auth::user()->id)->get();
        $care_home = $this->care_home->userBasedActiveHomeList();
        $get_course_detail = $this->training_courses->find($id);
        $view = $id == 0 ? 'training-course.create' : 'training-course.edit';
        return view($view, compact('care_home', 'get_course_detail'));
    }

    public function saveTrainingCourse(Request $request)
    {

        //Validate data
        request()->validate([
            'home_id' => 'required',
            //'staff_id' => 'required',
            'title' => 'required',
            //'due_date' => 'required',
            'status' => 'required',
        ]);
        
        $result = $this->training_courses->addUpdateTrainingCourse($request);
        $redirect_url = isset($request->redirectURL) ? $request->redirectURL : route('training-course-list');
        return redirect($redirect_url)->with(['type' => 'success', 'message' => 'Training Course added successfully']);
    }

    public function deleteTrainingCourse(Request $request, $id)
    {
        $request->id = $id;
        $result = $this->training_courses->deleteTrainingCourse($request);
        if ($result) {
            return redirect()->back()->with(['type' => 'success', 'message' => 'Training Course deleted successfully']);
        } else {
            return redirect()->back()->with(['type' => 'error', 'message' => 'Some error occured please try again']);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $staff_id = $request->staff_id;
        $home_id = $request->home_id;
        $user_detail = $this->users->withTrashed()->find($staff_id);

        return view('users.assign_training.index', compact('staff_id', 'home_id', 'user_detail'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response JSON
     */
    public function getData(Request $request)
    {
        $course_status = config('const.course_status');
        $course_status[5] = "Completed";
        $user_detail = $this->users->withTrashed()->find($request->staff_id);
        $data = $this->staff_courses->getStaffCourseList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $view = 'users.assign_training.partials.training-list-table-body';

        $html = view($view, compact('records', 'course_status', 'user_detail'))->render();
        $pagination = $this->preparePaginationData($request, $records, $total_count);

        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination]);
    }

    /** Assign course to the both staff and manager */

    public function create(Request $request)
    {

        $user_id = $request->id;
        $home_id = $request->home_id;

        $training_course_list = $this->training_courses->where(['home_id' => $home_id, 'status' => 1])->get();
        $course_status = config('const.course_status');
        return view("users.assign_training.create", compact('user_id', 'home_id', 'training_course_list', 'course_status'));
    }

    public function store(Request $request)
    {

        //Validate data
        $status = ($request->status) ? 'required' : '';
        request()->validate([
            'course_id' => 'required',
            'course_status' => 'required',
            'status' => $status,
        ]);
        if (!$request->has('status')) {
            $request->merge(['status' => 1]);
        }

        $result = $this->staff_courses->addUpdateUserCourse($request);
        $redirect_url = isset($request->redirectURL) ? $request->redirectURL : route('homes.show', $request->home_id);
        return redirect($redirect_url)->with(['type' => 'success', 'message' => 'Training assigned to staff successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StaffAssignedTraining  $staff_courses
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $home_id = $request->home_id;
        $assined_training_list = $this->staff_courses->find($id);
        $training_course_list = $this->training_courses->where('home_id', $home_id)->get();
        $course_status = config('const.course_status');
        return view("users.assign_training.edit", compact('assined_training_list', 'training_course_list', 'course_status', 'home_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @param  \App\Models\StaffAssignedTraining $staff_courses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        request()->validate([
            'course_id' => 'required',
            'course_status' => 'required',
            'status' => 'required',
        ]);

        $result = $this->staff_courses->addUpdateUserCourse($request);
        $redirect_url = isset($request->redirectURL) ? $request->redirectURL : route('homes.show', $request->home_id);
        return redirect($redirect_url)->with(['type' => 'success', 'message' => 'Training assigned to staff updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StaffAssignedTraining $staff_courses
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->id = $id;
        $result = $this->staff_courses->deleteUserCourse($request);

        if ($result) {
            return redirect()->back()->with(['type' => 'success', 'message' => 'Assigned training course deleted successfully']);
        } else {
            return redirect()->back()->with(['type' => 'error', 'message' => 'Some error occured please try again']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $home_id = $request->home_id;
        $assined_training = $this->staff_courses->find($id);
        $course_status = config('const.course_status');
        return view("users.assign_training.view", compact('id', 'assined_training', 'home_id', 'course_status'));
    }

    public function uploadTrainingCertificate(Request $request)
    {

        $result = $this->staff_courses->saveCourseCertificate($request);
        if ($result) {
            return redirect()->back()->with(['type' => 'success', 'message' => 'Training certificate uploaded successfully']);
        } else {
            return redirect()->back()->with(['type' => 'error', 'message' => 'Some error occured please try again']);
        }

    }
    public function completeTrainingByAdmin(Request $request, $id)
    {

        $course = $this->staff_courses->findOrFail($request->training_id);
        $course_data = ['course_status' => 5];

        if ($course->update($course_data)) {
            return response()->json(['type' => 'success', 'message' => "Training Successfully completed"]);
        } else {
            return response()->json(['type' => 'error', 'message' => 'Some error occured please try again']);
        }
    }

}
