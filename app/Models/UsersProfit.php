<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsersProfit extends Model
{
    use HasFactory;

    protected $fillable = [
        'profits_id',
        'amount',
        'contributor_id',
        'payment_date',
        'is_paid',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_paid' => 'boolean',
        'payment_date' => 'datetime',
    ];

    /**
     * Get the contributor that owns the profit.
     */
    public function contributor(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'contributor_id');
    }

    /**
     * Get the profit type.
     */
    public function profit(): BelongsTo
    {
        return $this->belongsTo(Profit::class, 'profits_id');
    }

    /**
     * Scope to get paid profits.
     */
    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    /**
     * Scope to get unpaid profits.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }
}
