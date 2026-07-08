<?php

namespace App\Services;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Support\UserTimezone;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function get(array $filters = [], array $with = [])
    {
        return UserTimezone::apply($this->productRepository->get($filters, $with));
    }

    public function getAll()
    {
        return $this->productRepository->getAll();
    }

    public function find($id)
    {
        return $this->productRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->productRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->productRepository->delete($id);
    }

    public function lowStock()
    {
        return $this->productRepository->lowStock();
    }

    public function searchAvailable(string $term = '')
    {
        return $this->productRepository->searchAvailable($term);
    }
}
