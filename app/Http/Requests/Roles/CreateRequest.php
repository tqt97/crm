<?php

namespace App\Http\Requests\Roles;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class CreateRequest extends BaseApiRequest
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
                'unique:roles,name',
            ],
            'guard_name' => [
                'required',
                'max:255',
                Rule::in(['api']),
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
