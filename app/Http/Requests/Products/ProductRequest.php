<?php

namespace App\Http\Requests\Products;

use Illuminate\Validation\Rule;
use App\Enums\ProductStatus;
use App\Http\Requests\BaseApiRequest;

class ProductRequest extends BaseApiRequest
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
            ],
            'product_code' => [
                'required',
                'max:255',
                Rule::unique('products', 'product_code')->ignore($this->route('product')),
            ],
            'serial_number' => [
                'nullable',
                'max:255',
                Rule::unique('products', 'serial_number')->ignore($this->route('product')),
            ],
            'parent_product_id' => [
                'nullable',
                'integer',
                'exists:products,id,parent_product_id,0',
            ],
            'status' => [
                Rule::enum(ProductStatus::class),
            ],
            'purchased_date' => [
                'required',
                'date',
                'date_format:Y-m-d',
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'attribute_value' => [
                'nullable',
                'array',
            ],
            'attribute_value.*.category_attribute_id' => [
                'required',
                'integer',
                'exists:category_attributes,id',
            ],
            'attribute_value.*.value' => [
                'required',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('custom_validation.product.name'),
            'product_code' => __('custom_validation.product.product_code'),
            'serial_number' => __('custom_validation.product.serial_number'),
            'parent_product_id' => __('custom_validation.product.parent_product_id'),
            'status' => __('custom_validation.product.status'),
            'purchased_date' => __('custom_validation.product.purchased_date'),
            'category_id' => __('custom_validation.product.category_id'),
            'attribute_value' => __('custom_validation.product.attribute_value'),
            'attribute_value.*.category_attribute_id' => __('custom_validation.product.category_attribute_id'),
            'attribute_value.*.value' => __('custom_validation.product.attribute_value'),
        ];
    }
}
