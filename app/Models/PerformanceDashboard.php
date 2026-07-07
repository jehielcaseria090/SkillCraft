<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceDashboard extends Model
{
    use HasFactory;

    protected $table      = 'performance_dashboard';
    protected $primaryKey = 'dashboard_id';
    protected $fillable   = [
        'user_id', 'strand_id', 'average_score',
        'missions_completed', 'last_updated',
    ];

    protected $casts = [
        'last_updated'  => 'datetime',
        'average_score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function strand()
    {
        return $this->belongsTo(Strand::class, 'strand_id', 'strand_id');
    }
}
