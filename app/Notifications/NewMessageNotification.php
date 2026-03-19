<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected $sender;
    protected $msgText;

    // Yuboruvchi va xabar matnini qabul qilib olamiz
    public function __construct($sender, $msgText)
    {
        $this->sender = $sender;
        $this->msgText = $msgText;
    }

    // Faqat bazaga yozish (database)
    public function via($notifiable)
    {
        return ['database'];
    }

    // Bazaga qanday ma'lumot saqlanishini belgilaymiz
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'message', // Xabar turi
            'name' => $this->sender->name,
            'username' => $this->sender->username,
            'avatar' => $this->sender->avatar,
            'message' => 'Sizga yangi xabar yubordi: ' . $this->msgText,
        ];
    }
}