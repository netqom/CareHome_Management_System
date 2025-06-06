<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth, File;
use App\Models\User;
use App\Models\Patient;
use App\Models\Notification;
use App\Events\CreateNotification;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientAppointmentRemark extends Model
{
    use HasFactory, SoftDeletes;
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'appointment_id',
        'appointment_date',
		'status',
        'remark',
		'marked_by',
		'created_at',
        'updated_at'
    ]; 


    public function added_by()
	{
		return $this->belongsTo(User::class, 'marked_by', 'id');
	}
}
