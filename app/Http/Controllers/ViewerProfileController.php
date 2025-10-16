<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreViewerProfileRequest;
use App\Models\ViewerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ViewerProfileController extends Controller
{
    /**
     * Show the profile completion form.
     */
    public function show()
    {
        // If user already has a complete profile, redirect to dashboard
        if (auth()->user()->hasCompletedProfile()) {
            return redirect()->route('dashboard');
        }

        // Get existing profile if any
        $profile = auth()->user()->viewerProfile;

        return view('profile.complete', compact('profile'));
    }

    /**
     * Store the profile information.
     */
    public function store(StoreViewerProfileRequest $request)
    {
        $user = auth()->user();

        // If user already has a complete profile, redirect to dashboard
        if ($user->hasCompletedProfile()) {
            return redirect()->route('dashboard');
        }

        // Create or update the profile
        $profile = $user->viewerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_key' => Session::getId(),
            ]
        );

        // Set session flag to indicate profile is completed
        Session::put('viewer_profile_completed', true);

        return redirect()->route('dashboard')
            ->with('status', 'Profile completed successfully! Welcome to ChefBull Academy.');
    }
}
