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

        if ($this->has('live_sse')) {
            $this->merge([
                'live_sse' => filter_var($this->live_sse, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the eletric request
     * columns and subset__order_by validation are defined in child request classes with model-specific shape validation
     *
     * @see https://github.com/electric-sql/electric/blob/main/packages/typescript-client/src/constants.ts
     * @see https://github.com/electric-sql/electric/blob/main/website/electric-api.yaml
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Required parameters
            'offset' => ['required', 'string', 'max:255'],

            // Shape handle (required when offset is not -1 or now)
            'handle' => ['required_unless:offset,-1,now', 'string', 'max:255'],

            // Live updates
            'live' => ['sometimes', 'boolean'],
            'live_sse' => ['sometimes', 'boolean'],
            'cursor' => ['sometimes', 'nullable', 'string', 'max:255'],

            // Replica mode
            'replica' => ['sometimes', 'in:default,full'],

            // Log mode
            'log' => ['sometimes', 'in:full,changes_only'],

            // Subset snapshot parameters (limited for security)
            'subset__limit' => ['sometimes', 'integer', 'min:1', 'max:1000'],
            'subset__offset' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
