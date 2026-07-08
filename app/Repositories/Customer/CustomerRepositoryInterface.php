<?php

namespace App\Repositories\Customer;

interface CustomerRepositoryInterface
{
    public function query(array $filters = []);

    public function get(array $filters = [], array $with = []);

    public function getAll();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
