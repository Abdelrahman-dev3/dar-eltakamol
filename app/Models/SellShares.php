<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SellShares extends Model
{
    use HasFactory;

    protected $fillable = [
        'count',
        'amount_per_share',
        'end_date',
        'notes',
        'insert_date',
        'ad_status',
        'user_id',
        'independent_purchase_order_id',
        'independent_offer_status',
        'accepted_count',
        'responded_at',
    ];

    protected $casts = [
        'end_date' => 'datetime',
        'insert_date' => 'datetime',
        'count' => 'float',
        'amount_per_share' => 'float',
        'ad_status' => 'integer',
        'accepted_count' => 'decimal:2',
        'responded_at' => 'datetime',
    ];

    // Ad Status Constants
    const AD_STATUS_INITIAL = 0;
    const AD_STATUS_ACTIVE = 1;
    const AD_STATUS_COMPLETED = 2;
    const AD_STATUS_CANCELLED = 3;

    public const INDEPENDENT_STATUS_PENDING = 'pending';
    public const INDEPENDENT_STATUS_ACCEPTED = 'accepted';
    public const INDEPENDENT_STATUS_PARTIAL = 'partial';
    public const INDEPENDENT_STATUS_REJECTED = 'rejected';

    /**
     * Get the seller (contributor) that owns the sell share.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'user_id');
    }

    public function independentPurchaseOrder(): BelongsTo
    {
        return $this->belongsTo(IndependentPurchaseOrder::class, 'independent_purchase_order_id');
    }

    /**
     * Get the shares purchase orders for the sell share.
     */
    public function sharesPOs(): HasMany
    {
        return $this->hasMany(SharesPO::class, 'sale_number');
    }

    public function settlement(): HasOne
    {
        return $this->hasOne(SellShareSettlement::class, 'sell_share_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(SellShareAllocation::class, 'sell_share_id');
    }

    public function companyPurchaseObligations(): HasMany
    {
        return $this->hasMany(CompanyPurchaseObligation::class, 'sell_share_id');
    }

    /**
     * Get the ad status text.
     */
    public function getAdStatusText(): string
    {
        return match($this->ad_status) {
            self::AD_STATUS_INITIAL => 'مبدئي',
            self::AD_STATUS_ACTIVE => 'نشط',
            self::AD_STATUS_COMPLETED => 'مكتمل',
            self::AD_STATUS_CANCELLED => 'مغلق',
            default => 'غير محدد',
        };
    }

    /**
     * Get the total amount.
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->count * $this->amount_per_share;
    }

    public function getAcceptedTotalAmountAttribute(): float
    {
        return (float) $this->accepted_count * (float) $this->amount_per_share;
    }

    public function getIndependentOfferStatusText(): string
    {
        return match ($this->independent_offer_status) {
            self::INDEPENDENT_STATUS_PENDING => 'قيد انتظار رد صاحب الطلب',
            self::INDEPENDENT_STATUS_ACCEPTED => 'مقبول بالكامل',
            self::INDEPENDENT_STATUS_PARTIAL => 'مقبول جزئياً',
            self::INDEPENDENT_STATUS_REJECTED => 'مرفوض',
            default => 'غير مرتبط',
        };
    }
}
