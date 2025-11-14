<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentLoginAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public $loginTime;
    public $ipAddress;

    /**
     * Create a new notification instance.
     */
    public function __construct($loginTime, $ipAddress)
    {
        $this->loginTime = $loginTime;
        $this->ipAddress = $ipAddress;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Successful Login Alert')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have successfully logged into your student account.')
            ->line('Login Time: ' . $this->loginTime)
            ->line('IP Address: ' . $this->ipAddress)
            ->line('If this was you, you can ignore this message.')
            ->line('If you did not log in, please contact support immediately.');
    }
}
