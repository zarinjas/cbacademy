<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateLearnerCredentialsRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $sharedLearner = User::where('email', 'access@chefbullacademy.local')->first();
        
        return view('admin.settings.index', compact('sharedLearner'));
    }

    /**
     * Update the shared learner credentials.
     */
    public function updateLearnerCredentials(UpdateLearnerCredentialsRequest $request)
    {
        $validated = $request->validated();

        $sharedLearner = User::where('email', 'access@chefbullacademy.local')->first();
        
        if (!$sharedLearner) {
            return back()->withErrors(['learner' => 'Shared learner account not found.']);
        }

        $sharedLearner->update([
            'name' => $validated['learner_name'],
        ]);

        if (!empty($validated['learner_password'])) {
            $sharedLearner->update([
                'password' => Hash::make($validated['learner_password']),
            ]);
        }

        return back()->with('success', 'Shared learner credentials updated successfully.');
    }
}
