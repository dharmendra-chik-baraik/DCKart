<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'rating', 'product_id']);
        $reviews = $this->reviewService->getAllReviews($filters);
        $stats = $this->reviewService->getReviewStatistics();

        return view('admin.reviews.index', compact('reviews', 'stats', 'filters'));
    }

    public function pending()
    {
        $reviews = $this->reviewService->getPendingReviews();
        $stats = $this->reviewService->getReviewStatistics();

        return view('admin.reviews.pending', compact('reviews', 'stats'));
    }

    public function approved()
    {
        $reviews = $this->reviewService->getApprovedReviews();
        $stats = $this->reviewService->getReviewStatistics();

        return view('admin.reviews.approved', compact('reviews', 'stats'));
    }

    public function rejected()
    {
        $reviews = $this->reviewService->getRejectedReviews();
        $stats = $this->reviewService->getReviewStatistics();

        return view('admin.reviews.rejected', compact('reviews', 'stats'));
    }

    public function approve($id)
    {
        try {
            $this->reviewService->approveReview($id);
            
            return redirect()->back()->with('success', 'Review approved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to approve review: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            $this->reviewService->rejectReview($id);
            
            return redirect()->back()->with('success', 'Review rejected successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reject review: ' . $e->getMessage());
        }
    }

    public function markPending($id)
    {
        try {
            $this->reviewService->markReviewPending($id);
            
            return redirect()->back()->with('success', 'Review marked as pending.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update review: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->reviewService->deleteReview($id);
            
            return redirect()->back()->with('success', 'Review deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete review: ' . $e->getMessage());
        }
    }

    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,pending,delete',
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:product_reviews,id'
        ]);

        try {
            if ($request->action === 'delete') {
                foreach ($request->review_ids as $reviewId) {
                    $this->reviewService->deleteReview($reviewId);
                }
                $message = 'Selected reviews deleted successfully.';
            } else {
                $this->reviewService->bulkUpdateStatus($request->review_ids, $request->action);
                $message = 'Selected reviews updated successfully.';
            }
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }
}