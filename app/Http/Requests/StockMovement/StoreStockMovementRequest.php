<?php

namespace App\Http\Requests\StockMovement;

use Illuminate\Foundation\Http\FormRequest;

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
            'type' => 'required|in:sale,stock_in,adjustment,return',
            'quantity_change' => 'required|integer',
            'reason' => 'required_if:type,adjustment|nullable|string',
        ];
    }
}
