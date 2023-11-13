<?php

namespace App\Observers;

use App\Events\TodoCreatedEvent;
use App\Events\TodoDeletedEvent;
use App\Events\TodoUpdatedEvent;
use App\Models\Todo;

class TodoObserver
{
    public function created(Todo $todo): void
    {
        if (!$todo->relationLoaded('user'))
            $todo->load('user');

        event(new TodoCreatedEvent($todo));
    }

    public function updated(Todo $todo): void
    {
        if (!$todo->relationLoaded('user'))
            $todo->load('user');

        event(new TodoUpdatedEvent($todo));
    }

    public function deleted(Todo $todo): void
    {
        event(new TodoDeletedEvent($todo->id));
    }

    public function restored(Todo $todo): void
    {
        event(new TodoCreatedEvent($todo->id));
    }

    public function forceDeleted(Todo $todo): void
    {
        event(new TodoDeletedEvent($todo));
    }
}
