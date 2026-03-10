<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SharesPO extends Model
{
    use HasFactory;

    protected $table = 'shares_poes';

    protected $fillable = [
        'user_id',
        'sale_number',
        'count',
        'amount_per_share',
        'accept',
        'insert_date',
        'po_status',
    ];

    protected $casts = [
        'count' => 'decimal:2',
        'amount_per_share' => 'decimal:2',
        'accept' => 'boolean',
        'insert_date' => 'datetime',
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
        return $this->belongsTo(SellShares::class, 'sale_number');
    }
}
