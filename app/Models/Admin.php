<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'created_by',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Role constants
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ORIGIN_ADMIN = 'origin_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_MODERATOR = 'moderator';

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isOriginAdmin()
    {
        return $this->role === self::ROLE_ORIGIN_ADMIN;
    }

    public function isAdmin()
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ORIGIN_ADMIN, self::ROLE_ADMIN]);
    }

    public function isModerator()
    {
        return $this->role === self::ROLE_MODERATOR || $this->isAdmin();
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    // Helper method to get role badge color
    public function getRoleBadgeClass()
    {
        return match($this->role) {
            self::ROLE_SUPER_ADMIN => 'badge bg-primary',
            self::ROLE_ORIGIN_ADMIN => 'badge bg-success',
            self::ROLE_ADMIN => 'badge bg-info',
            self::ROLE_MODERATOR => 'badge bg-warning',
            default => 'badge bg-secondary',
        };
    }

    // Helper method to get role display name
    public function getRoleDisplayName()
    {
        return match($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ORIGIN_ADMIN => 'Origin Admin',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_MODERATOR => 'Moderator',
            default => ucfirst($this->role),
        };
    }
}