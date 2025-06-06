<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Patient;
use App\Models\User;
use App\Events\CreateNotification;

class PatientActivityRemark extends Model
{
    use HasFactory;
    protected $fillable = [
		'log_id', 
		'shift_id',
		'patient_id',
		'activity_id',
		'status', 
		'remark',
		'created_at', 
		'updated_at'
	];
	
}
