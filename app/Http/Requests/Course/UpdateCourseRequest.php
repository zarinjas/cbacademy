<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
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
        $courseId = $this->route('course')->id;
        
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'hero_image_url' => 'nullable|url|max:500',
            'display_order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Course title is required.',
            'title.max' => 'Course title cannot exceed 255 characters.',
            'description.required' => 'Course description is required.',
            'description.max' => 'Course description cannot exceed 1000 characters.',
            'hero_image_url.url' => 'Please provide a valid URL for the hero image.',
            'hero_image_url.max' => 'Hero image URL cannot exceed 500 characters.',
            'display_order.integer' => 'Display order must be a number.',
            'display_order.min' => 'Display order cannot be negative.',
        ];
    }
}
