<?php

namespace App\Http\Controllers;

use App\Events\CreateNotification;
use App\Models\CareHome;
use App\Models\Patient;
use App\Models\Role;
use App\Models\StaffTraining;
use App\Models\Tasks;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use App\Models\Chat;
use App\Models\ChatMessage;

class UsersController extends Controller
{

    private $users;
    private $roles;
    private $homes;
    private $tasks;
    private $staffTrainings;
    private $chats;
	private $chat_message;

    /**
     * Constructor Instance
     */
    public function __construct(User $users, Role $roles, CareHome $homes, Tasks $tasks, StaffTraining $staffTrainings, Chat $chats, ChatMessage $chat_message)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->homes = $homes;
        $this->tasks = $tasks;
        $this->staffTrainings = $staffTrainings;
        $this->chats        = $chats;
		$this->chat_message = $chat_message;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $homes = [];
        $home_ids = $this->homes->where('user_id', Auth::user()->id)->pluck('id')->toArray();

        if (Auth::user()->role_id == 2) {
            $staffs = $this->users->where(['status' => 1, 'parent_id' => Auth::user()->id])->whereIn('role_id', [3, 4])->pluck('name', 'id');
            $homes = $this->homes->where('user_id', Auth::user()->id)->pluck('name', 'id');
        } else {
            $staffs = $this->users->where(['status' => 1])->whereIn('role_id', [3, 4])->pluck('name', 'id');
            $homes = $this->homes->where('status', 1)->pluck('name', 'id');

        }

        $tasks = $this->tasks->whereIn('user_type', ['staff', 'manager'])->whereIn('home_id', $home_ids)->pluck('title', 'id');
        $staffTrainingDatas = $this->staffTrainings->whereIn('home_id', $home_ids)->pluck('title', 'id');

        return view('users.index', compact('staffs', 'tasks', 'staffTrainingDatas', 'homes'));
    }

    public function careHomeAdmin(Request $request)
    {
        return view('users.care-home-admin');
    }

    public function careHomeUsers(Request $request)
    {
        $home_id = $request->home_id;
        $staffs = $this->users->where(['status' => 1])
            ->when($home_id, function ($qry) use ($home_id) {
                $qry->where('home_id', $home_id);
            })
            ->whereIn('role_id', [3, 4])
            ->pluck('name', 'id');

        $html = "<option value=''></option>";
        foreach ($staffs as $staffKey => $staff) {
            $html .= "<option value='" . $staffKey . "'>" . $staff . "</option>";
        }
        return response()->json(['type' => 'success', 'html' => $html]);
    }

    public function changeUserStatus(Request $request)
    {
        $status = 0;

        if ($request->status == 1) {
            $status = 0;
            $data = $this->users->where('id', $request->user_id)->update(['status' => $status]);
            // UserAppInfo::where('user_id', $request->user_id)->delete();
            $token = \Laravel\Sanctum\PersonalAccessToken::where('tokenable_id', $request->user_id)->delete();

        } else {
            $status = 1;
            $data = $this->users->where('id', $request->user_id)->update(['status' => $status]);
        }

        return response()->json(['type' => 'success', 'message' => 'Status updated successfuly.']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response JSON
     */
    public function getData(Request $request)
    {
        $data = $this->users->getUserList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $view = 'users.partials.user-list-table-body';

        $html = view($view, compact('records'))->render();
        $pagination = $this->preparePaginationData($request, $records, $total_count);
        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination]);
    }

    public function careHomeGetData(Request $request)
    {
        $data = $this->users->where(['role_id' => 2])->orderBy('id', 'desc');
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $view = 'users.partials.care-list-table-body';

        $html = view($view, compact('records'))->render();
        $pagination = $this->preparePaginationData($request, $records, $total_count);
        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $home_id = $request->has('home_id') ? $request->home_id : '';
        // if($home_id == ''){
        //      return redirect()->back()->with(['type' => 'error', 'message' => 'You need to have home id for adding user']);
        // }
        $homes = $this->homes->userBasedActiveHomeList();
        if ($homes->count() == 0) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You need to add care home first before adding staff']);
        }
        $roles = $this->roles->userBasedRoleList();
        $shifts = getWorkShifts();
        $geo_status = getGeofencingStatuses();
        return view("users.create", compact('roles', 'homes', 'home_id', 'shifts', 'geo_status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required',
            'phone_number' => 'required',
            // 'status' => 'required',
        ]);

        /*   if(checkUserAllowedCapacity($request->home_id) >= countAddedStaff($request->home_id))
        {
        $homeDetail= $this->homes->find($request->home_id);

        return redirect()->back()->with(['type' => 'error', 'message' => 'CareHome '.$homeDetail->name.' staff limit exceed please upgrade your plan or purchase Addons']);
        } */
        $request->status = 1;
        $this->users->addUpdateUser($request, 0);
        $routes = Route::getRoutes();
        $route = $routes->match(request()->create($request->redirectURL, 'GET'));
        $routeName = $route->getName();

        if ($routeName == 'homes.create') {
            $redirect_url = route('homes.index');
        } elseif ($routeName == 'homes.show') {
            $redirect_url = $request->redirectURL . '#tab2';
        } else {
            $redirect_url = $request->redirectURL;
        }

        $redirect_url = isset($request->redirectURL) ? $redirect_url : route('users.index');
        //return redirect()->route('users.index')->with(['type' => 'success', 'message' => 'Users created successfully.']);
        return redirect($redirect_url)->with(['type' => 'success', 'message' => 'Users created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->users->withTrashed()->find($id);
        $task_type = config('const.task_type');
        $task_status = config('const.task_status');
        return view("users.show", compact('user', 'task_status', 'task_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $homes = $this->homes->userBasedHomeList();
        //$roles = $this->roles->userBasedRoleList();
        $roles = $this->roles->whereIn('id', [3, 4])->get();
        $shifts = getWorkShifts();
        $geo_status = getGeofencingStatuses();
        return view("users.edit", compact('user', 'roles', 'homes', 'shifts', 'geo_status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'name' => 'required',
            //'email' => 'required|email|unique:users,email,'.$id,
            'role_id' => 'required',
            'phone_number' => 'required',
            'status' => 'required',
        ]);
        $this->users->addUpdateUser($request, $id);
        $userData = $this->users->find($id);
        if ($userData->status == 0) {
            $token = \Laravel\Sanctum\PersonalAccessToken::where('tokenable_id', $id)->delete();
        }
        $redirect_url = isset($request->redirectURL) ? $request->redirectURL : route('users.index');
        return redirect($redirect_url)->with(['type' => 'success', 'message' => 'Users updated successfully.']);
        //return redirect()->route('users.index')->with(['type' => 'success', 'message' => 'Users updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //check if user is admin of any care homes
        $home = $this->homes->where('user_id', $user->id)->first();
        if ($home) {
            return redirect()->route('users.index')->with(['type' => 'error', 'message' => 'Users is admin of a care home either you delete care home or you change the admin .']);
        }
        $user->status = '0'; // Update the status to inactive
        $user->deleted_at = Carbon::now();
        if($user->save()) { // Save the changes to the database
            $update = $this->chat_message->where('sender_id', $user->id)->update(["seen"=>1]);
        }
        // $user->save(); // Save the changes to the database
        // $user->delete();
        return redirect()->route('users.index')->with(['type' => 'success', 'message' => 'Users deleted successfully.']);
    }

    public function viewUserAssignedTask($id)
    {
        $user_assigned_task = $this->tasks->find($id);
        $task_type = config('const.task_type');
        $task_status = config('const.task_status');
        return view('users.partials.view-user-assigned-task', compact('user_assigned_task', 'task_type', 'task_status'));
    }
    public function checkIsAdminAbleToAddStaff(Request $request)
    {
        $home_id = $request->home_id;
        $home = $this->homes->where('id', $home_id)->first();
        $home['getCareHomeSubscription'] =   $this->homes->getCareHomeActiveSubscription($home_id);
        if($home->getCareHomeSubscription->stripe_status != 'active'){
            return response()->json(['type' => 'failed', 'name' => $home->name, 'subscription_status' => true ]);
        }
        if ($home->deleted_at == null && checkUserAllowedCapacity($home->id) > countAddedStaff($home->id)) {
            return response()->json(['type' => 'failed', 'name' => $home->name]);

        } else {
            return response()->json(['type' => 'success', 'name' => $home->name]);
        }
    }

    public function checkIsAdminAbleToAddStaffRestore(Request $request)
    {
        $home_id = $request->home_id;
        $home = $this->homes->where('id', $home_id)->first();
        $staffData = $this->users->withTrashed()->where('id', $request->staff_id)->first();
        // dd($staffData);
        if ($home->deleted_at == null && checkUserAllowedCapacity($home->id) > countAddedStaff($home->id)) {
            //dd($request->all());
            User::withTrashed()->where('id', $request->staff_id)->update(['deleted_at' => null, 'status' => 1]);
            event(new CreateNotification('staff_restore', $staffData));
            return response()->json(['type' => 'failed', 'name' => $home->name]);
        } else {
            return response()->json(['type' => 'success', 'name' => $home->name]);
        }
    }

    public function checkIsAdminAbleToAddPatient(Request $request)
    {
        //$home_id=$request->home_id;
        $home = $this->homes->where('id', $request->home_id)->first(); //Patient
        $patientData = Patient::withTrashed()->where('id', $request->patient_id)->first();
        //dd($home);

        $checkPatientCapacity = checkPatientAllowedCapacity($request->home_id);
        if (!$checkPatientCapacity) {
            return response()->json(['status' => 'failed', 'message' => 'Exceed the limit']);
        } else {
            $patient = Patient::withTrashed()->where('id', $request->patient_id)->update([
                'discharged' => 0,
                'discharged_request' => 0,
                'delete_request' => 0,
                'status' => 1,
                'deleted_at' => null,
            ]);

            event(new CreateNotification('patient_restore', $patientData));
            return response()->json(['status' => 'success', 'message' => 'Patient successfully restored!']);
        }
    }

    public function getStaffCount(Request $request)
    {
        $home_id = $request->home_id;
        $home = $this->homes->where('id', $home_id)->first();
        $allowCapacity = checkUserAllowedCapacity($home->id);
        $addedStaff = countAddedStaff($home->id);
        return response()->json(['type' => 'success', 'staff_limit' => $allowCapacity, 'added_staff' => $addedStaff]);
    }

}
