<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => 'required|string|min:3|max:255|unique:users|not_regex:/@/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'login.unique' => 'This login has already been taken',
            'email.unique' => 'This email has already been taken',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->first();

        throw new HttpResponseException(
            response()->json(['message' => $errors], 422)
        );
    }
}
