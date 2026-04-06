<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupplyService;
use Illuminate\Support\Facades\Log;

class SupplyController extends Controller
{
    //
    public function store(Request $request, SupplyService $service)
{
    Log::info('Incoming request debug', [
        'cookies' => request()->cookies->all(),
        'headers' => request()->headers->all(),
        'session' => session()->all(),
        'payload' => $request->all(),
    ]);

    $validated = $request->validate([
        'warehouse_id' => 'required|exists:warehouses,id',
        'date_received'=> 'required|date',
        'items'        => 'required|array',
        'items.*.item_id' => 'required|exists:items,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    $supply = $service->createSupply($validated);

    return response()->json($supply, 201);
}
}
