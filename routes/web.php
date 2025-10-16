<?php

use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLessonController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViewerProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Debug route to check user role
Route::get('/debug/user', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return response()->json([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_admin' => $user->isAdmin(),
            'is_learner' => $user->isLearner(),
            'has_completed_profile' => $user->hasCompletedProfile(),
            'current_route' => request()->route()->getName(),
            'current_url' => request()->url(),
        ]);
    }
    return response()->json(['message' => 'Not authenticated']);
})->middleware('auth');

// User Dashboard - Only for learners (exclude admins)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile completion route (no middleware to avoid redirect loop)
    Route::get('/profile', [ViewerProfileController::class, 'show'])->name('profile.complete');
    Route::post('/profile', [ViewerProfileController::class, 'store'])->name('profile.store');
    
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/edit', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Courses routes
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
    
    // Lessons routes with scope bindings
    Route::scopeBindings()->group(function () {
        Route::get('/courses/{course:slug}/lessons/{lesson:slug}', [LessonController::class, 'show'])
            ->name('courses.lessons.show');
    });
    
    // Admin routes - Only for admins
    Route::middleware(['role:admin', 'throttle:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Admin root redirects to dashboard
        Route::get('/', function() {
            return redirect()->route('admin.dashboard');
        });
        
        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User management
        Route::resource('users', AdminUserController::class);
        Route::patch('/users/{user}/toggle-role', [AdminUserController::class, 'toggleRole'])->name('users.toggle-role');
        
        // Course management
        Route::resource('courses', AdminCourseController::class);
        Route::patch('/courses/{course}/toggle-publish', [AdminCourseController::class, 'togglePublish'])->name('courses.toggle-publish');
        Route::get('/courses/{course}', [AdminCourseController::class, 'show'])->name('courses.show');
        
        // Lesson management
        Route::get('/courses/{course}/lessons/create', [AdminLessonController::class, 'create'])->name('lessons.create');
        Route::post('/courses/{course}/lessons', [AdminLessonController::class, 'store'])->name('lessons.store');
        Route::get('/courses/{course}/lessons/{lesson}/edit', [AdminLessonController::class, 'edit'])->name('lessons.edit');
        Route::patch('/courses/{course}/lessons/{lesson}', [AdminLessonController::class, 'update'])->name('lessons.update');
        Route::delete('/courses/{course}/lessons/{lesson}', [AdminLessonController::class, 'destroy'])->name('lessons.destroy');
        Route::patch('/courses/{course}/lessons/{lesson}/toggle-publish', [AdminLessonController::class, 'togglePublish'])->name('lessons.toggle-publish');
        Route::patch('/courses/{course}/lessons/{lesson}/toggle-free-preview', [AdminLessonController::class, 'toggleFreePreview'])->name('lessons.toggle-free-preview');
        
        // Settings
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/learner-credentials', [AdminSettingsController::class, 'updateLearnerCredentials'])->name('settings.update-learner-credentials');
    });
});

require __DIR__.'/auth.php';

use App\Http\Controllers\VideoController;

// Signed route for streaming local videos (validity enforced when generating URL)
Route::get('/videos/stream', [VideoController::class, 'stream'])->name('videos.stream');
