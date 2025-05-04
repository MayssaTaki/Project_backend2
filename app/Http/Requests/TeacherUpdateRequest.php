<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
        if (is_string($this->route('teacher'))) {
            $this->route()->setParameter(
                'teacher',
                \App\Models\Teacher::findOrFail($this->route('teacher'))
            );
        }
    }
    public function rules()
    {
        $teacher = $this->route('teacher');

        return [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'specialization' => 'sometimes|required|string|max:255',
            'Previous_experiences' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'gender' => 'sometimes|required|in:male,female',
            'email' => 'sometimes|email|unique:users,email,' . $this->teacher->user_id, // الآن استخدم $this->teacher
            'password' => 'sometimes|string|min:6',
        ];
    }

   
}
