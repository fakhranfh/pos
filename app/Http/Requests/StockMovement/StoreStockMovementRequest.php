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
            'type' => ['required', Rule::enum(StockMovementType::class)],
            'quantity_change' => 'required|integer',
            'reason' => 'required_if:type,adjustment|nullable|string',
        ];
    }
}
