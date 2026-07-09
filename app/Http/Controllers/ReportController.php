<?php

namespace App\Http\Controllers;

use App\Http\Responses\PaginatedResponse;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function sales(Request $request)
    {
        $totals = $this->reportService->salesTotals(
            $request->query('date_from'),
            $request->query('date_to'),
        );

        return view('app.report.sales', $totals);
    }

    public function salesTotals(Request $request)
    {
        return response()->json($this->reportService->salesTotals(
            $request->query('date_from'),
            $request->query('date_to'),
        ));
    }

    public function salesProducts(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to']);
        $perPage = (int) $request->query('per_page', 15);
        $items = $this->reportService->topSellingProducts($filters, $request->query('sort'), $request->query('direction', 'desc'), $perPage);

        $data = $items->getCollection()->map(fn ($item) => [
            'name' => $item->product_name ?? '',
            'quantity' => (int) $item->quantity,
            'revenue' => (float) $item->revenue,
        ])->toArray();

        return new PaginatedResponse($data, $items);
    }
}
