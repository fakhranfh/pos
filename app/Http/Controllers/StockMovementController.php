<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockMovement\StoreStockMovementRequest;
use App\Http\Responses\PaginatedResponse;
use App\Models\User;
use App\Services\ProductService;
use App\Services\StockMovementService;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    protected $stockMovementService;

    protected $productService;

    public function __construct(
        StockMovementService $stockMovementService,
        ProductService $productService,
    ) {
        $this->stockMovementService = $stockMovementService;
        $this->productService = $productService;
    }

    private function foreignData()
    {
        return [
            'products' => $this->productService->getAll(),
            'users' => User::all(),
        ];
    }

    public function index()
    {
        return view('app.stock-movement.index');
    }

    public function list(Request $request)
    {
        $filters = $request->only(['type', 'quantity_change', 'created_from', 'created_to']);
        $perPage = (int) $request->query('per_page', 15);
        $items = $this->stockMovementService->get($filters, [], $request->query('sort'), $request->query('direction', 'asc'), $perPage);

        $data = $items->getCollection()->map(fn ($item) => [
            'id' => $item->id ?? '',
            'type' => $item->type ?? '',
            'quantity_change' => $item->quantity_change ?? '',
            'created_at' => $item->formatted_created_at ?? '',
            'actions' => [
                'show' => route('stock-movements.show', $item->id),
            ],
        ])->toArray();

        return new PaginatedResponse($data, $items);
    }

    public function show($id)
    {
        $item = $this->stockMovementService->find($id);
        $foreignData = $this->foreignData();

        return view('app.stock-movement.show', [
            'item' => $item,
        ] + $foreignData);
    }

    public function create()
    {
        return view('app.stock-movement.create', $this->foreignData());
    }

    public function store(StoreStockMovementRequest $request)
    {
        $item = $this->stockMovementService->create($request->validated() + ['user_id' => $request->user()->id]);

        return redirect()->route('stock-movements.show', $item)->with('success', __('Stock Movements created successfully.'));
    }
}
