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
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lessons', 'slug')->ignore($lessonId),
            ],
            'content' => 'required|string|max:2000',
            'youtube_url' => 'nullable|url|max:500',
            'google_drive_url' => 'nullable|url|max:500',
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
            $youtubeUrl = $this->input('youtube_url');
            $googleDriveUrl = $this->input('google_drive_url');

            if (!$youtubeUrl && !$googleDriveUrl) {
                $validator->errors()->add('video_url', 'Either YouTube URL or Google Drive URL is required.');
                return;
            }

            if ($youtubeUrl && $googleDriveUrl) {
                $validator->errors()->add('video_url', 'Please provide only one video URL (YouTube OR Google Drive).');
                return;
            }

            if ($youtubeUrl && !Lesson::extractYoutubeId($youtubeUrl)) {
                $validator->errors()->add('youtube_url', 'Invalid YouTube URL format.');
            }

            if ($googleDriveUrl && !Lesson::extractGoogleDriveId($googleDriveUrl)) {
                $validator->errors()->add('google_drive_url', 'Invalid Google Drive URL format.');
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
            'slug.required' => 'Lesson slug is required.',
            'slug.unique' => 'This lesson slug is already taken.',
            'slug.max' => 'Lesson slug cannot exceed 255 characters.',
            'content.required' => 'Lesson content is required.',
            'content.max' => 'Lesson content cannot exceed 2000 characters.',
            'youtube_url.required' => 'YouTube URL is required.',
            'youtube_url.url' => 'Please provide a valid YouTube URL.',
            'youtube_url.max' => 'YouTube URL cannot exceed 500 characters.',
            'duration_seconds.integer' => 'Duration must be a number.',
            'duration_seconds.min' => 'Duration must be at least 1 second.',
            'display_order.integer' => 'Display order must be a number.',
            'display_order.min' => 'Display order cannot be negative.',
            'course_id.required' => 'Course is required.',
            'course_id.exists' => 'Selected course does not exist.',
        ];
    }
}
