<?php

namespace App\Repositories\TransactionItem;

use App\Enums\TransactionStatus;
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

    public function topSellingProducts(array $filters = [], ?string $sort = null, string $direction = 'desc', int $perPage = 15)
    {
        $query = TransactionItem::query()
            ->selectRaw('product_id, product_name, sum(quantity) as quantity, sum(line_total) as revenue')
            ->whereHas('transaction', function ($query) use ($filters) {
                $query->where('status', TransactionStatus::Completed);

                if (! empty($filters['date_from'])) {
                    $query->whereDate('created_at', '>=', $filters['date_from']);
                }

                if (! empty($filters['date_to'])) {
                    $query->whereDate('created_at', '<=', $filters['date_to']);
                }
            })
            ->groupBy('product_id', 'product_name');

        $query = $this->applySort($query, $sort, $direction, ['product_name', 'quantity', 'revenue'], 'revenue', 'desc');

        return $query->paginate($perPage);
    }
}
