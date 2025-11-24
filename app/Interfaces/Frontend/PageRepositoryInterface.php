<?php

namespace App\Interfaces\Frontend;

interface PageRepositoryInterface
{
    public function getAllPages();
    public function getPageBySlug($slug);
    public function getActivePages();
}