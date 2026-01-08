<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\ElectricRequest;
use App\Models\ItemUser;
use App\Rules\ValidElectricColumns;
use App\Rules\ValidElectricOrderBy;

class IndexItemUserRequest extends ElectricRequest
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
            'columns' => ['sometimes', new ValidElectricColumns(allowedColumns: ItemUser::getShape())],
            'subset__order_by' => ['required_with:subset__limit,subset__offset', 'string', 'max:255', new ValidElectricOrderBy(allowedColumns: ItemUser::getShape())],
        ];
    }
}
