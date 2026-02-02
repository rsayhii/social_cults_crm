<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SystemNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(
        public array $data
    ) {}

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return $this->data;
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->data);
    }
}
