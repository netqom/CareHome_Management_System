<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Patient;
use App\Models\User;
use App\Events\CreateNotification;

class PatientMedicineRemark extends Model
{
    use HasFactory;

    protected $fillable = [
		'log_id', 
		'shift_id',
		'patient_id',
		'medicine_id',
		'medicine_status',
		'status', 
		'remark',
		'remark_comment',
		'staff_note',
		'note_time',
		'ad_hoc_time',
		'created_at', 
		'updated_at'
	];
}
