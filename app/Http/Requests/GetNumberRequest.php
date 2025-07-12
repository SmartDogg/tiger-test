<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetNumberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country' => 'required|string|size:2',
            'service' => 'required|string|max:10',
            'token' => 'required|string|size:32',
            'rent_time' => 'sometimes|integer|min:1|max:24'
        ];
    }

    public function messages(): array
    {
        return [
            'country.required' => 'Country parameter is required',
            'country.size' => 'Country must be 2 characters',
            'service.required' => 'Service parameter is required',
            'token.required' => 'Token parameter is required',
            'token.size' => 'Invalid token format',
            'rent_time.integer' => 'Rent time must be an integer',
            'rent_time.max' => 'Maximum rent time is 24 hours'
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