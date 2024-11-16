<?php

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait SpatieLogsActivity
{
    use LogsActivity;

    /**
     * Get Activitylog Options
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = new LogOptions();
        $logOptions->logAll();
        $logOptions->logOnlyDirty();

        return $logOptions;
    }
}
