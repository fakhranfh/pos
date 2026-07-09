<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(
        CategoryService $categoryService,

    ) {
        $this->categoryService = $categoryService;

    }

    private function foreignData()
    {
        return [];
    }

    public function index()
    {
        return view('app.category.index');
    }

    public function list(Request $request)
    {
        $filters = $request->only(['name', 'created_from', 'created_to']);
        $perPage = (int) $request->query('per_page', 15);
        $items = $this->categoryService->get($filters, [], $request->query('sort'), $request->query('direction', 'asc'), $perPage);

        return response()->json([
            'data' => $items->getCollection()->map(fn ($item) => [
                'id' => $item->id ?? '',
                'name' => $item->name ?? '',
                'created_at' => $item->created_at?->format('Y-m-d H:i:s') ?? '',
                'actions' => [
                    'show' => route('categories.show', $item->id),
                    'edit' => route('categories.edit', $item->id),
                    'delete' => route('categories.destroy', $item->id),
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
        $item = $this->categoryService->find($id);
        $foreignData = $this->foreignData();

        return view('app.category.show', [
            'item' => $item,
        ] + $foreignData);
    }

    public function create()
    {
        return view('app.category.create', $this->foreignData());
    }

    public function store(StoreCategoryRequest $request)
    {
        $item = $this->categoryService->create($request->validated());

        return redirect()->route('categories.show', $item)->with('success', __('Categories created successfully.'));
    }

    public function edit($id)
    {
        $item = $this->categoryService->find($id);
        $foreignData = $this->foreignData();

        return view('app.category.edit', [
            'item' => $item,
        ] + $foreignData);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $this->categoryService->update($id, $request->validated());

        return redirect()->route('categories.show', $id)->with('success', __('Categories updated successfully.'));
    }

    public function destroy($id)
    {
        $this->categoryService->delete($id);

        if (request()->expectsJson()) {
            return response()->json(['message' => __('Item deleted successfully.')]);
        }

        return redirect()->route('categories.index')->with('success', __('Categories deleted successfully.'));
    }
}
