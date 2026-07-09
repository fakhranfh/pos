<?php

namespace App\Repositories\Product;

interface ProductRepositoryInterface
{
    public function query(array $filters = []);

    public function get(array $filters = [], array $with = [], ?string $sort = null, string $direction = 'asc', int $perPage = 15);

    public function getAll();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function adjustStock($id, int $delta);

    public function lowStock();

    public function searchAvailable(string $term = '', int $perPage = 20);
}
