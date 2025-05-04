<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'name' => 'nullable|string|max:255|unique:categories,name,' . $this->route('id'),
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        
    }
    public function messages(): array
{
    return [
        'name.unique' => 'اسم الفئة مستخدم مسبقًا.',
        'image.image' => 'الملف المرفق يجب أن يكون صورة.',
        'image.mimes' => 'امتداد الصورة يجب أن يكون jpeg أو png أو jpg أو gif أو svg.',
        'image.max' => 'أقصى حجم للصورة هو 2 ميغابايت.',
    ];
}
}
