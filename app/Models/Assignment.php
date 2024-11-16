<?php

namespace App\Models;

use App\Builders\AssignmentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
use App\Traits\SpatieLogsActivity;

class Assignment extends BaseModel
{
    use SpatieLogsActivity, SoftDeletes;

    protected $table = 'assignments';

    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'reason_type',
        'description',
        'status',
        'assigned_date',
        'returned_date',
        'deleted_by',
        'deleted_at',
    ];

    public function newEloquentBuilder($query)
    {
        return new AssignmentBuilder($query);
    }

    /**
     * Related User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    /**
     * products
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->BelongsTo(Product::class);
    }
}
