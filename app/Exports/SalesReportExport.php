<?php

namespace App\Exports;

use App\Services\ReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $filters;
    protected $reportService;

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->reportService = app(ReportService::class);
    }

    public function collection()
    {
        $reportData = $this->reportService->getSalesReport($this->filters);
        return collect($reportData['salesData']);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Total Orders',
            'Total Sales',
            'Average Order Value',
        ];
    }

    public function map($row): array
    {
        return [
            $row->date,
            $row->total_orders,
            $row->total_sales,
            $row->avg_order_value,
        ];
    }

    public function title(): string
    {
        return 'Sales Report';
    }
}