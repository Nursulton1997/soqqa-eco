<?php

namespace App\Models;

use App\Enums\AdminRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminUser extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'last_login_at',
        'login_attempts',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => AdminRole::class,
        'last_login_at' => 'datetime',
        'login_attempts' => 'integer',
    ];

    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        return in_array($permission, $this->role->permissions());
    }

    public function hasRole(AdminRole $role): bool
    {
        return $this->role === $role;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === AdminRole::SUPER_ADMIN;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function incrementLoginAttempts(): void
    {
        $this->increment('login_attempts');

        if ($this->login_attempts >= 5) {
            $this->update(['status' => 'blocked']);
        }
    }

    public function clearLoginAttempts(): void
    {
        $this->update([
            'login_attempts' => 0,
            'last_login_at' => now(),
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, AdminRole $role)
    {
        return $query->where('role', $role);
    }
}
