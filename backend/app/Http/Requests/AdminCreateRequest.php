<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCreateRequest extends FormRequest
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
            'name' => 'required|string|max:200|min:3',
            'email' => 'required|unique:users,email,|email|max:200',
            'password1' => 'required|min:8|max:20',
            'password2' => 'required|min:8|max:20|same:password1'
        ];
    }

    public function messages() :array
    {
        return [
            'password1.required' => 'The password field is required.',
            'password2.required' => 'The confirm password field is required.',
            'password2.same' => 'The confirm password field must match password'
        ];
    }
}