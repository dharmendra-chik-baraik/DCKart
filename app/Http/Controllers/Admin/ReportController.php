<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function sales(Request $request)
    {
        $filters = [
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'type' => $request->get('type', 'daily'),
        ];

        $reportData = $this->reportService->getSalesReport($filters);

        return view('admin.reports.sales', compact('reportData', 'filters'));
    }

    public function products(Request $request)
    {
        $filters = [
            'period' => $request->get('period', '30d'),
            'limit' => $request->get('limit', 10),
        ];

        $analyticsData = $this->reportService->getProductAnalytics($filters);

        return view('admin.reports.products', compact('analyticsData', 'filters'));
    }

    public function vendors(Request $request)
    {
        $filters = [
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'period' => $request->get('period', '30d'),
        ];

        $performanceData = $this->reportService->getVendorPerformance($filters);

        return view('admin.reports.vendors', compact('performanceData', 'filters'));
    }

    public function exportSales(Request $request)
    {
        $filters = [
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'type' => $request->get('type', 'daily'),
        ];

        $format = $request->get('format', 'excel');

        if ($format === 'excel') {
            return Excel::download(new SalesReportExport($filters), 'sales-report-' . date('Y-m-d') . '.xlsx');
        }

        // You can add other formats like PDF, CSV here
        return back()->with('error', 'Export format not supported');
    }
}