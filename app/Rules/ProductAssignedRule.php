<?php

namespace App\Rules;

use App\Enums\AssignmentStatus;
use App\Models\Product;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductAssignedRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_array($value)) {
            $productIds = Product::select('id')->whereIn('id', $value)->where('status', AssignmentStatus::ASSIGNED)->pluck('id')->toArray();

            // Check if the Product list has assigned products
            if (count($productIds)) {
                $fail(__('Products ids [:ids] are already assigned.', ['ids' => implode(',', $productIds)]));
            }
        }
    }
}
