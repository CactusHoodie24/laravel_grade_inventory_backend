<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
           'item_id',
           'quantity',
           'type', 
           'warehouse_id',
           'reference'// whatever other fields your transactions table has    
    ];
    //
    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function warehouse() {
        return $this->belongsTo(warehouse::class);
    }
}

