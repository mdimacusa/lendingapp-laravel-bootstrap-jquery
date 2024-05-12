<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class EventNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $category;
    public $message;
    public $amount;
    public $reference;
    public $name;
    public $client_id;
    public $processed_by;

    public function __construct($category,$message,$amount,$reference,$name,$client_id,$processed_by)
    {
        $this->category     = $category;
        $this->message      = $message;
        $this->amount       = $amount;
        $this->reference    = $reference;
        $this->name         = $name;
        $this->client_id    = $client_id;
        $this->processed_by = $processed_by;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('borrow-channel'),
        ];
    }

    public function broadcastAs()
    {
        return 'borrow-event';
    }
}
