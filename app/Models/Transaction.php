<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'card_id',
        'machine_id',
        'slot_id',
        'product_category_id',
        'points_deducted',
        'transaction_time',
        'status',
        'failure_reason',
    ];

    protected $casts = [
        'transaction_time' => 'datetime',
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
