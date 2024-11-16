<?php

namespace App\Http\Requests\Departments;

use App\Http\Requests\BaseApiRequest;

class DepartmentRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('department name'),
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
