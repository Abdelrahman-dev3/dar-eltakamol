<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'question',
        'poll_type',
        'meeting_id',
        'start_date',
        'end_date',
        'is_active',
        'created_date',
        'created_by',
        'zoom_meeting_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the poll questions for the poll (new system with multiple questions).
     */
    public function questions(): HasMany
    {
        return $this->hasMany(PollQuestion::class)->orderBy('order');
    }

    /**
     * Get the poll options for the poll (old system - direct options).
     */
    public function pollOptions(): HasMany
    {
        return $this->hasMany(PollOption::class, 'poll_id');
    }

    /**
     * Get the poll answers for the poll.
     */
    public function pollAnswers(): HasMany
    {
        return $this->hasMany(PollAnswer::class, 'poll_id');
    }

    /**
     * Get the user who created the poll.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the zoom meeting associated with this poll (old system).
     */
    public function zoomMeeting(): BelongsTo
    {
        return $this->belongsTo(ZoomMeeting::class, 'zoom_meeting_id');
    }

    /**
     * Get the meeting associated with this poll (new system).
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    /**
     * Get the users who are referenced to answer this poll.
     */
    public function referencedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'poll_users', 'poll_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Check if the poll is currently active.
     */
    public function isCurrentlyActive(): bool
    {
        $now = now();
        return $this->is_active && 
               $this->start_date <= $now && 
               $this->end_date >= $now;
    }

    /**
     * Get the total votes for this poll.
     */
    public function getTotalVotesAttribute(): int
    {
        return $this->pollOptions()->sum('votes');
    }
}
