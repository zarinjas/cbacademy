<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCourseController extends Controller
{
    /**
     * Display a listing of all courses.
     */
    public function index()
    {
        $courses = Course::with(['lessons' => function ($query) {
            $query->ordered();
        }])->ordered()->get();

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'hero_image_url' => 'nullable|url',
            'display_order' => 'required|integer|min:0',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');

        Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully!');
    }

    /**
     * Display the specified course with its lessons.
     */
    public function show(Course $course)
    {
        $course->load(['lessons' => function ($query) {
            $query->ordered();
        }]);

        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'hero_image_url' => 'nullable|url',
            'display_order' => 'required|integer|min:0',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    /**
     * Toggle the published status of a course.
     */
    public function togglePublish(Course $course)
    {
        $course->update(['is_published' => !$course->is_published]);

        $status = $course->is_published ? 'published' : 'unpublished';
        return redirect()->back()->with('success', "Course {$status} successfully!");
    }
}
