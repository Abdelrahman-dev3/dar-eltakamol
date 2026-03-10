<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MainMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'rec_type',
        'sort',
    ];

    protected $casts = [
        'rec_type' => 'integer',
        'sort' => 'integer',
    ];

    /**
     * Get the roles for the main menu.
     */
    public function roles(): HasMany
    {
        return $this->hasMany(AppRole::class, 'main_id');
    }
}