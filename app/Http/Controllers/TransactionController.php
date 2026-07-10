<?php

namespace App\Http\Controllers;

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
        $item = $this->transactionService->find($id);
        abort_if(! $item, 404);
        $item->load(['cashier', 'items']);
        $foreignData = $this->foreignData();

        return view('app.transaction.show', [
            'item' => $item,
        ] + $foreignData);
    }
}
