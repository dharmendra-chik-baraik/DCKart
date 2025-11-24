@extends('layouts.app')

@section('title', $page->title . ' - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">Information</a></li>
            <li class="breadcrumb-item active">{{ $page->title }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <!-- Page Header -->
                    <div class="text-center mb-5">
                        <h1 class="fw-bold text-primary mb-3">{{ $page->title }}</h1>
                        <div class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Last updated: {{ $page->updated_at->format('F d, Y') }}
                        </div>
                    </div>

                    <!-- Page Content -->
                    <div class="page-content">
                        {!! $page->content !!}
                    </div>

                    <!-- Page Actions -->
                    <div class="mt-5 pt-4 border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('pages.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Information
                                </a>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                                        <i class="fas fa-print me-2"></i>Print
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="sharePage()">
                                        <i class="fas fa-share-alt me-2"></i>Share
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Pages -->
    @if($relatedPages && $relatedPages->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Related Information</h3>
            <div class="row g-4">
                @foreach($relatedPages as $relatedPage)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">{{ $relatedPage->title }}</h5>
                            <p class="text-muted mb-3">{{ Str::limit(strip_tags($relatedPage->content), 100) }}</p>
                            <a href="{{ route('pages.show', $relatedPage->slug) }}" class="btn btn-sm btn-outline-primary">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.page-content {
    line-height: 1.8;
    font-size: 1.1rem;
    color: #374151;
}

.page-content h2 {
    color: #1f2937;
    margin-top: 2.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e5e7eb;
}

.page-content h3 {
    color: #374151;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.page-content p {
    margin-bottom: 1.5rem;
}

.page-content ul, .page-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.page-content li {
    margin-bottom: 0.5rem;
}

.page-content table {
    width: 100%;
    margin-bottom: 1.5rem;
    border-collapse: collapse;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.page-content table th {
    background-color: #f8f9fa;
    font-weight: 600;
    text-align: left;
}

.page-content table th,
.page-content table td {
    padding: 0.75rem 1rem;
    border: 1px solid #e5e7eb;
}

.page-content blockquote {
    border-left: 4px solid #6366f1;
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #6b7280;
}

.page-content code {
    background-color: #f3f4f6;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-family: 'Courier New', monospace;
}

.page-content pre {
    background-color: #1f2937;
    color: #f9fafb;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.page-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin: 1.5rem 0;
}
</style>

<script>
function sharePage() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $page->title }}',
            text: '{{ Str::limit(strip_tags($page->content), 100) }}',
            url: window.location.href
        })
        .then(() => console.log('Successful share'))
        .catch((error) => console.log('Error sharing:', error));
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert('Link copied to clipboard!');
        }, function() {
            alert('Failed to copy link. Please copy the URL manually.');
        });
    }
}
</script>
@endsection