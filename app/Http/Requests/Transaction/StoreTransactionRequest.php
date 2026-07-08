<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'invoice_number' => 'required|string|max:255|unique:transactions,invoice_number',
            'cashier_id' => 'required|integer|exists:users,id',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'subtotal' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'amount_tendered' => 'required|numeric|min:0',
            'change_due' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,other',
            'status' => 'required|in:completed,voided',
        ];
    }
}
