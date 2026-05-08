<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellShareAllocation extends Model
{
    use HasFactory;

    public const TYPE_BUYER = 'buyer';
    public const TYPE_COMPANY = 'company';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PARTIALLY_PAID = 'partially_paid';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'settlement_id',
        'sell_share_id',
        'shares_po_id',
        'seller_id',
        'buyer_id',
        'allocation_type',
        'shares_count',
        'amount_per_share',
        'total_amount',
        'paid_amount',
        'transferred_count',
        'status',
        'posted_at',
    ];

    protected $casts = [
        'shares_count' => 'decimal:2',
        'amount_per_share' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'transferred_count' => 'decimal:2',
        'posted_at' => 'datetime',
    ];

    public function settlement(): BelongsTo
    {
        return $this->belongsTo(SellShareSettlement::class, 'settlement_id');
    }

    public function sellShare(): BelongsTo
    {
        return $this->belongsTo(SellShares::class, 'sell_share_id');
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(SharesPO::class, 'shares_po_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'seller_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'buyer_id');
    }
}
