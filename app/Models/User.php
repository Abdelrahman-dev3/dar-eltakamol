<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'id_number',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the contributor associated with the user.
     */
    public function contributor(): HasOne
    {
        return $this->hasOne(Contributor::class, 'user_id');
    }

    /**
     * Get the groups for the user.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(AppGroup::class, 'app_users_groups', 'user_id', 'group_id')
            ->withTimestamps();
    }

    /**
     * Get the permissions assigned directly to the user.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user')
            ->withTimestamps();
    }

    /**
     * Get the categories for the user.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'user_category')
            ->withTimestamps();
    }

    /**
     * Get the departments for the user.
     */
    public function departments(): BelongsToMany
    {
        return $this->categories()->whereNotNull('categories.parent_id');
    }

    /**
     * Get the assigned department for the user.
     */
    public function getDepartmentAttribute(): ?Category
    {
        if ($this->relationLoaded('departments')) {
            return $this->departments->first();
        }

        return $this->departments()->with('parent')->first();
    }

    /**
     * Get all department names as a readable string.
     */
    public function getDepartmentNamesAttribute(): string
    {
        $departments = $this->relationLoaded('departments')
            ? $this->departments
            : $this->departments()->with('parent')->get();

        return $departments->pluck('name')->filter()->implode('، ');
    }

    /**
     * Get all company names as a readable string.
     */
    public function getCompanyNamesAttribute(): string
    {
        $departments = $this->relationLoaded('departments')
            ? $this->departments
            : $this->departments()->with('parent')->get();

        return $departments
            ->pluck('parent.name')
            ->filter()
            ->unique()
            ->implode('، ');
    }

    /**
     * Get all inherited permissions from departments and linked contributor.
     */
    public function getInheritedPermissionsAttribute(): Collection
    {
        $departments = $this->relationLoaded('departments')
            ? $this->departments
            : $this->departments()->with('permissions')->get();

        $departments->loadMissing('permissions');

        $permissions = $departments->flatMap(function ($department) {
            return $department->permissions;
        });

        $contributor = $this->relationLoaded('contributor')
            ? $this->contributor
            : $this->contributor()->with('departments.permissions')->first();

        if ($contributor) {
            $contributor->loadMissing('departments.permissions');

            $permissions = $permissions->merge(
                $contributor->departments->flatMap(function ($department) {
                    return $department->permissions;
                })
            );
        }

        return $permissions
            ->unique('id')
            ->sortBy('name')
            ->values();
    }

    /**
     * Get all effective permissions for the user.
     */
    public function getEffectivePermissionsAttribute(): Collection
    {
        $directPermissions = $this->relationLoaded('permissions')
            ? $this->permissions
            : $this->permissions()->get();

        return $directPermissions
            ->merge($this->inherited_permissions)
            ->unique('id')
            ->sortBy('name')
            ->values();
    }

    /**
     * Check whether the user has a permission directly or through inherited departments.
     */
    public function hasPermission(string $slug): bool
    {
        return $this->effective_permissions->contains(function (Permission $permission) use ($slug) {
            return $permission->slug === $slug;
        });
    }

    /**
     * Check whether the user has at least one of the provided permissions.
     */
    public function hasAnyPermission(string|array $slugs): bool
    {
        $slugs = collect(is_array($slugs) ? $slugs : [$slugs])
            ->filter(fn ($slug) => is_string($slug) && $slug !== '')
            ->unique()
            ->values();

        if ($slugs->isEmpty()) {
            return true;
        }

        return $this->effective_permissions->contains(function (Permission $permission) use ($slugs) {
            return $slugs->contains($permission->slug);
        });
    }

    public function isAdmin(): bool
    {
        if ($this->email === 'admin@board.com') {
            return true;
        }

        $rolesCount = AppRole::query()->count();

        if ($rolesCount === 0) {
            return false;
        }

        return $this->groups()
            ->whereHas('roles', function ($query): void {
                $query->where('app_group_roles.group_permission', true);
            }, '=', $rolesCount)
            ->exists();
    }

    /**
     * Get direct permission names as a readable string.
     */
    public function getDirectPermissionNamesAttribute(): string
    {
        $permissions = $this->relationLoaded('permissions')
            ? $this->permissions
            : $this->permissions()->get();

        return $permissions->pluck('display_name')->filter()->implode('، ');
    }

    /**
     * Get effective permission names as a readable string.
     */
    public function getEffectivePermissionNamesAttribute(): string
    {
        return $this->effective_permissions->pluck('display_name')->filter()->implode('، ');
    }
}
