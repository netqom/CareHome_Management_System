<?php

namespace App\Models;

use App\Mail\CareHomeRegisteredMail;
use App\Models\Expense;
use App\Models\ManageHomeDocument;
use App\Models\Patient;
use App\Models\PatientDocument;
use App\Models\PatientLog;
// use App\Models\PatientExpense;
use App\Models\PatientMedicine;
use App\Models\PatientPayment;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use App\Models\SubscriptionPayment;
use App\Models\Tasks;
use App\Models\User;
use Auth;
use File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Billable;
use Mail;
use Str;
use App\Models\UserAppInfo;

class CareHome extends Model
{
    use HasFactory, Billable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'contact_no',
        'about',
        'address',
        'street',
        'city',
        'state',
        'zip_code',
        'image',
        'capacity',
        'privacy_policy',
        'term_conditions',
        'status',
        'location_lat',
        'location_long',
        'geofencing_radius',
        'created_by',
        'patient_capacity',
        'updated_by',
        'subscription_id',
        'subscription_plan_id',
        'subscription_plan_permission',
    ];

    protected $appends = ['image_path'];

    public function getImagePathAttribute()
    {
        // $image = asset('assets/img/care-home-dummy.jpg');
        $image = asset('assets/img/nursing-icon-green.png');
        if (!is_null($this->image)) {
            if (file_exists(public_path($this->image))) {
                $image = url($this->image);
            }
        }
        return $image;
    }

    public function admin()
    {
        // home table has user_id field that stores id of related user model
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function activity_time()
    {
        // home table has user_id field that stores id of related user model
        return $this->belongsTo(ActivityTime::class, 'id', 'home_id');
    }

    public function staff_users()
    {
        return $this->hasMany(User::class, 'home_id', 'id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'home_id', 'id');
    }
    public function documents()
    {
        return $this->hasMany(ManageHomeDocument::class, 'home_id', 'id');
    }

    /*public function addUpdateHome($request, $user, $pass, $id)
    {
    $home_data = [
    'user_id'       => $user->id,
    'name'          => $request->name,
    'email'         => $request->email,
    'contact_no'    => $request->contact_no,
    'about'         => $request->about,
    //'address'       => $request->address,
    'street'        => $request->street,
    'city'          => $request->city,
    'state'         => $request->state,
    'zip_code'      => $request->zip_code,
    'location_lat'  => $request->location_lat,
    'location_long' => $request->location_long,
    'capacity'      => isset($request->capacity) && $request->capacity != '' ? $request->capacity : 0,
    'status'        => $request->status,
    'updated_by'    => Auth::user()->id,
    ];
    if ($id != 0) {
    $home = $this->findOrFail($id);
    $homeData = $home->update($home_data);
    $this->saveHomeImage($request, $home);
    } else {
    $home_data['created_by']        = Auth::user()->id;
    $homeData = $this->create($home_data);
    $this->saveHomeImage($request, $homeData);
    $data = [
    'name'  => $user->name,
    'email' => $user->email,
    'user_type'  => $request->selected_user_id == 0 ? 'new' : 'existing',
    'password'  => $pass,
    'subject' => "New Care Home Registered",
    ];
    Mail::to($user->email)->send(new CareHomeRegisteredMail($data));
    }

    return true;
    }*/

    public function addUpdateHome($request, $id)
    {
        $home_data = [
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'about' => $request->about,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'location_lat' => $request->location_lat,
            'location_long' => $request->location_long,
            'subscription_plan_permission' => json_encode(array('allow_geofencing' => 1, 'allow_add_images' => 1, 'allow_manage_med_report' => 1)),
            'geofencing_radius' => isset($request->geofencing_radius) ? $request->geofencing_radius : 2,
            'status' => $request->status,
            'updated_by' => Auth::user()->id,
        ];
        if ($id != 0) {
            $home = $this->findOrFail($id);
            $staff_ids = $home->staff_users->pluck('id');

            if($request->status==0){
                UserAppInfo::whereIn('user_id', $staff_ids)->delete();
                // Delete tokens for all staff members when care home status is 0
                \Laravel\Sanctum\PersonalAccessToken::whereIn('tokenable_id', $staff_ids)->delete();
            }

            $homeData = $home->update($home_data);
            $this->saveHomeImage($request, $home);
            $this->saveDocument($request, $home);
            return true;
        } else {
            $user = Auth::user();
            $home_data['created_by'] = Auth::user()->id;
            $homeData = $this->create($home_data);
            $this->saveHomeImage($request, $homeData);
            $this->saveDocument($request, $homeData);
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'care_home_name' => $request->name,
                'subject' => "New Care Home Registered",
            ];
            if (!empty($request->doc[0]['name'])) {
                $docs = $request->doc;

                // Modify the $docs array using array_map()
                $docs = array_map(function ($docItem) use ($homeData) {
                    $docItem['staff_id'] = 0;
                    $docItem['home_id'] = $homeData->id;
                    $docItem['type'] = 1;
                    return $docItem;
                }, $docs);

                $request->merge(['doc' => $docs]);
                $manageDocument = new ManageHomeDocument();
                $manageDocument->addDocument($request);
            }
            Mail::to($user->email)->send(new CareHomeRegisteredMail($data));

            return $homeData;
        }

    }

    public function userBasedHomeList()
    {
        return Auth::user()->role_id == 1 ? $this->all() : $this->where('user_id', Auth::user()->id)->get();
    }
    public function userBasedActiveHomeList()
    {
        $query = $this->query(); // Start with a base query for the CareHome model

        if (Auth::user()->role_id == 1) {
            // If the user's role is 1 (assuming it's the admin role), return all care homes
            return $query->get();
        } else {
            $subQuery = DB::table('subscriptions')
                ->select('care_home_id', DB::raw('MAX(updated_at) as latest_updated_at'))
                ->groupBy('care_home_id');

            $homeIds = $query->joinSub($subQuery, 'sq', function ($join) {
                $join->on('care_homes.id', '=', 'sq.care_home_id');
            })
                ->join('subscriptions as sp', function ($join) {
                    $join->on('sp.care_home_id', '=', 'care_homes.id')
                        ->on('sp.updated_at', '=', 'sq.latest_updated_at');
                })
                ->where('care_homes.user_id', Auth::user()->id)
                ->where('care_homes.status', 1)
                ->pluck('sp.id')
                ->toArray();

            return $query->join('subscriptions as s', 's.care_home_id', '=', 'care_homes.id')
                ->where('s.stripe_status', '!=', 'canceled')->whereIn('s.id', $homeIds)
                ->select('care_homes.*')
                ->get();
        }
    }

    public function saveHomeImage($request, $home)
    {
        if ($request->hasFile('image')) {
            if (!is_null($home->image)) {
                $old_image = public_path($home->image);
                File::delete($old_image);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $imageName = "home_image_" . uniqid() . "." . $extension;
            $file->move(public_path('uploads/care-home/'), $imageName);
            $home->update(['image' => "uploads/care-home/" . $imageName]);
        }
    }
    public function saveDocument($request, $home)
    {
        if ($request->hasFile('privacy_policy')) {
            if (!is_null($home->privacy_policy)) {
                $old_image = public_path($home->privacy_policy);
                File::delete($old_image);
            }
            $file = $request->file('privacy_policy');
            $extension = $file->getClientOriginalExtension();
            $imageName = "home_privacy_policy_" . uniqid() . "." . $extension;
            $file->move(public_path('uploads/care-home/documents/'), $imageName);
            $home->update(['privacy_policy' => "uploads/care-home/documents/" . $imageName]);
        }
        if ($request->hasFile('term_conditions')) {
            if (!is_null($home->term_conditions)) {
                $old_image = public_path($home->term_conditions);
                File::delete($old_image);
            }
            $file = $request->file('term_conditions');
            $extension = $file->getClientOriginalExtension();
            $imageName = "home_term_conditions_" . uniqid() . "." . $extension;
            $file->move(public_path('uploads/care-home/documents/'), $imageName);
            $home->update(['term_conditions' => "uploads/care-home/documents/" . $imageName]);
        }
    }

    public function getList($request)
    {
        $sort_field = isset($request->sort_field) ? $request->sort_field : 'id';
        $sort_order = isset($request->sort_order) ? $request->sort_order : 'desc';
        $care_home_status = isset($request->care_home_status) ? $request->care_home_status : 'active';

        return $this->when(Auth::user()->role_id == 2, function ($q) {
            
            return $q->where('care_homes.user_id', Auth::user()->id)
                ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, name,email FROM care_homes WHERE stripe_id IS NOT NULL GROUP BY email) AS latest_care_homes'), function ($join) {
                    $join->on('care_homes.id', '=', 'latest_care_homes.id');
                });
        }, function ($q) {
            // For users with role other than 2
            return $q->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, name,email FROM care_homes WHERE stripe_id IS NOT NULL GROUP BY email) AS latest_care_homes'), function ($join) {
                $join->on('care_homes.id', '=', 'latest_care_homes.id');
            });
        })
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->orWhere("care_homes.name", "LIKE", "%{$search}%")
                        ->orWhere("care_homes.about", "LIKE", "%{$search}%")
                        ->orWhere("care_homes.address", "LIKE", "%{$search}%");
                });
            })
            ->when($care_home_status != '', function ($q) use ($care_home_status) {
                if ($care_home_status == 'inactive') {
                    return $q->where('care_homes.status', '0');
                } else if ($care_home_status == 'archive') {
                    return $q->withTrashed()->whereNotNull('care_homes.deleted_at');
                } else {
                    return $q->where('care_homes.status', '1');
                }
            })
            ->when($sort_field != '', function ($q) use ($sort_field, $sort_order) {
                return $q->orderBy('care_homes.' . $sort_field, $sort_order);
            });
    }

    public function countTotalRegisteredHomes()
    {
        $count = 0;
        if (Auth::user()->role_id == 1) {
            $count = $this->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, name,email FROM care_homes WHERE stripe_id IS NOT NULL GROUP BY email) AS latest_care_homes'), function ($join) {
                $join->on('care_homes.id', '=', 'latest_care_homes.id');
            })->where('status', 1)->count();
        } else {
            $count = $this->where('user_id', Auth::user()->id)->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, name,email FROM care_homes WHERE stripe_id IS NOT NULL GROUP BY email) AS latest_care_homes'), function ($join) {
                $join->on('care_homes.id', '=', 'latest_care_homes.id');
            })->where('status', 1)->count();
        }
        return $count;
    }

    public function deleteCareHome($home)
    {
        //delete staff related to care home
        // User::where('home_id', $home->id)->delete();
        $users = User::where('home_id', $home->id)->get();
        if ($users->count() > 0) {
            foreach ($users as $user) {
                Tasks::where('user_id', $user->id)->delete();

                $user->delete();
            }
        }
        //delete patient and tere related data
        $patients = Patient::where('home_id', $home->id)->get();
        if ($patients->count() > 0) {
            foreach ($patients as $patient) {
                //delete patient medicine
                PatientMedicine::where('patient_id', $patient->id)->delete();
                //delete patient expense list
                // PatientExpense::where('patient_id', $patient->id)->delete();
                Expense::where('item_id', $patient->id)->where('type', '1')->delete();
                //delete patient logs
                PatientLog::where('patient_id', $patient->id)->delete();
                //delete patient payments list
                PatientPayment::where('patient_id', $patient->id)->delete();
                //delete patient document
                $documents = PatientDocument::where('patient_id', $patient->id)->get();
                if ($documents->count() > 0) {
                    foreach ($documents as $document) {
                        // if(!is_null($document->document_path)){
                        //     $old_image = public_path($document->document_path);
                        //     File::delete($old_image);
                        // }
                        $document->delete();
                    }
                }

                $patient->delete();
            }
        }
        //delete susbscription data
        $subscriptions = Subscription::where('care_home_id', $home->id)->get();
        if ($subscriptions->count() > 0) {
            foreach ($subscriptions as $subscription) {
                SubscriptionItem::where('subscription_id', $subscription->id)->delete();
                $subscription->delete();
            }
        }
        //check subscription payment method
        SubscriptionPayment::where('customer', $home->stripe_id)->delete();
        // if(!is_null($home->image)){
        //     $old_image = public_path($home->image);
        //     File::delete($old_image);
        // }

        $home->delete();
    }

    public function subscriptionPayments()
    {
        return $this->hasMany(SubscriptionPayment::class, 'care_home_id', 'id')->withTrashed();
    }

    public function patientPayments()
    {
        return $this->hasMany(PatientPayment::class, 'home_id', 'id');
    }

    public function patientExpenses()
    {
        return $this->hasMany(Expense::class, 'home_id', 'id')->orderBy('id', 'desc');

    }
    public function getCareHomeSubscription()
    {
        return $this->hasOne(Subscription::class, 'id', 'subscription_id')->withTrashed();
    }
    public function getCareHomeActiveSubscriptionitem()
    {
        return $this->hasOne(Subscription::class, 'care_home_id', 'id')->latest('updated_at')->withTrashed();
    }
    public function getCareHomeActiveSubscription($careHomeId)
    {
        return Subscription::where('care_home_id', $careHomeId)
            ->withTrashed()
            ->whereHas('subscriptionPlan', function ($query) {
                $query->whereColumn('subscription_plans.stripe_price_id', 'subscriptions.stripe_price');
            })
            ->join('subscription_plans', 'subscriptions.stripe_price', '=', 'subscription_plans.stripe_price_id')
            ->select('subscriptions.*', 'subscription_plans.id as subscription_plan_id') // Select subscription_plan_id
            ->orderBy('subscriptions.updated_at', 'desc') // Use subscriptions.updated_at to avoid ambiguity
            ->first(); // Assuming you expect only one result; use get() if you expect multiple
    }

    public function getAdminCareHome()
    {
        return $this->select('care_homes.id', 'care_homes.name', 'care_homes.email', 'care_homes.user_id', 'care_homes.created_by')->where(['care_homes.status' => 1, 'care_homes.deleted_at' => null, 'care_homes.created_by' => Auth::user()->id])
            ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, name FROM care_homes WHERE stripe_id IS NOT NULL GROUP BY name) AS latest_care_homes'), function ($join) {
                $join->on('care_homes.id', '=', 'latest_care_homes.id');
            })
            ->get();
    }

    public function getAllCareHome()
    {
        return $this->select('care_homes.id', 'care_homes.name', 'care_homes.email', 'care_homes.user_id', 'care_homes.created_by')->where(['care_homes.status' => 1, 'care_homes.deleted_at' => null])
            ->join(\Illuminate\Support\Facades\DB::raw('(SELECT MAX(id) AS id, name FROM care_homes WHERE stripe_id IS NOT NULL GROUP BY name) AS latest_care_homes'), function ($join) {
                $join->on('care_homes.id', '=', 'latest_care_homes.id');
            })
            ->get();
    }
}
