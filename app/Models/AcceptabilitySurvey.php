<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcceptabilitySurvey extends Model
{
    use HasFactory;

    protected $primaryKey = 'survey_id';
    protected $fillable   = [
        'user_id', 'usability_rating', 'interface_rating',
        'educational_value', 'curriculum_alignment',
        'overall_rating', 'comments', 'submitted_at',
    ];

    protected $casts = [
        'submitted_at'         => 'datetime',
        'usability_rating'     => 'float',
        'interface_rating'     => 'float',
        'educational_value'    => 'float',
        'curriculum_alignment' => 'float',
        'overall_rating'       => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
