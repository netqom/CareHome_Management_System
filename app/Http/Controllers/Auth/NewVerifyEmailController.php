<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;

class NewVerifyEmailController extends Controller
{
    public function verify(Request $request)
    {
        $token = $request->query('token');
       

        if (!$token) { 
            return redirect()->intended(RouteServiceProvider::HOME)->with('error', 'Invalid verification link');
        }

        $verifiedUser = User::where('token', $token)->first();
        if(empty($verifiedUser)){
            return redirect()->route('login')->with('error','Token does not match or may be expired');
        }
        $verifiedUser->status = 1; // Update status if needed
        $verifiedUser->token = null; // Update status if needed
        $verifiedUser->email_verified_at = now(); // Update status if needed
        if($verifiedUser->save()){
            return redirect()->route('login')->with('info','Email verified successfully');
        }
    }
}
