<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'open',
        'priority' => 'medium',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // scope filter status
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // scope filter priority
    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    // status bagde
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'open' => 'bg-warning',
            'in_progress' => 'bg-info',
            'closed' => 'bg-success',
            default => 'bg-secondary',
        };
    }

    // priority badge
    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            'low' => 'bg-secondary',
            'medium' => 'bg-primary',
            'high' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
