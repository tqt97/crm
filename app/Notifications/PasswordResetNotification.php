<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetNotification extends Notification
{
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $baseUrl = config('erp.base_url');
        $url = $baseUrl . "/reset-password?token=" . $this->token;
        return (new MailMessage)
            ->subject('[ERP THK] - Password Reset Request')
            ->greeting('Hello!')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->success()
            ->action('Reset Password', url($url))
            ->line('If you did not request a password reset, no further action is required.')
            ->salutation('Best Regards,');
    }
}
