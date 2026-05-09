<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndependentPurchaseOrder extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_REVIEW = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELLED = 3;

    protected $fillable = [
        'contributor_id',
        'count',
        'amount_per_share',
        'notes',
        'status',
        'requested_at',
    ];

    protected $casts = [
        'count' => 'decimal:2',
        'amount_per_share' => 'decimal:2',
        'requested_at' => 'datetime',
    ];

    public function contributor(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'contributor_id');
    }

    public function getStatusText(): string
    {
        return match ((int) $this->status) {
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_REVIEW => 'قيد المراجعة',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
            default => 'غير محدد',
        };
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) $this->count * (float) $this->amount_per_share;
    }
}
