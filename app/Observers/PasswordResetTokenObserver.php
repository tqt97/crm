<?php

namespace App\Observers;

use App\Notifications\PasswordResetNotification;

class PasswordResetTokenObserver
{
    /**
     * Handle the PasswordResetToken "created" event.
     *
     * @param  \App\Models\PasswordResetToken  $user
     * @return void
     */
    public function created($user)
    {
        $user->notify(new PasswordResetNotification($user->token));
    }

    /**
     * Handle the PasswordResetToken "updated" event.
     *
     * @param  \App\Models\PasswordResetToken  $user
     * @return void
     */
    public function updated($user)
    {
        $user->notify(new PasswordResetNotification($user->token));
    }
}
