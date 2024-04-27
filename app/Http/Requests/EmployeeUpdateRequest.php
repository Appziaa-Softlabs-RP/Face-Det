<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
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
        $id = auth()->guard('api')->id();
        return [
            "name" => "required|string|max:255",
            'email' => [
                'email',
                Rule::unique('employees')->where(function ($query) use ($id) {
                    return $query->where('user_id', $id);
                })->ignore($this->empId, 'source_emp_id'),
            ],
            'empId' => [
                'required',
                'string',
                Rule::exists('employees', 'source_emp_id')->where(function ($query) use ($id) {
                    return $query->where('user_id', $id);
                }),
            ],
            'image' => 'required|image|mimes:jpeg,jpg|max:2048',
        ];
    }
}
