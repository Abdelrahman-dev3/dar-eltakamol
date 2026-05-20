<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    public const STATUS_RECEIVED = 'received';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'service_id',
        'user_id',
        'booking_date',
        'booking_time',
        'notes',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime',
    ];

    public static function getStatuses(): array
    {
        return [
            self::STATUS_RECEIVED => 'تم استلام الطلب',
            self::STATUS_IN_PROGRESS => 'قيد التقدم',
            self::STATUS_COMPLETED => 'مكتملة',
            self::STATUS_CANCELLED => 'ملغية',
            'pending' => 'تم استلام الطلب',
            'confirmed' => 'قيد التقدم',
            'no_show' => 'لم يحضر',
            'rescheduled' => 'تم إعادة الجدولة',
        ];
    }

    public function getStatusTextAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getStatusLabelAttribute(): string
    {
        $classes = [
            self::STATUS_RECEIVED => 'warning',
            self::STATUS_IN_PROGRESS => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
            'pending' => 'warning',
            'confirmed' => 'primary',
        ];

        $class = $classes[$this->status] ?? 'default';

        return '<span class="label label-' . $class . '">' . e($this->status_text) . '</span>';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(BookingMessage::class)->orderBy('created_at')->orderBy('id');
    }
}
