<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class ElectricRequest extends FormRequest
{
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
            'cursor' => ['sometimes', 'string'],
            'live' => ['sometimes', 'boolean'],
        ];
    }
}
