<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "category_id" => $this->category_id,
            "category_name" => $this->category->name,
            "product_code" => $this->product_code,
            "serial_number" => $this->serial_number,
            "parent_product_id" => $this->parent_product_id,
            "parent_product_name" => $this->parent->name ?? null,
            "status" => $this->status,
            "description" => $this->description,
            "purchased_date" => $this->purchased_date,
            "attribute_value" => json_decode($this->attribute_value),
        ];
    }
}
