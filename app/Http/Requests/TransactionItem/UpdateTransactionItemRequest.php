<?php

namespace App\Http\Requests\TransactionItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'transaction_id' => 'required|integer|exists:transactions,id',
            'product_id' => 'required|integer|exists:products,id',
            'product_name' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'discount_amount' => 'nullable|numeric|min:0',
            'line_total' => 'required|numeric|min:0',
        ];
    }
}
