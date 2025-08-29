<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class ElectricRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('live')) {
            $this->merge([
                'live' => filter_var($this->live, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the eletric request
     *
     * @see https://github.com/electric-sql/electric/blob/main/packages/typescript-client/src/constants.ts
     * @see https://github.com/electric-sql/electric/blob/main/website/electric-api.yaml
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'offset' => ['required', 'string'],
            'handle' => ['required_unless:offset,-1', 'string'],
            'cursor' => ['sometimes', 'nullable', 'string'], // nullable to handle case cursor=& bug on tanstack-db
            'live' => ['sometimes', 'boolean'],
        ];
    }
}
