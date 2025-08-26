<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get statistics for admin dashboard
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_lessons' => Lesson::count(),
            'published_courses' => Course::where('is_published', true)->count(),
        ];

        // Get recent users
        $recentUsers = User::latest()->take(3)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}
