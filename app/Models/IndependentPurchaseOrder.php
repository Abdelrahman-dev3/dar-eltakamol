<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndependentPurchaseOrder extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_PUBLISHED = 1;
    public const STATUS_REVIEW = self::STATUS_PUBLISHED;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CLOSED = 3;
    public const STATUS_CANCELLED = self::STATUS_CLOSED;

    protected $fillable = [
        'contributor_id',
        'count',
        'amount_per_share',
        'notes',
        'status',
        'requested_at',
        'published_at',
        'closed_at',
    ];

    protected $casts = [
        'count' => 'decimal:2',
        'amount_per_share' => 'decimal:2',
        'requested_at' => 'datetime',
        'published_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function contributor(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'contributor_id');
    }

    public function sellOffers(): HasMany
    {
        return $this->hasMany(SellShares::class, 'independent_purchase_order_id');
    }

    public function getStatusText(): string
    {
        return match ((int) $this->status) {
            self::STATUS_PENDING => 'قيد الانتظار',
            self::STATUS_PUBLISHED => 'منشور',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CLOSED => 'مغلق',
            default => 'غير محدد',
        };
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) $this->count * (float) $this->amount_per_share;
    }

    public function getAcceptedSharesAttribute(): float
    {
        if ($this->relationLoaded('sellOffers')) {
            return (float) $this->sellOffers->sum(fn (SellShares $offer) => (float) $offer->accepted_count);
        }

        return (float) $this->sellOffers()->sum('accepted_count');
    }

    public function getRemainingSharesAttribute(): float
    {
        return max(0, (float) $this->count - $this->accepted_shares);
    }

    public function hasPendingSellOffers(): bool
    {
        return $this->sellOffers()
            ->where('independent_offer_status', SellShares::INDEPENDENT_STATUS_PENDING)
            ->exists();
    }

    public function canBeClosed(): bool
    {
        $offersCount = $this->sellOffers()->count();

        if ($offersCount === 0) {
            return true;
        }

        return !$this->hasPendingSellOffers() && $this->accepted_shares <= 0;
    }
}
