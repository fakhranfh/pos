<?php

namespace App\Services;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Support\UserTimezone;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function get(array $filters = [], array $with = [], ?string $sort = null, string $direction = 'asc')
    {
        return UserTimezone::apply($this->categoryRepository->get($filters, $with, $sort, $direction));
    }

    public function getAll()
    {
        return $this->categoryRepository->getAll();
    }

    public function find($id)
    {
        return $this->categoryRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->categoryRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->categoryRepository->delete($id);
    }
}
