<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Lesson;
use Illuminate\Validation\Rule;

class UpdateLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $lessonId = $this->route('lesson')->id;
        
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
            'video_type' => 'required|in:youtube,google_drive,local',
            'youtube_url' => 'nullable|url|max:500',
            'google_drive_url' => 'nullable|url|max:500',
            'local_filename' => 'nullable|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'duration_seconds' => 'nullable|integer|min:1',
            'display_order' => 'nullable|integer|min:0',
            'is_free_preview' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $videoType = $this->input('video_type');
            $youtubeUrl = $this->input('youtube_url');
            $googleDriveUrl = $this->input('google_drive_url');

            // Validate based on video type
            if ($videoType === 'youtube') {
                if (!$youtubeUrl) {
                    $validator->errors()->add('youtube_url', 'YouTube URL is required when YouTube is selected.');
                    return;
                }
                
                if (!Lesson::extractYoutubeId($youtubeUrl)) {
                    $validator->errors()->add('youtube_url', 'Invalid YouTube URL format.');
                }
            } elseif ($videoType === 'google_drive') {
                if (!$googleDriveUrl) {
                    $validator->errors()->add('google_drive_url', 'Google Drive URL is required when Google Drive is selected.');
                    return;
                }
                
                if (!Lesson::extractGoogleDriveId($googleDriveUrl)) {
                    $validator->errors()->add('google_drive_url', 'Invalid Google Drive URL format.');
                }
            } elseif ($videoType === 'local') {
                if (!$this->input('local_filename')) {
                    $validator->errors()->add('local_filename', 'Local filename is required when Local is selected.');
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Lesson title is required.',
            'title.max' => 'Lesson title cannot exceed 255 characters.',
            'content.required' => 'Lesson content is required.',
            'content.max' => 'Lesson content cannot exceed 2000 characters.',
            'youtube_url.url' => 'Please provide a valid YouTube URL.',
            'youtube_url.max' => 'YouTube URL cannot exceed 500 characters.',
            'google_drive_url.url' => 'Please provide a valid Google Drive URL.',
            'google_drive_url.max' => 'Google Drive URL cannot exceed 500 characters.',
            'duration_seconds.integer' => 'Duration must be a number.',
            'duration_seconds.min' => 'Duration must be at least 1 second.',
            'display_order.integer' => 'Display order must be a number.',
            'display_order.min' => 'Display order cannot be negative.',
            'course_id.required' => 'Course is required.',
            'course_id.exists' => 'Selected course does not exist.',
        ];
    }
}
