<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyItem extends Model
{
    //
    protected $fillable = [
        'supply_id',
        'item_id',
        'quantity',
        'unit',
        'description'
    ];

    public function supply()
{
    return $this->belongsTo(Supply::class);
}

public function item()
{
    return $this->belongsTo(Item::class);
}
}
