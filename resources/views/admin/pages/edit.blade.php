@extends('admin.layouts.app')

@section('title', 'Edit Page - ' . $page->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Page: {{ $page->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Pages
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pages.update', $page) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title">Page Title *</label>
                                    <input type="text" name="title" id="title" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title', $page->title) }}" 
                                           placeholder="Enter page title" required>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="form-group">
                                    <label for="slug">Slug *</label>
                                    <input type="text" name="slug" id="slug" 
                                           class="form-control @error('slug') is-invalid @enderror" 
                                           value="{{ old('slug', $page->slug) }}" 
                                           placeholder="page-slug" required>
                                    @error('slug')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        URL-friendly version of the title
                                    </small>
                                </div>

                                <!-- Content -->
                                <div class="form-group">
                                    <label for="content">Page Content *</label>
                                    <textarea name="content" id="content" 
                                              class="form-control @error('content') is-invalid @enderror" 
                                              rows="15" 
                                              placeholder="Enter page content..." required>{{ old('content', $page->content) }}</textarea>
                                    @error('content')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Status Card -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Page Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Status -->
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" name="status" 
                                                       class="custom-control-input" id="status" 
                                                       {{ old('status', $page->status) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status">
                                                    Page Status
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Enable or disable this page
                                            </small>
                                        </div>

                                        <!-- Page Info -->
                                        <div class="form-group">
                                            <label>Page Information</label>
                                            <div class="small text-muted">
                                                <div>Created: {{ $page->created_at->format('M d, Y') }}</div>
                                                <div>Last Updated: {{ $page->updated_at->format('M d, Y') }}</div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-save"></i> Update Page
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO Card -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">SEO Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Meta Title -->
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" name="meta_title" id="meta_title" 
                                                   class="form-control @error('meta_title') is-invalid @enderror" 
                                                   value="{{ old('meta_title', $page->meta_title) }}" 
                                                   placeholder="Meta title for SEO">
                                            @error('meta_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Meta Description -->
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea name="meta_description" id="meta_description" 
                                                      class="form-control @error('meta_description') is-invalid @enderror" 
                                                      rows="3" 
                                                      placeholder="Meta description for SEO">{{ old('meta_description', $page->meta_description) }}</textarea>
                                            @error('meta_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Preview Link -->
                                        @if($page->status)
                                        <div class="form-group">
                                            <label>Preview</label>
                                            <div>
                                                <a href="{{ url('/pages/' . $page->slug) }}" 
                                                   target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-external-link-alt"></i> View Page
                                                </a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generate slug from title if empty
    $('#title').on('keyup', function() {
        const title = $(this).val();
        const currentSlug = $('#slug').val();
        
        if (title && (!currentSlug || currentSlug === '{{ $page->slug }}')) {
            const slug = title.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            $('#slug').val(slug);
        }
    });

    // Initialize text editor
    if ($('#content').length) {
        // Initialize your preferred rich text editor here
        console.log('Initialize text editor for content field');
    }
});
</script>
@endpush