<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\BaseModel;
use App\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends BaseModel
{
    use SpatieLogsActivity;
    use SoftDeletes;
    use HasFactory;

    protected $table = 'positions';

    protected $fillable = [
        'department_id',
        'title',
        'status'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get user information for locations.
     *
     * @return belongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, UserPosition::class)->using((UserPosition::class));
    }

    /**
     * Get department information for that position
     *
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
