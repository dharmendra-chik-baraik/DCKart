<?php

namespace App\Interfaces;

interface ReviewRepositoryInterface
{
    public function getAllReviews($filters = []);
    public function getReviewById($reviewId);
    public function getPendingReviews();
    public function getApprovedReviews();
    public function getRejectedReviews();
    public function updateReviewStatus($reviewId, $status);
    public function deleteReview($reviewId);
    public function bulkUpdateStatus($reviewIds, $status);
    public function getReviewStats();
    public function getProductReviews($productId);
}