<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Services\ItemService;
use App\Http\Resources\ItemResource;

class ItemController extends Controller
{

    protected $itemService;

    public function __construct(ItemService $itemService) 
    {
        $this->itemService = $itemService;
    }
    // GET a;; Items
    public function index() {
        return ItemResource::collection(Item::with('supplier')->get());
    }

    public function store(StoreItemRequest $request)
{
   

    $item = $this->itemService->createItem($request->validated());

    return response()->json($item, 201);
}

public function show($id) {
    $item = $this->itemService->getItem($id);

    if(!$item) {
        return response() -> json([
            'message' => 'Item not found'
        ], 404);
    }


    return response() -> json(new ItemResource($item), 200);
}

public function update(UpdateItemRequest $request, $id) {
    $item = $this->itemService->updateItem($id, $request->validated());
    if(!$item) {
        return response()->json([
            'message' => 'Item not found'
        ], 404);
    }

    return response()->json($item,200);
}

public function destroy($id) {
    $deleted = $this->itemService->deleteItem($id);
    if(!$deleted) {
        return response()->json([
            'message' => 'Item not found'
        ], 404);
    }

    return response()->json([
        'message' => 'Item deleted Successfully'
    ]);
}

public function addStock(Request $request, $id) {
    $result = $this->itemService->addStock($id, $request->warehouseId, $request->quantity);
    return response()->json($result, 200);
}

public function removeStock(Request $request, $id) {
    try {
       $item = $this->itemService->removeStock($id, $request->quantity, $request->warehouseId);
       return response()->json($item);
    } catch (\Exception $e) {
       return response()->json([
            'message' => $e->getMessage()
        ], 400);
    }
}

public function requestStock(Request $request, $id) {
      $userId = auth()->id();
      return $this->itemService->requestStock(
        $id,
        $request->warehouseId,
        $request->quantity,
        $userId
    );
}

public function approve($id)
{
    $userId = auth()->id();
    return $this->itemService->approveRequest($id,
    $userId
    );
}

public function reject($id, $approverId)
{
    return $this->itemService->rejectRequest($id, $approverId);
}
}
