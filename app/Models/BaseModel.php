<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Traits\ScopeEloquent;
use App\Traits\SpatieLogsActivity;

class BaseModel extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use ScopeEloquent;

    protected $timezone;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->timezone = Arr::get(Auth::user(), 'timezone', config('app.timezone'));
    }

    /**
     * Set format & value for the attributes
     */
    public function setAttribute($key, $value)
    {
        // set timezone to UTC before saving db
        if ($this->isDateTimeCast($key)) {
            $value = Carbon::parse($value)->tz('UTC');
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Convert format & value for the attributes
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        // set timezone to UTC before showing
        if ($this->isDateTimeCast($key)) {
            return Carbon::parse($value)->tz($this->timezone)->format('Y-m-d H:i:s');
        }

        return $value;
    }

    /**
     * Checking Datetime
     */
    protected function isDateTimeCast($key)
    {
        return array_key_exists($key, $this->getCasts()) && Arr::get($this->getCasts(), $key) === 'datetime';
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // updating created_by and updated_by when model is created
        static::creating(
            function ($model) {
                if (Schema::hasColumn($model->getTable(), 'created_by') && !$model->isDirty('created_by')) {
                    $model->created_by = Auth::id() ?? 0;
                }
                if (Schema::hasColumn($model->getTable(), 'updated_by') && !$model->isDirty('updated_by')) {
                    $model->updated_by = Auth::id() ?? 0;
                }
            }
        );

        // updating updated_by when model is updated
        static::updating(
            function ($model) {
                if (Schema::hasColumn($model->getTable(), 'updated_by') && !$model->isDirty('updated_by')) {
                    $model->updated_by = Auth::id() ?? 0;
                }
            }
        );

        // updating deleted_by when model is deleted
        static::deleting(
            function ($model) {
                if (Schema::hasColumn($model->getTable(), 'deleted_by') && !$model->isDirty('deleted_by')) {
                    $model->deleted_by = Auth::id() ?? 0;
                    $model->save();
                }
            }
        );
    }
}
