<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\UpdateUserDetail;
use App\Mail\UserInvitationMail;
use App\Models\CareHome;
use App\Models\ManageHomeDocument;
use App\Models\UserShiftHistory;
use App\Notifications\ResetPasswordNotification;
use Auth, Str, Hash, Mail,File;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserAppInfo;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'parent_id',
        'home_id',
        'shift_id',
        'password',
        'role_id',
        'status',
        'geofencing_status',
        'profile_image',
        'phone_number',
        'email_verified_at',
        'token',
        'created_by',
        'updated_by',
        'otp_verify',
        'otp_sent_at',
        'otp_invalid_attempt',
        'fcm_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected $appends = ['image_path', 'shift_name'];

    public function getImagePathAttribute()
    {
        // $image = asset('assets/img/care-home-dummy.jpg');
        $image = asset('assets/img/profile-pic.jpg');
        if (!is_null($this->profile_image)) {
            if (file_exists(public_path($this->profile_image))) {
                $image = url($this->profile_image);
            }
        }
        return $image;
    }

    public function getShiftNameAttribute()
    {
        if ($this->role_id == 4) {
            return getWorkShiftName($this->shift_id);
        }
    }

    /** Get Role Name **/
    public function role_name()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function care_home()
    {
        // home table has user_id field that stores id of related user model
        return $this->belongsTo(CareHome::class, 'home_id', 'id')->withTrashed();
    }

    public function admin_home()
    {
        // home table has user_id field that stores id of related user model
        return $this->hasMany(CareHome::class, 'home_id', 'id');
    }

    public function notification()
    {
        return $this->hasMany(Notification::class, 'staff_id', 'id');
    }
    public function documents()
    {
        return $this->hasMany(ManageHomeDocument::class, 'staff_id', 'id');
    }

    public function addUpdateUser($request, $id)
    {
        $user_data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'home_id' => isset($request->home_id) && $request->home_id != '' ? $request->home_id : 0,
            'status' => $request->status,
            'phone_number' => $request->phone_number,
            'updated_by' => Auth::user()->id,
        ];
        if ($request->role_id == 4) {
            $user_data['shift_id'] = isset($request->shift_id) && $request->shift_id != '' ? $request->shift_id : 0;
            $user_data['geofencing_status'] = isset($request->geofencing_status) && $request->geofencing_status != '' ? $request->geofencing_status : 0;
        }
        if ($id != 0) {
            $user = $this->findOrFail($id);
           
            if($user->role_id!=$request->role_id)
            {
                UserAppInfo::where('user_id', $user->id)->delete();
                $token = \Laravel\Sanctum\PersonalAccessToken::where('tokenable_id', $user->id)->delete();
            }
            if($request->status==0)
            {
                UserAppInfo::where('user_id', $user->id)->delete();
                $token = \Laravel\Sanctum\PersonalAccessToken::where('tokenable_id', $user->id)->delete();
            }
            $user_data['email'] = $user->email;
            $userData = $user->update($user_data);

            $this->saveUserImage($request, $user);

            $user_data['subject'] = "Admin update your profile";
            Mail::to($user->email)->send(new UpdateUserDetail($user_data));
        } else {
            $random = Str::random(8);
            $user_data['password'] = Hash::make($random);
            $user_data['parent_id'] = Auth::user()->id;
            $user_data['created_by'] = Auth::user()->id;
            $user = $this->create($user_data);
            //update email verification is not required
            $user->email_verified_at = now();
            $user->save();

            $this->saveUserImage($request, $user);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $random,
                'subject' => $request->role == 2 ? "Admin Invitation" : ($request->role == 3 ? "Manager Invitation" : "Staff Invitation"),
            ];

            if (!empty($request->doc[0]['name'])) {
                $docs = $request->doc;

                // Modify the $docs array using array_map()
                $docs = array_map(function ($docItem) use ($user) {
                    $docItem['staff_id'] = $user->id;
                    $docItem['type'] = 2;
                    return $docItem;
                }, $docs);

                $request->merge(['doc' => $docs]);

                $manageDocument = new ManageHomeDocument();
                $manageDocument->addDocument($request);
            }
            Mail::to($request->email)->send(new UserInvitationMail($data));
        }

        if (isset($user->id)) {
            return $user->id;
        } else {
            return 0;
        }
    }

    public function saveUserImage($request, $user)
    {
        if ($request->hasFile('profile_image')) {

            if (!is_null($user->profile_image)) {
                $old_image = public_path($user->profile_image);
                File::delete($old_image);
            }

            $file = $request->file('profile_image');
            $extension = $file->getClientOriginalExtension();
            $imageName = "user_image_" . uniqid() . "." . $extension;
            $file->move(public_path('uploads/user-profile/'), $imageName);
            $user->update(['profile_image' => "uploads/user-profile/" . $imageName]);
        }
    }

    public function createCareHomeAdmin($request, $id)
    {
        $user_data = [
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'role_id' => $request->role_id,
            'home_id' => isset($request->home_id) && $request->home_id != '' ? $request->home_id : 0,
            'status' => $request->status,
            'phone_number' => $request->admin_phone_number,
            'updated_by' => Auth::user()->id,
        ];
        if ($id != 0) {
            $user = $this->findOrFail($id);
            $userData = $user->update($user_data);
            return true;
        } else {
            $random = Str::random(8);
            $user_data['password'] = Hash::make($random);
            $user_data['parent_id'] = Auth::user()->id;
            $user_data['created_by'] = Auth::user()->id;
            $user = $this->create($user_data);
            //update email verification is not required
            $user->email_verified_at = now();
            $user->save();
            return [$user, $random];
        }
    }

    public function getUserList($request)
    {
        $sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        $start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
        $end_date = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';
        $staffName = isset($request->staff_name) ? explode(',', $request->staff_name) : '';
        $shift = isset($request->shift) ? $request->shift : '';
        $task = isset($request->task) ? $request->task : '';
        $training_id = isset($request->training_id) ? $request->training_id : '';
        $staff_status = isset($request->staff_status) ? $request->staff_status : '';

        return $this->where('role_id', '!=', 1)->where('role_id', '!=', 2)
            ->when(Auth::user()->role_id == 2, function ($q, $search) {
                $house_ids = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();
                return $q->whereIn('users.home_id', $house_ids);
            })
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere("users.email", "LIKE", "%{$search}%")
                        ->orWhere("users.name", "LIKE", "%{$search}%")
                        ->orWhere("users.phone", "LIKE", "%{$search}%");
                });
            })
            ->when($staffName != '', function ($q) use ($staffName) {
                return $q->whereIn('id', $staffName);
            })
        // ->when($start_date != '' && $end_date != '', function ($q) use($start_date, $end_date) {
        //     $q->whereBetween('users.created_at', [$start_date, $end_date]);
        // })
        // ->when($shift != '', function($q) use($shift){
        //     return $q->where('shift_id', $shift);
        // })
            ->when($staff_status != '', function ($q) use ($staff_status) {
                if ($staff_status == 'inactive') {
                    return $q->where('users.status', '0');
                } else if ($staff_status == 'archive') {
                    // return $q->where('users.status', '0')->whereNotNull('users.deleted_at');
                    return $q->withTrashed()->whereNotNull('users.deleted_at');
                } else {
                    return $q->where('users.status', '1');
                }
            })
        // ->when($task != '', function ($q) use($task){
        //     $q->join('tasks as t', 't.user_id', '=', 'users.id')->where('t.id', $task);
        // })
        // ->when($training_id != '', function ($q) use($training_id){
        //     $q->join('staff_assigned_trainings as sat', 'sat.user_id', '=', 'users.id')->where('sat.course_id', $training_id);
        // })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                return $q->orderBy('users.' . $sort_field, $sort_order);
            });
    }

    public function getCareHomeUser()
    {
        $house_ids = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();
        return $this->whereIn('role_id', [3, 4])->whereIn('users.home_id', $house_ids)->where('status', 1)->get();
    }

    public function searchUser($request)
    {
        $search = $request->QueryFilter;
        return $this->where('role_id', 2)
            ->when($search != '', function ($q, $search) {
                return $q->where("users.name", "LIKE", "%{$search}%");
            })
            ->orderBy('users.name', 'ASC');
    }

    public function countTotalRegisteredUser()
    {
        $count = 0;
        if (Auth::user()->role_id == 1) {
            $count = $this->where('role_id', '!=', 1)->where('role_id', '!=', 2)->count();
        } else {
            $homes = CareHome::where('user_id', Auth::user()->id)->pluck('id')->toArray();
            $count = $this->whereIn('home_id', $homes)->count();
        }
        return $count;
    }

    public function getCareHomeStaffList($request)
    {
        $sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        return $this->where('home_id', $request->home_id)->whereIn('role_id', [4])
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere("users.email", "LIKE", "%{$search}%")
                        ->orWhere("users.name", "LIKE", "%{$search}%")
                        ->orWhere("users.phone", "LIKE", "%{$search}%");
                });
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                return $q->orderBy('users.' . $sort_field, $sort_order);
            });
    }

    public function addUpdateStaffShift($request)
    {
        $user = $this->find($request->staff_id);
        $user->shift_id = $request->shift_id;
        $user->save();
        return true;
    }

    public function getTaskStaff($request)
    {
        return $this->where('role_id', 4)->where('users.home_id', $request->home_id)->select('id as key', 'name as value')->get();
    }

    public function getUsers()
    {
        return $this->where('role_id', 4)->where('users.parent_id', Auth::user()->id)->where('status', 1)->get();
    }

    public function getManager()
    {
        return $this->where('role_id', 3)->where('users.parent_id', Auth::user()->id)->where('status', 1)->get();
    }

    public function user_shift()
    {
        return $this->hasOne(UserShiftHistory::class, 'user_id', 'id');
        // return $this->belongsTo(UserShiftHistory::class, 'user_id', 'id');

    }

    public function getActiveWorkingUsers($home_id = null)
    {
        $currentHour = date('H'); // Get the current hour in 24-hour format

        if ($currentHour >= 5 && $currentHour < 12) {
            // Morning Shift
            $shiftType = 1;
        } elseif ($currentHour >= 12 && $currentHour < 18) {
            // Afternoon Shift
            $shiftType = 2;
        } elseif ($currentHour >= 18 && $currentHour < 23) {
            // Afternoon Shift
            $shiftType = 3;
        } else {
            // Night Shift
            $shiftType = 4;
        }

        // Fetch currently working users based on shift and role conditions
        /*  $query = $this->where(function ($query) use ($shiftType) {
        $query->where(function ($subquery) use ($shiftType) {
        $subquery->where('shift_id', $shiftType)
        //  ->orWhere('shift_id', 0);
        })
        ->when(Auth::user()->role_id == 2, function ($q) {
        return $q->where('created_by', Auth::user()->id);
        });
        }); */
        $query = $this->where(function ($query) use ($shiftType) {
            $query->where(function ($subquery) use ($shiftType) {
                $hasShift5Records = $this->where('shift_id', 5)->where('created_by', Auth::user()->id)->exists();

                if ($hasShift5Records) {
                    $subquery->where('shift_id', 5);
                }
                $subquery->orWhere('shift_id', $shiftType);

            });

            // Restrict to the created_by user if role_id is 2
            $query->when(Auth::user()->role_id == 2, function ($q) {
                return $q->where('created_by', Auth::user()->id);
            });
        });
        // ->where(function ($query) {
        //     $query->whereIn('role_id', [3, 4]);
        // })

        // Check if care_home_id is provided
        if ($home_id) {
            $query->where('home_id', $home_id);
        }

        // Fetch currently working users based on the modified query
        return $query->get();

    }

    public function getUserAssignedTasks()
    {
        return $this->hasMany(Tasks::class, 'user_id', 'id')->orderBy('created_by', 'desc');
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    public function appInfo()
    {
        return $this->hasOne(UserAppInfo::class);
    }

    public function update_by()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
