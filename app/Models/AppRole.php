<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AppRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'main_id',
    ];

    protected $casts = [
        'main_id' => 'integer',
    ];

    /**
     * Get the main menu that owns the role.
     */
    public function mainMenu(): BelongsTo
    {
        return $this->belongsTo(MainMenu::class, 'main_id');
    }

    /**
     * Get the groups for the role.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(AppGroup::class, 'app_group_roles', 'role_id', 'group_id')
                    ->withPivot('group_permission')
                    ->withTimestamps();
    }
}