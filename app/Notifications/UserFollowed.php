<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserFollowed extends Notification
{
    use Queueable;
    public $follower;

    public function __construct($follower)
    {
        $this->follower = $follower; // Kim obuna bo'lganini saqlab qolamiz
    }

    public function via($notifiable)
    {
        return ['database']; // Xabarni faqat bazaga (sayt ichiga) yuboramiz
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'follow',
            'user_id' => $this->follower->id,
            'name' => $this->follower->name,
            'username' => $this->follower->username,
            'avatar' => $this->follower->avatar,
            'message' => 'sizga obuna bo\'ldi.',
        ];
    }
}