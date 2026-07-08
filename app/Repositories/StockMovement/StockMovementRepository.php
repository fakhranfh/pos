<?php

namespace App\Repositories\StockMovement;

use App\Models\StockMovement;

class StockMovementRepository implements StockMovementRepositoryInterface
{
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

    public function get(array $filters = [], array $with = [])
    {
        return $this->query($filters)->with($with)->get();
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

    public function update($id, array $data)
    {
        $model = StockMovement::findOrFail($id);
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        return StockMovement::destroy($id);
    }
}
