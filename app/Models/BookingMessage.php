<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingMessage extends Model
{
    public const AUTHOR_ADMIN = 'admin';
    public const AUTHOR_CONTRIBUTOR = 'contributor';

    protected $fillable = [
        'booking_id',
        'user_id',
        'author_type',
        'message',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAuthorLabelAttribute(): string
    {
        return $this->author_type === self::AUTHOR_ADMIN ? 'الإدارة' : 'المساهم';
    }
}
