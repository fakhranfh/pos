<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\Concerns\Sortable;

class CategoryRepository implements CategoryRepositoryInterface
{
    use Sortable;

    protected array $sortable = ['id', 'name', 'created_at'];

    public function query(array $filters = [])
    {
        $query = Category::query();

        foreach ($filters as $key => $value) {
            if (is_null($value) || $value === '') {
                continue;
            }

            $query->where($key, $value);
        }

        return $query;
    }

    public function get(array $filters = [], array $with = [], ?string $sort = null, string $direction = 'asc')
    {
        $query = $this->applySort($this->query($filters), $sort, $direction, $this->sortable);

        return $query->with($with)->get();
    }

    public function getAll()
    {
        return Category::all();
    }

    public function find($id)
    {
        return Category::find($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $model = Category::findOrFail($id);
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        return Category::destroy($id);
    }
}
