<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  mixed $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'email' => $this->email,
            'status' => $this->status ?? 0,
            'employee_code' => $this->employee_code,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender ?? 0,
            'avatar_url' => $this->avatar_url,
            'language' => $this->language,
            'address' => $this->address,
            'departments' => $this->whenLoaded('positions', $this->loadDepartments()),
        ];
    }

    protected function loadDepartments()
    {
        return $this->positions->groupBy('department_id')
            ->map(function ($positions) {
                // Get the first department information as the group information
                $department = $positions->first()->department;

                // Map positions to department information and return it as an array
                return [
                    'id' => $department->id,
                    'name' => $department->name,
                    'positions' => $positions->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'title' => $position->title,
                            'status' => $position->status,
                        ];
                    })->values(),
                ];
            })->values();
    }
}
