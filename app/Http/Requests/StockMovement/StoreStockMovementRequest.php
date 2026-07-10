<?php

namespace App\Http\Requests\StockMovement;

use App\Enums\StockMovementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStockMovementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            // 'sale' movements are only ever created internally by
            // TransactionService::checkout() when a real sale is recorded;
            // allowing it here would let stock be marked "sold" with no
            // corresponding transaction.
            'type' => ['required', Rule::in([
                StockMovementType::StockIn->value,
                StockMovementType::Adjustment->value,
                StockMovementType::Return->value,
            ])],
            'quantity_change' => 'required|integer|not_in:0|min:-100000|max:100000',
            'reason' => 'required_if:type,adjustment|nullable|string|max:1000',
        ];
    }
}
