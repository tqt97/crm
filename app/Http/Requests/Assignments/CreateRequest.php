<?php

namespace App\Http\Requests\Assignments;

use App\Rules\ProductAssignedRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'assignments' => 'required|array',
            'assignments.*.user_id' => 'required|exists:users,id|distinct',
            'assignments.*.product_ids' => ['required', 'array', new ProductAssignedRule()],
            'assignments.*.product_ids.*' => 'exists:products,id,parent_product_id,0|distinct',
        ];
    }

    public function attributes(): array
    {
        return [
            'assignments' => __('assignments'),
            'assignments.*.user_id' => __('User Id'),
            'assignments.*.product_ids' => __('Products'),
            'assignments.*.product_ids.*' => __('Product'),
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
