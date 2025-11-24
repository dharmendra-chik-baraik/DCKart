<?php

namespace App\Services;

use App\Interfaces\PageRepositoryInterface;
use App\Models\Page;
use Illuminate\Pagination\LengthAwarePaginator;

class PageService
{
    protected $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function getAllPages(array $filters = []): LengthAwarePaginator
    {
        return $this->pageRepository->getFiltered($filters);
    }

    public function createPage(array $data): Page
    {
        // Ensure status is set properly
        $data['status'] = isset($data['status']) ? (bool)$data['status'] : true;
        
        return $this->pageRepository->create($data);
    }

    public function updatePage(Page $page, array $data): bool
    {
        $data['status'] = isset($data['status']) ? (bool)$data['status'] : $page->status;
        
        return $this->pageRepository->update($page, $data);
    }

    public function deletePage(Page $page): bool
    {
        return $this->pageRepository->delete($page);
    }

    public function toggleStatus(Page $page): bool
    {
        return $this->pageRepository->toggleStatus($page);
    }

    public function getActivePages()
    {
        return Page::where('status', true)->get();
    }
}