<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\StoreCheckoutRequest;
use App\Services\CustomerService;
use App\Services\ProductService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    protected $productService;

    protected $transactionService;

    protected $customerService;

    public function __construct(
        ProductService $productService,
        TransactionService $transactionService,
        CustomerService $customerService,
    ) {
        $this->productService = $productService;
        $this->transactionService = $transactionService;
        $this->customerService = $customerService;
    }

    public function index()
    {
        return view('checkout.index', [
            'customers' => $this->customerService->getAll(),
        ]);
    }

    public function search(Request $request)
    {
        $products = $this->productService->searchAvailable((string) $request->query('q', ''));

        return response()->json([
            'data' => $products->map(fn ($product) => [
                'id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
                'price' => (float) $product->price,
                'stock' => $product->stock,
                'category' => $product->category->name ?? null,
            ]),
        ]);
    }

    public function store(StoreCheckoutRequest $request)
    {
        $data = $request->validated();
        $data['cashier_id'] = $request->user()->id;

        try {
            $transaction = $this->transactionService->checkout($data);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        return redirect()->route('checkout.receipt', $transaction)
            ->with('success', __('Transaction completed successfully.'));
    }

    public function receipt($id)
    {
        $transaction = $this->transactionService->find($id);

        abort_if(! $transaction, 404);

        $transaction->load(['items', 'customer', 'cashier']);

        return view('checkout.receipt', [
            'transaction' => $transaction,
        ]);
    }
}
