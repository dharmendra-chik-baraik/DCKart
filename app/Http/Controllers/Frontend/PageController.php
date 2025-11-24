<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function index()
    {
        $pages = $this->pageService->getAllPages();
        return view('frontend.pages.index', compact('pages'));
    }

    public function show($slug)
    {
        $page = $this->pageService->getPageBySlug($slug);
        return view('frontend.pages.show', compact('page'));
    }
}