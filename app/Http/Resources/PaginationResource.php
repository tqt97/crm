<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  mixed $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $data = empty($this->links()->getData()['elements'])
        ? collect([])
        : collect($this->links()->getData()['elements']);

        $flattenedArray = $data->flatten()->all();

        return [
            'data' => $this->items(),
            'paginate' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
                'links' => $flattenedArray,
            ],
        ];
    }
}
