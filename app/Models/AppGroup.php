<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AppGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the roles for the group.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(AppRole::class, 'app_group_roles', 'group_id', 'role_id')
                    ->withPivot('group_permission')
                    ->withTimestamps();
    }

    /**
     * Get the users for the group.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'app_users_groups', 'group_id', 'user_id')
                    ->withTimestamps();
    }
}