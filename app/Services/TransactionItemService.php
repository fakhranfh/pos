<?php

namespace App\Services;

use App\Repositories\TransactionItem\TransactionItemRepositoryInterface;

class TransactionItemService
{
    protected $transactionItemRepository;

    public function __construct(TransactionItemRepositoryInterface $transactionItemRepository)
    {
        $this->transactionItemRepository = $transactionItemRepository;
    }

    public function get(array $filters = [], array $with = [])
    {
        return $this->transactionItemRepository->get($filters, $with);
    }

    public function getAll()
    {
        return $this->transactionItemRepository->getAll();
    }

    public function find($id)
    {
        return $this->transactionItemRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->transactionItemRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->transactionItemRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->transactionItemRepository->delete($id);
    }
}
