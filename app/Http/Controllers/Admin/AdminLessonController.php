<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
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
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|url',
            'duration_seconds' => 'nullable|integer|min:0',
            'display_order' => 'required|integer|min:0',
            'is_free_preview' => 'boolean',
            'is_published' => 'boolean',
        ]);

        // Extract YouTube ID from URL
        $youtubeId = Lesson::extractYoutubeId($validated['youtube_url']);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'Invalid YouTube URL provided.'])->withInput();
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['youtube_id'] = $youtubeId;
        $validated['course_id'] = $course->id;
        $validated['is_free_preview'] = $request->has('is_free_preview');
        $validated['is_published'] = $request->has('is_published');

        Lesson::create($validated);

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
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        // Ensure lesson belongs to course
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|url',
            'duration_seconds' => 'nullable|integer|min:0',
            'display_order' => 'required|integer|min:0',
            'is_free_preview' => 'boolean',
            'is_published' => 'boolean',
        ]);

        // Extract YouTube ID from URL
        $youtubeId = Lesson::extractYoutubeId($validated['youtube_url']);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'Invalid YouTube URL provided.'])->withInput();
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['youtube_id'] = $youtubeId;
        $validated['is_free_preview'] = $request->has('is_free_preview');
        $validated['is_published'] = $request->has('is_published');

        $lesson->update($validated);

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
