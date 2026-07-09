<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\TransactionStatus;
use App\Models\Concerns\HasFormattedTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFormattedTimestamps;

    protected $fillable = [
        'invoice_number',
        'cashier_id',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total',
        'amount_tendered',
        'change_due',
        'payment_method',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'payment_method' => PaymentMethod::class,
            'status' => TransactionStatus::class,
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'amount_tendered' => 'decimal:2',
            'change_due' => 'decimal:2',
        ];
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
