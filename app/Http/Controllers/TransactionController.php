<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Responses\MessageResponse;
use App\Http\Responses\PaginatedResponse;
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
        $perPage = (int) $request->query('per_page', 15);
        $items = $this->transactionService->get($filters, [], $request->query('sort'), $request->query('direction', 'asc'), $perPage);

        $data = $items->getCollection()->map(fn ($item) => [
            'id' => $item->id ?? '',
            'invoice_number' => $item->invoice_number ?? '',
            'payment_method' => $item->payment_method?->label() ?? '',
            'status' => $item->status?->label() ?? '',
            'created_at' => $item->formatted_created_at ?? '',
            'actions' => [
                'show' => route('transactions.show', $item->id),
            ],
        ])->toArray();

        return new PaginatedResponse($data, $items);
    }

    public function show($id)
    {
        $item = $this->transactionService->find($id)->load(['cashier', 'items']);
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
            return new MessageResponse(__('Item deleted successfully.'));
        }

        return redirect()->route('transactions.index')->with('success', __('Transactions deleted successfully.'));
    }
}
