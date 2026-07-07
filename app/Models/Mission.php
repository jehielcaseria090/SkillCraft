<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $primaryKey = 'mission_id';
    protected $fillable = [
        'module_id', 'mission_title', 'scenario_description',
        'max_score', 'difficulty_level',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'module_id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'mission_id', 'mission_id');
    }
}
