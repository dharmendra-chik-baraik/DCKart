<?php

namespace App\Repositories;

use App\Interfaces\ReviewRepositoryInterface;
use App\Models\ProductReview;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function getAllReviews($filters = [])
    {
        $query = ProductReview::with(['user', 'product.vendor', 'product.category']);
        
        // Apply filters
        if (isset($filters['search']) && $filters['search']) {
            $query->whereHas('product', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%');
            })->orWhereHas('user', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['rating']) && $filters['rating']) {
            $query->where('rating', $filters['rating']);
        }
        
        if (isset($filters['product_id']) && $filters['product_id']) {
            $query->where('product_id', $filters['product_id']);
        }
        
        // Order by latest
        $query->orderBy('created_at', 'desc');
        
        return $query->paginate(20);
    }

    public function getReviewById($reviewId)
    {
        return ProductReview::with(['user', 'product.vendor', 'product.category'])
            ->findOrFail($reviewId);
    }

    public function getPendingReviews()
    {
        return ProductReview::with(['user', 'product.vendor', 'product.category'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function getApprovedReviews()
    {
        return ProductReview::with(['user', 'product.vendor', 'product.category'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function getRejectedReviews()
    {
        return ProductReview::with(['user', 'product.vendor', 'product.category'])
            ->where('status', 'rejected')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function updateReviewStatus($reviewId, $status)
    {
        $review = $this->getReviewById($reviewId);
        $review->update(['status' => $status]);
        return $review;
    }

    public function deleteReview($reviewId)
    {
        $review = $this->getReviewById($reviewId);
        return $review->delete();
    }

    public function bulkUpdateStatus($reviewIds, $status)
    {
        return ProductReview::whereIn('id', $reviewIds)->update(['status' => $status]);
    }

    public function getReviewStats()
    {
        return [
            'total_reviews' => ProductReview::count(),
            'pending_reviews' => ProductReview::where('status', 'pending')->count(),
            'approved_reviews' => ProductReview::where('status', 'approved')->count(),
            'rejected_reviews' => ProductReview::where('status', 'rejected')->count(),
            'average_rating' => round(ProductReview::where('status', 'approved')->avg('rating') ?? 0, 1),
            'five_star_reviews' => ProductReview::where('status', 'approved')->where('rating', 5)->count(),
            'one_star_reviews' => ProductReview::where('status', 'approved')->where('rating', 1)->count(),
        ];
    }

    public function getProductReviews($productId)
    {
        return ProductReview::with('user')
            ->where('product_id', $productId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
}