<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PublisherNotifications extends Notification
{
    use Queueable;
    private $notificationData;

    public function __construct($notificationData)
    {
        //
        $this->notificationData = $notificationData;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return $this->notificationData;
    }
}
