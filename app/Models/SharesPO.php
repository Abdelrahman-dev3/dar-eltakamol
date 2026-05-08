<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SharesPO extends Model
{
    use HasFactory;

    public const PO_STATUS_PENDING = 0;
    public const PO_STATUS_REVIEW = 1;
    public const PO_STATUS_COMPLETED = 2;

    protected $table = 'shares_poes';

    protected $fillable = [
        'user_id',
        'sale_number',
        'count',
        'amount_per_share',
        'accept',
        'insert_date',
        'po_status',
        'transferred_count',
        'defaulted_at',
    ];

    protected $casts = [
        'count' => 'decimal:2',
        'amount_per_share' => 'decimal:2',
        'accept' => 'boolean',
        'insert_date' => 'datetime',
        'transferred_count' => 'decimal:2',
        'defaulted_at' => 'datetime',
    ];

    /**
     * Get the contributor for this PO.
     */
    public function contributor(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'user_id');
    }

    /**
     * Get the sell share for this PO.
     */
    public function sellShare(): BelongsTo
    {
        return $this->belongsTo(SellShares::class, 'sale_number', 'id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(SellShareAllocation::class, 'shares_po_id');
    }

    /**
     * Get the purchase order status label.
     */
    public function getPoStatusText(): string
    {
        return match ((int) $this->po_status) {
            self::PO_STATUS_PENDING => 'في الانتظار',
            self::PO_STATUS_REVIEW => 'قيد المراجعة',
            self::PO_STATUS_COMPLETED => 'مكتمل',
            default => 'غير محدد',
        };
    }

    /**
     * Get the total value for the purchase order.
     */
    public function getTotalAmountAttribute(): float
    {
        return (float) $this->count * (float) $this->amount_per_share;
    }
}
