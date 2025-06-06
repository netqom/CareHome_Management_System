<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAppInfo extends Model
{
    use HasFactory;
    protected $table = 'user_app_info';

    protected $fillable = [
        'user_id',
        'device_type',
        'version',
        'fcm_token',
    ];

    /**
     * Get the user that owns the app info.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
