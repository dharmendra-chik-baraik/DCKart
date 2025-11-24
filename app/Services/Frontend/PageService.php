<?php

namespace App\Services\Frontend;

use App\Interfaces\Frontend\PageRepositoryInterface;

class PageService
{
    protected $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function getAllPages()
    {
        return $this->pageRepository->getAllPages();
    }

    public function getPageBySlug($slug)
    {
        return $this->pageRepository->getPageBySlug($slug);
    }

    public function getActivePages()
    {
        return $this->pageRepository->getActivePages();
    }
}