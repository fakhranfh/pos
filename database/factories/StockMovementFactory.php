<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['stock_in', 'adjustment', 'return']);
        $quantityChange = $type === 'adjustment'
            ? $this->faker->numberBetween(-5, 5)
            : $this->faker->numberBetween(5, 50);

        return [
            'product_id' => Product::factory(),
            'type' => $type,
            'quantity_change' => $quantityChange,
            'reason' => match ($type) {
                'stock_in' => 'Restock dari supplier',
                'adjustment' => 'Koreksi stok setelah stok opname',
                'return' => 'Retur barang dari pelanggan',
                default => null,
            },
            'user_id' => User::factory(),
        ];
    }
}
