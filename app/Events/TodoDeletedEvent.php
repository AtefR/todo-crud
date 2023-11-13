<?php

namespace App\Events;

use App\Models\Todo;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TodoDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $todoId,
    )
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('todo.' . $this->todoId),
        ];
    }

    public function broadcastAs()
    {
        return 'todo-removed';
    }
}

