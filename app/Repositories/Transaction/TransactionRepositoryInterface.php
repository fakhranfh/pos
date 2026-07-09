<?php

namespace App\Repositories\Transaction;

use Illuminate\Support\Carbon;

interface TransactionRepositoryInterface
{
    public function query(array $filters = []);

    public function get(array $filters = [], array $with = [], ?string $sort = null, string $direction = 'asc', int $perPage = 15);

    public function getAll();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    /**
     * @return array{totalSales: float, totalTransactions: int}
     */
    public function salesTotals(Carbon $dateFrom, Carbon $dateTo): array;
}
