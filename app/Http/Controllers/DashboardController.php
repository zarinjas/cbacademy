<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard with courses and progress.
     */
    public function index()
    {
        $user = auth()->user();
        
        // If user is admin, redirect to admin dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Check if learner has completed profile
        if (!$user->hasCompletedProfile()) {
            return redirect()->route('profile.complete');
        }

        // Get all published courses ordered by display order
        $courses = Course::published()
            ->ordered()
            ->with(['publishedLessons' => function ($query) {
                $query->ordered();
            }])
            ->get();

        // Get user's learning progress (placeholder for future implementation)
        $userProgress = [
            'courses_started' => 0,
            'lessons_completed' => 0,
            'total_learning_time' => 0,
        ];

        return view('dashboard', compact('courses', 'userProgress'));
    }
}
