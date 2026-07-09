<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->data['name'] ?? '-',
            'message' => __(':name reached its low stock threshold (:stock left).', [
                'name' => $this->data['name'] ?? '-',
                'stock' => $this->data['stock'] ?? '?',
            ]),
            'read' => $this->read_at !== null,
            'created_at' => $this->created_at?->format('d M Y H:i') ?? '',
            'actions' => [
                'view' => route('products.low-stock', ['highlight' => $this->data['product_id'] ?? null]),
            ],
        ];
    }
}
