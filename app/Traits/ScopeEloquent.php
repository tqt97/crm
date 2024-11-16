<?php

namespace App\Traits;

trait ScopeEloquent
{
    /**
     * Scope function to apply sorting based on request parameters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort($query, $request)
    {
        $routeParams = $request->query();

        $sortParams = array_filter($routeParams, function ($value, $key) {
            return strpos($key, 'sort_') === 0;
        }, ARRAY_FILTER_USE_BOTH);

        $sortParams = array_combine(
            array_map(function ($key) {
                return str_replace('sort_', '', $key);
            }, array_keys($sortParams)),
            array_values($sortParams)
        );

        if (!empty($sortParams)) {
            foreach ($sortParams as $field => $type) {
                if (in_array($field, $this->getFillable()) && in_array(strtolower($type), ['asc', 'desc'])) {
                    $query->orderBy($field, $type);
                }
            }
            return $query;
        } else {
            return $query->defaultSort();
        }
    }

    /**
     * Scope function to apply a pagination limit based on the request.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request|null  $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function scopeApplyLimit($query, $request = null)
    {
        if (!empty($request->limit) && is_numeric($request->limit)) {
            return $query->paginate($request->limit);
        }
        return $query->paginate(config('erp.per_page'));
    }

    /**
     * Scope function to apply a default sorting order.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefaultSort($query)
    {
        return $query->orderBy('id', 'DESC');
    }
}
