<?php

namespace App\Http\Controllers;

use App\Http\Responses\PaginatedResponse;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    private function dateRangeRules(): array
    {
        return [
            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to' => 'nullable|date_format:Y-m-d|after_or_equal:date_from',
        ];
    }

    public function sales(Request $request)
    {
        $request->validate($this->dateRangeRules());

        $totals = $this->reportService->salesTotals(
            $request->query('date_from'),
            $request->query('date_to'),
        );

        return view('app.report.sales', $totals);
    }

    public function salesTotals(Request $request)
    {
        $validator = Validator::make($request->query(), $this->dateRangeRules());

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        return response()->json($this->reportService->salesTotals(
            $request->query('date_from'),
            $request->query('date_to'),
        ));
    }

    public function salesProducts(Request $request)
    {
        $request->validate($this->dateRangeRules());

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
