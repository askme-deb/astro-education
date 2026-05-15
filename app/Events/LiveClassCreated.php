<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveClassCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $liveClass;

    public function __construct($liveClass)
    {
        $this->liveClass = $liveClass;
    }

    public function broadcastOn()
    {
        return [
            new Channel('live-classes'),
            new Channel('live-classes.' . $this->liveClass['course_id'])
        ];
    }

    public function broadcastAs()
    {
        return 'live-class.created';
    }
}
