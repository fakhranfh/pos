<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use App\Repositories\Concerns\Sortable;

class TransactionRepository implements TransactionRepositoryInterface
{
    use Sortable;

    protected array $sortable = ['id', 'invoice_number', 'payment_method', 'status', 'total', 'created_at'];

    public function query(array $filters = [])
    {
        $query = Transaction::query();

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
        return Transaction::all();
    }

    public function find($id)
    {
        return Transaction::find($id);
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function update($id, array $data)
    {
        $model = Transaction::findOrFail($id);
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        return Transaction::destroy($id);
    }
}
