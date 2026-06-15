<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AmbulanceLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $referral_id;
    public $latitude;
    public $longitude;
    public $user_id;
    public $user_name;

    /**
     * Create a new event instance.
     */
    public function __construct($referral, $latitude, $longitude)
    {
        $this->referral_id = $referral->id;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->user_id = auth()->id();
        $this->user_name = auth()->user()->name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('referral.' . $this->referral_id),
        ];
    }
}
