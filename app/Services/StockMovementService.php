<?php

namespace App\Services;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\StockMovement\StockMovementRepositoryInterface;
use App\Support\UserTimezone;
use Illuminate\Support\Facades\DB;

class StockMovementService
{
    protected $stockMovementRepository;

    protected $productRepository;

    public function __construct(
        StockMovementRepositoryInterface $stockMovementRepository,
        ProductRepositoryInterface $productRepository,
    ) {
        $this->stockMovementRepository = $stockMovementRepository;
        $this->productRepository = $productRepository;
    }

    public function get(array $filters = [], array $with = [], ?string $sort = null, string $direction = 'asc')
    {
        return UserTimezone::apply($this->stockMovementRepository->get($filters, $with, $sort, $direction));
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
        return DB::transaction(function () use ($data) {
            $this->productRepository->adjustStock($data['product_id'], $data['quantity_change']);

            return $this->stockMovementRepository->create($data);
        });
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
