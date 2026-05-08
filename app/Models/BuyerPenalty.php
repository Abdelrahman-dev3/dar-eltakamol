<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuyerPenalty extends Model
{
    use HasFactory;

    public const TYPE_WARNING = 'warning';
    public const TYPE_BAN = 'ban';

    protected $fillable = [
        'user_id',
        'contributor_id',
        'shares_po_id',
        'type',
        'reason',
        'banned_until',
        'created_by',
    ];

    protected $casts = [
        'banned_until' => 'date',
    ];

    public function contributor(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'contributor_id');
    }
}
