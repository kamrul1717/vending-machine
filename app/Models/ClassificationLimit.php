<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassificationLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'classification_id',
        'product_category_id',
        'daily_limit',
    ];
    
    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }
    
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
