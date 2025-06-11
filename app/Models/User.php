<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Role constants
     */
    const ROLE_ADMIN = 0;
    const ROLE_STAFF = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'password',
        'role',
        'contact_no',
        'address',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'role' => 'integer'
    ];

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmin(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    /**
     * Scope a query to only include staff users.
     */
    public function scopeStaff(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_STAFF);
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if the user is a staff member.
     */
    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    /**
     * Get the user who created this user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the users created by this user.
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update([
            'last_login_at' => now()
        ]);
    }

    /**
     * Get all available user roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_STAFF => 'Staff'
        ];
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        $nameParts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix
        ]);

        return implode(' ', $nameParts);
    }

    /**
     * Get the role display name
     */
    public function getRoleName(): string
    {
        return $this->role === 0 ? 'Admin' : 'Staff';
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}
