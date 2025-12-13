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
     * Store a new organization profile.
     */
    public function store(Request $request)
    {
        // 1. Access Control: Ensure user is an organizer
        if ($request->user()->role !== 'organizer') {
            return response()->json(['message' => 'Unauthorized. Only organizers can create profiles.'], 403);
        }

        // 2. Validate inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'logo' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        try {
            // 3. Handle File Upload
            $path = null;
            if ($request->hasFile('logo')) {
                // Use native Laravel Storage with 'public' disk
                $path = $request->file('logo')->store('logos', 'public');
            }

            // 4. Create Organization
            $organization = Organization::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'description' => $request->description,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'logo' => $path,
            ]);

            // 5. Return JSON (201 Created)
            return response()->json([
                'message' => 'Organization profile created successfully.',
                'data' => $organization,
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the specified organization.
     */
    public function show($id)
    {
        $organization = Organization::with('events')->find($id);

        if (!$organization) {
            return response()->json(['message' => 'Organization not found.'], 404);
        }

        // Generate relative URL for logo to avoid APP_URL port mismatch
        if ($organization->logo) {
            $organization->logo_url = '/storage/' . $organization->logo;
        }

        // Format events (e.g. image URLs or dates if needed by frontend)
        // Ensure image_url is present for frontend consistency
        foreach ($organization->events as $event) {
            $banner = $event->banner;
            if ($banner) {
                if (filter_var($banner, FILTER_VALIDATE_URL) || str_starts_with($banner, 'http' || 'https')) {
                    $event->image_url = $banner;
                } else {
                    $event->image_url = '/storage/' . $banner;
                }
            } else {
                $event->image_url = null;
            }
        }

        return response()->json($organization);
    }
}
