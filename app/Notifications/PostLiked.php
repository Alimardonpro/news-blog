<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    use Queueable;
    public $liker;

    public function __construct($liker)
    {
        $this->liker = $liker;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'like',
            'user_id' => $this->liker->id,
            'name' => $this->liker->name,
            'username' => $this->liker->username,
            'avatar' => $this->liker->avatar,
            'message' => 'sizning postingizga layk bosdi.',
        ];
    }
}