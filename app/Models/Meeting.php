<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'date',
        'audience_scope',
        'audience_committee',
        'audience_category_id',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * The users that should attend this meeting.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'meeting_user')
            ->withTimestamps();
    }

    /**
     * The attachments for this meeting.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(MeetingAttachment::class);
    }
}

