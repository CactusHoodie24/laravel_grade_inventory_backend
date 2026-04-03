<?php

namespace App\Jobs;

use App\Models\StockRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendStockApprovalEmail implements ShouldQueue
{
    use Queueable;

      public $tries = 3;           // retry up to 3 times if it fails
    public $timeout = 60;        // kill job if it runs longer than 60 seconds
    public $backoff = [10, 30];  // wait 10s then 30s between retries

    /**
     * Create a new job instance.
     */
    public function __construct(public StockRequest $stockRequest)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Log::info('Sending approval email for request: ' . $this->stockRequest->id);
    }

    public function failed(\Throwable $exception): void
    {
        // Called when all retries are exhausted
        Log::error('Job failed: ' . $exception->getMessage());
    }
}
