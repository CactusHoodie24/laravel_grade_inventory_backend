<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\RequestApproved;
use App\Events\LowStockDetected;
use App\Listeners\LogApprovedRequest;
use App\Listeners\HandleLowStock;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Event::listen(
        RequestApproved::class, 
        LogApprovedRequest::class
       );
       Event::listen(LowStockDetected::class, HandleLowStock::class);
    }
}
