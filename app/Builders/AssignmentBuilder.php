<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AssignmentBuilder extends Builder
{
    public function __construct($query)
    {
        parent::__construct($query);
    }

    public function getListWithPagination($request)
    {
        $query = $this->select('assignments.*')
            ->with('product', 'user')
            ->join('products as p', 'p.id', '=', 'assignments.product_id')
            ->join('users as u', 'u.id', '=', 'assignments.user_id');

        $query->when($request->input('search'), function ($q, $value) {
            $q->where(function ($query) use ($value) {
                $query->whereLike('u.name', $value)
                    ->orWhereLike('u.email', $value)
                    ->orWhereLike('p.name', $value)
                    ->orWhereLike('p.serial_number', $value)
                    ->orWhereLike('p.product_code', $value);
            });
        });
        $query->when($request->input('user_id'), function ($q, $value) {
            $q->where('assignments.user_id', $value);
        });
        $query->when($request->input('status'), function ($q, $value) {
            $q->where('assignments.status', $value);
        });
        $query->orderByDesc('assignments.created_at');
        $limit = $request->input('limit', config('erp.per_page'));

        return $query->paginate($limit);
    }
}
