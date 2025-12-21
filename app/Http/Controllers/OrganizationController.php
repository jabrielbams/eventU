<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    /**
     * Display a listing of all organizations.
     */
    public function index()
    {
        $organizations = Organization::withCount(['users as members_count' => function($query) {
            $query->where('organization_user.status', 'approved');
        }])->orderBy('name')->get();

        return view('organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create()
    {
        return view('organizations.create');
    }

    /**
     * Store a newly created organization.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:organizations,name',
            'description' => 'nullable|string',
            'email' => 'nullable|email|unique:organizations,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
        }

        $organization = Organization::create([
            'name' => $request->name,
            'description' => $request->description,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'logo' => $path,
        ]);

        // Auto-add the creator as approved member
        $user = Auth::user();
        $organization->users()->attach($user->id, ['status' => 'approved']);
        $user->organization_id = $organization->id;
        $user->save();

        return redirect()->route('organizations.index')
            ->with('success', 'Organisasi berhasil dibuat! Kamu otomatis menjadi anggota.');
    }

    /**
     * Show the form for editing the specified organization.
     */
    public function edit($id)
    {
        $organization = Organization::findOrFail($id);

        // Check if user is approved member of this organization
        $isApprovedMember = Auth::user()->organizations()
            ->wherePivot('organization_id', $id)
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$isApprovedMember) {
            return redirect()->route('organizations.index')
                ->with('error', 'Kamu tidak memiliki akses untuk mengedit organisasi ini.');
        }

        return view('organizations.edit', compact('organization'));
    }

    /**
     * Update the specified organization.
     */
    public function update(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);

        // Check if user is approved member
        $isApprovedMember = Auth::user()->organizations()
            ->wherePivot('organization_id', $id)
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$isApprovedMember) {
            return redirect()->route('organizations.index')
                ->with('error', 'Kamu tidak memiliki akses untuk mengedit organisasi ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:organizations,name,' . $id,
            'description' => 'nullable|string',
            'email' => 'nullable|email|unique:organizations,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($organization->logo) {
                Storage::disk('public')->delete($organization->logo);
            }
            $organization->logo = $request->file('logo')->store('logos', 'public');
        }

        $organization->update([
            'name' => $request->name,
            'description' => $request->description,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'logo' => $organization->logo,
        ]);

        return redirect()->route('organizations.index')
            ->with('success', 'Organisasi berhasil diperbarui!');
    }

    /**
     * Remove the specified organization.
     */
    public function destroy($id)
    {
        $organization = Organization::findOrFail($id);

        // Check if user is approved member
        $isApprovedMember = Auth::user()->organizations()
            ->wherePivot('organization_id', $id)
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$isApprovedMember) {
            return redirect()->route('organizations.index')
                ->with('error', 'Kamu tidak memiliki akses untuk menghapus organisasi ini.');
        }

        // Delete logo file
        if ($organization->logo) {
            Storage::disk('public')->delete($organization->logo);
        }

        // Clear organization_id for all users in this org
        User::where('organization_id', $id)->update(['organization_id' => null]);

        $organization->delete();

        return redirect()->route('organizations.index')
            ->with('success', 'Organisasi berhasil dihapus!');
    }

    /**
     * Display organization management page with members list.
     */
    public function manage()
    {
        $user = Auth::user();
        
        // Get the user's approved organization
        $organization = $user->organizations()
            ->wherePivot('status', 'approved')
            ->first();

        if (!$organization) {
            return redirect()->route('profile.edit')
                ->with('error', 'Kamu belum tergabung dalam organisasi manapun.');
        }

        // Get all members of the organization with their status
        $members = $organization->users()
            ->withPivot('status', 'created_at')
            ->orderByRaw("CASE 
                WHEN organization_user.status = 'pending' THEN 1 
                WHEN organization_user.status = 'approved' THEN 2 
                WHEN organization_user.status = 'rejected' THEN 3 
                ELSE 4 END")
            ->orderBy('organization_user.created_at', 'desc')
            ->get();

        $pendingCount = $members->where('pivot.status', 'pending')->count();
        $approvedCount = $members->where('pivot.status', 'approved')->count();

        return view('organizations.manage', compact('organization', 'members', 'pendingCount', 'approvedCount'));
    }

    /**
     * Approve a user's join request.
     */
    public function approveUser(Request $request, $organizationId, $userId)
    {
        $currentUser = Auth::user();
        
        // Check if current user is an approved member of this organization
        $isApprovedMember = $currentUser->organizations()
            ->wherePivot('organization_id', $organizationId)
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$isApprovedMember) {
            return redirect()->back()->with('error', 'Kamu tidak memiliki akses untuk menyetujui anggota.');
        }

        $organization = Organization::findOrFail($organizationId);
        
        // Check if user exists in organization with pending status
        $user = $organization->users()->where('user_id', $userId)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan dalam organisasi ini.');
        }

        if ($user->pivot->status !== 'pending') {
            return redirect()->back()->with('error', 'User sudah diproses sebelumnya.');
        }

        // Approve the user
        $organization->users()->updateExistingPivot($userId, ['status' => 'approved']);

        // Update user's organization_id
        User::where('id', $userId)->update(['organization_id' => $organizationId]);

        return redirect()->back()->with('success', 'User berhasil disetujui!');
    }

    /**
     * Reject a user's join request.
     */
    public function rejectUser(Request $request, $organizationId, $userId)
    {
        $currentUser = Auth::user();
        
        // Check if current user is an approved member of this organization
        $isApprovedMember = $currentUser->organizations()
            ->wherePivot('organization_id', $organizationId)
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$isApprovedMember) {
            return redirect()->back()->with('error', 'Kamu tidak memiliki akses untuk menolak anggota.');
        }

        $organization = Organization::findOrFail($organizationId);
        
        // Check if user exists in organization
        $user = $organization->users()->where('user_id', $userId)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan dalam organisasi ini.');
        }

        // Reject the user
        $organization->users()->updateExistingPivot($userId, ['status' => 'rejected']);

        return redirect()->back()->with('success', 'User berhasil ditolak.');
    }

    /**
     * Remove a user from the organization.
     */
    public function removeUser(Request $request, $organizationId, $userId)
    {
        $currentUser = Auth::user();
        
        // Cannot remove yourself
        if ($currentUser->id == $userId) {
            return redirect()->back()->with('error', 'Kamu tidak dapat menghapus dirimu sendiri.');
        }

        // Check if current user is an approved member of this organization
        $isApprovedMember = $currentUser->organizations()
            ->wherePivot('organization_id', $organizationId)
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$isApprovedMember) {
            return redirect()->back()->with('error', 'Kamu tidak memiliki akses untuk menghapus anggota.');
        }

        $organization = Organization::findOrFail($organizationId);
        
        // Remove user from organization
        $organization->users()->detach($userId);

        // Clear user's organization_id if it matches
        User::where('id', $userId)
            ->where('organization_id', $organizationId)
            ->update(['organization_id' => null]);

        return redirect()->back()->with('success', 'Anggota berhasil dihapus dari organisasi.');
    }
}
