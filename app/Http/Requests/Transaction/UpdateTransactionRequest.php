<?php

namespace App\Http\Requests\Transaction;

use App\Enums\PaymentMethod;
use App\Enums\TransactionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'invoice_number' => ['required', 'string', 'max:255', Rule::unique('transactions', 'invoice_number')->ignore($this->route('transaction'))],
            'cashier_id' => 'required|integer|exists:users,id',
            'subtotal' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'amount_tendered' => 'required|numeric|min:0',
            'change_due' => 'required|numeric|min:0',
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
            'status' => ['required', Rule::enum(TransactionStatus::class)],
        ];
    }
}
