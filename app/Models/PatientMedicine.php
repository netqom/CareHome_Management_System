<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientMedicine extends Model
{
     use HasFactory, SoftDeletes;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'patient_id',
		'name',
		'medicine_type',
		'medicine_type_other',
        'time_id',
		'medicine_time',
        'time_other',
		'intake_method',
		'other_intake_method',
		'intake_supervised_by',
		'intake_supervised_other',
        'dose',
		'other_dose',
		'status',
		'med_frequency',
		'other_med_frequency',
		'bi_daily_start_date',
		'bi_daily_end_date',
		'instructions',
		'created_by',
        'updated_by'
    ];
	
	protected $appends = ['medicine_type_name', 'time_id_name', 'intake_method_name', 'intake_supervised_by_name','med_frequency_name'];
	
	public function getMedicineTypeNameAttribute(){
        $medicine_type = config('const.medicine_type');
		return $medicine_type[$this->medicine_type];
    }
	
	
	// public function getTimeIdNameAttribute(){
    //     $medicine_time = config('const.medicine_time');
	// 	return $medicine_time[$this->time_id];
	// 	Filtered key-value pairs
	// 	return $filteredArray = array_intersect_key($medicine_time, array_flip(explode(',',$this->time_id)));
    // }
	

	public function getTimeIdNameAttribute(){
        // Get the time_id attribute from the model
		$timeIds = json_decode($this->attributes['time_id'], true);

		// Ensure $timeIds is always an array
		if (!is_array($timeIds)) {
			$timeIds = [$timeIds];
		}

		 // Initialize an array to store the time names
		 $timeNames = [];
 
		 // Get the time names from the config based on the time_id
		 foreach ($timeIds as $timeId) {
			 // Check if the time_id exists in the config
			 if (isset(config('const.medicine_time')[$timeId])) {
				 // Add the time name to the array
				 $timeNames[] = config('const.medicine_time')[$timeId];
			 }
		 }
 
		 // Return the time names as a comma-separated string
		 return implode(', ', $timeNames);
		
    }
	
	public function getIntakeMethodNameAttribute(){
        $intake_method = config('const.medicine_intake_method');
		return $intake_method[$this->intake_method];
    }
	
	public function getIntakeSupervisedByNameAttribute(){
        $intake_guidedby = config('const.medicine_intake_supervised_by');
		return $intake_guidedby[$this->intake_supervised_by];
    }
	public function getMedFrequencyNameAttribute(){
        $intake_guidedby = config('const.medicine_frequency');
		return $intake_guidedby[$this->med_frequency];
    }
	
	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id', 'id');
	}
	
	public function added_by()
	{
		return $this->belongsTo(User::class, 'created_by', 'id');
	}
	
	public function addUpdateMedicine($request)
	{
		$medicineData=$request->all();
		$medicineData['other_med_frequency']=null;
		if(!empty($medicineData['other_med_frequency_week']) && $medicineData['other_med_frequency_week'][0]!=null)
		{
			$medicineData['other_med_frequency']=json_encode($medicineData['other_med_frequency_week']);
		}
		if(!empty($medicineData['other_med_frequency_month']) && $medicineData['other_med_frequency_month'][0]!=null)
		{
			$medicineData['other_med_frequency']=json_encode($medicineData['other_med_frequency_month']);
		}
		if (!empty($medicineData['medicine_time'])) {
			$processedMedicineTime = [];
			foreach ($medicineData['medicine_time'] as $key => $times) {
						
				if (is_array($times)) {
					// Remove null values
					$filteredTimes = array_filter($times, function($time) {
						return !is_null($time);
					});

					// Remove duplicate values
					$uniqueTimes = array_unique($filteredTimes);

					// If you want to reindex the array (optional)
					$uniqueTimes = array_values($uniqueTimes);
					$processedMedicineTime[$key] = implode(',', $uniqueTimes);
				} else {
					$processedMedicineTime[$key] = $times; 
				}
			}
			//dd($processedMedicineTime);
			$medicineData['medicine_time'] = json_encode($processedMedicineTime);
		}
		$medicine_data = [
			'patient_id'              => $request->patient_id,
			'name'                    => !empty($medicineData['medicine_name']) ? $medicineData['medicine_name'] : NULL,
			'medicine_type'           => !empty($medicineData['medicine_type']) ? $medicineData['medicine_type'] : 0,
			'medicine_type_other'     => !empty($medicineData['medicine_type_other']) ? $medicineData['medicine_type_other'] : NULL,
			'time_id'                 => !empty($medicineData['time_id']) ? json_encode($medicineData['time_id']) : 0,
			//'medicine_time'           => !empty($medicineData['medicine_time']) ? json_encode($medicineData['medicine_time']) : 0,
			'medicine_time'           => !empty($medicineData['medicine_time']) ? $medicineData['medicine_time'] : 0,
			'time_other'              => !empty($medicineData['time_other']) ? $medicineData['time_other'] : NULL, 
			'intake_method'           => !empty($medicineData['intake_method']) ? $medicineData['intake_method'] : 0,
			'other_intake_method'     => !empty($medicineData['other_intake_method']) ? $medicineData['other_intake_method'] : NULL,
			'intake_supervised_by'    => !empty($medicineData['intake_supervised_by']) ? $medicineData['intake_supervised_by'] : 0,
			'intake_supervised_other' => !empty($medicineData['intake_supervised_other']) ? $medicineData['intake_supervised_other'] : NULL,
			'instructions'            => !empty($medicineData['instructions']) ? $medicineData['instructions'] : NULL,
			'dose'                    => !empty($medicineData['dose']) ? $medicineData['dose'].'__'.$medicineData['dose_type'] : NULL,
			'other_dose'              => !empty($medicineData['other_dose']) ? $medicineData['other_dose'] : NULL,
			'med_frequency'           => !empty($medicineData['med_frequency']) ? $medicineData['med_frequency'] : NULL,
			'other_med_frequency'           => $medicineData['other_med_frequency'],
			'bi_daily_start_date'     => !empty($medicineData['med_frequency']) &&  in_array($medicineData['med_frequency'], [5, 3]) ? date('Y-m-d', strtotime($medicineData['bi_daily_start_date'])) : NULL,
			'bi_daily_end_date'           => !empty($medicineData['med_frequency']) &&  in_array($medicineData['med_frequency'], [5, 3]) && !empty($medicineData['bi_daily_end_date']) ? date('Y-m-d', strtotime($medicineData['bi_daily_end_date'])) : date('Y-m-d', strtotime("+1 year", strtotime($medicineData['bi_daily_start_date']))),
			'updated_by'              => Auth::user()->id,
		];
		if($request->id == 0){
			$medicine_data['created_by'] = Auth::user()->id;
			$medicine = $this->create($medicine_data);
		}else{
			
			$medicine = $this->findOrFail($request->id);
			$medicineData = $medicine->update($medicine_data);
		}
		return true;
	}
	
	public function addMedicine($request)
	{
		
		foreach ($request->medicines as $medicineData) {
			if(!empty($medicineData['medicine_name']))
			{
				$medicineData['other_med_frequency']=null;
				if(!empty($medicineData['other_med_frequency_week']) && $medicineData['other_med_frequency_week'][0]!=null)
				{
					$medicineData['other_med_frequency']=json_encode($medicineData['other_med_frequency_week']);
				}
				if(!empty($medicineData['other_med_frequency_month']) && $medicineData['other_med_frequency_month'][0]!=null)
				{
					$medicineData['other_med_frequency']=json_encode(explode(',',$medicineData['other_med_frequency_month'][0]));
				}

				// Process medicine_time to the desired format
				if (!empty($medicineData['medicine_time'])) {
					$processedMedicineTime = [];
					foreach ($medicineData['medicine_time'] as $key => $times) {
						
						if (is_array($times)) {
							// Remove null values
							$filteredTimes = array_filter($times, function($time) {
								return !is_null($time);
							});

							// Remove duplicate values
							$uniqueTimes = array_unique($filteredTimes);

							// If you want to reindex the array (optional)
							$uniqueTimes = array_values($uniqueTimes);
							$processedMedicineTime[$key] = implode(',', $uniqueTimes);
						} else {
							$processedMedicineTime[$key] = $times; 
						}
					}
					//dd($processedMedicineTime);
					$medicineData['medicine_time'] = json_encode($processedMedicineTime);
				}

				if($request->id == 0){
					$medicine_data = [
						'patient_id'              => $request->patient_id,
						'name'                    => !empty($medicineData['medicine_name']) ? $medicineData['medicine_name'] : NULL,
						'medicine_type'           => !empty($medicineData['medicine_type']) ? $medicineData['medicine_type'] : 0,
						'medicine_type_other'     => !empty($medicineData['medicine_type_other']) ? $medicineData['medicine_type_other'] : NULL,
						'time_id'                 => !empty($medicineData['time_id']) ? json_encode($medicineData['time_id']) : 0,
						// 'medicine_time'           => !empty($medicineData['medicine_time']) ? json_encode($medicineData['medicine_time']) : 0,
						'medicine_time'           => !empty($medicineData['medicine_time']) ? $medicineData['medicine_time'] : 0,
						'time_other'              => !empty($medicineData['time_other']) ? $medicineData['time_other'] : NULL, 
						'intake_method'           => !empty($medicineData['intake_method']) ? $medicineData['intake_method'] : 0,
						'other_intake_method'     => !empty($medicineData['other_intake_method']) ? $medicineData['other_intake_method'] : NULL,
						'intake_supervised_by'    => !empty($medicineData['intake_supervised_by']) ? $medicineData['intake_supervised_by'] : 0,
						'intake_supervised_other' => !empty($medicineData['intake_supervised_other']) ? $medicineData['intake_supervised_other'] : NULL,
						'instructions'            => !empty($medicineData['instructions']) ? $medicineData['instructions'] : NULL,
						'dose'                    => !empty($medicineData['dose']) ? $medicineData['dose'].'__'.$medicineData['dose_type'] : NULL,
						'other_dose'              => !empty($medicineData['other_dose']) ? $medicineData['other_dose'] : NULL,
						'med_frequency'           => !empty($medicineData['med_frequency']) ? $medicineData['med_frequency'] : NULL,
						'other_med_frequency'     => $medicineData['other_med_frequency'],
						'bi_daily_start_date'     => !empty($medicineData['med_frequency']) &&  in_array($medicineData['med_frequency'], [5, 3]) ? date('Y-m-d', strtotime($medicineData['bi_daily_start_date'])) : NULL,
						'bi_daily_end_date'           => !empty($medicineData['med_frequency']) &&  in_array($medicineData['med_frequency'], [5, 3]) && !empty($medicineData['bi_daily_end_date']) ? date('Y-m-d', strtotime($medicineData['bi_daily_end_date'])) : date('Y-m-d', strtotime("+1 year", strtotime($medicineData['bi_daily_start_date']))),
						'updated_by'              => Auth::user()->id,
					];
				
					$medicine_data['created_by'] = Auth::user()->id;
					$medicine = $this->create($medicine_data);
				}else{
					$medicine_data = [
						'patient_id'              => $request->patient_id,
						'name'                    => $medicineData['medicine_name'] ? $medicineData['medicine_name'] : NULL,
						'medicine_type'           => !empty($medicineData['medicine_type']) ? $medicineData['medicine_type'] : 0,
						'medicine_type_other'     => !empty($medicineData['medicine_type_other']) ? $medicineData['medicine_type_other'] : NULL,
						'time_id'                 => !empty($medicineData['time_id']) ? json_encode($medicineData['time_id']) : 0,
						// 'medicine_time'           => !empty($medicineData['medicine_time']) ? json_encode($medicineData['medicine_time']) : 0,
						'medicine_time'           => !empty($medicineData['medicine_time']) ? $medicineData['medicine_time'] : 0,
						'time_other'              => !empty($medicineData['time_other']) ? $medicineData['time_other'] : NULL, 
						'intake_method'           => !empty($medicineData['intake_method']) ? $medicineData['intake_method'] : 0,
						'other_intake_method'     => !empty($medicineData['other_intake_method']) ? $medicineData['other_intake_method'] : NULL,
						'intake_supervised_by'    => !empty($medicineData['intake_supervised_by']) ? $medicineData['intake_supervised_by'] : 0,
						'intake_supervised_other' => !empty($medicineData['intake_supervised_other']) ? $medicineData['intake_supervised_other'] : NULL,
						'instructions'            => !empty($medicineData['instructions']) ? $medicineData['instructions'] : NULL,
						'dose'                    => !empty($medicineData['dose']) ? $medicineData['dose'].'__'.$medicineData['dose_type'] : NULL,
						'other_dose'              => !empty($medicineData['other_dose']) ? $medicineData['other_dose'] : NULL,
						'med_frequency'           => !empty($medicineData['med_frequency']) ? $medicineData['med_frequency'] : NULL,
						'other_med_frequency'     => $medicineData['other_med_frequency'],
						'bi_daily_start_date'     => !empty($medicineData['med_frequency']) &&  in_array($medicineData['med_frequency'], [5, 3]) ? date('Y-m-d', strtotime($medicineData['bi_daily_start_date'])) : NULL,
						'bi_daily_end_date'       => !empty($medicineData['med_frequency']) &&  in_array($medicineData['med_frequency'], [5, 3]) ? date('Y-m-d', strtotime($medicineData['bi_daily_end_date'])) : date('Y-m-d', strtotime("+1 year", strtotime($medicineData['bi_daily_start_date']))),
						'updated_by'              => Auth::user()->id,
					];
					$medicine = $this->findOrFail($request->id);
					$medicinesData = $medicine->update($medicine_data);
				}
			}
		}
		return true;
	}
	
	public function deleteMedicine($request)
	{
		$this->findOrFail($request->id)->delete();
		return true;
	}
	
	public function getMedicineList($request)
	{
		$sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        return $this->where('patient_id', $request->patient_id)
			->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere("patient_medicines.name", "LIKE", "%{$search}%");
                });
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                 return $q->orderBy('patient_medicines.' . $sort_field, $sort_order);
            });
	}
	public function getAdHocMedicines($request)
	{
		$sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        $data= $this->where('patient_id', $request->patient_id)
			->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere("patient_medicines.name", "LIKE", "%{$search}%");
                });
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                 return $q->orderBy('patient_medicines.' . $sort_field, $sort_order);
            })->get();
			$adhoc=[];
		if(!$data->isEmpty())
		{
			foreach($data as $med)
			{
				$pre_time_array=[];
				if($med->time_id!=null)
				{
					$timeId= json_decode($med->time_id,true);
					if(is_array($timeId))
					{
						$pre_time_array=$timeId;
					}else{
						$pre_time_array[]=$med->time_id;
					}
					if(in_array(3,$pre_time_array))
					{
						$adhoc[]=[
							"name"=>$med->name,
							"id"=>$med->id,
							"type_name"=>'select'

						];
					}
				}
				
			}
		}
		return $adhoc;

	}

	
}
