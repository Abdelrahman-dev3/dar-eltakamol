<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
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
     * Scope companies (top-level memberships).
     */
    public function scopeCompanies($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope departments (memberships under a company).
     */
    public function scopeDepartments($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Check if this membership is a company.
     */
    public function isCompany(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Check if this membership is a department.
     */
    public function isDepartment(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Backward-compatible helpers for older code paths.
     */
    public function isParent(): bool
    {
        return $this->isCompany();
    }

    public function isChild(): bool
    {
        return $this->isDepartment();
    }

    /**
     * Get the full membership path (Company > Department).
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

    /**
     * Get a readable label for the membership level.
     */
    public function getLevelLabelAttribute(): string
    {
        return $this->isCompany() ? 'الشركة' : 'الإدارة';
    }
}

