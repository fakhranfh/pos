<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification
{
    public function __construct(public Product $product) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'sku' => $this->product->sku,
            'name' => $this->product->name,
            'stock' => $this->product->stock,
            'low_stock_threshold' => $this->product->low_stock_threshold,
            'message' => __(':name reached its low stock threshold (:stock left).', [
                'name' => $this->product->name,
                'stock' => $this->product->stock,
            ]),
        ];
    }
}
