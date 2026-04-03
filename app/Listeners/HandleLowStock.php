<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\LowStockDetected;

class HandleLowStock
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LowStockDetected $event): void
    {
        //
    Log::channel('stack')->warning('low_stock_detected', [
    'item_id' => $event->itemId,
    'warehouse_id' => $event->warehouseId,
    'quantity' => $event->quantity,
    'timestamp' => now()->toDateTimeString()
]);
    }
}
