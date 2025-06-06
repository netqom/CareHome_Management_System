<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {   
        $request->user()->fill($request->validated());
        if ($request->user()->isDirty('email')) { 
            $request->user()->email_verified_at = null;
        }
        if ($request->hasFile('image')) {
            if (!is_null($request->user()->profile_image)) {
                $old_image = public_path($request->user()->profile_image);
                File::delete($old_image);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $imageName = "home_image_" . uniqid() . "." . $extension;
            $file->move(public_path('uploads/profile/'), $imageName);
            $request->user()->profile_image = "uploads/profile/" . $imageName;
        }
        $request->user()->save();
        return Redirect::route('profile.edit')->with(['type' => 'success', 'message' => 'Profile updated successfully']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);
        $user = $request->user();
        //first logout the user
        Auth::logout();
        //delete the user
        $user->delete();
        //invalidate the session
        $request->session()->invalidate();
        //regenerate the session token
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }
}
