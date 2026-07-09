<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $perPage = (int) $request->query('per_page', 15);
        $items = $this->productService->get($filters, ['category'], $request->query('sort'), $request->query('direction', 'asc'), $perPage);

        return response()->json([
            'data' => $items->getCollection()->map(fn ($item) => [
                'sku' => $item->sku ?? '',
                'name' => $item->name ?? '',
                'category' => $item->category->name ?? '',
                'price' => $item->price ?? 0,
                'created_at' => $item->created_at?->format('Y-m-d H:i:s') ?? '',
                'actions' => [
                    'show' => route('products.show', $item->id),
                    'edit' => route('products.edit', $item->id),
                    'delete' => route('products.destroy', $item->id),
                ],
            ])->toArray(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
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
        $data = $request->validated();
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products', 'public');
        }

        $item = $this->productService->create($data);

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
        $data = $request->validated();
        unset($data['image']);

        if ($request->hasFile('image')) {
            $item = $this->productService->find($id);

            if ($item->image_url) {
                Storage::disk('public')->delete($item->image_url);
            }

            $data['image_url'] = $request->file('image')->store('products', 'public');
        }

        $this->productService->update($id, $data);

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
