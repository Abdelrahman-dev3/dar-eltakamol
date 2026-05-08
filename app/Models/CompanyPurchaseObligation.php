<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyPurchaseObligation extends Model
{
    use HasFactory;

    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'sell_share_id',
        'seller_id',
        'shares_count',
        'amount_per_share',
        'total_amount',
        'due_year',
        'annual_percentage',
        'status',
        'payment_kind',
        'appraisers',
        'selected_appraiser',
        'fair_value',
        'valuation_date',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'shares_count' => 'decimal:2',
        'amount_per_share' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'annual_percentage' => 'decimal:2',
        'appraisers' => 'array',
        'fair_value' => 'decimal:2',
        'valuation_date' => 'date',
        'due_date' => 'date',
    ];

    public function sellShare(): BelongsTo
    {
        return $this->belongsTo(SellShares::class, 'sell_share_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'seller_id');
    }
}
