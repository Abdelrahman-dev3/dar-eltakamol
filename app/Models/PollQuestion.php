<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'question_text',
        'question_type',
        'order',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the poll that owns the question.
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    /**
     * Get the options for this question.
     */
    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class)->orderBy('id');
    }

    /**
     * Get the answers for this question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(PollAnswer::class);
    }

    /**
     * Get total votes for this question.
     */
    public function getTotalVotesAttribute(): int
    {
        return $this->answers()->count();
    }

    /**
     * Check if question allows multiple answers.
     */
    public function isMultipleChoice(): bool
    {
        return $this->question_type === 'multiple';
    }

    /**
     * Check if question allows single answer.
     */
    public function isSingleChoice(): bool
    {
        return $this->question_type === 'single';
    }
}


