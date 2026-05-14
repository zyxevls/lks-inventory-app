<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'code',
        'description',
        'price',
        'stock',
        'stock_min',
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_min');
    }
}
