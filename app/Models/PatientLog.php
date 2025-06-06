<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PatientLogImage;
use App\Models\Patient;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;
use Auth, File;
use App\Events\CreateNotification;
use Illuminate\Database\Eloquent\SoftDeletes;


class PatientLog extends Model
{
     use HasFactory, SoftDeletes;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'patient_id',
		'log_type',
		'is_in_house',
		'comment',
		'expected_return_date',
		'expected_return_time',
		'charge_amount',
        'report_date',
        'report_time',
        'report',
		'status',
		'created_by',
        'updated_by'
    ];
	
	protected $appends = ['images'];
	
	public function getImagesAttribute(){
        return PatientLogImage::where('log_id', $this->id)->where('type', '!=', 2)->get();
    }
	
	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id', 'id')->withTrashed();
	}
	
	public function added_by()
	{
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function addUpdateLogFromAdmin($request, $patientId)
	{
		
		\Log::info(['med_log_data' => $request->all()]);
		$log_data = [
			'patient_id'  => $patientId,
			'log_type'=>0,
			'is_in_house' => 1,
			'comment'     => null,
			'expected_return_date' => null,
			'expected_return_time' => null,
			'report_date' => !empty($request->report_date) ? date('Y-m-d',strtotime($request->report_date)): date('Y-m-d'),
			'status'      => 1,
			'updated_by'  => Auth::user()->id,
		];
		//$request->shift_id= $request->shift_id;
		if($request->id == 0){
			$log_data['created_by']  = Auth::user()->id;
			$log_data['report_time'] = json_encode([$request->shift_id => date('h:i:s a')]);
			//$log_data['report']      = json_encode($request->form_data);
			$patient_log = $this->create($log_data);
			//save the image if any for shift
			$this->saveLogImages($request, $patient_log);
			$this->saveLogPdf($request, $patient_log);
			//save the charge added on patient for the day
			$this->saveChargeAmount($request, $patient_log);
			// call the event
			$shiftname = getShiftName($request->shift_id);
			event(new CreateNotification('log_created-'.$shiftname, $patient_log));
			return $patient_log;
		}else{
			$patient_log = $this->findOrFail($request->id);
			//check time object exist then update it else add new array
			$exist_time = !is_null($patient_log->report_time) ? json_decode($patient_log->report_time, true) : [];
			$exist_time[$request->shift_id] = date('h:i:s a');			
			$log_data['report_time'] = json_encode($exist_time);
			//$log_data['report']     = json_encode($request->form_data);
			$patient_log->update($log_data);
			\Log::info(['images' => $request->file('images')]);
			//save the image if any for shift
			$this->saveLogImages($request, $patient_log);
			$this->saveLogPdf($request, $patient_log);
			//save the charge added on patient for the day
			$this->saveChargeAmount($request, $patient_log);
			// call the event
			$shiftname = getShiftName($request->shift_id);
			event(new CreateNotification('log_created-'.$shiftname, $patient_log));
			return $patient_log;
		}
	}
	
	public function addUpdateLog($request)
	{
		\Log::info(['med_log_data' => $request->all()]);
		$log_data = [
			'patient_id'  => $request->patient_id,
			'log_type'=>0,
			'is_in_house' => $request->is_in_house,
			'comment'     => $request->comment=='null'? null : $request->comment ,
			'expected_return_date' => $request->expected_return_date=='null' ? null : $request->expected_return_date,
			'expected_return_time' => $request->expected_return_time=='null' ? null : $request->expected_return_time,
			'report_date' => isset($request->report_date) ? date('Y-m-d', strtotime($request->report_date)) : date('Y-m-d'),
			'status'      => $request->status,
			'updated_by'  => Auth::user()->id,
		];
		$request->shift_id=($request->is_call_ad_hoc=='true')? 5: $request->shift_id;
		/*if($request->shift_id == 5){
			$request->id = 0;
		}*/
		if($request->id == 0){
			$log_data['created_by']  = Auth::user()->id;
			$log_data['report_time'] = $request->is_in_house == 1 ? json_encode([$request->shift_id => date('h:i:s a')]) : json_encode([$request->shift_id => date('h:i:s a').'__outhome']);
			//$log_data['report']      = json_encode($request->form_data);
			$patient_log = $this->create($log_data);
			//save the image if any for shift
			$this->saveLogImages($request, $patient_log);
			//save the charge added on patient for the day
			$this->saveChargeAmount($request, $patient_log);
			// call the event
			$shiftname = getShiftName($request->shift_id);
			event(new CreateNotification('log_created-'.$shiftname, $patient_log));
			return $patient_log;
		}else{
			$patient_log = $this->findOrFail($request->id);
			//check time object exist then update it else add new array
			$exist_time = !is_null($patient_log->report_time) ? json_decode($patient_log->report_time, true) : [];
			$exist_time[$request->shift_id] = $request->is_in_house == 1 ? date('h:i:s a') : date('h:i:s a').'__outhome';			
			$log_data['report_time'] = json_encode($exist_time);
			//$log_data['report']     = json_encode($request->form_data);
			$patient_log->update($log_data);
			\Log::info(['images' => $request->file('images')]);
			//save the image if any for shift
			$this->saveLogImages($request, $patient_log);
			//save the charge added on patient for the day
			$this->saveChargeAmount($request, $patient_log);
			// call the event
			$shiftname = getShiftName($request->shift_id);
			event(new CreateNotification('log_created-'.$shiftname, $patient_log));
			return $patient_log;
		}
	}
	public function addUpdateMedLog($request)
	{
		\Log::info(['MedLogdataadhoc' => $request->all()]);
	
		$log_data = [
			'patient_id'  => $request->patient_id,
			'log_type'=>1,
			'report_date' => isset($request->report_date) ? date('Y-m-d', strtotime($request->report_date)) : date('Y-m-d'),
			'status'      => 1,
			'updated_by'  => Auth::user()->id,
		];
		$request->id == 0;
		
			$report_date=isset($request->report_date) ? date('Y-m-d', strtotime($request->report_date)) : date('Y-m-d');
			$patient_log = $this->wheredate('report_date',$report_date)->where(['patient_id'  => $request->patient_id,'log_type'=>1])->first();
			if(!empty($patient_log))
			{
				$request->id=$patient_log->id;
			}
			
	
		if($request->id == 0){
			$log_data['created_by']  = Auth::user()->id;
			if($request->type==1)
			{
				$request->shift_id=5;
			}
			$log_data['report_time'] = json_encode([$request->shift_id => date('h:i:s a')]);
			//$log_data['report']      = json_encode($request->form_data);
			$patient_log = $this->create($log_data);
			
			$shiftname = getShiftName($request->shift_id);
			event(new CreateNotification('log_created-'.$shiftname, $patient_log));
			return $patient_log;
		}else{
			$patient_log = $this->findOrFail($request->id);
			//check time object exist then update it else add new array
			$exist_time = !is_null($patient_log->report_time) ? json_decode($patient_log->report_time, true) : [];
			if($request->type!=2)
			{
				if($request->type==1)
			{
				$request->shift_id=5;
			}
			$exist_time[$request->shift_id] = date('h:i:s a');			
			$log_data['report_time'] = json_encode($exist_time);
			}
			//$log_data['report']     = json_encode($request->form_data);
			$patient_log->update($log_data);
			
			$shiftname = getShiftName($request->shift_id);
			event(new CreateNotification('log_created-'.$shiftname, $patient_log));
			return $patient_log;
		}
	}

	public function addUpdateMedLogAdmin($request)
	{
		\Log::info(['MedLogdata' => $request->all()]);
	
		$log_data = [
			'patient_id'  => $request->patient_id,
			'log_type'=>1,
			'report_date' => isset($request->report_date) ? date('Y-m-d', strtotime($request->report_date)) : date('Y-m-d'),
			'status'      => 1,
			'updated_by'  => $request->created_by, //Auth::user()->id,
		];
		$request->id == 0;
		
			$report_date=isset($request->report_date) ? date('Y-m-d', strtotime($request->report_date)) : date('Y-m-d');
			$patient_log = $this->wheredate('report_date',$report_date)->where(['patient_id'  => $request->patient_id,'log_type'=>1])->first();
			if(!empty($patient_log))
			{
				$request->id=$patient_log->id;
			}
			
	
		if($request->id == 0){
			$log_data['created_by']  = $request->created_by; //Auth::user()->id;
			if($request->type==1)
			{
				$request->shift_id=5;
			}
			$log_data['report_time'] = json_encode([$request->shift_id => date('h:i:s a')]);
			//$log_data['report']      = json_encode($request->form_data);
			$patient_log = $this->create($log_data);
			
			$shiftname = getShiftName($request->shift_id);
			event(new CreateNotification('log_created-'.$shiftname, $patient_log));
			return $patient_log;
		}else{
			$patient_log = $this->findOrFail($request->id);
			//check time object exist then update it else add new array
			$exist_time = !is_null($patient_log->report_time) ? json_decode($patient_log->report_time, true) : [];
			if($request->type!=2)
			{
				if($request->type==1)
			{
				$request->shift_id=5;
			}
			$exist_time[$request->shift_id] = date('h:i:s a');			
			$log_data['report_time'] = json_encode($exist_time);
			}
			$log_data['report']     = json_encode($request->form_data);
			$patient_log->update($log_data);
			
			$shiftname = getShiftName($request->shift_id);
			event(new CreateNotification('log_created-'.$shiftname, $patient_log));
			return $patient_log;
		}
	}
	
	public function saveChargeAmount($request, $patient_log)
	{
		$patient = Patient::find($request->patient_id);
		$patient_log->charge_amount = $request->is_in_house == 1 ? $patient->per_day_cost : $patient->per_day_reserve_cost;
		$patient_log->save();
	}

	public function saveLogPdf($request, $log_data)
	{
		\Log::info(['imageData' => $request->all()]);
		$home_id     = $log_data->patient->home_id;
		$report_date = $log_data->report_date;
		if ($request->hasFile('pdf')) {
			$files = $request->file('pdf');
			foreach($files as $file){
				$extension = $file->getClientOriginalExtension();
				$doc_type=1;
				if ($extension === 'pdf' && $file->getMimeType() === 'application/pdf') {
					$doc_type=2;
				}
				$fileName = "log_images_". uniqid()."." . $extension;
				$file->move(public_path('uploads/patient-log-images/'.$home_id.'/'.$report_date.'/'), $fileName);
				$image_data = [
					'log_id'     => $log_data->id,
					'shift_id'   => $request->shift_id,
					'image'      => 'uploads/patient-log-images/'.$home_id.'/'.$report_date.'/'.$fileName,
					'type'		 => $doc_type,
					'status'     => $request->status,
					'updated_by' => Auth::user()->id,
					'created_by' => Auth::user()->id,
				];
				\Log::info(['image_data' => $image_data]);
				PatientLogImage::create($image_data);
			}
		}
	}
	
	public function saveLogImages($request, $log_data)
	{
		ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
		$home_id     = $log_data->patient->home_id;
		$report_date = $log_data->report_date;
		\Log::info(['imageDataer' => $request->file('images')]);
		if ($request->hasfile('images')) {
			$files = $request->file('images');
			
			foreach ($files as $file) {
				try {
					
					if (!$file->isValid()) {
						\Log::error('File upload error: ' . $file->getErrorMessage());
						continue; // Skip this file if there's an error
					}
			
					$extension = $file->getClientOriginalExtension();
					$doc_type = 1;
			
					// Determine document type based on file extension and MIME type
					if ($extension === 'pdf' && $file->getMimeType() === 'application/pdf') {
						$doc_type = 2;
					}
			
					$fileName = "log_images_" . uniqid() . "." . $extension;
			
					\Log::info(['file' => $file, 'fileName' => $fileName]);
			
					// Attempt to move the uploaded file to the desired location
					$file->move(public_path('uploads/patient-log-images/' . $home_id . '/' . $report_date . '/'), $fileName);
			
					// Prepare the image data for database insertion
					$image_data = [
						'log_id'     => $log_data->id,
						'shift_id'   => $request->shift_id,
						'image'      => 'uploads/patient-log-images/' . $home_id . '/' . $report_date . '/' . $fileName,
						'type'       => $doc_type,
						'status'     => $request->status,
						'updated_by' => Auth::user()->id,
						'created_by' => Auth::user()->id,
					];
			
					\Log::info(['image_data' => $image_data]);
			
					// Insert the image data into the database
					PatientLogImage::create($image_data);
			
				} catch (\Exception $e) {
					// Log any exceptions that occur during file processing
					\Log::error('File processing error: ' . $e->getMessage());
				}
			}
			
		}
	}
	
	public function getLogList($request)
	{
		$sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
		$start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
		$end_date   = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';
        return $this->where('patient_logs.patient_id', $request->patient_id)->where('log_type',0)
			->when($start_date != '' && $end_date != '', function ($q) use($start_date, $end_date) {
				$q->whereBetween('patient_logs.report_date', [$start_date, $end_date]);
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                 return $q->orderBy('patient_logs.' . $sort_field, $sort_order);
            });
	}
	public function getMedLogList($request)
	{
		$sort_field = isset($request->sort_field) ? $request->sort_field : 'report_date';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
		$start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
		$end_date   = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';
        return $this->where('patient_logs.patient_id', $request->patient_id)->where('log_type',1)
			->when($start_date != '' && $end_date != '', function ($q) use($start_date, $end_date) {
				$q->whereBetween('patient_logs.report_date', [$start_date, $end_date]);
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                 return $q->orderBy('patient_logs.' . $sort_field, $sort_order);
            });
	}
	
	public function getUserLog($request)
	{
		$log_date   = isset($request->log_date) ? date('Y-m-d', strtotime($request->log_date)) : '';
        return $this->where('patient_logs.patient_id', $request->patient_id)
			->when($log_date != '', function ($q) use($log_date) {
				$q->whereDate('patient_logs.report_date', '=', $log_date);
            })
            ->first();
	}
	
	public function deleteLog($request)
	{
		$patient_log = $this->findOrFail($request->id);
		$patient_log->delete();
		return true;
	}

	public function getAdminRevenue()
	{
		return CareHome::where('care_homes.user_id', Auth::user()->id)
			->leftJoin('patients', 'care_homes.id', '=', 'patients.home_id')
			->leftJoin('patient_logs', 'patients.id', '=', 'patient_logs.patient_id')
			->sum('patient_logs.charge_amount');
	}
	
	public function getLastWeekData()
	{
		$ids_array  = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();
		return $this->leftJoin('patients', 'patient_logs.patient_id', '=', 'patients.id')
					->leftJoin('care_homes', 'patients.home_id', '=', 'care_homes.id')
					->whereIn('care_homes.id', $ids_array)
					->whereBetween('patient_logs.created_at', 
                            [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]
                        )
					->sum('patient_logs.charge_amount');			
        
	}
	
	public function getLastMonthData()
	{
		$ids_array  = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();
		return $this->leftJoin('patients', 'patient_logs.patient_id', '=', 'patients.id')
					->leftJoin('care_homes', 'patients.home_id', '=', 'care_homes.id')
					->whereIn('care_homes.id', $ids_array)
					->whereMonth('patient_logs.created_at',Carbon::now()->subMonth()->format('m'))
					->sum('patient_logs.charge_amount');
	}
	
	public function getPaymentList($request)
	{
		$sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
		$currentUrl = $request->input('current_url', '');
    $currentPath = $request->input('current_path', '');
		if($currentPath=='/last-week-revenue')
		{
		$start_date = $request->has('start_date') ? date('Y-m-d', strtotime($request->start_date)) : Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
        $end_date = $request->has('end_date') ? date('Y-m-d', strtotime($request->end_date)) : Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');
		}else{
		// 	$start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        // $end_date   = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

		$start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : "";
        $end_date   = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : "";
		}

		$ids_array  = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();
		return $this->leftJoin('patients', 'patient_logs.patient_id', '=', 'patients.id')
					->leftJoin('care_homes', 'patients.home_id', '=', 'care_homes.id')
					->whereIn('care_homes.id', $ids_array)
					->when($start_date != '' && $end_date != '', function($q) use($start_date, $end_date){
						 return $q->whereBetween('patient_logs.created_at', [$start_date." 00:00:00",$end_date." 23:59:59"]);
					})
					->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
						 return $q->orderBy('patient_logs.' . $sort_field, $sort_order);
					})
					->select('patient_logs.*', 'care_homes.name as care_home_name', 'patients.name as patient_name');
	}
	
	public function getTotalRevenue($request)
	{
		$sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
		$currentUrl = $request->input('current_url', '');
    $currentPath = $request->input('current_path', '');
		if($currentPath=='/last-week-revenue')
		{
		$start_date = $request->has('start_date') ? date('Y-m-d', strtotime($request->start_date)) : Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
        $end_date = $request->has('end_date') ? date('Y-m-d', strtotime($request->end_date)) : Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');
		}else{
		// 	$start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        // $end_date   = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

		$start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : "";
        $end_date   = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : "";
		}
		$ids_array  = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();
		return $this->leftJoin('patients', 'patient_logs.patient_id', '=', 'patients.id')
					->leftJoin('care_homes', 'patients.home_id', '=', 'care_homes.id')
					->whereIn('care_homes.id', $ids_array)
					->when($start_date != '' && $end_date != '', function($q) use($start_date, $end_date){
						 return $q->whereBetween('patient_logs.created_at', [$start_date." 00:00:00",$end_date." 23:59:59"]);
					})
					->sum('patient_logs.charge_amount');
	}


	public function getCareHomePaymentList($id)
	{
		return $this->leftJoin('patients', 'patient_logs.patient_id', '=', 'patients.id')
					->leftJoin('care_homes', 'patients.home_id', '=', 'care_homes.id')
					->where('care_homes.id', $id)
					->select('patient_logs.*', 'care_homes.name as care_home_name', 'patients.name as patient_name');
	}


	public function getAllLogList($request)
	{
		
		$sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
		$home_ids = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
		$start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
		$end_date   = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';
		$homeName   = isset($request->home_name) ? $request->home_name : '';
		$clientName   = isset($request->client_name) ? $request->client_name : '';
		$staffName   = isset($request->staff_name) ? $request->staff_name : '';
		$shift   = isset($request->shift) ? $request->shift : '';
		$client_availability   = isset($request->client_availability) ? $request->client_availability : '';
		//$client_activity = isset($request->client_activity) ? $request->client_activity : '';
		$log_id = isset($request->client_activity) ? $request->client_activity : '';
		$client_medicine = isset($request->client_medicine) ? $request->client_medicine : '';
		$task   = isset($request->task) ? $request->task : '';
		$clientActivity = isset($request->client_activity) ? $request->client_activity : ''; 
		$assignedActivity = isset($request->assigned_activity) ? $request->assigned_activity : ''; 
		// $manger = isset($request->manager_name) ? User::find($request->manager_name) : '';
		// $staffs = [];
		// if($manger != ''){
		// 	$staffs = User::where(['role_id' => 4, 'status' => 1, 'home_id' => $manger->home_id])->pluck('id');
		// }
		
		//dd($staffs);
		// $staffIds=[];
		// if($shift != ''){
		// 	$staffIds = User::where(['parent_id' => Auth::user()->id, 'role_id' => '4', 'shift_id' => $shift])->pluck('id');
		// }
		//dd($staffIds);
        return $this->select('patient_logs.*', 'p.name','p.id as log_patient_id','care_homes.name as care_name')
				->join('patients as p', 'p.id', '=', 'patient_logs.patient_id')
				->join('care_homes', 'p.home_id', '=', 'care_homes.id')
				->whereIn('p.home_id', $home_ids)
				->where('patient_logs.log_type',0)
			->when($homeName != '', function ($q) use($homeName){
				$q->where('p.home_id', $homeName);
			})
			->when($log_id != '', function ($q) use($log_id){
				$q->where('patient_logs.id', $log_id);
			})
			->when($clientName != '', function ($q) use($clientName){
				$q->where('patient_logs.patient_id', $clientName);
			})
			->when($staffName != '', function ($q) use($staffName){
				$q->where('patient_logs.created_by', $staffName);
			})
			// ->when(!empty($staffs), function ($q) use($staffs){
			// 	$q->whereIn('patient_logs.created_by', $staffs);
			// })
			->when($client_availability != '', function ($q) use($client_availability){
				$q->where('patient_logs.is_in_house', $client_availability);
			})
		->when($clientActivity != '', function ($q) use($clientActivity){
				$q->join('patient_activities as paa', 'paa.log_id', '=', 'patient_logs.id')
				->whereRaw("FIND_IN_SET($clientActivity, paa.field_value)");
				 //->where('pa.field_id', $clientActivity);
			}) 
			->when($shift != '', function ($q) use($shift){
				$q->join('patient_activities as pa', 'pa.log_id', '=', 'patient_logs.id')->where('pa.shift_id', $shift)->groupBy('patient_logs.patient_id');
			})
			->when($client_medicine != '', function ($q) use($client_medicine){
				$q->join('patient_medicines as pm', 'pm.patient_id', '=', 'patient_logs.patient_id')->where('pm.id', $client_medicine)->groupBy('pm.patient_id');
			})
			->when($assignedActivity != '', function ($q) use($assignedActivity){
				$q->join('patient_activity_remarks as par', 'par.log_id', '=', 'patient_logs.id')->where('par.activity_id', $assignedActivity);
			})
			->when($task != '', function ($q) use($task){
				// $q->join('tasks as t', 't.patient_id', '=', 'patient_logs.patient_id')->where('t.id', $task);//->groupBy('patient_logs.id');
				$q->join('tasks as t', 't.user_id', '=', 'patient_logs.created_by')->where('t.id', $task)->groupBy('patient_logs.patient_id');
			})
			->when($start_date != '' && $end_date != '', function ($q) use($start_date, $end_date) {
				$q->whereBetween('patient_logs.report_date', [$start_date, $end_date]);
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                 return $q->orderBy('patient_logs.' . $sort_field, $sort_order);
            });
			
	}
	
}


