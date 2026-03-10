<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PollAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'poll_option_id',
        'poll_question_id',
        'user_id',
        'answer_date',
    ];

    protected $casts = [
        'answer_date' => 'datetime',
    ];

    /**
     * Get the poll that owns the answer.
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }

    /**
     * Get the poll option that was selected.
     */
    public function pollOption(): BelongsTo
    {
        return $this->belongsTo(PollOption::class, 'poll_option_id');
    }

    /**
     * Get the poll question this answer belongs to (new system).
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(PollQuestion::class, 'poll_question_id');
    }

    /**
     * Get the user who answered the poll.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
