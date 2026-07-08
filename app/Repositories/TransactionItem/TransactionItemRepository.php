<?php

namespace App\Repositories\TransactionItem;

use App\Models\TransactionItem;

class TransactionItemRepository implements TransactionItemRepositoryInterface
{
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

    public function get(array $filters = [], array $with = [])
    {
        return $this->query($filters)->with($with)->get();
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
