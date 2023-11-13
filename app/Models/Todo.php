<?php

namespace App\Models;

use App\Enums\TodoStatus;
use App\Observers\TodoObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => TodoStatus::class,
    ];

    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
