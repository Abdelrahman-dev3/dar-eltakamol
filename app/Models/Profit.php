<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profit extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'end_date',
        'amount',
        'confirmed',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'confirmed' => 'boolean',
        'date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the user profits for this profit type.
     */
    public function usersProfits(): HasMany
    {
        return $this->hasMany(UsersProfit::class, 'profits_id');
    }

    /**
     * Scope to get confirmed profits.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('confirmed', true);
    }
}
