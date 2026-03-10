<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareTransLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'contributor_id',
        'line_notes',
        'count_debit',
        'count_credit',
        'amount_per_share',
        'trans_id',
        'posted',
    ];

    protected $casts = [
        'count_debit' => 'decimal:2',
        'count_credit' => 'decimal:2',
        'amount_per_share' => 'decimal:2',
        'posted' => 'boolean',
    ];

    /**
     * Get the shares transaction that owns the line.
     */
    public function sharesTrans(): BelongsTo
    {
        return $this->belongsTo(SharesTrans::class, 'trans_id');
    }

    /**
     * Get the contributor for this line.
     */
    public function contributor(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'contributor_id');
    }
}
