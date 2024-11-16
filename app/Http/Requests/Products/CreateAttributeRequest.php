<?php

namespace App\Http\Requests\Products;

use App\Enums\AttributeDataType;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class CreateAttributeRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'data_type' => [
                'required',
                'integer',
            ],
            'value_attributes' => [
                'array',
                Rule::requiredIf(request()->input('data_type') === AttributeDataType::SELECT_BOX->value)
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
