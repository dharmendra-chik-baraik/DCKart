@extends('admin.layouts.app')

@section('title', 'Reviews Management')

@section('content')
    <div class="container-fluid">
        <div class="row my-3 ">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h1 class="page-title h3 mb-0 text-gray-800">Reviews Management</h1>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Reviews</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-3">
            <div class="col-xl-2 col-lg-3 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-star widget-icon text-primary"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Total Reviews</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['total_reviews'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-clock widget-icon text-warning"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Pending</h5>
                        <h3 class="mt-3 mb-3">
                            <a href="{{ route('admin.reviews.pending') }}" class="text-warning">
                                {{ $stats['pending_reviews'] }}
                            </a>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-check-circle widget-icon text-success"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Approved</h5>
                        <h3 class="mt-3 mb-3">
                            <a href="{{ route('admin.reviews.approved') }}" class="text-success">
                                {{ $stats['approved_reviews'] }}
                            </a>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-times-circle widget-icon text-danger"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Rejected</h5>
                        <h3 class="mt-3 mb-3">
                            <a href="{{ route('admin.reviews.rejected') }}" class="text-danger">
                                {{ $stats['rejected_reviews'] }}
                            </a>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-chart-line widget-icon text-info"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Avg Rating</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['average_rating'] }}/5</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-sm-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="fas fa-star widget-icon text-warning"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">5-Star Reviews</h5>
                        <h3 class="mt-3 mb-3">{{ $stats['five_star_reviews'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <form method="GET" class="d-flex">
                                    <input type="text" name="search" class="form-control me-2"
                                        placeholder="Search reviews..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                        Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="rating" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Ratings</option>
                                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars
                                    </option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars
                                    </option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars
                                    </option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars
                                    </option>
                                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                                </select>
                            </div>
                            <div class="col-md-3 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.reviews.pending') }}" class="btn btn-warning">
                                        <i class="fas fa-clock"></i> Pending ({{ $stats['pending_reviews'] }})
                                    </a>
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

        <!-- Reviews Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($reviews->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-centered table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAll">
                                            </th>
                                            <th>Product & Customer</th>
                                            <th>Rating & Review</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reviews as $review)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="review-checkbox"
                                                        value="{{ $review->id }}">
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($review->product->images->first())
                                                            <img src="{{ asset('storage/' . $review->product->images->first()->image_path) }}"
                                                                alt="{{ $review->product->name }}" class="rounded me-3"
                                                                width="50">
                                                        @else
                                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                                style="width: 50px; height: 50px;">
                                                                <i class="fas fa-box text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <a href="#"
                                                                    class="text-dark">{{ $review->product->name }}</a>
                                                            </h6>
                                                            <small class="text-muted">
                                                                Vendor: {{ $review->product->vendor->shop_name }}
                                                            </small>
                                                            <br>
                                                            <small class="text-muted">
                                                                By: {{ $review->user->name }} ({{ $review->user->email }})
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="max-width: 400px">
                                                    <div class="mb-2">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-light' }}"></i>
                                                        @endfor
                                                        <span class="badge bg-primary ms-1">{{ $review->rating }}/5</span>
                                                    </div>
                                                    @if ($review->review)
                                                        <p class="mb-0 text-muted">{{ Str::limit($review->review, 100) }}
                                                        </p>
                                                    @else
                                                        <span class="text-muted fst-italic">No review text</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge 
                                            @if ($review->status == 'approved') bg-success
                                            @elseif($review->status == 'rejected') bg-danger
                                            @else bg-warning @endif">
                                                        {{ ucfirst($review->status) }}
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
                                                        @if ($review->status == 'pending')
                                                            <form
                                                                action="{{ route('admin.reviews.approve', $review->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-success"
                                                                    title="Approve">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                            <form
                                                                action="{{ route('admin.reviews.reject', $review->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger" title="Reject">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        @elseif($review->status == 'approved')
                                                            <form
                                                                action="{{ route('admin.reviews.reject', $review->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-warning" title="Reject">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form
                                                                action="{{ route('admin.reviews.approve', $review->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-success"
                                                                    title="Approve">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if ($review->status != 'pending')
                                                            <form
                                                                action="{{ route('admin.reviews.mark-pending', $review->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-info"
                                                                    title="Mark Pending">
                                                                    <i class="fas fa-clock"></i>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            data-bs-toggle="modal" data-bs-target="#reviewDetailModal"
                                                            data-review-id="{{ $review->id }}"
                                                            data-product-name="{{ $review->product->name }}"
                                                            data-customer-name="{{ $review->user->name }}"
                                                            data-rating="{{ $review->rating }}"
                                                            data-review-text="{{ $review->review }}"
                                                            data-review-date="{{ $review->created_at->format('M d, Y h:i A') }}"
                                                            data-review-status="{{ $review->status }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this review?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Delete">
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
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No Reviews Found</h4>
                                <p class="text-muted">No reviews match your search criteria.</p>
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
                        <div class="col-md-8">
                            <h6 id="modalProductName" class="text-primary"></h6>
                            <p class="mb-2"><strong>Customer:</strong> <span id="modalCustomerName"></span></p>
                            <p class="mb-2"><strong>Date:</strong> <span id="modalReviewDate"></span></p>
                            <p class="mb-2"><strong>Status:</strong> <span id="modalReviewStatus"
                                    class="badge"></span></p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div id="modalRating" class="mb-3"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <strong>Review:</strong>
                        <p id="modalReviewText" class="mt-2 p-3 bg-light rounded"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="modalActions" class="w-100 text-end">
                        <!-- Actions will be loaded here -->
                    </div>
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
                <form action="{{ route('admin.reviews.bulk-actions') }}" method="POST" id="bulkActionsForm">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <span id="selectedCount">0</span> review(s) selected
                        </div>

                        <div class="mb-3">
                            <label for="bulkAction" class="form-label">Action</label>
                            <select class="form-select" id="bulkAction" name="action" required>
                                <option value="">Select Action</option>
                                <option value="approve">Approve Selected</option>
                                <option value="reject">Reject Selected</option>
                                <option value="pending">Mark as Pending</option>
                                <option value="delete">Delete Selected</option>
                            </select>
                        </div>

                        <input type="hidden" name="review_ids" id="bulkReviewIds">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Apply Action</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select All functionality
            const selectAll = document.getElementById('selectAll');
            const reviewCheckboxes = document.querySelectorAll('.review-checkbox');

            selectAll.addEventListener('change', function() {
                reviewCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });

            reviewCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            function updateSelectedCount() {
                const selected = document.querySelectorAll('.review-checkbox:checked');
                document.getElementById('selectedCount').textContent = selected.length;

                const reviewIds = Array.from(selected).map(checkbox => checkbox.value);
                document.getElementById('bulkReviewIds').value = JSON.stringify(reviewIds);
            }

            // Review Detail Modal
            const reviewDetailModal = document.getElementById('reviewDetailModal');
            reviewDetailModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const reviewId = button.getAttribute('data-review-id');
                const productName = button.getAttribute('data-product-name');
                const customerName = button.getAttribute('data-customer-name');
                const rating = button.getAttribute('data-rating');
                const reviewText = button.getAttribute('data-review-text');
                const reviewDate = button.getAttribute('data-review-date');
                const reviewStatus = button.getAttribute('data-review-status');

                document.getElementById('modalProductName').textContent = productName;
                document.getElementById('modalCustomerName').textContent = customerName;
                document.getElementById('modalReviewDate').textContent = reviewDate;

                // Set status badge
                const statusBadge = document.getElementById('modalReviewStatus');
                statusBadge.textContent = reviewStatus.charAt(0).toUpperCase() + reviewStatus.slice(1);
                statusBadge.className = 'badge ' +
                    (reviewStatus == 'approved' ? 'bg-success' :
                        reviewStatus == 'rejected' ? 'bg-danger' : 'bg-warning');

                // Set rating stars
                const ratingContainer = document.getElementById('modalRating');
                ratingContainer.innerHTML = '';
                for (let i = 1; i <= 5; i++) {
                    const star = document.createElement('i');
                    star.className = `fas fa-star ${i <= rating ? 'text-warning' : 'text-light'}`;
                    ratingContainer.appendChild(star);
                }
                ratingContainer.innerHTML += ` <span class="badge bg-primary">${rating}/5</span>`;

                // Set review text
                document.getElementById('modalReviewText').textContent = reviewText ||
                    'No review text provided.';

                // Set actions
                const actionsContainer = document.getElementById('modalActions');
                let actionsHTML = '';

                $baseUrl = "{{ url('/') }}";
                if (reviewStatus === 'pending') {
                    actionsHTML = `
                    <form action="`base_url + `/admin/reviews/${reviewId}/approve" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                    <form action="`base_url + `/admin/reviews/${reviewId}/reject" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
                `;
                } else if (reviewStatus === 'approved') {
                    actionsHTML = `
                    <form action="`base_url + `/admin/reviews/${reviewId}/reject" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning">Reject</button>
                    </form>
                `;
                } else {
                    actionsHTML = `
                    <form action="`base_url + `/admin/reviews/${reviewId}/approve" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                `;
                }

                actionsHTML += `
                <form action="`base_url + `/admin/reviews/${reviewId}/mark-pending" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-info">Mark Pending</button>
                </form>
                <form action="`base_url + `/admin/reviews/${reviewId}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            `;

                actionsContainer.innerHTML = actionsHTML;
            });

            // Bulk actions form validation
            const bulkActionsForm = document.getElementById('bulkActionsForm');
            bulkActionsForm.addEventListener('submit', function(e) {
                const selectedCount = document.querySelectorAll('.review-checkbox:checked').length;
                if (selectedCount === 0) {
                    e.preventDefault();
                    alert('Please select at least one review.');
                    return false;
                }
            });
        });
    </script>
@endpush
