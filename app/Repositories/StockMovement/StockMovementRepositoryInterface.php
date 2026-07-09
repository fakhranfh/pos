<?php

namespace App\Repositories\StockMovement;

interface StockMovementRepositoryInterface
{
    public function query(array $filters = []);

    public function get(array $filters = [], array $with = [], ?string $sort = null, string $direction = 'asc', int $perPage = 15);

    public function getAll();

    public function find($id);

    public function create(array $data);
}
