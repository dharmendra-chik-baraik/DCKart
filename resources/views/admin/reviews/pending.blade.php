@extends('admin.layouts.app')

@section('title', 'Pending Reviews')

@section('content')
    <div class="container-fluid">
        <div class="row my-3">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="page-title h3 mb-0 text-gray-800">Pending Reviews</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Reviews</a></li>
                            <li class="breadcrumb-item active">Pending Reviews</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Pending Reviews:</strong> These reviews are awaiting your approval before they become visible to
                    customers.
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-3">
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-clock widget-icon text-warning"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Pending Reviews</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['pending_reviews'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-check-circle widget-icon text-success"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Approved</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['approved_reviews'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-times-circle widget-icon text-danger"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Rejected</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['rejected_reviews'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-star widget-icon text-primary"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Average Rating</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['average_rating'] }}/5</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> All Reviews
                                </a>
                                <a href="{{ route('admin.reviews.approved') }}" class="btn btn-success">
                                    <i class="fas fa-check-circle"></i> Approved Reviews
                                </a>
                                <a href="{{ route('admin.reviews.rejected') }}" class="btn btn-danger">
                                    <i class="fas fa-times-circle"></i> Rejected Reviews
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#bulkActionsModal">
                                        <i class="fas fa-tasks"></i> Bulk Actions
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Reviews Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($reviews->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-centered table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                                </div>
                                            </th>
                                            <th>Review Details</th>
                                            <th>Product & Vendor</th>
                                            <th>Rating</th>
                                            <th>Status</th>
                                            <th>Submitted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reviews as $review)
                                            <tr class="table-warning">
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input review-checkbox"
                                                            name="review_ids[]" value="{{ $review->id }}">
                                                    </div>
                                                </td>
                                                <td style="max-width: 400px">
                                                    <div class="d-flex align-items-start">
                                                        <div class="bg-warning rounded text-white me-3 d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">{{ $review->user->name }}</h6>
                                                            <p class="text-muted mb-1">{{ $review->user->email }}</p>
                                                            @if ($review->review)
                                                                <p class="mb-0 text-dark">
                                                                    {{ Str::limit($review->review, 100) }}</p>
                                                            @else
                                                                <p class="mb-0 text-muted"><em>No review text provided</em>
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-start">
                                                        @if ($review->product->images->first())
                                                            <img src="{{ asset('storage/' . $review->product->images->first()->image_path) }}"
                                                                alt="{{ $review->product->name }}" class="rounded me-2"
                                                                width="40" height="40">
                                                        @else
                                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                                style="width: 40px; height: 40px;">
                                                                <i class="fas fa-box text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $review->product->name }}</h6>
                                                            <small class="text-muted">by
                                                                {{ $review->product->vendor->shop_name }}</small>
                                                            <br>
                                                            <small class="text-muted">SKU:
                                                                {{ $review->product->sku }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <div class="star-rating mb-1">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i
                                                                    class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-light' }}"></i>
                                                            @endfor
                                                        </div>
                                                        <span class="badge bg-primary">{{ $review->rating }}/5</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i> Pending
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $review->created_at->format('M d, Y') }}
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $review->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group-sm">
                                                        <form action="{{ route('admin.reviews.approve', $review->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                                title="Approve Review"
                                                                onclick="return confirm('Approve this review?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.reviews.reject', $review->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="Reject Review"
                                                                onclick="return confirm('Reject this review?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="modal" data-bs-target="#reviewDetailModal"
                                                            data-review-id="{{ $review->id }}"
                                                            data-user-name="{{ $review->user->name }}"
                                                            data-user-email="{{ $review->user->email }}"
                                                            data-product-name="{{ $review->product->name }}"
                                                            data-rating="{{ $review->rating }}"
                                                            data-review-text="{{ $review->review }}"
                                                            data-submitted="{{ $review->created_at->format('M d, Y h:i A') }}"
                                                            data-product-image="{{ $review->product->images->first() ? asset('storage/' . $review->product->images->first()->image_path) : '' }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this review?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="Delete Review">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $reviews->links('pagination::bootstrap-5') }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h4 class="text-success">No Pending Reviews</h4>
                                <p class="text-muted">All reviews have been processed. Great job!</p>
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Back to All Reviews
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Detail Modal -->
    <div class="modal fade" id="reviewDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Review Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="modalProductImage" src="" alt="Product Image"
                                class="img-fluid rounded mb-3" style="max-height: 200px; object-fit: cover;">
                            <h5 id="modalProductName" class="mb-2"></h5>
                            <div id="modalRating" class="star-rating mb-3"></div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h6>Customer Information</h6>
                                <p class="mb-1"><strong>Name:</strong> <span id="modalUserName"></span></p>
                                <p class="mb-1"><strong>Email:</strong> <span id="modalUserEmail"></span></p>
                            </div>
                            <div class="mb-3">
                                <h6>Review Content</h6>
                                <div class="border rounded p-3 bg-light">
                                    <p id="modalReviewText" class="mb-0"></p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <p class="mb-0"><strong>Submitted:</strong> <span id="modalSubmitted"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="modalApproveForm" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve Review</button>
                    </form>
                    <form id="modalRejectForm" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Reject Review</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkActionsForm" action="{{ route('admin.reviews.bulk-actions') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Apply action to selected reviews.
                            <span id="selectedCount" class="fw-bold">0 reviews</span> selected.
                        </div>

                        <div class="mb-3">
                            <label for="bulkAction" class="form-label">Action</label>
                            <select class="form-select" id="bulkAction" name="action" required>
                                <option value="">Select Action</option>
                                <option value="approve">Approve Selected</option>
                                <option value="reject">Reject Selected</option>
                                <option value="delete">Delete Selected</option>
                            </select>
                        </div>

                        <input type="hidden" name="review_ids" id="bulkReviewIds">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="bulkActionButton" disabled>
                            Apply Action
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .star-rating {
            display: inline-block;
        }

        .star-rating .fas.fa-star {
            font-size: 14px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Review Detail Modal
            const reviewDetailModal = document.getElementById('reviewDetailModal');
            reviewDetailModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const reviewId = button.getAttribute('data-review-id');
                const userName = button.getAttribute('data-user-name');
                const userEmail = button.getAttribute('data-user-email');
                const productName = button.getAttribute('data-product-name');
                const rating = button.getAttribute('data-rating');
                const reviewText = button.getAttribute('data-review-text');
                const submitted = button.getAttribute('data-submitted');
                const productImage = button.getAttribute('data-product-image');

                document.getElementById('modalUserName').textContent = userName;
                document.getElementById('modalUserEmail').textContent = userEmail;
                document.getElementById('modalProductName').textContent = productName;
                document.getElementById('modalReviewText').textContent = reviewText ||
                    'No review text provided';
                document.getElementById('modalSubmitted').textContent = submitted;

                // Set product image
                const productImg = document.getElementById('modalProductImage');
                if (productImage) {
                    productImg.src = productImage;
                    productImg.style.display = 'block';
                } else {
                    productImg.style.display = 'none';
                }

                // Set rating stars
                const ratingContainer = document.getElementById('modalRating');
                ratingContainer.innerHTML = '';
                for (let i = 1; i <= 5; i++) {
                    const star = document.createElement('i');
                    star.className = `fas fa-star ${i <= rating ? 'text-warning' : 'text-light'}`;
                    ratingContainer.appendChild(star);
                }

                // Set form actions
                base_url = "{{ url('/') }}";
                document.getElementById('modalApproveForm').action = base_url + `/admin/reviews/${reviewId}/approve`;
                document.getElementById('modalRejectForm').action = base_url + `/admin/reviews/${reviewId}/reject`;
            });

            // Bulk Actions
            const selectAll = document.getElementById('selectAll');
            const reviewCheckboxes = document.querySelectorAll('.review-checkbox');
            const selectedCount = document.getElementById('selectedCount');
            const bulkActionButton = document.getElementById('bulkActionButton');
            const bulkAction = document.getElementById('bulkAction');
            const bulkReviewIds = document.getElementById('bulkReviewIds');

            // Select All functionality
            selectAll.addEventListener('change', function() {
                reviewCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });

            // Individual checkbox change
            reviewCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            // Update selected count
            function updateSelectedCount() {
                const selected = document.querySelectorAll('.review-checkbox:checked');
                selectedCount.textContent = `${selected.length} reviews`;

                // Update bulk action button state
                bulkActionButton.disabled = selected.length === 0 || !bulkAction.value;

                // Update hidden input with selected IDs
                const selectedIds = Array.from(selected).map(cb => cb.value);
                bulkReviewIds.value = JSON.stringify(selectedIds);
            }

            // Bulk action selection
            bulkAction.addEventListener('change', function() {
                bulkActionButton.disabled = document.querySelectorAll('.review-checkbox:checked').length ===
                    0;
            });

            // Bulk actions modal
            const bulkActionsModal = document.getElementById('bulkActionsModal');
            bulkActionsModal.addEventListener('show.bs.modal', function() {
                updateSelectedCount();
            });
        });
    </script>
@endpush
