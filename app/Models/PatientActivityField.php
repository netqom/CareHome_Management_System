<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth, File;

class PatientActivityField extends Model
{
     use HasFactory;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'home_id',
		'parent_id',
		'name',
		'icon',
		'slug',
		'type',
		'status',
		'created_by',
        'updated_by'
    ];
	
	protected $appends = ['icon_url', 'type_name'];
	
	public function getIconUrlAttribute(){
        if(!is_null($this->icon)){
			if (file_exists(public_path($this->icon))) {
				return url($this->icon);
			}
		}
		return '';
    }
	
	public function getTypeNameAttribute(){
		$types = config('const.activity_types');
        return $types[$this->type];
    }
	
	public function logs()
	{
		return $this->hasMany(PatientLog::class, 'log_id', 'id');
	}
	
	public function home()
	{
		return $this->belongsTo(CareHome::class, 'home_id', 'id');
	}
	
	public function added_by()
	{
		return $this->belongsTo(User::class, 'created_by', 'id');
	}
	
	public function options()
    {
        return $this->hasMany(PatientActivityFieldValue::class, 'field_id', 'id');
    }
	
	public function children()
    {
        return $this->hasMany(PatientActivityField::class, 'parent_id', 'id')->where('status',1);
    }
	public function adhocchildren()
    {
        return $this->hasMany(PatientActivityField::class, 'parent_id', 'id')->where('patient_activity_fields.status',1);
    }

    public function parent()
    {
        return $this->belongsTo(PatientActivityField::class, 'parent_id', 'id');
    }

    public function subchilds()
    {
        return $this->children()->with(['options', 'subchilds']);
    }
    public function adhocsubchilds()
    {
        return $this->adhocchildren()->with(['options', 'adhocsubchilds']);
    }
}
