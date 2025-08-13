<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBalance extends Model
{
    protected $fillable = [
        'employee_id',
        'current_points',
        'last_recharge_date',
    ];

    protected $casts = [
        'last_recharge_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
