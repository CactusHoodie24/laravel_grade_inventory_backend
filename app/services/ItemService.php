<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\StockRequest;
use Illuminate\Support\Facades\DB;

class ItemService
{
    // -------------------------------------------------------
    // CRUD
    // -------------------------------------------------------

    public function createItem(array $data): Item
    {
        return Item::create($data);
    }

    public function getItem(int $id): Item
    {
        return Item::findOrFail($id);
    }

    public function updateItem(int $id, array $data): ?Item
    {
        $item = Item::find($id);

        if (!$item) {
            return null;
        }

        $item->update($data);
        return $item;
    }

    public function deleteItem(int $id): bool
    {
        $item = Item::find($id);

        if (!$item) {
            return false; // cleaner than null for a boolean outcome
        }

        $item->delete();
        return true;
    }

    // -------------------------------------------------------
    // Stock Management
    // -------------------------------------------------------

public function addStock(int $itemId, int $warehouseId, int $quantity): array
{
    $this->validateQuantity($quantity);

    return DB::transaction(function () use ($itemId, $warehouseId, $quantity) {
        DB::table('item_warehouse')->updateOrInsert(
            ['item_id' => $itemId, 'warehouse_id' => $warehouseId],
            ['quantity' => DB::raw("quantity + {$quantity}")]
        );

        $transaction = Transaction::create([
            'item_id'      => $itemId,
            'warehouse_id' => $warehouseId,
            'quantity'     => $quantity,
            'type'         => 'IN',
        ]);

        $stock = DB::table('item_warehouse')
            ->where('item_id', $itemId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        return [
            'item_id'      => $itemId,
            'warehouse_id' => $warehouseId,
            'stock'        => $stock->quantity,
            'transaction'  => $transaction,
        ];
    });
}

    public function removeStock(int $itemId, int $warehouseId, int $quantity): void
    {
        $this->validateQuantity($quantity);

        DB::transaction(function () use ($itemId, $warehouseId, $quantity) {
            // Lock the row to prevent race conditions
            $record = DB::table('item_warehouse')
                ->where('item_id', $itemId)
                ->where('warehouse_id', $warehouseId)
                ->lockForUpdate()
                ->first();

            if (!$record || $record->quantity < $quantity) {
                throw new \Exception("Insufficient stock in this warehouse");
            }

            DB::table('item_warehouse')
                ->where('item_id', $itemId)
                ->where('warehouse_id', $warehouseId)
                ->decrement('quantity', $quantity);

            Transaction::create([
                'item_id'      => $itemId,
                'warehouse_id' => $warehouseId,
                'quantity'     => $quantity,
                'type'         => 'OUT',
            ]);
        });
    }

    // -------------------------------------------------------
    // Stock Requests
    // -------------------------------------------------------

    public function requestStock(int $itemId, int $warehouseId, int $quantity, int $userId): StockRequest
    {
        $this->validateQuantity($quantity);

        return StockRequest::create([
            'item_id'      => $itemId,
            'warehouse_id' => $warehouseId,
            'quantity'     => $quantity,
            'requested_by' => $userId,
            'status'       => 'PENDING',
        ]);
    }

    public function approveRequest(int $requestId, int $approverId): StockRequest
    {
        return DB::transaction(function () use ($requestId, $approverId) {
            $request = StockRequest::findOrFail($requestId);

            if ($request->status !== 'PENDING') {
                throw new \Exception("Request already processed");
            }

            // Check stock from pivot table (consistent with addStock/removeStock)
            $record = DB::table('item_warehouse')
                ->where('item_id', $request->item_id)
                ->where('warehouse_id', $request->warehouse_id)
                ->lockForUpdate()
                ->first();

            if (!$record || $record->quantity < $request->quantity) {
                throw new \Exception("Insufficient stock");
            }

            // Deduct from pivot table (not item->quantity)
            DB::table('item_warehouse')
                ->where('item_id', $request->item_id)
                ->where('warehouse_id', $request->warehouse_id)
                ->decrement('quantity', $request->quantity);

            Transaction::create([
                'item_id'   => $request->item_id,
                'warehouse_id' => $request->warehouse_id,
                'quantity'  => $request->quantity,
                'type'      => 'OUT',
                'reference' => 'REQ-' . $request->id,
            ]);

            $request->update([
                'status'      => 'APPROVED',
                'approved_by' => $approverId,
            ]);

            return $request;
        });
    }

    public function rejectRequest(int $requestId, int $approverId): StockRequest
    {
        $request = StockRequest::findOrFail($requestId);

        if ($request->status !== 'PENDING') {
            throw new \Exception("Request already processed");
        }

        $request->update([
            'status'      => 'REJECTED',
            'approved_by' => $approverId,
        ]);

        return $request;
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    private function validateQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("Quantity must be greater than zero");
        }
    }
}