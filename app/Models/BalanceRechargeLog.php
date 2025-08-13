<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceRechargeLog extends Model
{
    protected $fillable = [
        'employee_id',
        'recharge_date',
        'points_added',
        'previous_balance',
        'new_balance',
    ];

    protected $casts = [
        'recharge_date' => 'date', // Cast to Carbon for easy date handling
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
