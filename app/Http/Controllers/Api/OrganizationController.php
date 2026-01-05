<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OrganizationController extends Controller
{
    /**
     * Display a listing of organizations.
     */
    public function index()
    {
        $organizations = Organization::with('users')->get();

        // Add logo URLs
        $organizations->each(function ($org) {
            if ($org->logo) {
                $org->logo_url = '/storage/' . $org->logo;
            }
        });

        return response()->json([
            'message' => 'Daftar organisasi berhasil diambil.',
            'data' => $organizations,
        ]);
    }

    /**
     * Store a new organization.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email|unique:organizations,email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        try {
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

            if ($path) {
                $organization->logo_url = '/storage/' . $path;
            }

            return response()->json([
                'message' => 'Organisasi berhasil dibuat.',
                'data' => $organization,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified organization.
     */
    public function show($id)
    {
        $organization = Organization::with(['users' => function ($query) {
            $query->withPivot('status');
        }, 'events'])->find($id);

        if (!$organization) {
            return response()->json(['message' => 'Organisasi tidak ditemukan.'], 404);
        }

        if ($organization->logo) {
            $organization->logo_url = '/storage/' . $organization->logo;
        }

        // Format events with image URLs
        foreach ($organization->events as $event) {
            $banner = $event->banner;
            if ($banner) {
                if (filter_var($banner, FILTER_VALIDATE_URL) || str_starts_with($banner, 'http')) {
                    $event->image_url = $banner;
                } else {
                    $event->image_url = '/storage/' . $banner;
                }
            } else {
                $event->image_url = null;
            }
        }

        return response()->json([
            'message' => 'Data organisasi berhasil diambil.',
            'data' => $organization,
        ]);
    }

    /**
     * Update the specified organization.
     */
    public function update(Request $request, $id)
    {
        $organization = Organization::find($id);

        if (!$organization) {
            return response()->json(['message' => 'Organisasi tidak ditemukan.'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email|unique:organizations,email,' . $id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        try {
            // Handle logo update
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($organization->logo) {
                    Storage::disk('public')->delete($organization->logo);
                }
                $organization->logo = $request->file('logo')->store('logos', 'public');
            }

            // Update other fields
            $organization->fill($request->only([
                'name',
                'description',
                'email',
                'phone',
                'address'
            ]));

            $organization->save();

            if ($organization->logo) {
                $organization->logo_url = '/storage/' . $organization->logo;
            }

            return response()->json([
                'message' => 'Data organisasi berhasil diperbarui.',
                'data' => $organization,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified organization.
     */
    public function destroy($id)
    {
        $organization = Organization::find($id);

        if (!$organization) {
            return response()->json(['message' => 'Organisasi tidak ditemukan.'], 404);
        }

        try {
            // Delete logo if exists
            if ($organization->logo) {
                Storage::disk('public')->delete($organization->logo);
            }

            $organization->delete();

            return response()->json([
                'message' => 'Organisasi berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all members of an organization.
     */
    public function getMembers($id)
    {
        $organization = Organization::find($id);

        if (!$organization) {
            return response()->json(['message' => 'Organisasi tidak ditemukan.'], 404);
        }

        $members = $organization->users()
            ->withPivot('status', 'created_at')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->pivot->status,
                    'requested_at' => $user->pivot->created_at,
                ];
            });

        return response()->json([
            'message' => 'Data anggota organisasi berhasil diambil.',
            'organization_id' => $id,
            'organization_name' => $organization->name,
            'total_members' => $members->count(),
            'data' => $members,
        ]);
    }

    /**
     * Approve a user's join request.
     */
    public function approveUser(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|integer|exists:organizations,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $organizationId = $request->organization_id;
        $userId = $request->user_id;

        $organization = Organization::find($organizationId);

        $user = $organization->users()->where('user_id', $userId)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan dalam organisasi ini.'], 404);
        }

        $organization->users()->updateExistingPivot($userId, ['status' => 'approved']);

        return response()->json([
            'message' => 'User berhasil disetujui.',
            'organization_id' => $organizationId,
            'user_id' => $userId,
            'status' => 'approved',
        ]);
    }

    /**
     * Reject a user's join request.
     */
    public function rejectUser(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|integer|exists:organizations,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $organizationId = $request->organization_id;
        $userId = $request->user_id;

        $organization = Organization::find($organizationId);

        $user = $organization->users()->where('user_id', $userId)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan dalam organisasi ini.'], 404);
        }

        $organization->users()->updateExistingPivot($userId, ['status' => 'rejected']);

        return response()->json([
            'message' => 'User berhasil ditolak.',
            'organization_id' => $organizationId,
            'user_id' => $userId,
            'status' => 'rejected',
        ]);
    }
}

