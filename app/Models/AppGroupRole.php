<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppGroupRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'role_id',
        'group_permission',
    ];

    protected $casts = [
        'group_id' => 'integer',
        'role_id' => 'integer',
        'group_permission' => 'integer',
    ];

    /**
     * Get the group that owns the group role.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(AppGroup::class, 'group_id');
    }

    /**
     * Get the role that owns the group role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(AppRole::class, 'role_id');
    }
}