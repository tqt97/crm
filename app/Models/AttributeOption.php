<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BaseModel;
use App\Traits\SpatieLogsActivity;

class AttributeOption extends BaseModel
{
    use SpatieLogsActivity;

    protected $table = 'attribute_options';

    /**
     * The categories that are mass assignable.
     */
    protected $fillable = [
        'value'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Attribute BelongsTo
     *
     * @return BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(CategoryAttribute::class);
    }
}
