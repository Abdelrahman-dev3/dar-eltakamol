<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'module',
    ];

    /**
     * Get the categories that have this permission.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_permission')
                    ->withTimestamps();
    }

    /**
     * Get the departments that have this permission.
     */
    public function departments(): BelongsToMany
    {
        return $this->categories()->whereNotNull('categories.parent_id');
    }
}


