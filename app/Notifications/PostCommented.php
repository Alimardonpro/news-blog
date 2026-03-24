<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostCommented extends Notification
{
    use Queueable;

    public $commenter;
    public $comment;
    public $post; // 1. Postni saqlash uchun o'zgaruvchi

    /**
     * Konstruktor endi 3 ta argument qabul qiladi.
     */
    public function __construct($commenter, $comment, $post)
    {
        $this->commenter = $commenter;
        $this->comment = $comment;
        $this->post = $post; // 2. Postni biriktirish
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Bazaga saqlanadigan ma'lumotlar.
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'comment',
            'user_id' => $this->commenter->id,
            'name' => $this->commenter->name,
            'username' => $this->commenter->username,
            'avatar' => $this->commenter->avatar,
            'message' => 'sizning postingizga izoh qoldirdi',
            'comment_text' => $this->comment->body,
            
            // 3. RASM VA JAVOB UCHUN ENG MUHIM QISMLAR:
            'post_id' => $this->post->id,       // ID bazaga yoziladi
            'post_image' => $this->post->image, // Rasm yo'li bazaga yoziladi
        ];
    }
}