<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255', 
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ];
    }
    public function messages()
{
    return [
        'name.required' => 'الاسم مطلوب.',
        'name.string' => 'الاسم يجب أن يكون نصًا.',
        'name.max' => 'الاسم يجب أن يكون أقل من 255 حرفًا.',
        'image.mimes' => 'امتداد الصورة يجب أن يكون jpeg أو png أو jpg أو gif أو svg.',
        'image.max' => 'أقصى حجم للصورة هو 2 ميغابايت.',

    ];
}

}
