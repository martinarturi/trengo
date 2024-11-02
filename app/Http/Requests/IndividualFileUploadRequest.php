<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class IndividualFileUploadRequest extends FormRequest
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
            'file' => 'required|file|mimes:csv,txt',
            'age' => 'nullable|integer|min:1',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'name' => 'nullable|string',
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $allowedParams = ['file', 'name', 'age', 'city', 'country'];
            $unexpectedParams = array_diff(array_keys($this->all()), $allowedParams);

            if (!empty($unexpectedParams)) {
                $validator->errors()->add('unexpected_parameters', 'The following parameters are not allowed: ' . implode(', ', $unexpectedParams));
            }
        });
    }
}
