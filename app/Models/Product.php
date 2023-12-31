<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description', 
        'buyingprice',
        'sellingprice',
        'category_id',
        'stockquantity'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps(); // Optional, if you want to record timestamps in the pivot table
    }
}
