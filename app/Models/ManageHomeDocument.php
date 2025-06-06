<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth, File;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManageHomeDocument extends Model
{
    use HasFactory, SoftDeletes;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'home_id',
        'staff_id',
        'name',
        'type',
        'expiry_date',
        'document_path',
		'is_expiry_applicable',
        'status',
		'created_by',
        'updated_by',
    ];
	
	protected $appends = ['document_url'];
	
	public function getDocumentUrlAttribute(){
        if(!is_null($this->document_path)){
			if (file_exists( public_path($this->document_path))) {
				return url($this->document_path);
			}
		}
		return '';
    }
    public function home()
	{
		return $this->belongsTo(Patient::class, 'home_id', 'id');
	}
	
	public function added_by()
	{
		return $this->belongsTo(User::class, 'created_by', 'id');
	}

    public function addUpdateDocument($request)
	{
		$document_data = [
			'home_id'    => $request->home_id,
			'name'          => $request->name,
			'type'          => $request->type,
			'expiry_date' => $request->expiry_date,
			'updated_by'    => Auth::user()->id,
		];
		if($request->id == 0){
			$document_data['created_by'] = Auth::user()->id;
			$document = $this->create($document_data);
			$this->saveDocument($request, $document);
		}else{
			$medicine = $this->findOrFail($medicine['id']);
			$document = $medicine->update($document_data);
			$this->saveDocument($request, $document);
		}
		return true;
	}
	
	public function saveDocument($request, $document)
	{
		if ($request->hasFile('file')) {
			if(!is_null($document->document_path)){
				$old_image = public_path($document->document_path);
				File::delete($old_image);
			}
			$file = $request->file('file');
			$extension = $file->getClientOriginalExtension();
			$fileName = "home_doc_". uniqid()."." . $extension;
            $file->move(public_path('uploads/home-document/'.$request->home_id.'/'), $fileName);
			$document->update(['document_path' => 'uploads/home-document/'.$request->home_id.'/'.$fileName]);
		}
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
	public function addDocument($request)
	{
	
		foreach ($request->doc as $docData) {
			
			if(!empty($docData['name']))
			{
				if($request->id == 0){
					$document_data = [
						'home_id'    => isset($docData['home_id'])? $docData['home_id'] : $request->home_id,
						'name'          => !empty($docData['name']) ? $docData['name'] : NULL,
						'type'          => isset($docData['type'])? $docData['type'] : null,
						'is_expiry_applicable'          =>($docData['is_expiry_applicable']==1) ? 1 : 0,
						'expiry_date' => !empty($docData['expiry_date']) ? $docData['expiry_date'] : NULL,
						 'staff_id'		=> isset($docData['staff_id'])? $docData['staff_id'] : 0,
						'updated_by'    => Auth::user()->id,
					];
					//dd($document_data);
					$document_data['created_by'] = Auth::user()->id;
					
					$document = $this->create($document_data);
					
					if (isset($docData['file']) && $docData['file']->isValid()) {
						//dd($docData,$request->all());
						$file = $docData['file']; 
						$extension = $file->getClientOriginalExtension();
						if(isset($docData['staff_id']))
						{
						$fileName = "staff_doc_". uniqid()."." . $extension;
						}else{
							$fileName = "home_doc_". uniqid()."." . $extension;
						}
						$file->move(public_path('uploads/home-document/'.$request->home_id.'/'), $fileName);
						$document->update(['document_path' => 'uploads/home-document/'.$request->home_id.'/'.$fileName]);
					}
				}else{
					$document_data = [
						'home_id'    => $request->home_id,
						'name'          => !empty($docData['name']) ? $docData['name'] : NULL,
						'is_expiry_applicable'          =>($docData['is_expiry_applicable']==1) ? 1 : 0,
						'expiry_date' => !empty($docData['expiry_date']) ? $docData['expiry_date'] : NULL,
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
						if($document->staff_id!=0)
						{
							$fileName = "staff_doc_". uniqid()."." . $extension;
						}else{
						$fileName = "home_doc_". uniqid()."." . $extension;
						}
						$file->move(public_path('uploads/home-document/'.$request->home_id.'/'), $fileName);
						$document->update(['document_path' => 'uploads/home-document/'.$request->home_id.'/'.$fileName]);
					}
				}
			}
		}
		return true;
	}
}
