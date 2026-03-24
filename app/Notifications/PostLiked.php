<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    use Queueable;

    public $liker;
    public $post; // YANGI: Postni saqlash uchun

    // Endi 2 ta ma'lumot qabul qiladi
    public function __construct($liker, $post)
    {
        $this->liker = $liker;
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['database']; // Yoki agar real-time qilsangiz ['database', 'broadcast']
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'like',
            'user_id' => $this->liker->id,
            'name' => $this->liker->name,
            'username' => $this->liker->username,
            'avatar' => $this->liker->avatar,
            'message' => 'sizning postingizga layk bosdi',
            
            // MANA SHU IKKI QATOR RASM VA LINK UCHUN JAVOB BERADI
            'post_id' => $this->post->id,
            'post_image' => $this->post->image,
        ];
    }
}