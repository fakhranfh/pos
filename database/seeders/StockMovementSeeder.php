<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::where('email', 'andi.admin@possystem.test')->value('id')
            ?? User::query()->value('id');

        Product::all()->each(function (Product $product) use ($adminId) {
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'stock_in',
                'quantity_change' => $product->stock,
                'reason' => 'Stok awal saat pembukaan toko',
                'user_id' => $adminId,
            ]);

            if (fake()->boolean(30)) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'adjustment',
                    'quantity_change' => fake()->numberBetween(-3, 3),
                    'reason' => 'Koreksi stok setelah stok opname',
                    'user_id' => $adminId,
                ]);
            }
        });
    }
}
