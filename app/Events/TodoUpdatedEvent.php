<?php

namespace App\Events;

use App\Models\Todo;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TodoUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Todo $todo,
    )
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('todo.' . $this->todo->id),
        ];
    }

    public function broadcastAs()
    {
        return 'todo-updated';
    }
}
