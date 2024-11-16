<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;

class PasswordResetToken extends Model
{
    use Notifiable;

    protected $table = 'password_reset_tokens';

    protected $fillable = [
        'email',
        'token',
    ];
    public $incrementing = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'email';
}
