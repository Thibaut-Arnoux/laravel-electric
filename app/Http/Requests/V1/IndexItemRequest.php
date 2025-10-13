<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\ElectricRequest;
use App\Models\Item;
use App\Rules\DelimitedExistArrayValues;

class IndexItemRequest extends ElectricRequest
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
            'columns' => ['sometimes', new DelimitedExistArrayValues(arrayUsed: Item::getShape())],
        ];
    }
}
