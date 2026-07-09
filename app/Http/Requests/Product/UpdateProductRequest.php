<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($this->route('product'))],
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'required|boolean',
        ];
    }
}
