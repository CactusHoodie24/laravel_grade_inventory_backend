<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\StockRequest;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function stockSummary()
    {
        return Item::select('id', 'name', 'quantity')->get();
    }

   public function stockUsage(Request $request)
{
    return Transaction::select(
        'item_id',
        DB::raw('SUM(quantity) as total_used')
    )
    ->where('type', 'OUT')
    ->when($request->start_date, function ($q) use ($request) {
        $q->whereDate('created_at', '>=', $request->start_date);
    })
    ->when($request->end_date, function ($q) use ($request) {
        $q->whereDate('created_at', '<=', $request->end_date);
    })
    ->groupBy('item_id')
    ->get();
}

    public function transactions()
    {
        return Transaction::with('item')
            ->latest()
            ->get();
    }

    public function requestStats()
    {
        return response()->json([
            'pending'  => StockRequest::where('status', 'PENDING')->count(),
            'approved' => StockRequest::where('status', 'APPROVED')->count(), // fixed case
            'rejected' => StockRequest::where('status', 'REJECTED')->count()  // fixed case
        ]);
    }
}