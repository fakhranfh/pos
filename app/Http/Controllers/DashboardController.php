<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\ReportService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected ProductService $productService,
        protected TransactionService $transactionService,
    ) {}

    public function show(Request $request): View
    {
        if ($request->user()->isCashier()) {
            return $this->showCashierDashboard($request);
        }

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

    /**
     * Cashiers only have access to the checkout flow, so their dashboard
     * is limited to their own sales and skips inventory/reporting widgets
     * that link to manager-only routes.
     */
    private function showCashierDashboard(Request $request): View
    {
        $cashierId = $request->user()->id;

        $myTransactions = $this->transactionService->get(
            ['cashier_id' => $cashierId],
            [],
            'created_at',
            'desc',
            50,
        );

        $myTransactionsToday = collect($myTransactions->items())
            ->filter(fn ($transaction) => $transaction->created_at->isToday());

        return view('dashboard-cashier', [
            'myTransactionsTodayCount' => $myTransactionsToday->count(),
            'myTotalSalesToday' => $myTransactionsToday->sum('total'),
            'recentTransactions' => $myTransactions->take(5),
        ]);
    }
}
