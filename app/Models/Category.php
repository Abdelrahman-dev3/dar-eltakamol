<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    /**
     * The users that belong to this category.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_category')
            ->withTimestamps();
    }

    /**
     * The permissions that belong to this category.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'category_permission')
            ->withTimestamps();
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all descendants recursively.
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Check if this is a parent category (has no parent).
     */
    public function isParent(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Check if this is a child category (has a parent).
     */
    public function isChild(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Get the full category path (Parent > Child).
     */
    public function getFullNameAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->name . ' > ' . $this->name;
        }
        return $this->name;
    }

    /**
     * Get the category name in uppercase.
     */
    public function getNameUpperAttribute(): string
    {
        return strtoupper($this->name);
    }

    /**
     * Get the number of users in this category.
     */
    public function getUsersCountAttribute(): int
    {
        return $this->users()->count();
    }
}

