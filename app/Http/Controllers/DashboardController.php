<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\ReportService;
use App\Services\TransactionService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected ProductService $productService,
        protected TransactionService $transactionService,
    ) {}

    public function show(): View
    {
        $today = $this->reportService->salesTotals(null, null);

        $topProducts = $this->reportService->topSellingProducts(
            ['date_from' => $today['dateFrom'], 'date_to' => $today['dateTo']],
            'revenue',
            'desc',
            5,
        );

        $lowStockProducts = $this->productService->lowStock();

        $recentTransactions = $this->transactionService->get([], ['cashier'], 'created_at', 'desc', 5);

        return view('dashboard', [
            'totalSalesToday' => $today['totalSales'],
            'totalTransactionsToday' => $today['totalTransactions'],
            'topProducts' => $topProducts,
            'lowStockCount' => $lowStockProducts->count(),
            'lowStockProducts' => $lowStockProducts->take(5),
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
