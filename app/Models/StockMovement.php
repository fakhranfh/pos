<?php

namespace App\Models;

use App\Enums\StockMovementType;
use App\Models\Concerns\HasFormattedTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFormattedTimestamps;

    protected $fillable = [
        'product_id',
        'type',
        'quantity_change',
        'reason',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => StockMovementType::class,
            'quantity_change' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
