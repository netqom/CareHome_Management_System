<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;


class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    // public function __invoke(EmailVerificationRequest $request): RedirectResponse
    // {
        
    //     if ($request->user()->hasVerifiedEmail()) {
    //         return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    //     }

    //     if ($request->user()->markEmailAsVerified()) {
    //         event(new Verified($request->user()));
	// 		$user = $request->user();
	// 		$user->status = 1;
	// 		$user->save();
    //     }

    //     return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    // }
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Get user from session (replace with your actual session key)
        $user = session()->get('verified_user');

        if (!$user) {
            // User not found in session, handle error
            return redirect()->intended(RouteServiceProvider::HOME)->with('error', 'Verification failed');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            $user->status = 1; // Update status if needed
            $user->save();
        }

        session()->forget('verified_user'); // Clear session data

        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}
