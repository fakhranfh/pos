<?php

namespace App\Repositories\TransactionItem;

use App\Models\TransactionItem;
use App\Repositories\Concerns\Sortable;

class TransactionItemRepository implements TransactionItemRepositoryInterface
{
    use Sortable;

    protected array $sortable = ['id', 'product_name', 'quantity', 'line_total', 'created_at'];

    public function query(array $filters = [])
    {
        $query = TransactionItem::query();

        foreach ($filters as $key => $value) {
            if (is_null($value) || $value === '') {
                continue;
            }

            $query->where($key, $value);
        }

        return $query;
    }

    public function get(array $filters = [], array $with = [], ?string $sort = null, string $direction = 'asc', int $perPage = 15)
    {
        $query = $this->applySort($this->query($filters), $sort, $direction, $this->sortable);

        return $query->with($with)->paginate($perPage);
    }

    public function getAll()
    {
        return TransactionItem::all();
    }

    public function find($id)
    {
        return TransactionItem::find($id);
    }

    public function create(array $data)
    {
        return TransactionItem::create($data);
    }

    public function update($id, array $data)
    {
        $model = TransactionItem::findOrFail($id);
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        return TransactionItem::destroy($id);
    }
}
