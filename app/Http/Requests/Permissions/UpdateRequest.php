<?php

namespace App\Http\Requests\Permissions;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class UpdateRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('permissions')->ignore($this->permission),
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
