<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    //
    public function index() {
        try {
            $suppliers = Supplier::all();
            return response()->json($suppliers, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to get suppliers',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
