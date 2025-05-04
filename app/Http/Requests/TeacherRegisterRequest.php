<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'specialization' => 'required|string|max:255',
            'Previous_experiences' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'role' => 'required|in:teacher,student',

        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'الاسم الأول مطلوب.',
            'last_name.required' => 'اسم العائلة مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'specialization.required' => 'التخصص مطلوب.',
            'gender.required' => 'الجنس مطلوب.',
            'role.required' => 'الدور مطلوب.',

            'gender.in' => 'الجنس يجب أن يكون male أو female.',
            'role.in' => 'الدور يجب أن يكون teacher أو student.',

            'profile_image.image' => 'يجب رفع صورة سليمة.',
            'profile_image.mimes' => 'امتدادات الصور المسموحة: jpeg, png, jpg, gif.',
        ];
    }
}
