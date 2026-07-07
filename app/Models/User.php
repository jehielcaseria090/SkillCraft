<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'username',
        'password',
        'password_hash',
        'confirm_password_hash',
        'role',
        'profile_picture',
        'contact_number',
    ];

    protected $hidden = [
        'password_hash',
        'confirm_password_hash',
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Filament needs 'name' and 'password' columns
    // We map them to our custom columns
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAuthPassword()
    {
        // Use password_hash if password column is empty
        return $this->password ?? $this->password_hash;
    }

    // Allow all admins to access Filament panel
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    // ─── Relationships ────────────────────────────────────────────────
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'user_id', 'user_id');
    }

    public function performanceDashboard()
    {
        return $this->hasMany(PerformanceDashboard::class, 'user_id', 'user_id');
    }

    public function loginSessions()
    {
        return $this->hasMany(LoginSession::class, 'user_id', 'user_id');
    }

    public function acceptabilitySurveys()
    {
        return $this->hasMany(AcceptabilitySurvey::class, 'user_id', 'user_id');
    }
}
