<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreViewerProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'min:2', 'max:80'],
            'phone' => ['required', 'string', 'regex:/^(\+?6?01)[0-46-9]-*[0-9]{7,8}$/'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required.',
            'full_name.min' => 'Full name must be at least 2 characters.',
            'full_name.max' => 'Full name cannot exceed 80 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid Malaysian phone number (e.g., 012-345-6789 or +6012-345-6789).',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'full_name' => 'full name',
            'phone' => 'phone number',
        ];
    }
}
