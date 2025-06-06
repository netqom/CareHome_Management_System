<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Auth;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            // 'current_password' => ['required', 'current_password'],
            'current_password' => ['required', function ($attribute, $value, $fail) use($request) {
                if (!Hash::check($value, Auth::user()->password)) {
                    // Display an error message if the current password is incorrect
                    // return $fail('The current password is incorrect.');

                    $request->session()->flash('current_password_error', 'The current password is incorrect.');
                    return back()->withInput();
                }
            }],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        if (Hash::check($validated['current_password'], Auth::user()->password)) {
            $request->user()->update(['password' => Hash::make($validated['password'])]);

            return back()->with(['type' => 'success', 'message' => 'Password updated successfully']);
        }else{
            return back();
        }
    }
}
