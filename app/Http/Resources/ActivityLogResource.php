<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  mixed $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $array = $this->getAttributes();

        if (Arr::has($array, 'properties')) {
            $properties = json_decode(Arr::get($array, 'properties'), true);

            // Remove password information
            self::setAccessToNull($properties, 'password', null);

            // set new value for Properties
            Arr::set($array, 'properties', $properties);
        }

        return $array;
    }

    /**
     * Set Access To Null
     *
     * @param  mixed $array
     * @param  mixed $element
     * @param  mixed $newValue
     * @return void
     */
    protected function setAccessToNull(&$array, $element, $newValue)
    {
        if (is_array($array) && !empty($element)) {
            foreach ($array as $key => &$value) {
                // Check if the key is $element and set its value to $newValue
                if ($element === $key) {
                    $value = $newValue;
                }

                // If the value is an array, call the function recursively
                if (is_array($value)) {
                    self::setAccessToNull($value, $element, $newValue);
                }
            }
        }
    }
}
