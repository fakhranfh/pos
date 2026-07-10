<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Models\Concerns\HasFormattedTimestamps;
use Database\Factories\UserFactory;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'pending_email', 'password', 'profile_photo_path', 'timezone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use CanResetPassword, HasFactory, HasFormattedTimestamps, Notifiable;

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
            'role' => UserRole::class,
        ];
    }

    public function hasVerifiedEmail(): bool
    {
        if (! config('features.email_enabled')) {
            return true;
        }

        return parent::hasVerifiedEmail();
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isManager(): bool
    {
        return $this->role === UserRole::Manager;
    }

    public function isCashier(): bool
    {
        return $this->role === UserRole::Cashier;
    }

    /**
     * Managers have every admin capability except RBAC/user management.
     */
    public function canManageOperations(): bool
    {
        return $this->isAdmin() || $this->isManager();
    }
}
