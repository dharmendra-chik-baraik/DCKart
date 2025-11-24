<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\VendorService;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    protected $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    public function index()
    {
        $vendors = $this->vendorService->getAllVendors();
        return view('frontend.vendors.index', compact('vendors'));
    }

    public function show($slug)
    {
        $data = $this->vendorService->getVendorWithProducts($slug);
        return view('frontend.vendors.show', $data);
    }

    public function products($slug)
    {
        $data = $this->vendorService->getVendorWithProducts($slug);
        return view('frontend.vendors.products', $data);
    }
}