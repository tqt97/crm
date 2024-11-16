<?php

namespace App\Http\Requests\Assignments;

use App\Enums\AssignmentStatus;
use App\Enums\ReasonType;
use App\Http\Requests\BaseApiRequest;

class UpdateRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Get the list of allowed values
        $statuses = implode(',', array_map(fn($status) => $status->value, AssignmentStatus::cases()));
        $reasonType = implode(',', array_map(fn($reasonType) => $reasonType->value, ReasonType::cases()));

        return [
            'assignments' => 'required|array',
            'assignments.*' => 'exists:assignments,id',
            'status' => "required|integer|in:$statuses",
            'reason_type' => "nullable|integer|in:$reasonType",
            'description' => 'nullable|string|max:5000',
            'assigned_date' => 'string|date',
            'returned_date' => 'required|date|after_or_equal:assigned_date',
        ];
    }

    public function attributes(): array
    {
        return [
            'assignments' => __('assignments'),
            'assignments.*' => __('assignments'),
            "status" => __('Status'),
            "reason_type" => __('reason type'),
            "description" => __('description')
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
