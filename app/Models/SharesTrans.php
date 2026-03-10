<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SharesTrans extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'notes',
        'trans_type',
        'posted',
    ];

    protected $casts = [
        'date' => 'datetime',
        'posted' => 'boolean',
        'trans_type' => 'integer',
    ];

    // Transaction Type Constants
    const TRANS_TYPE_BUY = 1;
    const TRANS_TYPE_SELL = 2;
    const TRANS_TYPE_TRANSFER = 3;
    const TRANS_TYPE_DIVIDEND = 4;

    /**
     * Get the share transaction lines for the transaction.
     */
    public function shareTransLines(): HasMany
    {
        return $this->hasMany(ShareTransLine::class, 'trans_id');
    }

    /**
     * Get the transaction type text.
     */
    public function getTransTypeText(): string
    {
        return match($this->trans_type) {
            self::TRANS_TYPE_BUY => 'شراء',
            self::TRANS_TYPE_SELL => 'بيع',
            self::TRANS_TYPE_TRANSFER => 'تحويل',
            self::TRANS_TYPE_DIVIDEND => 'أرباح',
            default => 'غير محدد',
        };
    }

    /**
     * Get the transaction type in Arabic.
     */
    public function getTransTypeArAttribute(): string
    {
        return $this->getTransTypeText();
    }
}