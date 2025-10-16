<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display the specified lesson.
     */
    public function show(Course $course, Lesson $lesson)
    {
        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        // Load the course with its published lessons for navigation
        $course->load(['publishedLessons' => function ($query) {
            $query->ordered();
        }]);

        // Get current lesson index and navigation
        $currentLessonIndex = $course->publishedLessons->search(function ($item) use ($lesson) {
            return $item->id === $lesson->id;
        });

        $nextLesson = null;
        $previousLesson = null;

        if ($currentLessonIndex !== false) {
            if ($currentLessonIndex < $course->publishedLessons->count() - 1) {
                $nextLesson = $course->publishedLessons[$currentLessonIndex + 1];
            }
            if ($currentLessonIndex > 0) {
                $previousLesson = $course->publishedLessons[$currentLessonIndex - 1];
            }
        }

        // Calculate progress percentage
        $totalLessons = $course->publishedLessons->count();
        $currentPosition = $currentLessonIndex !== false ? $currentLessonIndex + 1 : 0;
        $progressPercentage = $totalLessons > 0 ? round(($currentPosition / $totalLessons) * 100) : 0;

        $localVideoUrl = null;
        if ($lesson->video_type === 'local' && $lesson->local_filename) {
            // Simple relative streaming URL (no signature). This makes local testing straightforward.
            // For production, consider protecting this endpoint behind auth or signed URLs.
            $localVideoUrl = '/videos/stream?file=' . urlencode($lesson->local_filename);
        }

        return view('lessons.show', compact('course', 'lesson', 'nextLesson', 'previousLesson', 'progressPercentage', 'localVideoUrl'));
    }
}
