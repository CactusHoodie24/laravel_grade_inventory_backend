<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'unit',
        'quantity',
        'supplier_id'
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function requests() {
        return $this->hasMany(StockRequest::class);
    }

    public function warehouses() {
        return $this->belongsToMany(Warehouse::class)
         ->withPivot('quantity')
         ->withTimestamps();
    }
}
