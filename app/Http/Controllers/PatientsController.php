<?php

namespace App\Http\Controllers;

use App\Events\CreateNotification;
use App\Models\Activity;
use App\Models\ActivityTime;
use App\Models\CareHome;
use App\Models\Expense;
use App\Models\Patient;
use App\Models\PatientActivity;
use App\Models\PatientActivityField;
use App\Models\PatientAppointment;
use App\Models\PatientDoctor;
use App\Models\PatientDocument;
use App\Models\PatientLog;
use App\Models\PatientLogImage;
use App\Models\PatientMedicine;
use App\Models\SubscriptionPlan;
//use Str;
//use Barryvdh\DomPDF\Facade as Pdf;
use App\Models\Tasks;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use ZipArchive;

class PatientsController extends Controller
{

    private $patients;
    private $patient_medicines;
    private $patient_documents;
    private $patient_doctors;
    private $patient_appointments;
    private $patient_expenses;
    private $patient_logs;
    private $activities;
    private $patient_activities;
    private $tasks;
    private $homes;
    private $users;
    private $subscriptionPlan;
    private $activity_time;
    private $patient_activity_fields;

    /**
     * Constructor Instance
     */
    public function __construct(Patient $patients, PatientMedicine $patient_medicines, PatientDocument $patient_documents, Expense $patient_expenses, PatientLog $patient_logs, Activity $activities, PatientActivity $patient_activities, Tasks $tasks, PatientAppointment $patient_appointments, CareHome $homes, PatientDoctor $patient_doctors, User $users, SubscriptionPlan $subscriptionPlan, ActivityTime $activity_time, PatientActivityField $patient_activity_fields)
    {
        $this->patients = $patients;
        $this->patient_medicines = $patient_medicines;
        $this->patient_documents = $patient_documents;
        $this->patient_doctors = $patient_doctors;
        $this->patient_expenses = $patient_expenses;
        $this->patient_logs = $patient_logs;
        $this->activities = $activities;
        $this->patient_activities = $patient_activities;
        $this->tasks = $tasks;
        $this->patient_appointments = $patient_appointments;
        $this->homes = $homes;
        $this->users = $users;
        $this->subscriptionPlan = $subscriptionPlan;
        $this->activity_time = $activity_time;
        $this->patient_activity_fields = $patient_activity_fields;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $home_ids = $this->homes->where('user_id', Auth::user()->id)->pluck('id')->toArray();
        // $staffs = $this->patients->where(['status' => 1])->whereIn('role_id',[3,4])->pluck('name', 'id');
        // $tasks = $this->tasks->whereIn('user_type', ['staff','manager'])->whereIn('home_id', $home_ids)->pluck('title', 'id');
        // $staffTrainingDatas = $this->staffTrainings->whereIn('home_id', $home_ids)->pluck('title', 'id');

        return view('patients.index');
    }

    public function getData(Request $request)
    {
        $home_ids = $this->homes->where('user_id', Auth::user()->id)
            ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, name,email FROM care_homes WHERE stripe_id IS NOT NULL GROUP BY email) AS latest_care_homes'), function ($join) {
                $join->on('care_homes.id', '=', 'latest_care_homes.id');
            })->get()
            ->pluck('id')->toArray();
        $data = $this->patients->getPatientListAll($request, $home_ids);
        $total_count = $data->get()->isNotEmpty() ? $data->get()->count() : 0;
        [$offset, $limit] = $this->dataOffsetLimit($request);
        $records = $data->skip($offset)->take($limit)->get();
        $view = 'patients.partials.patient-list-table-body';

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
        $home_id = isset($request->home_id) ? $request->home_id : 0;
        //$care_homes = $this->homes->where(['user_id' => Auth::user()->id, 'status' => 1])->pluck('name', 'id');
        $care_homes = $this->homes->userBasedActiveHomeList();
        if ($home_id) {
            $staffs = $this->users->where(['role_id' => 4, 'created_by' => Auth::user()->id, 'home_id' => $home_id])->pluck('name', 'id');
        } else {
            $staffs = [];
        }
        $activities = $this->activities->where('created_by', Auth::user()->id)->get();
        $activity_recurrence = config('const.activity_recurrence');
        $medicine_type = config('const.medicine_type');
        $medicine_time = config('const.medicine_time');
        $medicine_time_other = config('const.medicine_time_other');
        $intake_method = config('const.medicine_intake_method');
        $intake_guidedby = config('const.medicine_intake_supervised_by');
        $shifts = getActivityShifts();
        $activity_for = config('const.activity_type');
        return view("patients.create", compact('home_id', 'care_homes', 'activities', 'activity_recurrence', 'medicine_type', 'medicine_time', 'intake_method', 'intake_guidedby', 'shifts', 'activity_for', 'staffs', 'medicine_time_other'));
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
            'home_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            //'phone_number' => 'required',
            'emergency_contact' => 'required',
            'per_day_cost' => 'required',
            'admission_date' => 'required',
            //'status' => 'required',
            'initial_payment' => 'required',
        ]);
        $home_id = $request->home_id;

        $result = $this->patients->addUpdatePatient($request);

        if ($result) {
            //return redirect()->route('patients.show', $result->id)->with(['type' => 'success', 'message' => 'Patient updated successfully']);
            return response()->json(['status' => 'success', 'home_id' => $home_id, 'message' => 'Patient added successfully']);
        }

        // return redirect()->route('homes.show', $home_id)->with(['type' => 'success', 'message' => 'Patient added successfully']);
    }

    public function isEmailExsist(Request $request)
    {
        $email = $request->email;
        if (isset($request->id) && !empty($request->id)) {
            $res = $this->patients->where('email', $email)->first();

            if (!empty($res)) {
                if ($res->id == $request->id) {

                    $user = [];
                } else {

                    $user = $res;
                }

            } else {
                $user = [];
            }
        } else {
            $user = $this->patients->where('email', $email)->first();
        }
        if (!empty($user)) {
            return response()->json(['status' => 'success', 'user' => $user, 'message' => 'Patient already exist', 'exsist' => true]);
        } else {
            return response()->json(['status' => 'success', 'user' => [], 'message' => 'Patient not exist', 'exsist' => false]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      
        $allowed = checkPatientBelongToAdminHome($id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action']);
        }
        $patient = $this->patients->withTrashed()->find($id);
        $medicine_report_list = $this->patient_activities->getPatientTakenNedicine($id);

        $subscriptionPermission = $this->homes->withTrashed()->where('id', $patient->home_id)->first();
        $subscriptionPermission = json_decode($subscriptionPermission->subscription_plan_permission);
        $logs = $this->patient_logs->where('patient_id', $id)->where('log_type', 0)->orderBy('id', 'desc')->paginate(10);
        $activity_recurrence = config('const.activity_recurrence');
        $task_type = config('const.task_type');
        $task_status = config('const.task_status');
        return view('patients.show', compact('patient', 'logs', 'task_status', 'activity_recurrence', 'task_type', 'subscriptionPermission', 'medicine_report_list'));
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function viewPatientActivity($id)
    {
        $allowed = checkPatientBelongToAdminHome($id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action']);
        }
        $patient = $this->patients->find($id);
        $logs = $this->patient_logs->where('patient_id', $id)->where('log_type', 1)->paginate(10);
        return view('patients.activity-list', compact('patient', 'logs'));
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function viewPatientActivityDetail($patient_id, $id)
    {
        $log = $this->patient_logs->find($id);
        $patient = $this->patients->withTrashed()->find($patient_id);
        $logAttachmentMorningShift = PatientLogImage::where(['log_id' => $id, 'shift_id' => 1, 'type' => 2])->get();
        $logAttachmentAfternoonShift = PatientLogImage::where(['log_id' => $id, 'shift_id' => 2, 'type' => 2])->get();
        $logAttachmentEveningShift = PatientLogImage::where(['log_id' => $id, 'shift_id' => 3, 'type' => 2])->get();
        $logAttachmentNightShift = PatientLogImage::where(['log_id' => $id, 'shift_id' => 4, 'type' => 2])->get();
        $logAttachmentAdHocShift = PatientLogImage::where(['log_id' => $id, 'shift_id' => 5, 'type' => 2])->get();
        $allowed = checkPatientBelongToAdminHome($patient_id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action']);
        }
        return view('patients.activity-detail', compact('log', 'logAttachmentMorningShift', 'logAttachmentAfternoonShift', 'logAttachmentEveningShift', 'logAttachmentNightShift', 'logAttachmentAdHocShift', 'patient'));
    }

    public function downloadPatientActivityLog($id)
    {
        $log = $this->patient_logs->find($id);
        $allowed = checkPatientBelongToAdminHome($log->patient_id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action']);
        }
        //return view('patients.activity-detail-pdf', compact('log'));
        $pdf = Pdf::loadView('patients.activity-detail-pdf', compact('log'));
        $name_slug = Str::slug($log->patient->name, '-');
        $file_name = $name_slug . '-' . $log->report_date . '.pdf';
        return $pdf->download($file_name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = $this->patients->find($id);
        $allowed = checkPatientBelongToAdminHome($id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action']);
        }
        return view("patients.edit", compact('id', 'patient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @param  \App\Models\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request['patient_id'] = $id;
        request()->validate([
            'home_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'emergency_contact' => 'required',
            'per_day_cost' => 'required',
            'admission_date' => 'required',
            //'status' => 'required',
            'initial_payment' => 'required',
        ]);
        $home_id = $request->home_id;
        $this->patients->addUpdatePatient($request);
        //return redirect()->back()->with(['type' => 'success', 'message' => 'Patient updated successfully']);
        return redirect()->route('patients.show', $id)->with(['type' => 'success', 'message' => 'Patient updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        //
    }

    /**
     * Add/Update the patients medicine from storage.
     *
     * @param  \App\Http\Request  $request
     * @param  \App\Models\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function addUpdatePatientsMedicine($patient_id, $id)
    {

        $patient = $this->patients->find($patient_id);
        $allowed = checkPatientBelongToAdminHome($patient_id);

        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action']);
        }
        
        $medicine = $this->patient_medicines->find($id);

        $medicine_type = config('const.medicine_type');
        $medicine_time = config('const.medicine_time');
        $medicine_time_other = config('const.medicine_time_other');
        $intake_method = config('const.medicine_intake_method');
        $intake_guidedby = config('const.medicine_intake_supervised_by');
        $medicine_frequency = config('const.medicine_frequency');
        $home_id = $patient->home_id;
        $care_home_activity_time = $this->activity_time->where('home_id', $home_id)->first();

        return view('patients.add-update-patient-medicine', compact('home_id', 'medicine', 'patient_id', 'id', 'medicine_type', 'medicine_time', 'intake_method', 'intake_guidedby', 'medicine_time_other', 'medicine_frequency', 'care_home_activity_time'));
    }

    public function savePatientsMedicine(Request $request)
    {
        //dd($request->all())

        request()->validate([
            'medicines.*.medicine_name' => 'required',
            'medicines.*.dose' => 'required',
            'medicines.*.dose_type' => 'required',
            'medicines.*.medicine_type' => 'required',
            'medicines.*.intake_method' => 'required',
            'medicines.*.intake_supervised_by' => 'required',
            'medicines.*.instructions' => 'required',
            'medicines.*.med_frequency' => 'required',
        ]);

        $this->patient_medicines->addMedicine($request);
        return redirect()
            ->route('patients.show', $request->patient_id)
            ->with(['type' => 'success', 'message' => 'Patient medicine added successfully'])
            ->withFragment('tab1');
    }

    public function deletePatientsMedicine($id)
    {

        $patient = $this->patient_medicines->find($id);
        $allowed = checkPatientBelongToAdminHome($patient->patient_id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action'])->withFragment('tab1');
        }
        $this->patient_medicines->find($id)->delete();
        return redirect()->back()->with(['type' => 'success', 'message' => 'Patient medicine deleted successfully'])->withFragment('tab1');
    }

    // Discontinue Medicien for patient

    public function discontinuePatientsMedicine(Request $request, $patient_id)
    {
        request()->validate([
            'note' => 'required',
        ]);

        $this->patient_medicines->where('id', $request->medicine_id)->update(['is_discontinue' => 1, 'discontinue_note' => $request->note, 'discontinue_date' => date('Y-m-d')]);
        return redirect()->route('patients.show', $patient_id)->with(['type' => 'success', 'message' => 'Patient medicine discontinue successfully'])->withFragment('tab1');
    }

    /**
     * Add/Update the patients document from storage.
     *
     * @param  \App\Http\Request  $request
     * @param  \App\Models\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function addUpdatePatientsDocument($patient_id, $id)
    {

        $allowed = checkPatientBelongToAdminHome($patient_id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action'])->withFragment('tab2');
        }
        $patient = $this->patients->find($patient_id);
        $document = $this->patient_documents->find($id);

        $home_id = $patient->home_id;
        if ($home_id) {
            $staffs = $this->users->where(['role_id' => 4, 'created_by' => Auth::user()->id, 'home_id' => $home_id])->pluck('name', 'id');
        } else {
            $staffs = [];
        }
        return view('patients.add-update-patient-document', compact('home_id', 'document', 'patient_id', 'id', 'staffs'));
    }

    public function savePatientsDocument(Request $request)
    {

        $this->patient_documents->addDocument($request);
        return redirect()->route('patients.show', $request->patient_id)->with(['type' => 'success', 'message' => 'Patient document added successfully'])->withFragment('tab2');
    }

    public function addUpdatePatientsSchedule($patient_id, $id)
    {

        $allowed = checkPatientBelongToAdminHome($patient_id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action'])->withFragment('tab7');
        }
        $patient = $this->patients->find($patient_id);
        $medicine = $this->patient_medicines->find($id);
        $appointment = $this->patient_appointments->find($id);
        $home_id = $patient->home_id;
        $doctors = $this->patient_doctors->where('patient_id', $patient_id)->pluck('name', 'id');
        $medicine_frequency = config('const.schdule_frequency');

        return view('patients.add-update-patient-schedule', compact('home_id', 'medicine', 'medicine_frequency', 'appointment', 'patient_id', 'id', 'doctors'));
    }

    public function patientActivityByShiftDate(Request $request)
    {

        $data = $this->patient_logs->where(['patient_id' => $request->patient_id, 'log_type' => $request->type])->whereDate('report_date', date('Y-m-d', strtotime($request->report_date)))->first(); //array_key_exists('1',$medicine_time)

        $msg = 'Activity already Exist';
        if ($data) {
            $patientActivity = PatientActivity::where(['log_id' => $data->id, 'shift_id' => $request->shift_id])->first();

            $reportItem = json_decode($data->report_time, true);
            if (array_key_exists($request->shift_id, $reportItem)) {
                
                if ($request->type != 1) {
                    $redirectUrl = route('patients-show-activity-detail', ['patient_id' => $request->patient_id, 'id' => $data->id]);
                    
                } else {
                    if ($patientActivity) {
                        $msg = 'Medicine report already Exist';
                        $redirectUrl = route('patients.show', $request->patient_id) . '#tab10'; //->withFragment('tab10');
                       
                    } else {
                        return response()->json(['type' => 'false'], 200);
                    }

                    //route('patients-show-activity-detail', ['patient_id' => $request->patient_id, 'id' => $data->id]);
                }

                return response()->json(['type' => 'success', 'message' => $msg, 'data' => $data, 'redirect_url' => $redirectUrl], 200);
            }
        }
        return response()->json(['type' => 'false'], 200);
    }

    public function addPatientMedicineReport(Request $request, $patient_id, $shift_id)
    {
        //$shift_id= 1;//$activity_slug;
        $shiftNames = [
            1 => 'morning',
            2 => 'afternoon',
            3 => 'evening',
            4 => 'night',
            5 => 'ad_hoc',
        ];

        $report_date = !empty($request->query('report_date')) ? $request->query('report_date') : date('Y-m-d');
        $patientActivity = [];
        $data = $this->patient_logs->where(['patient_id' => $patient_id, 'log_type' => 1])->whereDate('report_date', $report_date)->first();

        if ($data) {
            $patientActivity = PatientActivity::where(['log_id' => $data->id, 'shift_id' => $shift_id])->get();
        }

        $patient = $this->patients->find($patient_id);
        $home_id = $patient->home_id;
        $staffList = $this->users->where(['home_id' => $home_id, 'status' => 1, 'role_id' => 4])->pluck('name', 'id')->toArray();

        $patient_medicines = $this->patient_medicines->where('patient_id', $request->patient_id);

        if ($shift_id == 5) {
            $patient_medicines = $this->getMedicineByType($request, 1, $shift_id, 1);

        } else {
            $patient_medicines = $this->getMedicineByType($request, 3, $shift_id, 1);
        }

        $medicineData = $patient_medicines;
        $activity_form = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => $shiftNames[$shift_id]])->get()->toArray();
        foreach ($activity_form['0']['subchilds'] as $key => $subchild) {

            if ($subchild['slug'] == 'medication' || $subchild['slug'] == 'Medication') {
                foreach ($subchild['subchilds'] as $key1 => $subchild1) {
                    if ($subchild1['slug'] == 'medicines') {

                        $options = $subchild1['options'];
                        if ($shift_id == 1 || $shift_id == 3) {
                            $reversedOptions = array_reverse($options);
                            $options = $reversedOptions;
                        }
                        foreach ($medicineData as $key2 => $patient_medicine) {
                            $medicineData[$key2]['options'] = $options;
                        }
                    }
                }
            }
        }
        $activity_shift_id = $shiftNames[$shift_id] ?? 'unknown_shift';
        $activity_form = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => $activity_shift_id])->get()->toArray();
        foreach ($activity_form[0]['subchilds'] as $key => $subchild) {
            if (strcasecmp($subchild['slug'], 'medication') === 0) {
                unset($activity_form[0]['subchilds'][$key]);
            }
        }
        $super_admin_ids = $this->users->where('role_id', 1)->pluck('id')->toArray();

        $activity_form[0]['subchilds'] = array_values($activity_form[0]['subchilds']);
        $activities = $this->activities->whereRaw('(home_id = 0 OR home_id = ?)', [$home_id])
            ->whereRaw('(patient_id = 0 OR patient_id = ?)', [$patient_id])
            ->whereRaw('FIND_IN_SET(' . $shift_id . ',shift_id)')
            ->where(function ($query) use ($super_admin_ids) {
                $query->where('created_by', Auth::user()->id)
                    ->orWhereIn('created_by', $super_admin_ids);
            })
            ->where('status', 1)->get();
        $add_medicine = 1;
        return view('patients.daily-medicine-report-form', compact('patient_id', 'patient', 'activity_form', 'activities', 'medicineData', 'staffList', 'patientActivity', 'add_medicine'));
    }

    public function updatePatientMedicineReport(Request $request, $patient_id, $shift_id)
    {
        //$shift_id= 1;//$activity_slug;
        $shiftNames = [
            1 => 'morning',
            2 => 'afternoon',
            3 => 'evening',
            4 => 'night',
            5 => 'ad_hoc',
        ];

        $report_date = $request->query('report_date');
        $patientActivity = [];
        $data = $this->patient_logs->where(['patient_id' => $patient_id, 'log_type' => 1])->whereDate('report_date', $report_date)->first();

        if ($data) {
            $patientActivity = PatientActivity::where(['log_id' => $data->id, 'shift_id' => $shift_id])->get();
        }

        $patient = $this->patients->find($patient_id);
        $home_id = $patient->home_id;
        $staffList = $this->users->where(['home_id' => $home_id, 'status' => 1, 'role_id' => 4])->pluck('name', 'id')->toArray();

        $patient_medicines = $this->patient_medicines->where('patient_id', $request->patient_id);

        if ($shift_id == 5) {
            $patient_medicines = $this->getMedicineByType($request, 1, $shift_id, 2);

        } else {
            $patient_medicines = $this->getMedicineByType($request, 3, $shift_id, 2);

        }

        $medicineData = $patient_medicines;

        $activity_form = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => $shiftNames[$shift_id]])->get()->toArray();
        foreach ($activity_form['0']['subchilds'] as $key => $subchild) {

            if ($subchild['slug'] == 'medication' || $subchild['slug'] == 'Medication') {
                foreach ($subchild['subchilds'] as $key1 => $subchild1) {
                    if ($subchild1['slug'] == 'medicines') {

                        $options = $subchild1['options'];
                        if ($shift_id == 1 || $shift_id == 3) {
                            $reversedOptions = array_reverse($options);
                            $options = $reversedOptions;
                        }
                        foreach ($medicineData as $key2 => $patient_medicine) {
                            $medicineData[$key2]['options'] = $options;
                        }
                    }
                }
            }
        }
        $activity_shift_id = $shiftNames[$shift_id] ?? 'unknown_shift';
        $activity_form = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => $activity_shift_id])->get()->toArray();
        foreach ($activity_form[0]['subchilds'] as $key => $subchild) {
            if (strcasecmp($subchild['slug'], 'medication') === 0) {
                unset($activity_form[0]['subchilds'][$key]);
            }
        }
        $super_admin_ids = $this->users->where('role_id', 1)->pluck('id')->toArray();

        $activity_form[0]['subchilds'] = array_values($activity_form[0]['subchilds']);
        $activities = $this->activities->whereRaw('(home_id = 0 OR home_id = ?)', [$home_id])
            ->whereRaw('(patient_id = 0 OR patient_id = ?)', [$patient_id])
            ->whereRaw('FIND_IN_SET(' . $shift_id . ',shift_id)')
            ->where(function ($query) use ($super_admin_ids) {
                $query->where('created_by', Auth::user()->id)
                    ->orWhereIn('created_by', $super_admin_ids);
            })
            ->where('status', 1)->get();

        $add_medicine = 2;
        return view('patients.daily-medicine-report-form', compact('patient_id', 'patient', 'activity_form', 'activities', 'medicineData', 'staffList', 'patientActivity', 'add_medicine'));
    }
    public function getMedicineByType($request, $type, $shift_id, $add_update_report = "")
    {
        if (!empty($request->query('report_date'))) {
            $report_date = $request->query('report_date');
        } else {
            $report_date = date('Y-m-d');
        }
        
        $patient_medicines = $this->patient_medicines->where('patient_id', $request->patient_id)->whereDate("created_at", '<=', $report_date);
     
        if ($type == 1) {
            $patient_medicines->whereRaw('JSON_CONTAINS(time_id, \'["6"]\')');
        } else {

            $patient_medicines->where(function ($query) use ($shift_id) {
                $query->whereRaw('JSON_CONTAINS(time_id, \'["' . $shift_id . '"]\')')
                    ->orWhereRaw('JSON_CONTAINS(time_id, \'["5"]\')');
            });
        }

        if ($add_update_report == 1) {
            $patient_medicines->where(function ($query) {
                $query->where('is_discontinue', 0);
            });
        } else {
            $patient_medicines->where(function ($query) use ($report_date) {
                $query->where('is_discontinue', 0)
                    ->orWhere(function ($query) use ($report_date) {
                        $query->where('is_discontinue', 1)
                            ->whereDate('discontinue_date', '>', $report_date);
                    });
            });
        }

        $patient_medicines->select('*', DB::raw("'select' as type_name"));

        $result = $patient_medicines->get();
       
        // Apply conditions based on the med_frequency for each patient medicine record
        foreach ($result as $medicine) {

            $med_frequency = $medicine->med_frequency;

            if ($med_frequency == 2) { // Weekly
                $date = Carbon::parse($report_date);
                $currentDay = strtolower($date->format('l'));

                $expectedDays = json_decode($medicine->other_med_frequency, true);
                // Ensure case-insensitivity for day comparison
                $expectedDays = array_map('strtoupper', $expectedDays); // Convert expected days to uppercase
                if (is_array($expectedDays)) {
                    if (!in_array(strtoupper($currentDay), $expectedDays)) {
                        $result = $result->filter(function ($item) use ($medicine) {
                            return $item->id != $medicine->id;
                        });
                    }
                }
            } else if ($med_frequency == 3) { // Bi-Weekly
              
                $date = Carbon::parse($report_date);
                $currentDate = Carbon::parse($report_date)->toDateString();
                $expectedDays = json_decode($medicine->other_med_frequency, true);
              
                $dateArray = $this->generateBiWeeklyMedicationDates($medicine->bi_daily_start_date, $medicine->bi_daily_end_date,$expectedDays,$date);
                if (!in_array($currentDate, $dateArray)) {
                    $result = $result->filter(function ($item) use ($medicine) {
                        return $item->id != $medicine->id;
                    });
                }
            } else if ($med_frequency == 4) { // Monthly
                $date = Carbon::parse($report_date);
                $currentDateOfMonth = $date->day;

                $expectedDays = json_decode($medicine->other_med_frequency, true);
                if (is_array($expectedDays)) {
                    if (!in_array($currentDateOfMonth, $expectedDays)) {
                        $result = $result->filter(function ($item) use ($medicine) {
                            return $item->id != $medicine->id;
                        });
                    }
                }
            } else if ($med_frequency == 5) {
                $date = Carbon::parse($report_date);
                // $currentDay = Carbon::now()->startOfDay();
                $currentDate = $date->format('Y-m-d');
                $dateArray = $this->generateBiDailyMedicationDates($medicine->bi_daily_start_date, $medicine->bi_daily_end_date);
               
                if (!in_array($currentDate, $dateArray)) {
                    $result = $result->filter(function ($item) use ($medicine) {
                        return $item->id != $medicine->id;
                    });
                }

            }

           /* if ($shift_id != 5) {
                // Adding medicine_time based on shift_id
                // if($medicine->medicine_time != 0 && $medicine->medicine_time != null){
                $medicine_time = json_decode($medicine->medicine_time, true);
                if (array_key_exists($shift_id, $medicine_time)) {
                    // Assuming $medicine_time[$shift_id] contains multiple times separated by commas
                    $times = explode(',', $medicine_time[$shift_id]);

                    $medicine->medicine_times_shift = implode(',', $times);
                    // Format each time to h:i:s
                    $formatted_times = array_map(function ($time) {
                        $dateTime = \DateTime::createFromFormat('H:i', trim($time));
                        return $dateTime ? $dateTime->format('h:i A') : $time;
                    }, $times);

                    // Join the formatted times back into a single string
                    $medicine->medicine_time_shift = implode(',', $formatted_times);
                } else {
                    $medicine->medicine_times_shift = "";
                    $medicine->medicine_time_shift = "";
                }
                // }
            }*/
            
        }
        

      
    
        $finalResult = collect();

        foreach ($result as $medicine) {
		
            $medicine_time =$medicine->medicine_time;
            if($medicine_time!=0 && $medicine_time!=null && $shift_id!=5)
        {
            $time_ids = json_decode($medicine->time_id,true);
            if(in_array('5',$time_ids))
            {
                $medicine_time =json_decode($medicine->medicine_time,true);
                if(in_array($shift_id,$medicine_time))
                {
                $medicine->med_time=null;
                $medicine->med_key=1;
                $medicine->new_id=$medicine->id.'_1';
                $finalResult->push($medicine);
                }
            }
            else if(in_array($shift_id,$time_ids))
            {
            $medicine_time =json_decode($medicine->medicine_time,true);
            $times = strpos($medicine_time[$shift_id], ',') !== false ? explode(',', $medicine_time[$shift_id]) : [$medicine_time[$shift_id]];
            
            $med_key = 1;
            foreach ($times as $time) {
                $medicine_clone = clone $medicine;
                $medicine_clone->med_key = $med_key;
                $medicine_clone->new_id = $medicine->id.'_'.$med_key;
                $medicine_clone->med_time = date('h:i A',strtotime($time));
                $finalResult->push($medicine_clone);
                $med_key++;
            }
            }
        }else{
                $medicine->med_time=null;
                $medicine->med_key=1;
                $medicine->new_id=$medicine->id.'_1';
                $finalResult->push($medicine);
        }
    }
    return $finalResult->values();

    }

    public function generateBiDailyMedicationDates($start_date, $end_date)
    {

        $medication_dates = [];
        $start_date = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
        if ($end_date) {
            $end_date = Carbon::createFromFormat('Y-m-d', $end_date)->startOfDay();
        } else {
            $currentDay = Carbon::now()->startOfDay();
            $end_date = $currentDay->copy()->addDays(4);
        }
        $current_date = $start_date;
        while ($current_date <= $end_date) {

            $medication_dates[] = $current_date->format('Y-m-d');
            $current_date->addDays(2);
        }
        return $medication_dates;
    }
    public function generateBiWeeklyMedicationDates($start_date, $end_date, $expectedDays, $date)
    {
        $medication_dates = [];
        $start_date = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
    
        if ($end_date) {
            $end_date = Carbon::createFromFormat('Y-m-d', $end_date)->startOfDay();
        } else {
            $end_date = Carbon::now()->startOfDay()->addDays(60); // Assuming 60 days for bi-weekly cycles
        }
    
        $current_date = $start_date;
    
        while ($current_date <= $end_date) {
            $currentDayOfWeek = strtolower($current_date->format('l'));
    
            if (in_array(ucfirst($currentDayOfWeek), $expectedDays)) {
                $medication_dates[] = $current_date->format('Y-m-d');
                $current_date->addWeeks(2); // Move to the same day, two weeks later
            } else {
                $current_date->addDay(); // Move to the next day
            }
        }
    
        return $medication_dates;
    }
    

    public function savePatientsSchedule(Request $request)
    {
        request()->validate([
            'doctor_name' => 'required',
        ]);

        $this->patient_appointments->addaAppointment($request);
        return redirect()->route('patients.show', $request->patient_id)->with(['type' => 'success', 'message' => 'Patient appointment added successfully'])->withFragment('tab7');
    }

    public function viewPatientsSchedule($patient_id, $id = '')
    {
        $patient_appointment = $this->patient_appointments->find($id);
        $medicine_frequency = config('const.schdule_frequency');

        return view('patients.partials.view-patient-schedule', compact('patient_appointment', 'medicine_frequency'));
    }

    public function deletePatientsDocument($id)
    {
        $patient = $this->patient_documents->find($id);
        $allowed = checkPatientBelongToAdminHome($patient->patient_id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action'])->withFragment('tab2');
        }
        $this->patient_documents->find($id)->delete();
        return redirect()->back()->with(['type' => 'success', 'message' => 'Patient document deleted successfully'])->withFragment('tab2');
    }

    public function deletePatientsSchedule($id)
    {
        $this->patient_appointments->find($id)->delete();
        return redirect()->back()->with(['type' => 'success', 'message' => 'Patient Appointment deleted successfully'])->withFragment('tab7');
    }

    public function deletePatient($id)
    {
        $patient = $this->patients->find($id);
        $delete = $this->patients->find($id)->delete();
        if ($delete) {
            // call the event
            event(new CreateNotification('patient_deleted', $patient));
        }
        return redirect()->back()->with(['type' => 'success', 'message' => 'Patient deleted successfully']);
    }

    public function deletePatientRequest($id)
    {
        $patient = $this->patients->find($id);
        $patient->delete_request = 0;
        $patient->save();
        return redirect()->back()->with(['type' => 'success', 'message' => 'Patient request deleted successfully']);
    }

    public function updatePatientDischargeStatus(Request $request)
    {
        $result = $this->patients->updateDischargeStatus($request);
        if ($result) {
            return response()->json(['type' => 'success', 'message' => 'Patient discharge status updated successfully'], 200);
        } else {
            return response()->json(['type' => 'error', 'message' => 'Some error occured please try again'], 200);
        }
    }

    public function viewPatientAssignedActivity($patient_id, $id = '')
    {
        $patient_assigned_activity = $this->activities->find($id);
        $activity_recurrence = config('const.activity_recurrence');
        return view('patients.partials.view-patient-assigned-activities', compact('patient_id', 'id', 'patient_assigned_activity', 'activity_recurrence'));
    }

    public function addUpdatePatientAssignedActivity($patient_id, $id = '')
    {
        $patient = $this->patients->find($patient_id);
        $allowed = checkPatientBelongToAdminHome($patient_id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action'])->withFragment('tab5');
        }
        // $assigned_activity = $this->patient_activities->find($id);
        $assigned_activity = $this->activities->find($id);
   
        $activity_recurrence = config('const.activity_recurrence');
        $home_id = $patient->home_id;
        $activities = $this->activities->where('created_by', Auth::user()->id)->get();
        $shifts = getActivityShifts();
        return view('patients.add-update-patient-assign-activity', compact('home_id', 'patient_id', 'id', 'activity_recurrence', 'assigned_activity', 'activities', 'shifts'));
    }

    public function savePatientAssignedActivity(Request $request)
    {
        // $request['patient_activity_id'] = $request->id;
        // $this->patient_activities->assignActivity($request);
        $this->activities->addActivity($request);
        $message = $request->id ? 'Assign Activity to patient updated successfully' : 'Assign Activity to patient added successfully';
        return redirect()->route('patients.show', $request->patient_id)->with(['type' => 'success', 'message' => $message])->withFragment('tab5');
    }

    public function deletePatientsAssignedActivity($id)
    {
        // $patient_activity = $this->patient_activities->select('id','patient_id')->find($id);
        $patient_activity = $this->activities->select('id', 'patient_id')->find($id);
        $allowed = checkPatientBelongToAdminHome($patient_activity->patient_id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action'])->withFragment('tab5');
        }
        $this->activities->find($id)->delete();
        return redirect()->back()->with(['type' => 'success', 'message' => 'Patient assigned activity deleted successfully'])->withFragment('tab5');
    }

    public function viewPatientAssignedTask($id)
    {
        $patient_assigned_task = $this->tasks->find($id);
        $task_type = config('const.task_type');
        $task_status = config('const.task_status');
        return view('patients.partials.view-patient-assigned-task', compact('patient_assigned_task', 'task_type', 'task_status'));
    }

    public function addUpdatePatientsDoctor($patient_id, $id)
    {
        $patient = $this->patients->find($patient_id);
        $allowed = checkPatientBelongToAdminHome($patient_id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action']);
        }
        $doctor = $this->patient_doctors->find($id);

        $home_id = $patient->home_id;
        return view('patients.add-update-patient-doctor', compact('home_id', 'doctor', 'patient_id', 'id'));
    }

    public function savePatientsDoctor(Request $request)
    {
        $this->patient_doctors->addDoctor($request);
        return redirect()->route('patients.show', $request->patient_id)->with(['type' => 'success', 'message' => "Patient`s doctor added successfully"])->withFragment('tab3');
    }

    public function addNewDoctor(Request $request)
    {

        $doctor = $this->patient_doctors->addDoctor($request);
        if (!empty($doctor)) {
            return response()->json(['status' => 'success', 'message' => 'Doctor added successfully', "doctor" => $doctor]);
        }
        return response()->json(['status' => 'failed', 'message' => 'something went wrong...']);
    }

    public function getCareHomeStaffs(Request $request)
    {
        $home = $this->homes->where('id', $request->home_id)->first();
        $careHomeSubscription =   $this->homes->getCareHomeActiveSubscription($request->home_id);
        if($careHomeSubscription->stripe_status != 'active'){
            return response()->json(['status' => 'success', 'name' => $home->name, 'subscription_status' => true ]);
        }

        $checkPatientCapacity = checkPatientAllowedCapacity($request->home_id);
        if (!$checkPatientCapacity) {
            return response()->json(['status' => 'failed', 'message' => 'Exceed the limit']);
        }

        $staffs = getCareHomeStaff($request->home_id);
        return response()->json($staffs);
    }
    public function viewPatientsMedicineByMonth($patient_id, $id)
    {
        $patient = $this->patients->withTrashed()->find($patient_id);
        $medicine = $this->patient_medicines->withTrashed()->find($id);
        $home_id = $patient->home_id;
        $activity_time = $this->activity_time->where('home_id', $home_id)->first();
        return view('patients.view-patient-medicine', compact('home_id', 'patient_id', 'id', 'patient', 'medicine', 'activity_time'));
    }
    public function downloadPatientMedicineReport(Request $request, $patient_id)
    {
        ini_set('memory_limit', '512M');  // Increase memory
        ini_set('max_execution_time', 300);  // Increase execution time
        $isBlankReport = $request->blank_report;
        $monthName = date("F", strtotime("$request->year-$request->month-01"));
        $month = $request->input('month');
        $year = $request->input('year');
        $lastDayOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        $lastdate = $lastDayOfMonth->format('d');
        $totalDays = Carbon::createFromDate($year, $month, 1)->diffInDays($lastDayOfMonth) + 1; // Add 1 to include the start day
        $year = $request->year;
        $patient = $this->patients->withTrashed()->find($patient_id);
        $activity_time = $this->activity_time->where('home_id', $patient->home_id)->first();

        $morning = '';
        $afternoon = '';
        $night = '';
        if (!empty($activity_time)) {
            $morning = explode('-', $activity_time->morning_time);
            $afternoon = explode('-', $activity_time->afternoon_time);
            $night = explode('-', $activity_time->night_time);
        }

        $medicineArray = $this->patient_medicines->where('patient_id', $patient_id)->whereRaw('NOT JSON_CONTAINS(time_id, \'["6"]\')')->pluck('id')->toArray();
        $adhocmedicineArray = $this->patient_medicines->where('patient_id', $patient_id)->whereRaw('JSON_CONTAINS(time_id, \'["6"]\')')->pluck('id')->toArray();
        $all_med = $this->patient_medicines->where('patient_id', $patient_id)->pluck('id')->toArray();
        $logs = getMedLogByType($patient_id, $year, $request->month, $medicineArray);

        $adhoclogs = getMedLogByType($patient_id, $year, $request->month, $adhocmedicineArray);
        $allMedLog = getNoteMedLog($patient_id, $year, $request->month, $all_med);

        $all_medicine = $this->patient_medicines->where('patient_id', $patient_id)->whereRaw('NOT JSON_CONTAINS(time_id, \'["6"]\')')->get();
        $adhocAllMedicine = $this->patient_medicines->where('patient_id', $patient_id)->whereRaw('JSON_CONTAINS(time_id, \'["6"]\')')->get();
        $all_medicine_note = $this->patient_medicines->where('patient_id', $patient_id)->get();

        $result = [];
        $adHocresult = [];
        $html = ''; // Initialize the HTML string

        foreach ($logs as $log) {
            if ($log['med_key'] == null) {
                $med_key = 1;
            } else {
                $med_key = $log['med_key'];
            }
            $result['data'][$log['med_id']][$log['shift_id'] . '_' . $med_key][date('j', strtotime($log['report_date']))] = $log;

            // Store medicine name in result array
            $result['med'][$log['med_id']]['id'] = $log['log_iddd'];
            $result['med'][$log['med_id']]['name'] = $log['med_name'];
            $result['med'][$log['med_id']]['med_id'] = $log['med_id'];
            $result['med'][$log['med_id']]['dose'] = $log['dose'];
            $result['med'][$log['med_id']]['instructions'] = $log['instructions'];
            $result['med'][$log['med_id']]['other_dose'] = $log['other_dose'];
            $result['med'][$log['med_id']]['intake_method'] = $log['intake_method'];
            $result['med'][$log['med_id']]['reason'] = $log['med_reason'];
            $result['med'][$log['med_id']]['medicine_type_other'] = $log['medicine_type_other'];
            $result['med'][$log['med_id']]['time_id'] = $log['time_id'];
            $result['med'][$log['med_id']]['medicine_time'] = $log['medicine_time'];
            $result['med'][$log['med_id']]['report_time'] = $log['report_time'];
            $result['med'][$log['med_id']]['activity_time'] = $log['activity_time'];
            $result['med'][$log['med_id']]['is_discontinue'] = $log['is_discontinue'];
            $result['med'][$log['med_id']]['discontinue_date'] = $log['discontinue_date'];
            $result['med'][$log['med_id']]['med_time'] = $log['med_time'];
            $result['med'][$log['med_id']]['med_key'] = $log['med_key'];
        }
      //  dd($result);

        foreach ($adhoclogs as $log) {
            $adHocresult['data'][$log['med_id']][$log['id']][date('j', strtotime($log['report_date']))] = $log;

            // Store medicine name in adHocresult array
            $adHocresult['med'][$log['med_id']]['name'] = $log['med_name'];
            $adHocresult['med'][$log['med_id']]['instructions'] = $log['instructions'];
            $adHocresult['med'][$log['med_id']]['dose'] = $log['dose'];
            $adHocresult['med'][$log['med_id']]['instructions'] = $log['instructions'];
            $adHocresult['med'][$log['med_id']]['intake_method'] = $log['intake_method'];
            $adHocresult['med'][$log['med_id']]['reason'] = $log['med_reason'];
            $adHocresult['med'][$log['med_id']]['medicine_type_other'] = $log['medicine_type_other'];
            $adHocresult['med'][$log['med_id']]['time_id'] = $log['time_id'];
            $adHocresult['med'][$log['med_id']]['report_time'] = $log['report_time'];
            $adHocresult['med'][$log['med_id']]['medicine_time'] = $log['medicine_time'];
            $adHocresult['med'][$log['med_id']]['is_discontinue'] = $log['is_discontinue'];
            $adHocresult['med'][$log['med_id']]['discontinue_date'] = $log['discontinue_date'];
            $adHocresult['med'][$log['med_id']]['med_time'] = $log['med_time'];
            $adHocresult['med'][$log['med_id']]['med_key'] = $log['med_key'];
        }

        $allowed = checkPatientBelongToAdminHome($patient_id);
        if (!$allowed) {
            return redirect()->back()->with(['type' => 'error', 'message' => 'You are not authorized for this action']);
        }
        $medicine_time_other = config('const.medicine_time_other');
        // return view('patients.patient-medication-report-pdf', compact('patient','result','adHocresult','allMedLog','monthName','year','totalDays','lastdate','month','morning','afternoon','night','medicine_time_other', 'isBlankReport', 'all_medicine', 'adhocAllMedicine', 'all_medicine_note'));die;
        $mainPdf = Pdf::loadView('patients.patient-medication-report-pdf', compact('patient', 'adHocresult', 'result', 'allMedLog', 'monthName', 'year', 'totalDays', 'lastdate', 'month', 'morning', 'afternoon', 'night', 'medicine_time_other', 'isBlankReport', 'all_medicine', 'adhocAllMedicine'));
        $adhocPdf = Pdf::loadView('patients.patient-adhoc-report-pdf', compact('patient', 'adHocresult', 'result', 'allMedLog', 'monthName', 'year', 'totalDays', 'lastdate', 'month', 'morning', 'afternoon', 'night', 'medicine_time_other', 'isBlankReport', 'all_medicine', 'adhocAllMedicine'));
        $notePdf = Pdf::loadView('patients.patient-med-note-report-pdf', compact('patient', 'adHocresult', 'result', 'allMedLog', 'monthName', 'year', 'totalDays', 'lastdate', 'month', 'morning', 'afternoon', 'night', 'medicine_time_other', 'isBlankReport', 'all_medicine', 'adhocAllMedicine', 'all_medicine_note'));
        $mainPdf->render();
        $canvas = $mainPdf->getDomPDF()->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            // Text to display
            $text = "Page $pageNumber of $pageCount";
            // Margins from the bottom right
            $marginRight = 15;
            $marginBottom = 20;
           // $marginTop = 10; // Add a top margin
           //$paddingBottom = 5; // Add a bottom padding value
            // Font and font size
            $fontSize = 7;
            $font = $fontMetrics->getFont('Montserrat', 'sans-serif');
            // Set position
            $textWidth = $fontMetrics->getTextWidth($text, $font, $fontSize);
            $xPosition = $canvas->get_width() - $textWidth - $marginRight;
            $yPosition = $canvas->get_height() - $marginBottom;
            // Draw text
            $canvas->text($xPosition, $yPosition, $text, $font, $fontSize);
        });

        $adhocPdf->render();
        $adhoc_canvas = $adhocPdf->getDomPDF()->getCanvas();
        $adhoc_canvas->page_script(function ($pageNumber, $pageCount, $adhoc_canvas, $fontMetrics) {
            // Text to display
            $text = "Page $pageNumber of $pageCount";
            // Margins from the bottom right
            $marginRight = 15;
            $marginBottom = 15;
            // Font and font size
            $fontSize = 8;
            $font = $fontMetrics->getFont('Montserrat', 'sans-serif');
            // Set position
            $textWidth = $fontMetrics->getTextWidth($text, $font, $fontSize);
            $xPosition = $adhoc_canvas->get_width() - $textWidth - $marginRight;
            $yPosition = $adhoc_canvas->get_height() - $marginBottom;
            // Draw text
            $adhoc_canvas->text($xPosition, $yPosition, $text, $font, $fontSize);
        });

        $notePdf->render();
        $note_canvas = $notePdf->getDomPDF()->getCanvas();
        $note_canvas->page_script(function ($pageNumber, $pageCount, $note_canvas, $fontMetrics) {
            // Text to display
            $text = "Page $pageNumber of $pageCount";
            // Margins from the bottom right
            $marginRight = 15;
            $marginBottom = 15;
            // Font and font size
            $fontSize = 8;
            $font = $fontMetrics->getFont('Montserrat', 'sans-serif');
            // Set position
            $textWidth = $fontMetrics->getTextWidth($text, $font, $fontSize);
            $xPosition = $note_canvas->get_width() - $textWidth - $marginRight;
            $yPosition = $note_canvas->get_height() - $marginBottom;
            // Draw text
            $note_canvas->text($xPosition, $yPosition, $text, $font, $fontSize);
        });

        $name_slug = Str::slug($patient->name, '-');
        //$file_name = $name_slug.'.pdf';
        //return $mainPdf->download($file_name);
        //return $adhocPdf->download($file_name);
        //return $notePdf->download($file_name);
        $mainPdfPath = storage_path("app/public/{$name_slug}-medicine.pdf");
        $adhocPdfPath = storage_path("app/public/{$name_slug}-adhoc.pdf");
        $notePdfPath = storage_path("app/public/{$name_slug}-note.pdf");

        Storage::put("public/{$name_slug}-medicine.pdf", $mainPdf->output());
        Storage::put("public/{$name_slug}-adhoc.pdf", $adhocPdf->output());
        Storage::put("public/{$name_slug}-note.pdf", $notePdf->output());

        $zip = new ZipArchive();
        $zipPath = storage_path("app/public/{$name_slug}.zip");
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFile($mainPdfPath, "{$name_slug}-medicine.pdf");
            $zip->addFile($adhocPdfPath, "{$name_slug}-adhoc.pdf");
            $zip->addFile($notePdfPath, "{$name_slug}-note.pdf");
            $zip->close();
        }

        // Step 4: Clean up temporary files
        Storage::delete([
            "public/{$name_slug}-medicine.pdf",
            "public/{$name_slug}-adhoc.pdf",
            "public/{$name_slug}-note.pdf",
        ]);

        // Step 5: Return ZIP file for download
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function getDailyActivityFormBySlug($patient_id, $shift_id)
    {
        $patient = $this->patients->find($patient_id);
        $home_id = $patient->home_id;
        $times = ActivityTime::where('home_id', $home_id)->first();
        $log_times = config('const.log_times');
        if ($times) {
            $log_times = [1 => $times->morning_time, 2 => $times->afternoon_time, 3 => $times->evening_time, 4 => $times->night_time];
        } else {
            $log_times = $log_times;
        }
        $shift_time = array_key_exists($shift_id, $log_times);
        $timeActivityArray = [];
        if ($shift_id == 1) {
            $timeActivityArray = explode('-', $log_times[1]);
        } else if ($shift_id == 2) {
            $timeActivityArray = explode('-', $log_times[2]);
        } else if ($shift_id == 3) {
            $timeActivityArray = explode('-', $log_times[3]);
        } else if ($shift_id == 4) {
            $timeActivityArray = explode('-', $log_times[4]);
        }
        // $shift_id=1; //$activity_slug;
        $shiftNames = [
            1 => 'morning',
            2 => 'afternoon',
            3 => 'evening',
            4 => 'night',
            5 => 'ad_hoc',
        ];

        $activity_shift_id = $shiftNames[$shift_id] ?? 'unknown_shift';
        $activity_form = $this->patient_activity_fields->with('subchilds')->where(['parent_id' => 0, 'slug' => $activity_shift_id])->get()->toArray();
        foreach ($activity_form[0]['subchilds'] as $key => $subchild) {
            if (strcasecmp($subchild['slug'], 'medication') === 0) {
                unset($activity_form[0]['subchilds'][$key]);
            }
        }
        $super_admin_ids = $this->users->where('role_id', 1)->pluck('id')->toArray();

        $activity_form[0]['subchilds'] = array_values($activity_form[0]['subchilds']);
        $activities = $this->activities->whereRaw('(home_id = 0 OR home_id = ?)', [$home_id])
            ->whereRaw('(patient_id = 0 OR patient_id = ?)', [$patient_id])
            ->whereRaw('FIND_IN_SET(' . $shift_id . ',shift_id)')
            ->where(function ($query) use ($super_admin_ids) {
                $query->where('created_by', Auth::user()->id)
                    ->orWhereIn('created_by', $super_admin_ids);
            })
            ->where('status', 1)->get();
        if (!$activities->isEmpty()) {
            foreach ($activities as $act) {
                if ($act->recurrence == 2) {
                    $currentDay = strtolower(date('l'));
                    $expectedDays = json_decode($act->activity_performance_day, true);
                    $expectedDays = array_map('strtoupper', $expectedDays);
                    if (!in_array(strtoupper($currentDay), $expectedDays)) {
                        $activities = $activities->filter(function ($item) use ($act) {
                            return $item->id != $act->id;
                        });
                    }
                } else if ($act->recurrence == 3) { // Monthly
                    $currentDateOfMonth = date('j');
                    $expectedDays = json_decode($act->activity_performance_day, true);
                    if (is_array($expectedDays)) {
                        if (!in_array($currentDateOfMonth, $expectedDays)) {
                            $activities = $activities->filter(function ($item) use ($act) {
                                return $item->id != $act->id;
                            });
                        }
                    }
                }
            }
            $activities = $activities->values();
        }

        return view('patients.daily-activity-form', compact('patient_id', 'patient', 'activity_form', 'activities', 'timeActivityArray'));
    }

    // public function addDailyActivityForm(Request $request,$patient_id,$id)
    // {
    //   dd($request->all());
    // }

    public function addDailyActivityForm(Request $request, $patientId)
    {
        request()->validate([
            'report_date' => 'required',
        ]);
        $data = $this->patient_logs->where(['patient_id' => $patientId, 'log_type' => 0])->whereDate('report_date', date('Y-m-d', strtotime($request->report_date)))->first(); //array_key_exists('1',$medicine_time)
        if ($data) {
            $patientActivity = PatientActivity::where(['log_id' => $data->id, 'shift_id' => $request->shift_id])->first();
            $reportItem = json_decode($data->report_time, true);
            if (array_key_exists($request->shift_id, $reportItem) && $request->shift_id != 5 && $patientActivity) {

                return redirect()->back()->with(['type' => 'error', 'message' => 'Activity Already Exist.']);
            }
        }
        //return response()->json(['type' => 'false'], 200);

        $request->merge([
            'is_in_house' => 1,
            'status' => 1,
            'is_call_ad_hoc' => $request->shift_id == 5 ? true : false,
            'id' => (!empty($data)) ? $data->id : 0,
        ]);

        $patientLog = $this->patient_logs->addUpdateLogFromAdmin($request, $patientId);
        if ($patientLog) {
            $result = $this->patient_activities->addUpdateLogActivityWeb($request, $patientLog);
            return redirect()
                ->route('patients.show', $patientId)
                ->with(['type' => 'success', 'message' => 'Patient activity added successfully'])
                ->withFragment('tab4');
        }
    }

    public function addDailyMedicineForm(Request $request, $patientId)
    {

        request()->validate([
            'report_date' => 'required',
        ]);
        $data = $this->patient_logs->where(['patient_id' => $patientId, 'log_type' => 1])->whereDate('report_date', date('Y-m-d', strtotime($request->report_date)))->first(); //array_key_exists('1',$medicine_time)
        /*  if($data){
        $patientActivity = PatientActivity::where(['log_id' => $data->id, 'shift_id' => $request->shift_id])->first();
        $reportItem = json_decode($data->report_time,true);
        if(array_key_exists($request->shift_id,$reportItem) && $patientActivity){
        return redirect()->back()->with(['type' => 'error', 'message' => 'Medicine Report Already Exist.']);
        //return response()->json(['type' => 'error','message' => 'Medicine Report Already Exsist.'], 200);
        }
        } */
        //return response()->json(['type' => 'false'], 200);

        $request->merge([
            'patient_id' => $patientId,
        ]);

        $patientLog = $this->patient_logs->addUpdateMedLogAdmin($request);
        if ($patientLog) {
            $result = $this->patient_activities->addUpdateMedLogActivityAdmin($request, $patientLog);
            return redirect()
                ->route('patients.show', $patientId)
                ->with(['type' => 'success', 'message' => 'Patient medicine activity added successfully'])
                ->withFragment('tab10');
        }
    }

    public function saveStaffNote(Request $request, $patient_id)
    {
        request()->validate([
            'note' => 'required',
        ]);
        $this->patient_activities->where('id', $request->activity_id)->update(['staff_note' => $request->note, 'note_time' => date('H:i:s'), 'noted_by' => Auth::user()->id]);
        return redirect()
            ->route('patients.show', $patient_id)
            ->with(['type' => 'success', 'message' => 'Staff note added successfully'])
            ->withFragment('tab10');
    }

}
