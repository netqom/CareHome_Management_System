<?php

namespace App\Http\Controllers;

use App\Models\CareHome;
use App\Models\Patient;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    //
    private $users;
    private $tasks;
    private $patients;
    private $homes;

    /**
     * Constructor Instance
     */
    public function __construct(User $users, Tasks $tasks, Patient $patients, CareHome $homes)
    {
        $this->users = $users;
        $this->tasks = $tasks;
        $this->patients = $patients;
        $this->homes = $homes;
    }

    public function viewTasksList(Request $request)
    {

        return view('tasks.index');
    }

    public function getTasksList(Request $request)
    {
        $data = $this->tasks->getAllTasksList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $view = 'tasks.partials.list-table-body';

        $html = view($view, compact('records'))->render();
        $pagination = $this->preparePaginationData($request, $records, $total_count);

        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination]);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tasks = $this->tasks->leftJoin('users', function ($join) {
            $join->on('tasks.user_id', '=', 'users.id');
        })
            ->leftJoin('patients', function ($join) {
                $join->on('tasks.patient_id', '=', 'patients.id');
            })
            ->select('tasks.*', 'users.name as user_name', 'patients.name as patient_name')
            ->withTrashed()
            ->find($id);
        return view("tasks.show", compact('tasks'));

    }

    public function addUpdateTasks($id = 0)
    {
        $users = $this->users->getUsers();
        $managers = $this->users->getManager();
        $care_homes = $this->homes->userBasedActiveHomeList();
        $patients = $this->patients->getCareHomePatients();
        $get_task_detail = $this->tasks->find($id);
        $view = $id == 0 ? 'tasks.create' : 'tasks.edit';
        return view($view, compact('users', 'managers', 'patients', 'get_task_detail', 'care_homes'));
    }

    public function saveTasks(Request $request)
    {
        //Validate data
        request()->validate([
            'title' => 'required',
            'start_date' => 'required',
            'start_time' => 'required',
            'task_type' => 'required',
        ]);
        if (isset($request->selected_month_dates) && !empty($request->selected_month_dates)) {
            $request->selected_month_dates = explode(',', $request->selected_month_dates);

        }

        $result = $this->tasks->addUpdateTasks($request);
        $redirect_url = isset($request->redirectURL) ? $request->redirectURL : route('tasks-list');
        return redirect($redirect_url)->with(['type' => 'success', 'message' => 'Task added successfully']);
    }

    public function deleteTask(Request $request, $id)
    {
        $request->id = $id;
        $result = $this->tasks->deleteTask($request);
        if ($result) {
            return redirect()->back()->with(['type' => 'success', 'message' => 'Task deleted successfully']);
        } else {
            return redirect()->back()->with(['type' => 'error', 'message' => 'Some error occured please try again']);
        }
    }
}
