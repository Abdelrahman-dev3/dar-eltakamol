<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Get the categories for the user.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'user_category')
                    ->withTimestamps();
    }
}
