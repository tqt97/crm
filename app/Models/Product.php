<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_id',
        'product_code',
        'serial_number',
        'parent_product_id',
        'status',
        'name',
        'description',
        'purchased_date',
        'attribute_value',
    ];
    /**
     * Attribute Value format json before save into database
     *
     * @return Attribute
     */
    protected function attributeValue(): Attribute
    {
        return Attribute::make(
            set: fn(array $value) => empty($value) ? null : json_encode($value),
        );
    }

    /**
     * Category
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_product_id');
    }

    /**
     * Parent BelongsTo
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'parent_product_id');
    }
}
