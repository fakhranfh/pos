<?php

namespace App\Services;

use App\Repositories\StockMovement\StockMovementRepositoryInterface;
use App\Support\UserTimezone;

class StockMovementService
{
    protected $stockMovementRepository;

    public function __construct(StockMovementRepositoryInterface $stockMovementRepository)
    {
        $this->stockMovementRepository = $stockMovementRepository;
    }

    public function get(array $filters = [], array $with = [])
    {
        return UserTimezone::apply($this->stockMovementRepository->get($filters, $with));
    }

    public function getAll()
    {
        return $this->stockMovementRepository->getAll();
    }

    public function find($id)
    {
        return $this->stockMovementRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->stockMovementRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->stockMovementRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->stockMovementRepository->delete($id);
    }
}
