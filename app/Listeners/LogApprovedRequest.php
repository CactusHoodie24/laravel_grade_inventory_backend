<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use App\Events\RequestApproved;
use App\Jobs\SendStockApprovalEmail;

class LogApprovedRequest
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
    public function handle(RequestApproved $event): void
    {
        //
         Log::info('Request Approved', [
        'request_id' => $event->request->id,
        'item_id' => $event->request->item_id,
        'quantity' => $event->request->quantity,
    ]);

    // Dispatch email job to background
    SendStockApprovalEmail::dispatch($event->request);
    }
}
