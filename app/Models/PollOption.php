<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'poll_question_id',
        'option_text',
        'votes',
    ];

    protected $casts = [
        'votes' => 'integer',
    ];

    /**
     * Get the poll that owns the option.
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }

    /**
     * Get the poll question that owns this option (new system).
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(PollQuestion::class, 'poll_question_id');
    }

    /**
     * Get the poll answers for this option.
     */
    public function pollAnswers(): HasMany
    {
        return $this->hasMany(PollAnswer::class, 'poll_option_id');
    }

    /**
     * Get the percentage of votes for this option.
     */
    public function getVotePercentageAttribute(): float
    {
        $totalVotes = $this->poll->total_votes;
        if ($totalVotes == 0) {
            return 0;
        }
        return round(($this->votes / $totalVotes) * 100, 2);
    }
}
