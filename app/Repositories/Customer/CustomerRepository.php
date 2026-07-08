<?php

namespace App\Repositories\Customer;

use App\Models\Customer;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function query(array $filters = [])
    {
        $query = Customer::query();

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
        return Customer::all();
    }

    public function find($id)
    {
        return Customer::find($id);
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function update($id, array $data)
    {
        $model = Customer::findOrFail($id);
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        return Customer::destroy($id);
    }
}
