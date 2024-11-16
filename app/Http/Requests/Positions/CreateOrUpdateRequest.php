<?php

namespace App\Http\Requests\Positions;

use App\Http\Requests\BaseApiRequest;

class CreateOrUpdateRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'status' => [
                'nullable',
                'integer'
            ]
        ];
    }
}
