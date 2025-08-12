<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'machine_id',
        'product_category_id',
        'slot_number',
        'price',
        'product_name',
        'is_available',
    ];
    
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
    
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
