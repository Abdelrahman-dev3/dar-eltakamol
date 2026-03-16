<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_number',
        'phone_num',
        'temp_password',
        'user_id',
        'iban',
        'bank_name',
        'position',
        'profile_picture',
        'share_count_cr',
        'is_board_member',
    ];

    protected $casts = [
        'is_board_member' => 'boolean',
        'share_count_cr' => 'float',
    ];

    /**
     * Get the user that owns the contributor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the sell shares for the contributor.
     */
    public function sellShares(): HasMany
    {
        return $this->hasMany(SellShares::class, 'user_id');
    }

    /**
     * Get the shares purchase orders for the contributor.
     */
    public function sharesPOs(): HasMany
    {
        return $this->hasMany(SharesPO::class, 'user_id');
    }

    /**
     * Get the share transaction lines for the contributor.
     */
    public function shareTransLines(): HasMany
    {
        return $this->hasMany(ShareTransLine::class, 'contributor_id');
    }

    /**
     * Get the user profits for the contributor.
     */
    public function userProfits(): HasMany
    {
        return $this->hasMany(UsersProfit::class, 'contributor_id');
    }

    /**
     * Get the documents for the contributor.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ContributorDocument::class, 'contributor_id');
    }

    /**
     * Get the departments the contributor belongs to.
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_contributor')
            ->withTimestamps();
    }

    /**
     * Get the share count attribute.
     */
    public function getShareCountAttribute(): float
    {
        return $this->share_count_cr ?? 0;
    }

    /**
     * Get the profile picture URL.
     */
    public function getProfilePictureUrlAttribute(): string
    {
        if ($this->profile_picture && \Storage::disk('public')->exists($this->profile_picture)) {
            return \Storage::disk('public')->url($this->profile_picture);
        }
        
        // Return default avatar
        return asset('images/default-avatar.png');
    }

    /**
     * Get contributor initials for avatar.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
        }
        return mb_substr($this->name, 0, 2);
    }

    /**
     * Get the companies related through departments.
     */
    public function getCompaniesAttribute()
    {
        $departments = $this->relationLoaded('departments')
            ? $this->departments
            : $this->departments()->with('parent')->get();

        return $departments
            ->pluck('parent')
            ->filter()
            ->unique('id')
            ->values();
    }

    /**
     * Get the first related company.
     */
    public function getPrimaryCompanyAttribute(): ?Category
    {
        return $this->companies->first();
    }
}
