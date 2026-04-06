<?php

namespace App\Services;

use App\Models\Supply;
use App\Models\SupplyItem;
use Illuminate\Support\Facades\DB;
use App\Services\ItemService;

class SupplyService
{
    public function createSupply(array $data): Supply
    {
        return DB::transaction(function () use ($data) {
            $supply = Supply::create([
                'supplier_id' => $data['supplier_id'],
                'warehouse_id' => $data['warehouse_id'],
                'date_received' => $data['date_received'],
                'reference' => $data['reference'],
            ]);

            foreach ($data['items'] as $item) {
                SupplyItem::create([
                    'supply_id' => $supply->id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'description' => $item['description'],
                ]);

                app(ItemService::class)->addStock(
                    $item['item_id'],
                    $data['warehouse_id'],
                    $item['quantity']
                );
            }

            return $supply->load('items');
        });
    }
}