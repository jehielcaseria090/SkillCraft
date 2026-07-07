<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginSession extends Model
{
    use HasFactory;

    protected $primaryKey = 'session_id';
    protected $fillable   = [
        'user_id', 'email', 'password_hash',
        'login_at', 'logout_at', 'ip_address', 'user_agent',
    ];

    protected $hidden = ['password_hash'];

    protected $casts = [
        'login_at'  => 'datetime',
        'logout_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
