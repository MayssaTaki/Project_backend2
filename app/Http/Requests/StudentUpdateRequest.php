<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->route('student'))) {
            $this->route()->setParameter(
                'student',
                \App\Models\Student::findOrFail($this->route('student'))
            );
        }
    }

    public function rules(): array
    {
        $student = $this->route('student');

        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $student->user_id,
            'password' => 'sometimes|min:6|confirmed',
            'phone' => 'sometimes|string|max:20',
            'country' => 'sometimes|string|max:100',
            'city' => 'sometimes|string|max:100',
            'gender' => 'sometimes|in:male,female',
        ];
    }
}
