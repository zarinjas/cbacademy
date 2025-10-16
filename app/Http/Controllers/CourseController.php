<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of published courses.
     */
    public function index()
    {
        $courses = Course::published()
            ->ordered()
            ->with(['publishedLessons' => function ($query) {
                $query->ordered();
            }])
            ->get();

        return view('courses.index', compact('courses'));
    }

    /**
     * Display the specified course with its lessons.
     */
    public function show(Course $course)
    {
        // Load the course with its published lessons ordered by display order
        $course->load(['publishedLessons' => function ($query) {
            $query->ordered();
        }]);

        // Get the first lesson for "Continue" functionality
        $firstLesson = $course->publishedLessons->first();

        return view('courses.show', compact('course', 'firstLesson'));
    }
}
