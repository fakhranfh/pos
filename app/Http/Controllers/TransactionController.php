<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(
        TransactionService $transactionService,
    ) {
        $this->transactionService = $transactionService;
    }

    private function foreignData()
    {
        return [
            'users' => User::all(),
        ];
    }

    public function index()
    {
        return view('app.transaction.index');
    }

    public function list(Request $request)
    {
        $filters = $request->only(['invoice_number', 'payment_method', 'status', 'created_from', 'created_to']);
        $items = $this->transactionService->get($filters);

        return response()->json([
            'data' => $items->map(fn ($item) => [
                'id' => $item->id ?? '',
                'invoice_number' => $item->invoice_number ?? '',
                'payment_method' => $item->payment_method ?? '',
                'status' => $item->status ?? '',
                'created_at' => $item->created_at?->format('Y-m-d H:i:s') ?? '',
                'actions' => [
                    'show' => route('transactions.show', $item->id),
                    'edit' => route('transactions.edit', $item->id),
                    'delete' => route('transactions.destroy', $item->id),
                ],
            ])->toArray(),
        ]);
    }

    public function show($id)
    {
        $item = $this->transactionService->find($id);
        $foreignData = $this->foreignData();

        return view('app.transaction.show', [
            'item' => $item,
        ] + $foreignData);
    }

    public function create()
    {
        return view('app.transaction.create', $this->foreignData());
    }

    public function store(StoreTransactionRequest $request)
    {
        $item = $this->transactionService->create($request->validated());

        return redirect()->route('transactions.show', $item)->with('success', __('Transactions created successfully.'));
    }

    public function edit($id)
    {
        $item = $this->transactionService->find($id);
        $foreignData = $this->foreignData();

        return view('app.transaction.edit', [
            'item' => $item,
        ] + $foreignData);
    }

    public function update(UpdateTransactionRequest $request, $id)
    {
        $this->transactionService->update($id, $request->validated());

        return redirect()->route('transactions.show', $id)->with('success', __('Transactions updated successfully.'));
    }

    public function destroy($id)
    {
        $this->transactionService->delete($id);

        if (request()->expectsJson()) {
            return response()->json(['message' => __('Item deleted successfully.')]);
        }

        return redirect()->route('transactions.index')->with('success', __('Transactions deleted successfully.'));
    }
}
