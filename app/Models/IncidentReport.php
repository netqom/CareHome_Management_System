<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Patient;
use App\Models\CareHome;
use App\Events\CreateNotification;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidentReport extends Model
{
    use HasFactory, SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'patient_id',
        'incident_reason',
        'report_to_nine',
		'report_to_rcs',
        'report_to_manager',
        'report_to_other',
        'incident_report_by',
        'home_id',
        'title',
        'other',
        'action_taken',
    ];

    public function added_by()
	{
		return $this->belongsTo(User::class, 'incident_report_by', 'id')->withTrashed();
	}
    public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id', 'id')->withTrashed();
	}


    public function getIncidentListByPatientID($request)
	{
		$sort_field   = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order   = isset($request->sort_order) ? $request->sort_order : 'desc';
		$start_date   = isset($request->startDate) ? date('Y-m-d', strtotime($request->startDate)) : '';
        $end_date     = isset($request->endDate) ? date('Y-m-d', strtotime($request->endDate)) : ''; 
        return $this 
                ->leftJoin('users', 'incident_reports.incident_report_by', '=', 'users.id')
                ->leftJoin('patients', 'incident_reports.patient_id', '=', 'patients.id') 
                ->leftJoin('care_homes', 'incident_reports.home_id', '=', 'care_homes.id')
                ->when($start_date != '' && $end_date != '', function($q) use($start_date, $end_date){
            return $q->whereBetween('incident_reports.created_at', [$start_date." 00:00:00",$end_date." 23:59:59"]);
       }) ->select('incident_reports.*', 'users.name as incident_created_by','patients.name as patient_name','care_homes.name as care_home_name')->where(['incident_reports.patient_id' => $request->patient_id])->orderBy('id','desc');
	}
    public function downloadIncidentListByPatientID($id)
	{
        return $this 
                ->leftJoin('users', 'incident_reports.incident_report_by', '=', 'users.id')
                ->leftJoin('patients', 'incident_reports.patient_id', '=', 'patients.id') 
                ->leftJoin('care_homes', 'incident_reports.home_id', '=', 'care_homes.id')
                 ->select('incident_reports.*', 'users.name as incident_created_by','patients.name as patient_name','care_homes.name as care_home_name')->where(['incident_reports.patient_id' => $id])->orderBy('id','desc');
	}
    public function getIncidentList($request)
	{
        $role_id = Auth::user()->role_id;
        $ids_array  = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();
        $super_admin_ids = User::where('role_id',1)->pluck('id')->toArray();
		$sort_field   = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order   = isset($request->sort_order) ? $request->sort_order : 'desc';
		$start_date   = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
        $end_date     = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : ''; 
        return $this->leftJoin('users', 'incident_reports.incident_report_by', '=', 'users.id')
                    ->leftJoin('patients', 'incident_reports.patient_id', '=', 'patients.id') 
                    ->leftJoin('care_homes', 'incident_reports.home_id', '=', 'care_homes.id')
                    ->where(function ($query) use ($ids_array, $role_id) {
                        if ($role_id != 1) {
                            $query->whereIn('users.home_id', $ids_array);
                        }
                    })
                    ->when($start_date != '' && $end_date != '', function($q) use($start_date, $end_date){
            return $q->whereBetween('incident_reports.created_at', [$start_date." 00:00:00",$end_date." 23:59:59"]);
       }) ->select('incident_reports.*', 'users.name as incident_created_by','patients.name as patient_name','care_homes.name as care_home_name')->orderBy('id','desc');
	}
    public function addUpdateData($request)
    {
        $patient_expense = [
            'patient_id'         => isset($request->patient_id) && $request->patient_id != '' ? $request->patient_id : 0,
            'home_id'            => isset($request->home_id) && $request->home_id != '' ? $request->home_id : 0,
            'title'              => $request->title ? $request->title : null,
            'incident_reason'    => $request->incident_reason ? $request->incident_reason : null,
            'other'              => $request->other ? $request->other : null,
            'report_to_nine'     => $request->report_to_nine,
            'report_to_rcs'      => $request->report_to_rcs,
            'report_to_other'    => $request->report_to_other,
            'report_to_manager'  => $request->report_to_manager,
            'action_taken'  => isset($request->action_taken) ? $request->action_taken : null,
            'incident_report_by' => Auth::user()->id,
        ];

        if ($request->id != 0) {
            $expense = $this->findOrFail($request->id);
            $expenseData = $expense->update($patient_expense);
            event(new CreateNotification('patient_incident_update', $expense));
        } else {
            $expense = $this->create($patient_expense);
			$expense->save();
			// call the event
			event(new CreateNotification('patient_incident_added', $expense));
        }
		return true;

    }
    public function deletIncident($request)
	{
		$expense = $this->findOrFail($request->id);
		$expense->delete();
		return true;
	}
}
