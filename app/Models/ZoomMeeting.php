<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZoomMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'meeting_id',
        'meeting_url',
        'meeting_date',
        'password',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
    ];

    /**
     * Get the polls for this zoom meeting.
     */
    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class, 'zoom_meeting_id');
    }
}
