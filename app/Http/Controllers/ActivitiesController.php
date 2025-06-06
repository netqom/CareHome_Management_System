<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Role;
use App\Models\Activity;
use App\Models\CareHome;

class ActivitiesController extends Controller
{
	
	private $users;
	private $roles;
	private $activities;
    private $care_home;
	
	/**
     * Constructor Instance
     */
	public function __construct(User $users, Role $roles, Activity $activities, CareHome $care_home)
	{
		$this->users = $users;
		$this->roles = $roles;
		$this->activities = $activities;
        $this->care_home = $care_home;
	}
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('activities.index');
    }
	
	 /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response JSON
     */
	public function getData(Request $request)
    {
        $data = $this->activities->getActivityList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $view = 'activities.partials.list-table-body';

        $html = view($view, compact('records'))->render();
        $pagination = $this->preparePaginationData($request, $records, $total_count);
        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination]);
    }
	
	 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$shifts = getActivityShifts();
        $activity_recurrence = config('const.activity_recurrence');
        $activity_for = config('const.activity_type'); //Activity specify to all clients and specific client
        if(Auth::user()->role_id == 2){
            // $care_home = $this->care_home->select('id','name','email', 'user_id','created_by')->where(['status' => 1, 'deleted_at'=>null, 'created_by' => Auth::user()->id])->get();
            //$care_home = $this->care_home->getAdminCareHome();
            $care_home = $this->care_home->userBasedActiveHomeList();
        }else{
            // $care_home = $this->care_home->select('id','name','email', 'user_id','created_by')->where(['status' => 1, 'deleted_at'=>null])->get();
            $care_home = $this->care_home->getAllCareHome();
        }
        return view("activities.create", compact('shifts','activity_recurrence', 'care_home','activity_for'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		if($request->recurrence==2)
		{
			$request->activity_performance_day=json_encode($request->activity_performance_day);
		}else{
			$request->activity_performance_day=json_encode(explode(',',$request->activity_performance_month));
		}
      
        if(!empty($request->activity_performance_day)){
            request()->validate([
                'shift_id' => 'required',
                //'name' => 'required|unique:activities,name',
                'name' => 'required',
              
            ]);
        }else{
        
            request()->validate([
                'shift_id' => 'required',
                //'name' => 'required|unique:activities,name',
                'name' => 'required',
            ]);
        }
     
		$this->activities->addUpdateActivity($request, 0);
        return redirect()->route('activities.index')->with(['type' => 'success', 'message' => 'Activity created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$activity = $this->activities->find($id);
		return view("activities.show", compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {

		$shifts = getActivityShifts();
        $activity_recurrence = config('const.activity_recurrence');
        $activity_for = config('const.activity_type'); //Activity specify to all clients and specific client
        if(Auth::user()->role_id == 2){
            // $care_home = $this->care_home->select('id','name','email', 'user_id','created_by')->where(['status' => 1, 'deleted_at'=>null, 'created_by' => Auth::user()->id])->get();
            $care_home = $this->care_home->getAdminCareHome();
        }else{
            // $care_home = $this->care_home->select('id','name','email', 'user_id','created_by')->where(['status' => 1, 'deleted_at'=>null])->get();
            $care_home = $this->care_home->getAllCareHome();
        }
        return view("activities.edit", compact('activity', 'shifts','activity_recurrence','care_home','activity_for'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->recurrence==2)
		{
			$request->activity_performance_day=json_encode($request->activity_performance_day);
		}else{
			$request->activity_performance_day=json_encode(explode(',',$request->activity_performance_month));
		}
		request()->validate([
            //'name' => 'required|unique:activities,name,'.$id,
            'name' => 'required',
            'shift_id' => 'required',
            'status' => 'required',
        ]);
        $this->activities->addUpdateActivity($request, $id);
        return redirect()->route('activities.index')->with(['type' => 'success', 'message' => 'Activity updated successfully.']);
    }
	
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->back()->with(['type' => 'success', 'message' => 'Activity deleted successfully.']);
    }
	
    public function getCareHomePatients(Request $request){
        $patients = getCareHomePatient($request->home_id); 
        return response()->json($patients);
    }
}
