<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    protected $timezone;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->timezone = Arr::get(Auth::user(), 'timezone', config('app.timezone'));
    }

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
        ];
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
}
