<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cashiers = User::whereIn('email', [
            'budi.kasir@possystem.test',
            'siti.kasir@possystem.test',
        ])->get();

        if ($cashiers->isEmpty()) {
            $cashiers = User::all();
        }

        $products = Product::all();

        for ($day = 0; $day < 5; $day++) {
            $date = now()->subDays($day);
            $transactionsToday = fake()->numberBetween(3, 6);

            for ($i = 1; $i <= $transactionsToday; $i++) {
                $cashier = $cashiers->random();
                $lineItems = $products->random(fake()->numberBetween(1, 4));

                $subtotal = 0;
                $itemsData = [];

                foreach ($lineItems as $product) {
                    $quantity = fake()->numberBetween(1, 3);
                    $lineTotal = $product->price * $quantity;
                    $subtotal += $lineTotal;

                    $itemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $product->price,
                        'quantity' => $quantity,
                        'discount_amount' => 0,
                        'line_total' => $lineTotal,
                    ];
                }

                $discountAmount = fake()->randomElement([0, 0, 0, 2000, 5000]);
                $total = max($subtotal - $discountAmount, 0);
                $amountTendered = $total + fake()->randomElement([0, 0, 5000, 10000]);

                $transaction = Transaction::create([
                    'invoice_number' => 'INV-'.$date->format('Ymd').'-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'cashier_id' => $cashier->id,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'tax_amount' => 0,
                    'total' => $total,
                    'amount_tendered' => $amountTendered,
                    'change_due' => $amountTendered - $total,
                    'payment_method' => fake()->randomElement(['cash', 'other']),
                    'status' => 'completed',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                foreach ($itemsData as $itemData) {
                    TransactionItem::create($itemData + ['transaction_id' => $transaction->id]);
                }
            }
        }
    }
}
