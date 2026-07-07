<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $primaryKey = 'module_id';
    protected $fillable = ['strand_id', 'module_name', 'competency_area'];

    public function strand()
    {
        return $this->belongsTo(Strand::class, 'strand_id', 'strand_id');
    }

    public function missions()
    {
        return $this->hasMany(Mission::class, 'module_id', 'module_id');
    }
}
