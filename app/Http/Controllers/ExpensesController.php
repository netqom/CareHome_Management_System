<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Expense;
use App\Models\User;
use App\Models\Patient;
use App\Models\CareHome;

class ExpensesController extends Controller
{
    private $expenses;
    private $users;
    private $homes;
    private $patients;

    /**
     * Constructor Instance
     */
	public function __construct(User $users, Patient $patients, Expense $expenses, CareHome $homes)
	{
       
        $this->expenses         = $expenses;
        $this->users            = $users;
        $this->patients         = $patients;
        $this->homes = $homes;
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $homes = $this->homes->where('user_id', Auth::user()->id)->pluck('name', 'id');
		$users    = $this->users->getCareHomeUser();
		$patients = $this->patients->getCareHomePatients();
		return view('expenses.expenses-list', compact('users', 'patients', 'homes'));
    }

    public function careHomePatients(Request $request)
    {
        $patients = $this->patients->where(['home_id' => $request->home_id])->where('discharged', 0)->whereNull('deleted_at')->groupBy('name')->pluck('name', 'id');
        $html = "<option value=''>Select</option>";
        foreach($patients as $key => $patient){
            $html .= "<option value='".$key."'>".$patient."</option>";
        }
        return response()->json(['type' => 'success', 'html' => $html]);
    }
	
	
	
	/**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response JSON
     */
	public function getExpensesData(Request $request)
    {
        $data = $this->expenses->getExpenseList($request);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $view = 'expenses.partials.expenses-list-table-body';

        $html = view($view, compact('records'))->render();
        $pagination = $this->preparePaginationData($request, $records, $total_count);
        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $pagination]);
    }
	
	public function store(Request $request)
	{
		//echo"<pre>";print_r($request->all());die;
		$data = $this->expenses->addUpdateExpenseAdmin($request);
		if($data){
			return response()->json(['type' => 'success', 'message' => 'Expense added successfully']);
		}else{
			return response()->json(['type' => 'error', 'message' => 'Some error occured please try again.']);
		}
	}
	
}
