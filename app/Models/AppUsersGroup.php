<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppUsersGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
    ];

    protected $casts = [
        'group_id' => 'integer',
    ];

    /**
     * Get the user that owns the user group.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the group that owns the user group.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(AppGroup::class, 'group_id');
    }
}