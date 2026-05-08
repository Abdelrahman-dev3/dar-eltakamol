<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellShareSettlement extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ALLOCATED = 'allocated';
    public const STATUS_PARTIALLY_PAID = 'partially_paid';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'sell_share_id',
        'created_by',
        'status',
        'method',
        'offered_count',
        'allocated_count',
        'transferred_count',
        'posted_at',
        'notes',
    ];

    protected $casts = [
        'offered_count' => 'decimal:2',
        'allocated_count' => 'decimal:2',
        'transferred_count' => 'decimal:2',
        'posted_at' => 'datetime',
    ];

    public function sellShare(): BelongsTo
    {
        return $this->belongsTo(SellShares::class, 'sell_share_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(SellShareAllocation::class, 'settlement_id');
    }
}
