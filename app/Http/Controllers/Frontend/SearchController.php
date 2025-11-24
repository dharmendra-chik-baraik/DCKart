<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function index(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return redirect()->back()->with('error', 'Please enter a search term.');
        }

        $results = $this->searchService->search($query);
        $products = $results['products'];

        return view('frontend.search.index', compact('products', 'query'));
    }
}