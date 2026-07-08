<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 10000, 500000);
        $discountAmount = $this->faker->randomElement([0, 0, 0, 5000, 10000]);
        $taxAmount = 0;
        $total = max($subtotal - $discountAmount + $taxAmount, 0);
        $amountTendered = $total + $this->faker->randomElement([0, 0, 5000, 10000, 20000]);

        return [
            'invoice_number' => 'INV-'.$this->faker->unique()->numerify('########-####'),
            'cashier_id' => User::factory(),
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'amount_tendered' => $amountTendered,
            'change_due' => $amountTendered - $total,
            'payment_method' => $this->faker->randomElement(['cash', 'other']),
            'status' => 'completed',
        ];
    }
}
