<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostCommented extends Notification
{
    use Queueable;
    public $commenter;
    public $comment; // YANGI

    // Endi funksiya ikkita narsa qabul qiladi: Odam va Uning komenti
    public function __construct($commenter, $comment)
    {
        $this->commenter = $commenter;
        $this->comment = $comment; 
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'comment',
            'user_id' => $this->commenter->id,
            'name' => $this->commenter->name,
            'username' => $this->commenter->username,
            'avatar' => $this->commenter->avatar,
            'message' => 'sizning postingizga izoh qoldirdi:', // Biroz o'zgardi
            'comment_text' => $this->comment->body, // YANGI: Komment matni
        ];
    }
}