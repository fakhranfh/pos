<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TransactionItem>
 */
class TransactionItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitPrice = $this->faker->randomFloat(2, 2000, 50000);
        $quantity = $this->faker->numberBetween(1, 5);
        $discountAmount = 0;
        $lineTotal = ($unitPrice * $quantity) - $discountAmount;

        return [
            'transaction_id' => Transaction::factory(),
            'product_id' => Product::factory(),
            'product_name' => $this->faker->words(2, true),
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'discount_amount' => $discountAmount,
            'line_total' => $lineTotal,
        ];
    }
}
