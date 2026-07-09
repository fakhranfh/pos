<?php

namespace App\Services;

use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\TransactionItem\TransactionItemRepositoryInterface;
use Illuminate\Support\Carbon;

class ReportService
{
    protected $transactionRepository;

    protected $transactionItemRepository;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        TransactionItemRepositoryInterface $transactionItemRepository,
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->transactionItemRepository = $transactionItemRepository;
    }

    /**
     * @return array{dateFrom: string, dateTo: string, totalSales: float, totalTransactions: int}
     */
    public function salesTotals(?string $dateFrom, ?string $dateTo): array
    {
        $dateFrom = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : now()->startOfDay();
        $dateTo = $dateTo ? Carbon::parse($dateTo)->endOfDay() : now()->endOfDay();

        $totals = $this->transactionRepository->salesTotals($dateFrom, $dateTo);

        return [
            'dateFrom' => $dateFrom->toDateString(),
            'dateTo' => $dateTo->toDateString(),
            'totalSales' => $totals['totalSales'],
            'totalTransactions' => $totals['totalTransactions'],
        ];
    }

    public function topSellingProducts(array $filters = [], ?string $sort = null, string $direction = 'desc', int $perPage = 15)
    {
        return $this->transactionItemRepository->topSellingProducts($filters, $sort, $direction, $perPage);
    }
}
