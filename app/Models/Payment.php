<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'shares_po_number',
        'bank_info',
        'confirmed',
        'transfer_document',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'confirmed' => 'boolean',
        'date' => 'datetime',
    ];

    /**
     * Get the shares PO for this payment.
     */
    public function sharesPO(): BelongsTo
    {
        return $this->belongsTo(SharesPO::class, 'shares_po_number');
    }
}
