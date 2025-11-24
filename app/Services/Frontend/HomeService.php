<?php

namespace App\Services\Frontend;

use App\Interfaces\Frontend\HomeRepositoryInterface;

class HomeService
{
    protected $homeRepository;

    public function __construct(HomeRepositoryInterface $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    public function getHomePageData()
    {
        return [
            'featuredProducts' => $this->homeRepository->getFeaturedProducts(),
            'categories' => $this->homeRepository->getPopularCategories(),
            'vendors' => $this->homeRepository->getTopVendors(),
            'pages' => $this->homeRepository->getActivePages()
        ];
    }
}