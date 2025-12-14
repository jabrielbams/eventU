<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Organization;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'organization_id' => 'nullable|exists:organizations,id',
        ]);

        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
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

