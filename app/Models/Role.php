<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Artisan;
use App\Traits\ScopeEloquent;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasFactory;
    use ScopeEloquent;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * Booted
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Artisan::call("permission:cache-reset");
        });
        static::deleted(function () {
            Artisan::call("permission:cache-reset");
        });
    }

    /**
     * Scope Filter
     *
     * @param  mixed $query
     * @param  mixed $request
     * @return void
     */
    public function scopeFilter($query, $request)
    {
        if ($request->filled('search_name')) {
            $query->where('name', 'like', "%{$request->input('search_name')}%");
        }

        return $query;
    }
}
