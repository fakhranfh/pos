<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\StoreCheckoutRequest;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\ProductSearchResponse;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    protected $productService;

    protected $transactionService;

    protected $categoryService;

    public function __construct(
        ProductService $productService,
        TransactionService $transactionService,
        CategoryService $categoryService,
    ) {
        $this->productService = $productService;
        $this->transactionService = $transactionService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return view('checkout.index', [
            'categories' => $this->categoryService->getAll(),
        ]);
    }

    public function search(Request $request)
    {
        $categoryId = $request->query('category_id');
        $priceMin = $request->query('price_min');
        $priceMax = $request->query('price_max');

        $products = $this->productService->searchAvailable(
            (string) $request->query('q', ''),
            10,
            [
                'category_id' => $categoryId !== null && $categoryId !== '' ? (int) $categoryId : null,
                'price_min' => $priceMin !== null && $priceMin !== '' ? (float) $priceMin : null,
                'price_max' => $priceMax !== null && $priceMax !== '' ? (float) $priceMax : null,
                'sort' => (string) $request->query('sort', 'name'),
                'direction' => (string) $request->query('direction', 'asc'),
            ],
        );

        $data = $products->map(fn ($product) => [
            'id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'price' => (float) $product->price,
            'stock' => $product->stock,
            'category' => $product->category->name ?? null,
            'image_url' => $product->image_url,
        ])->values();

        return new ProductSearchResponse($data, $products->hasMorePages() ? $products->currentPage() + 1 : null);
    }

    public function store(StoreCheckoutRequest $request)
    {
        $data = $request->validated();
        $data['cashier_id'] = $request->user()->id;

        try {
            $transaction = $this->transactionService->checkout($data);
        } catch (ValidationException $e) {
            return new ErrorResponse($e->errors());
        }

        return redirect()->route('checkout.receipt', $transaction)
            ->with('success', __('Transaction completed successfully.'));
    }

    public function receipt(Request $request, $id)
    {
        $transaction = $this->transactionService->find($id);

        abort_if(! $transaction, 404);
        abort_if(! $request->user()->canManageOperations() && $transaction->cashier_id !== $request->user()->id, 403);

        $transaction->load(['items', 'cashier']);

        return view('checkout.receipt', [
            'transaction' => $transaction,
        ]);
    }
}
