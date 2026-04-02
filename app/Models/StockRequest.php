<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    //
    protected $fillable = [
        'item_id',
        'warehouse_id',
        'quantity',
        'status',
        'requested_by',
        'approved_by'
    ];
    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function requester() 
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function warehouse()
    {
        return $this->belongsToMany(warehouse::class);
    }
}
