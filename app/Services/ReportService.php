<?php

namespace App\Services;

use App\Interfaces\ReportRepositoryInterface;

class ReportService
{
    protected $reportRepository;

    public function __construct(ReportRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function getDashboardStats()
    {
        return [
            'totalSales' => $this->reportRepository->getTotalSales('30d'),
            'totalOrders' => $this->reportRepository->getPendingOrdersCount(),
            'totalUsers' => $this->reportRepository->getTotalUsersCount(),
            'totalProducts' => $this->reportRepository->getTotalProductsCount(),
            'conversionRate' => $this->reportRepository->getConversionRate('30d'),
            'averageOrderValue' => $this->reportRepository->getAverageOrderValue('30d'),
        ];
    }

    public function getSalesReport($filters = [])
    {
        return [
            'salesData' => $this->reportRepository->getSalesReport(
                $filters['start_date'] ?? null,
                $filters['end_date'] ?? null,
                $filters['type'] ?? 'daily'
            ),
            'salesByVendor' => $this->reportRepository->getSalesByVendor(
                $filters['start_date'] ?? null,
                $filters['end_date'] ?? null
            ),
            'salesByCategory' => $this->reportRepository->getSalesByCategory(
                $filters['start_date'] ?? null,
                $filters['end_date'] ?? null
            ),
            'topProducts' => $this->reportRepository->getTopProducts(10, '30d'),
        ];
    }

    public function getProductAnalytics($filters = [])
    {
        return [
            'topProducts' => $this->reportRepository->getTopProducts(
                $filters['limit'] ?? 10,
                $filters['period'] ?? null
            ),
            'topCategories' => $this->reportRepository->getTopCategories(
                $filters['limit'] ?? 10,
                $filters['period'] ?? null
            ),
            'revenueTrend' => $this->reportRepository->getRevenueTrend($filters['period'] ?? '30d'),
        ];
    }

    public function getVendorPerformance($filters = [])
    {
        return [
            'topVendors' => $this->reportRepository->getTopVendors(
                $filters['limit'] ?? 10,
                $filters['period'] ?? null
            ),
            'salesByVendor' => $this->reportRepository->getSalesByVendor(
                $filters['start_date'] ?? null,
                $filters['end_date'] ?? null
            ),
        ];
    }

    public function exportSalesReport($filters = [], $format = 'excel')
    {
        $data = $this->getSalesReport($filters);
        
        // You can implement different export formats here
        return $this->formatExportData($data, $format);
    }

    private function formatExportData($data, $format)
    {
        // Basic implementation - you can expand this for different formats
        return $data;
    }
}