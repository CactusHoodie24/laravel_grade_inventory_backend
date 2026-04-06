<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    //
    protected $fillable = [
        'supplier_id',
        'warehouse_id',
        'date_received',
        'reference'
    ];

    public function items() {
        return $this->hasMany(SupplyItem::class);
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }
}
