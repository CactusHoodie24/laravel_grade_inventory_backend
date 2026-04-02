<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected $fillable = [
        'name',
        'location',
    ];
    
    public function items()
{
    return $this->belongsToMany(Item::class)
        ->withPivot('quantity')
        ->withTimestamps();
}
}
