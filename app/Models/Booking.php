<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
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

    /**
     * Get available booking statuses.
     */
    public static function getStatuses(): array
    {
        return [
            'pending' => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'cancelled' => 'ملغي',
            'completed' => 'مكتمل',
            'no_show' => 'لم يحضر',
            'rescheduled' => 'تم إعادة الجدولة',
        ];
    }

    /**
     * Get status label with color.
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'pending' => '<span class="label label-warning">قيد الانتظار</span>',
            'confirmed' => '<span class="label label-success">مؤكد</span>',
            'cancelled' => '<span class="label label-danger">ملغي</span>',
            'completed' => '<span class="label label-info">مكتمل</span>',
            'no_show' => '<span class="label label-default">لم يحضر</span>',
            'rescheduled' => '<span class="label label-primary">تم إعادة الجدولة</span>',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class , 'service_id');
    }
}
