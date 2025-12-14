<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Organization;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('organizer.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        // Get fresh user instance from database to ensure save works
        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'organization_id' => 'nullable|exists:organizations,id',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store new photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        if ($request->filled('organization_id') && $user->role === 'organizer') {
            $organizationId = $request->organization_id;

            $organization = Organization::find($organizationId);

            if ($organization) {
                $existingRelation = $user->organizations()->where('organization_id', $organizationId)->first();

                if (!$existingRelation) {
                    $user->organizations()->attach($organizationId, ['status' => 'pending']);
                    $user->organization_id = $organizationId;

                    return redirect()->route('profile.edit')->with('success', 'Join request sent successfully!');
                } else {
                    return redirect()->route('profile.edit')->with('error', 'You already have a request for this organization.');
                }
            } else {
                return redirect()->route('profile.edit')->with('error', 'Organization ID not found.');
            }
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}

