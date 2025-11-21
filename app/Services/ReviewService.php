<?php

namespace App\Services;

use App\Interfaces\ReviewRepositoryInterface;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Log;

class ReviewService
{
    protected $reviewRepository;

    public function __construct(ReviewRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function getAllReviews($filters = [])
    {
        return $this->reviewRepository->getAllReviews($filters);
    }

    public function getReviewById($reviewId)
    {
        return $this->reviewRepository->getReviewById($reviewId);
    }

    public function getPendingReviews()
    {
        return $this->reviewRepository->getPendingReviews();
    }

    public function getApprovedReviews()
    {
        return $this->reviewRepository->getApprovedReviews();
    }

    public function getRejectedReviews()
    {
        return $this->reviewRepository->getRejectedReviews();
    }

    public function approveReview($reviewId)
    {
        try {
            $review = $this->reviewRepository->updateReviewStatus($reviewId, 'approved');
            
            Log::info("Review approved for product: {$review->product->name}", [
                'review_id' => $reviewId,
                'product_id' => $review->product_id,
                'approved_by' => auth()->id()
            ]);
            
            return $review;
        } catch (\Exception $e) {
            Log::error("Failed to approve review {$reviewId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function rejectReview($reviewId)
    {
        try {
            $review = $this->reviewRepository->updateReviewStatus($reviewId, 'rejected');
            
            Log::info("Review rejected for product: {$review->product->name}", [
                'review_id' => $reviewId,
                'product_id' => $review->product_id,
                'rejected_by' => auth()->id()
            ]);
            
            return $review;
        } catch (\Exception $e) {
            Log::error("Failed to reject review {$reviewId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function markReviewPending($reviewId)
    {
        try {
            $review = $this->reviewRepository->updateReviewStatus($reviewId, 'pending');
            
            Log::info("Review marked as pending: {$review->product->name}", [
                'review_id' => $reviewId,
                'updated_by' => auth()->id()
            ]);
            
            return $review;
        } catch (\Exception $e) {
            Log::error("Failed to mark review pending {$reviewId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteReview($reviewId)
    {
        try {
            $review = $this->reviewRepository->getReviewById($reviewId);
            $productName = $review->product->name;
            
            $this->reviewRepository->deleteReview($reviewId);
            
            Log::info("Review deleted for product: {$productName}", [
                'review_id' => $reviewId,
                'deleted_by' => auth()->id()
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete review {$reviewId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function bulkUpdateStatus($reviewIds, $status)
    {
        try {
            $this->reviewRepository->bulkUpdateStatus($reviewIds, $status);
            
            Log::info("Bulk review status update", [
                'count' => count($reviewIds),
                'status' => $status,
                'updated_by' => auth()->id()
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error("Bulk review update failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function getReviewStatistics()
    {
        return $this->reviewRepository->getReviewStats();
    }

    public function getProductReviews($productId)
    {
        return $this->reviewRepository->getProductReviews($productId);
    }
}