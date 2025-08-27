<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
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
            'youtube_url' => 'required|url|max:500',
            'duration_seconds' => 'nullable|integer|min:1',
            'display_order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
            'is_free_preview' => 'boolean',
            'course_id' => 'required|exists:courses,id',
        ];
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
