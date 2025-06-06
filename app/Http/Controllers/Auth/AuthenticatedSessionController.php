<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerify;
use DateTime;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();
       
        if($user){
            if($user->role_id == 2){
               
                if($user->status==1)
                {
                $request->authenticate();
                $request->session()->regenerate();
                return redirect('/dashboard');
                }else{
                    return redirect()->back()->with('error', 'Your account is inactive. Please contact the administrator.');
                }
            }else if($user->role_id == 1){
             /*   if($user->otp_invalid_attempt > 2){
                    return redirect()->back()->with('error', 'You have suspended for 12 hours please try after 12 hours!');
                }
                $currentDateTime = new DateTime();
                $currentDateTime->modify('+5 minutes');
                $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
                $otp = rand(1000, 9999);
                $user->update(['otp_verify' => $otp, 'otp_sent_at' => $formattedDateTime]);
                Mail::to($user->email)->send(new OtpVerify($otp));
                return redirect()->route('otp.verify');*/

                $request->authenticate();
                $request->session()->regenerate();
                return redirect('/dashboard');


            }else{
                $request->authenticate();
                $request->session()->regenerate();
            }
        }else{
            $request->authenticate();
            $request->session()->regenerate();
        }
        
    }

    public function otpVerify(Request $request)
    {
        //dd($request->session());
        $user = User::where('role_id', 1)->first();
        if(empty($user->otp_verify))
        {
            return redirect('/login');
        }
        return view('auth.otp-verify');
        
    }
    public function resendOtp(Request $request)
    {
        $user = User::where('role_id', 1)->first();

        if($user->otp_invalid_attempt > 2){
            $otpSentDate = $user->otp_sent_at;
            $otpSentDate = new DateTime($otpSentDate);
            $otpSentDate->modify('+12 hours');
            //dd($otpSentDate);
            $currentDateTimeCheck = new DateTime();
            if ($otpSentDate && $otpSentDate > $currentDateTimeCheck) {
                // $otpSentDate is 12 hours ahead of $currentDateTimeCheck
                return redirect()->back()->with('error', 'You have suspended for 12 hours please try after 12 hours!');
            } else {
                // $otpSentDate is null or not 12 hours ahead of $currentDateTimeCheck
                $currentDateTime = new DateTime();
                $currentDateTime->modify('+5 minutes');
                $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
                $otp = rand(1000, 9999);
                $user->update(['otp_verify' => $otp, 'otp_sent_at' => $formattedDateTime, 'otp_invalid_attempt' => 0]);
                Mail::to($user->email)->send(new OtpVerify($otp));
                return redirect()->route('otp.verify')->with('success', 'OTP sent successfuly please check your email!');
            }

        }else{

            $currentDateTime = new DateTime();
            $currentDateTime->modify('+5 minutes');
            $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
            $otp = rand(1000, 9999);
            $user->update(['otp_verify' => $otp, 'otp_sent_at' => $formattedDateTime, 'otp_invalid_attempt' => 0]);
            Mail::to($user->email)->send(new OtpVerify($otp));
            return redirect()->route('otp.verify')->with('success', 'OTP sent successfuly please check your email!');
        }
        
    }

    public function otpVerified(Request $request)
    {
        $checkAttemtOtp = User::where('role_id', 1)->first();
        if($checkAttemtOtp && $checkAttemtOtp->otp_invalid_attempt > 2){
            return redirect()->back()->with('error', 'You have suspend for 12 hours please try after 12 hours!');
        }
        $otp = $request->otp;
        $user = User::where('otp_verify', $otp)->first();
        
    if ($user && $user->otp_verify == $otp) {
        $currentDateTime = new DateTime();
        $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
        if(strtotime($user->otp_sent_at) < strtotime($formattedDateTime)){
            return redirect()->back()->with('error', 'Your otp has been expired, please resent otp.');
        }
        // OTP is correct, log in the super admin
        auth()->login($user);
        // Clear the OTP from session
        $user->update(['otp_verify' => NULL, 'otp_sent_at' => NULL]);
        return redirect()->route('dashboard');
    } else {
        User::where('role_id', 1)->increment('otp_invalid_attempt');
        return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
    }
        
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // return redirect('/');
        return redirect('/login');
    }
    public function staffVerified(Request $request)
    {
        
        return view('auth.staff-verify');
        
    }
}
