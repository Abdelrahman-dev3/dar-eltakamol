<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradingPeriod extends Model
{
    use HasFactory;

    public const PHASE_OFFER = 'offer';
    public const PHASE_PROCESSING = 'processing';
    public const PHASE_PRIVATE = 'private';

    protected $fillable = [
        'year',
        'name',
        'offer_starts_at',
        'offer_ends_at',
        'processing_starts_at',
        'processing_ends_at',
        'private_starts_at',
        'private_ends_at',
        'is_active',
    ];

    protected $casts = [
        'year' => 'integer',
        'offer_starts_at' => 'date',
        'offer_ends_at' => 'date',
        'processing_starts_at' => 'date',
        'processing_ends_at' => 'date',
        'private_starts_at' => 'date',
        'private_ends_at' => 'date',
        'is_active' => 'boolean',
    ];
}
