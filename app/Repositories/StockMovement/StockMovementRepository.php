<?php

namespace App\Repositories\StockMovement;

use App\Models\StockMovement;
use App\Repositories\Concerns\Sortable;

class StockMovementRepository implements StockMovementRepositoryInterface
{
    use Sortable;

    protected array $sortable = ['id', 'type', 'quantity_change', 'created_at'];

    public function query(array $filters = [])
    {
        $query = StockMovement::query();

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
        return StockMovement::all();
    }

    public function find($id)
    {
        return StockMovement::find($id);
    }

    public function create(array $data)
    {
        return StockMovement::create($data);
    }
}
