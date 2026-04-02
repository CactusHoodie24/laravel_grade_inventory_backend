<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'unit' => $this->unit,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at->toDateTimeString(),
            'supplier' => [
    'id' => $this->supplier->id,
    'name' => $this->supplier->name,
],
        ];
    }
}
