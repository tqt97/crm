<?php

namespace App\Http\Requests\Products;

use App\Enums\UserType;
use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class CategoryRequest extends BaseApiRequest
{
    protected $table = 'categories';

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules =  [
            'name' => [
                'required',
                'max:255',
            ],
            'code' => [
                'required',
                'max:20',
            ],
        ];
        $method = request()->getMethod();

        switch ($method) {
            case 'POST':
                $rules['name'][] = Rule::unique($this->table, 'name');
                $rules['code'][] = Rule::unique($this->table, 'code');
                break;
            break;
            case 'PUT':
            case 'PATCH':
                $rules['name'][] = Rule::unique($this->table, 'name')->ignore($this->route('category'));
                $rules['code'][] = Rule::unique($this->table, 'code')->ignore($this->route('category'));
                break;
            break;
            default:
                $rules;
            break;
        }
        return $rules;
    }
}
