<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionItem\StoreTransactionItemRequest;
use App\Http\Requests\TransactionItem\UpdateTransactionItemRequest;
use App\Services\ProductService;
use App\Services\TransactionItemService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionItemController extends Controller
{
    protected $transactionItemService;

    protected $transactionService;

    protected $productService;

    public function __construct(
        TransactionItemService $transactionItemService,
        TransactionService $transactionService,
        ProductService $productService,
    ) {
        $this->transactionItemService = $transactionItemService;
        $this->transactionService = $transactionService;
        $this->productService = $productService;
    }

    private function foreignData()
    {
        return [
            'transactions' => $this->transactionService->getAll(),
            'products' => $this->productService->getAll(),
        ];
    }

    public function index()
    {
        return view('app.transaction-item.index');
    }

    public function list(Request $request)
    {
        $filters = $request->only(['product_name', 'created_from', 'created_to']);
        $items = $this->transactionItemService->get($filters);

        return response()->json([
            'data' => $items->map(fn ($item) => [
                'id' => $item->id ?? '',
                'product_name' => $item->product_name ?? '',
                'created_at' => $item->created_at?->format('Y-m-d H:i:s') ?? '',
                'actions' => [
                    'show' => route('transaction-items.show', $item->id),
                    'edit' => route('transaction-items.edit', $item->id),
                    'delete' => route('transaction-items.destroy', $item->id),
                ],
            ])->toArray(),
        ]);
    }

    public function show($id)
    {
        $item = $this->transactionItemService->find($id);
        $foreignData = $this->foreignData();

        return view('app.transaction-item.show', [
            'item' => $item,
        ] + $foreignData);
    }

    public function create()
    {
        return view('app.transaction-item.create', $this->foreignData());
    }

    public function store(StoreTransactionItemRequest $request)
    {
        $item = $this->transactionItemService->create($request->validated());

        return redirect()->route('transaction-items.show', $item)->with('success', __('Transaction Items created successfully.'));
    }

    public function edit($id)
    {
        $item = $this->transactionItemService->find($id);
        $foreignData = $this->foreignData();

        return view('app.transaction-item.edit', [
            'item' => $item,
        ] + $foreignData);
    }

    public function update(UpdateTransactionItemRequest $request, $id)
    {
        $this->transactionItemService->update($id, $request->validated());

        return redirect()->route('transaction-items.show', $id)->with('success', __('Transaction Items updated successfully.'));
    }

    public function destroy($id)
    {
        $this->transactionItemService->delete($id);

        if (request()->expectsJson()) {
            return response()->json(['message' => __('Item deleted successfully.')]);
        }

        return redirect()->route('transaction-items.index')->with('success', __('Transaction Items deleted successfully.'));
    }
}
