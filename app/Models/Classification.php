<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'daily_point_limit',
    ];
    
    public function classificationLimits()
    {
        return $this->hasMany(classificationLimit::class);
    }
}
