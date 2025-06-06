<?php

namespace App\Models;

use Auth;
use File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientDocument extends Model
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
        'type',
        'document_date',
        'document_path',
        'remark',
        'status',
        'created_by',
        'updated_by',
        'staff_ids',
        'display_to_staff',
    ];

    protected $appends = ['document_url'];

    public function getDocumentUrlAttribute()
    {
        if (!is_null($this->document_path)) {
            if (file_exists(public_path($this->document_path))) {
                return url($this->document_path);
            }
        }
        return '';
    }
	
	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id', 'id');
	}
	
	public function added_by()
	{
		return $this->belongsTo(User::class, 'created_by', 'id');
	}
	
	public function addUpdateDocument($request)
	{
		\Log::info("request====",$request->all());
		$display=null;
		if($request->display_to_staff==0)
		{
			$display=0;
		}
		if($request->display_to_staff==1)
		{
			$display=1;
		}
		$document_data = [
			'patient_id'    => $request->patient_id,
			'name'          => $request->name,
			'type'          => $request->type,
			'document_date' => $request->document_date,
			'remark'        => $request->remark,
			'display_to_staff' => $display,
			'updated_by'    => Auth::user()->id,
		];
		\Log::info("request====",$document_data);
		if($request->id == 0){
			$document_data['created_by'] = Auth::user()->id;
			$document = $this->create($document_data);
			$this->saveDocument($request, $document);
		}else{
			$document = $this->findOrFail($request->id);
			$documentUpdate = $document->update($document_data);
			$this->saveDocument($request, $document);
		}
		return true;
	}
	
	public function saveDocument($request, $document)
	{
		if ($request->hasFile('document')) {
			if ($document && $document->document_path) {
				$old_image = public_path($document->document_path);
				File::delete($old_image);
			}
			$file = $request->file('document');
			$extension = $file->getClientOriginalExtension();
			$fileName = "patient_doc_". uniqid()."." . $extension;
            $file->move(public_path('uploads/patient-document/'.$request->patient_id.'/'), $fileName);
			$document->update(['document_path' => 'uploads/patient-document/'.$request->patient_id.'/'.$fileName]);
		}
	}
	
	public function addDocument($request)
	{
		//echo"<pre>";print_r($request->all());die;
		foreach ($request->doc as $docData) {
			if(!empty($docData['document_name']))
			{
				if($request->id == 0){
					$document_data = [
						'patient_id'    => $request->patient_id,
						'name'          => !empty($docData['document_name']) ? $docData['document_name'] : NULL,
						'type'          => !empty($docData['type']) ? $docData['type'] : NULL,
						'document_date' => !empty($docData['date']) ? $docData['date'] : NULL,
						'remark'        => !empty($docData['remark']) && $docData['type'] =='2' ? $docData['remark'] : NULL,
						// 'staff_ids'		=> !empty($docData['staff_ids']) ? json_encode($docData['staff_ids']) : 0,
						'display_to_staff' => !empty($docData['display_to_staff']) ? $docData['display_to_staff'] : 0,
						'updated_by'    => Auth::user()->id,
					];
					$document_data['created_by'] = Auth::user()->id;
					$document = $this->create($document_data);
					
					if (isset($docData['file']) && $docData['file']->isValid()) {
						$file = $docData['file']; 
						$extension = $file->getClientOriginalExtension();
						$fileName = "patient_doc_". uniqid()."." . $extension;
						$file->move(public_path('uploads/patient-document/'.$request->patient_id.'/'), $fileName);
						$document->update(['document_path' => 'uploads/patient-document/'.$request->patient_id.'/'.$fileName]);
					}
				}else{
					$document_data = [
						'patient_id'    => $request->patient_id,
						'name'          => !empty($docData['document_name']) ? $docData['document_name'] : NULL,
						'type'          => !empty($docData['type']) ? $docData['type'] : NULL,
						'document_date' => !empty($docData['date']) ? $docData['date'] : NULL,
						'remark'        => !empty($docData['remark']) && $docData['type'] =='2' ? $docData['remark'] : NULL,
						// 'staff_ids'		=> !empty($docData['staff_ids']) ? json_encode($docData['staff_ids']) : 0,
						'display_to_staff' => !empty($docData['display_to_staff']) ? $docData['display_to_staff'] : 0,
						'updated_by'    => Auth::user()->id,
					];
					$document = $this->findOrFail($request->id);
					$documentdata = $document->update($document_data);
					if (isset($docData['file']) && $docData['file']->isValid()) {
						if(!is_null($document->document_path)){
							$old_image = public_path($document->document_path);
							File::delete($old_image);
						}
						$file = $docData['file'];
						$extension = $file->getClientOriginalExtension();
						$fileName = "patient_doc_". uniqid()."." . $extension;
						$file->move(public_path('uploads/patient-document/'.$request->patient_id.'/'), $fileName);
						$document->update(['document_path' => 'uploads/patient-document/'.$request->patient_id.'/'.$fileName]);
					}
				}
			}
		}
		return true;
	}
	
	public function deleteDocument($request)
	{
		$document = $this->findOrFail($request->id);
		if(!is_null($document->document_path)){
			$old_image = public_path($document->document_path);
			File::delete($old_image);
		}
		$document->delete();
		return true;
	}
	
	public function getDocumentList($request)
	{
		$sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        return $this->where('patient_id', $request->patient_id)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere("patient_documents.name", "LIKE", "%{$search}%");
                });
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                return $q->orderBy('patient_documents.' . $sort_field, $sort_order);
            });
    }

}
