<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetSmsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string|size:32',
            'activation' => 'required|string|max:20'
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'Token parameter is required',
            'token.size' => 'Invalid token format',
            'activation.required' => 'Activation parameter is required'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            'code' => 'error',
            'message' => $validator->errors()->first()
        ], 400);

        throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
    }
}