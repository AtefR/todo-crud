<?php

namespace App\Enums;

enum TodoStatus: string
{
    case CANCELLED = 'cancelled';
    case INPROGRESS = 'inprogress';
    case COMPLETED = 'completed';
    case HOLD = 'hold';
}
