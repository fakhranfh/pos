<?php

namespace App\Repositories\Product;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function query(array $filters = [])
    {
        $query = Product::query();

        foreach ($filters as $key => $value) {
            if (is_null($value) || $value === '') {
                continue;
            }

            $query->where($key, $value);
        }

        return $query;
    }

    public function get(array $filters = [], array $with = [])
    {
        return $this->query($filters)->with($with)->get();
    }

    public function getAll()
    {
        return Product::all();
    }

    public function find($id)
    {
        return Product::find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $model = Product::findOrFail($id);
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        return Product::destroy($id);
    }

    public function lowStock()
    {
        return Product::query()
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->get();
    }

    public function searchAvailable(string $term = '')
    {
        return Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%{$term}%")
                        ->orWhere('sku', 'like', "%{$term}%");
                });
            })
            ->orderBy('name')
            ->limit(50)
            ->get();
    }
}
