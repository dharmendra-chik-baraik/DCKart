<?php

namespace App\Services\Frontend;

use App\Interfaces\Frontend\SearchRepositoryInterface;

class SearchService
{
    protected $searchRepository;

    public function __construct(SearchRepositoryInterface $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function search($query)
    {
        if (empty($query)) {
            return [
                'products' => collect(),
                'categories' => collect(),
                'vendors' => collect()
            ];
        }

        return [
            'products' => $this->searchRepository->searchProducts($query),
            'categories' => $this->searchRepository->searchCategories($query),
            'vendors' => $this->searchRepository->searchVendors($query)
        ];
    }

    public function searchProducts($query, $perPage = 12)
    {
        return $this->searchRepository->searchProducts($query, $perPage);
    }
}