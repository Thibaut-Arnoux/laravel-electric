<?php

namespace App\Http\Requests\V1;

use App\Enums\ElectricUserColumnsEnum;
use App\Http\Requests\ElectricRequest;
use App\Rules\DelimitedExistEnumValues;

class ElectricUserRequest extends ElectricRequest
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
            ...parent::rules(),
            'columns' => ['sometimes', new DelimitedExistEnumValues(enumUsed: ElectricUserColumnsEnum::class)],
        ];
    }
}
