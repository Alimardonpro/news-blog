<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $senderId;
    public $readerId;

    public function __construct($messageId, $senderId, $readerId)
    {
        $this->messageId = $messageId;
        $this->senderId = $senderId;
        $this->readerId = $readerId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->senderId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageRead';
    }

    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->messageId,
            'sender_id'  => $this->senderId,
            'reader_id'  => $this->readerId,
        ];
    }
}