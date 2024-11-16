<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SpatieLogsActivity;

use App\Models\BaseModel;

class Category extends BaseModel
{
    use SpatieLogsActivity;
    use SoftDeletes;

    protected $table = 'categories';

    /**
     * The categories that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    protected $hidden = [
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];
 
    /**
     * Category Attributes
     *
     * @return HasMany
     */
    public function categoryAttributes(): HasMany
    {
        return $this->hasMany(CategoryAttribute::class);
    }
}
