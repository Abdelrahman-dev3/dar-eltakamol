<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContributorMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'movement_type',
        'from_contributor_id',
        'to_contributor_id',
        'shares_count',
        'amount_per_share',
        'from_balance_before',
        'from_balance_after',
        'to_balance_before',
        'to_balance_after',
        'description',
        'shares_trans_id',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'movement_type' => 'integer',
        'shares_count' => 'decimal:2',
        'amount_per_share' => 'decimal:2',
        'from_balance_before' => 'decimal:2',
        'from_balance_after' => 'decimal:2',
        'to_balance_before' => 'decimal:2',
        'to_balance_after' => 'decimal:2',
    ];

    public function fromContributor(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'from_contributor_id');
    }

    public function toContributor(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'to_contributor_id');
    }

    public function sharesTrans(): BelongsTo
    {
        return $this->belongsTo(SharesTrans::class, 'shares_trans_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getMovementTypeText(): string
    {
        return match ($this->movement_type) {
            SharesTrans::TRANS_TYPE_BUY => 'شراء',
            SharesTrans::TRANS_TYPE_SELL => 'بيع',
            SharesTrans::TRANS_TYPE_TRANSFER => 'مناقلة',
            SharesTrans::TRANS_TYPE_DIVIDEND => 'توزيعات',
            default => 'غير محدد',
        };
    }
}
