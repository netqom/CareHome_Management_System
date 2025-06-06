<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth, File;

class PatientActivityFieldValue extends Model
{
     use HasFactory;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'field_id',
		'name',
		'remark',
		'has_options',
		'status',
		'created_by',
        'updated_by'
    ];
	
	public function options()
	{
		return $this->belongsTo(PatientActivityField::class, 'field_id', 'id');
	}
	
	public function added_by()
	{
		return $this->belongsTo(User::class, 'created_by', 'id');
	}
}
