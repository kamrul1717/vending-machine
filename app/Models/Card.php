<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'card_number',
        'employee_id',
        'is_active',
        'issued_date',
        'expired_date',
        'notes',
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
