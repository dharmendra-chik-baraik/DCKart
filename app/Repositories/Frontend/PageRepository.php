<?php

namespace App\Repositories\Frontend;

use App\Interfaces\Frontend\PageRepositoryInterface;
use App\Models\Page;

class PageRepository implements PageRepositoryInterface
{
    public function getAllPages()
    {
        return Page::where('status', true)
            ->orderBy('title')
            ->get();
    }

    public function getPageBySlug($slug)
    {
        return Page::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
    }

    public function getActivePages()
    {
        return Page::where('status', true)
            ->orderBy('title')
            ->get();
    }
}