<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateLearnerCredentialsRequest extends FormRequest
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
        return [
            'learner_name' => 'required|string|max:255',
            'learner_password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'learner_name.required' => 'Learner display name is required.',
            'learner_name.max' => 'Learner display name cannot exceed 255 characters.',
            'learner_password.required' => 'Learner password is required.',
            'learner_password.confirmed' => 'Password confirmation does not match.',
            'learner_password.min' => 'Password must be at least 8 characters.',
        ];
    }
}
