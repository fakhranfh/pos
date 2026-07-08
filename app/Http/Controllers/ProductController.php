<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    protected $categoryService;

    public function __construct(
        ProductService $productService,
        CategoryService $categoryService,
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    private function foreignData()
    {
        return [
            'categories' => $this->categoryService->getAll(),
        ];
    }

    public function index()
    {
        return view('app.product.index');
    }

    public function list(Request $request)
    {
        $filters = $request->only(['sku', 'name', 'created_from', 'created_to']);
        $items = $this->productService->get($filters, [], $request->query('sort'), $request->query('direction', 'asc'));

        return response()->json([
            'data' => $items->map(fn ($item) => [
                'id' => $item->id ?? '',
                'sku' => $item->sku ?? '',
                'name' => $item->name ?? '',
                'created_at' => $item->created_at?->format('Y-m-d H:i:s') ?? '',
                'actions' => [
                    'show' => route('products.show', $item->id),
                    'edit' => route('products.edit', $item->id),
                    'delete' => route('products.destroy', $item->id),
                ],
            ])->toArray(),
        ]);
    }

    public function show($id)
    {
        $item = $this->productService->find($id);
        $foreignData = $this->foreignData();

        return view('app.product.show', [
            'item' => $item,
        ] + $foreignData);
    }

    public function create()
    {
        return view('app.product.create', $this->foreignData());
    }

    public function store(StoreProductRequest $request)
    {
        $item = $this->productService->create($request->validated());

        return redirect()->route('products.show', $item)->with('success', __('Products created successfully.'));
    }

    public function edit($id)
    {
        $item = $this->productService->find($id);
        $foreignData = $this->foreignData();

        return view('app.product.edit', [
            'item' => $item,
        ] + $foreignData);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $this->productService->update($id, $request->validated());

        return redirect()->route('products.show', $id)->with('success', __('Products updated successfully.'));
    }

    public function destroy($id)
    {
        $this->productService->delete($id);

        if (request()->expectsJson()) {
            return response()->json(['message' => __('Item deleted successfully.')]);
        }

        return redirect()->route('products.index')->with('success', __('Products deleted successfully.'));
    }
}
