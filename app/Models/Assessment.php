<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $primaryKey = 'assessment_id';
    protected $fillable = [
        'user_id', 'mission_id', 'assessment_type',
        'score', 'accuracy_percentage', 'taken_at',
    ];

    protected $casts = [
        'taken_at'             => 'datetime',
        'accuracy_percentage'  => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function mission()
    {
        return $this->belongsTo(Mission::class, 'mission_id', 'mission_id');
    }
}
