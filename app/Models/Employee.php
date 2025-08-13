<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'employee_code',
        'classification_id',
    ];
    
    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function getCurrentBalanceAttribute()
    {
        return 1000;
    }

    public function latestActiveCard(): HasOne
    {
        return $this->hasOne(Card::class)
                ->where('is_active', true)
                ->whereNull('expired_date')
                ->latestOfMany();        
    }

    public function employeeBalance(): HasOne
    {
        return $this->hasOne(EmployeeBalance::class);
    }

    public function employeeDailyProductLimits(): HasMany
    {
        return $this->hasMany(EmployeeDailyProductLimit::class);
    }

    public function recharges()
    {
        return $this->hasMany(BalanceRechargeLog::class);
    }

}
