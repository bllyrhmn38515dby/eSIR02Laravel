<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $time;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->time = $message->created_at->format('H:i');
    }

    public function broadcastOn(): array
    {
        // Broadcast di channel private atau public untuk ID rujukan tertentu
        // Untuk kemudahan demo, kita pakai Channel public sementara. Di production gunakan PrivateChannel.
        return [
            new Channel('referral.' . $this->message->referral_id),
        ];
    }
    
    public function broadcastAs()
    {
        return 'MessageSent';
    }
}
