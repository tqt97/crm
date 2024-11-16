<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DragonCode\Support\Helpers\Str;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPosition extends Pivot
{
    protected $table = 'user_positions';
}
