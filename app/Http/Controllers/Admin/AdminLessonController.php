<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Str;

class AdminLessonController extends Controller
{
    /**
     * Show the form for creating a new lesson.
     */
    public function create(Course $course)
    {
        return view('admin.lessons.create', compact('course'));
    }

    /**
     * Store a newly created lesson in storage.
     */
    public function store(StoreLessonRequest $request, Course $course)
    {
        $data = $request->validated();
        
        // Generate slug from title
        $data['slug'] = Str::slug($data['title']);
        
        // Handle video type and URLs based on selection
        if ($data['video_type'] === 'youtube') {
            $data['youtube_url'] = $data['youtube_url'] ?? null;
            $data['google_drive_url'] = null;
            $data['local_filename'] = null;
        } elseif ($data['video_type'] === 'google_drive') {
            $data['google_drive_url'] = $data['google_drive_url'] ?? null;
            $data['youtube_url'] = null;
            $data['local_filename'] = null;
        } elseif ($data['video_type'] === 'local') {
            $data['local_filename'] = $data['local_filename'] ?? null;
            $data['youtube_url'] = null;
            $data['google_drive_url'] = null;
        }

        $lesson = $course->lessons()->create($data);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Lesson created successfully!');
    }

    /**
     * Show the form for editing the specified lesson.
     */
    public function edit(Course $course, Lesson $lesson)
    {
        // Ensure lesson belongs to course
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        return view('admin.lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Update the specified lesson in storage.
     */
    public function update(UpdateLessonRequest $request, Course $course, Lesson $lesson)
    {
        $data = $request->validated();
        
        // Generate slug from title
        $data['slug'] = Str::slug($data['title']);
        
        // Handle video type and URLs based on selection
        if ($data['video_type'] === 'youtube') {
            $data['youtube_url'] = $data['youtube_url'] ?? null;
            $data['google_drive_url'] = null;
            $data['local_filename'] = null;
        } elseif ($data['video_type'] === 'google_drive') {
            $data['google_drive_url'] = $data['google_drive_url'] ?? null;
            $data['youtube_url'] = null;
            $data['local_filename'] = null;
        } elseif ($data['video_type'] === 'local') {
            $data['local_filename'] = $data['local_filename'] ?? null;
            $data['youtube_url'] = null;
            $data['google_drive_url'] = null;
        }

        $lesson->update($data);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Lesson updated successfully!');
    }

    /**
     * Remove the specified lesson from storage.
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        // Ensure lesson belongs to course
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $lesson->delete();

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Lesson deleted successfully!');
    }

    /**
     * Toggle the published status of a lesson.
     */
    public function togglePublish(Course $course, Lesson $lesson)
    {
        // Ensure lesson belongs to course
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $lesson->update(['is_published' => !$lesson->is_published]);

        $status = $lesson->is_published ? 'published' : 'unpublished';
        return redirect()->back()->with('success', "Lesson {$status} successfully!");
    }

    /**
     * Toggle the free preview status of a lesson.
     */
    public function toggleFreePreview(Course $course, Lesson $lesson)
    {
        // Ensure lesson belongs to course
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $lesson->update(['is_free_preview' => !$lesson->is_free_preview]);

        $status = $lesson->is_free_preview ? 'enabled' : 'disabled';
        return redirect()->back()->with('success', "Free preview {$status} successfully!");
    }
}
