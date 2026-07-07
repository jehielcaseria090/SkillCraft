<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strand extends Model
{
    use HasFactory;

    protected $primaryKey = 'strand_id';
    protected $fillable = ['strand_name', 'description'];

    public function modules()
    {
        return $this->hasMany(Module::class, 'strand_id', 'strand_id');
    }

    public function performanceDashboards()
    {
        return $this->hasMany(PerformanceDashboard::class, 'strand_id', 'strand_id');
    }
}
