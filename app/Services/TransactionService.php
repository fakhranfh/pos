<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockAlert;
use App\Repositories\StockMovement\StockMovementRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\TransactionItem\TransactionItemRepositoryInterface;
use App\Support\UserTimezone;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    protected $transactionRepository;

    protected $transactionItemRepository;

    protected $stockMovementRepository;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        TransactionItemRepositoryInterface $transactionItemRepository,
        StockMovementRepositoryInterface $stockMovementRepository,
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->transactionItemRepository = $transactionItemRepository;
        $this->stockMovementRepository = $stockMovementRepository;
    }

    public function checkout(array $data)
    {
        return DB::transaction(function () use ($data) {
            $products = [];
            $subtotal = 0;

            foreach ($data['items'] as $line) {
                $product = Product::whereKey($line['product_id'])->lockForUpdate()->first();

                if (! $product || ! $product->is_active) {
                    throw ValidationException::withMessages([
                        'items' => "Product #{$line['product_id']} is not available.",
                    ]);
                }

                if ($product->stock < $line['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Insufficient stock for {$product->name}.",
                    ]);
                }

                $products[] = ['product' => $product, 'quantity' => $line['quantity']];
                $subtotal += $product->price * $line['quantity'];
            }

            $discountAmount = min($data['discount_amount'] ?? 0, $subtotal);
            $taxAmount = $data['tax_amount'] ?? 0;
            $total = max($subtotal - $discountAmount + $taxAmount, 0);

            if ($data['amount_tendered'] < $total) {
                throw ValidationException::withMessages([
                    'amount_tendered' => 'Amount tendered must be at least the total due.',
                ]);
            }

            $transaction = $this->transactionRepository->create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'cashier_id' => $data['cashier_id'],
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'amount_tendered' => $data['amount_tendered'],
                'change_due' => $data['amount_tendered'] - $total,
                'payment_method' => $data['payment_method'],
                'status' => 'completed',
            ]);

            foreach ($products as $line) {
                $product = $line['product'];
                $quantity = $line['quantity'];
                $lineTotal = $product->price * $quantity;

                $this->transactionItemRepository->create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'discount_amount' => 0,
                    'line_total' => $lineTotal,
                ]);

                $product->decrement('stock', $quantity);

                $this->stockMovementRepository->create([
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity_change' => -$quantity,
                    'reason' => "Sale via transaction {$transaction->invoice_number}",
                    'user_id' => $data['cashier_id'],
                ]);

                if ($product->stock <= $product->low_stock_threshold && $this->shouldSendLowStockAlert($product)) {
                    Notification::send(User::whereIn('role', [UserRole::Admin, UserRole::Manager])->get(), new LowStockAlert($product));
                }
            }

            return $transaction->load(['items', 'cashier']);
        });
    }

    /**
     * Only allow one LowStockAlert per product per day, so repeatedly
     * checking out small quantities of a low-stock item can't flood every
     * user's notifications.
     */
    private function shouldSendLowStockAlert(Product $product): bool
    {
        return Cache::add("low-stock-alert-sent:{$product->id}", true, now()->addDay());
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-'.now()->format('Ymd').'-';
        $todayCount = $this->transactionRepository->query()
            ->where('invoice_number', 'like', "{$prefix}%")
            ->count();

        return $prefix.str_pad((string) ($todayCount + 1), 4, '0', STR_PAD_LEFT);
    }

    public function get(array $filters = [], array $with = [], ?string $sort = null, string $direction = 'asc', int $perPage = 15)
    {
        return UserTimezone::apply($this->transactionRepository->get($filters, $with, $sort, $direction, $perPage));
    }

    public function getAll()
    {
        return $this->transactionRepository->getAll();
    }

    public function find($id)
    {
        return $this->transactionRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->transactionRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->transactionRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->transactionRepository->delete($id);
    }
}
