<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\DepartmentFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public $timestamps = true;

    protected $table = 'departments';

    /**
     * Scope Filter
     *
     * @param  mixed $query
     * @param  mixed $request
     * @return void
     */
    public function scopeFilter($query, $request)
    {
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->input('name')}%");
        }

        return $query;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return DepartmentFactory::new();
    }

    /**
     * Position HasMany
     *
     * @return HasMany
     */
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
}
