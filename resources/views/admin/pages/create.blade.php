@extends('admin.layouts.app')

@section('title', 'Create New Page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Page</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Pages
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pages.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title">Page Title *</label>
                                    <input type="text" name="title" id="title" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title') }}" 
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
                                           value="{{ old('slug') }}" 
                                           placeholder="page-slug" required>
                                    @error('slug')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        URL-friendly version of the title (lowercase, hyphens instead of spaces)
                                    </small>
                                </div>

                                <!-- Content -->
                                <div class="form-group">
                                    <label for="content">Page Content *</label>
                                    <textarea name="content" id="content" 
                                              class="form-control @error('content') is-invalid @enderror" 
                                              rows="15" 
                                              placeholder="Enter page content..." required>{{ old('content') }}</textarea>
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
                                                       {{ old('status', true) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status">
                                                    Page Status
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Enable or disable this page
                                            </small>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success btn-block">
                                                <i class="fas fa-save"></i> Create Page
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
                                                   value="{{ old('meta_title') }}" 
                                                   placeholder="Meta title for SEO">
                                            @error('meta_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Optional - If empty, page title will be used
                                            </small>
                                        </div>

                                        <!-- Meta Description -->
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea name="meta_description" id="meta_description" 
                                                      class="form-control @error('meta_description') is-invalid @enderror" 
                                                      rows="3" 
                                                      placeholder="Meta description for SEO">{{ old('meta_description') }}</textarea>
                                            @error('meta_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
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
    // Auto-generate slug from title
    $('#title').on('keyup', function() {
        const title = $(this).val();
        if (title && !$('#slug').val()) {
            const slug = title.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '') // Remove invalid chars
                .replace(/\s+/g, '-')        // Replace spaces with -
                .replace(/-+/g, '-');        // Replace multiple - with single -
            $('#slug').val(slug);
        }
    });

    // Initialize text editor for content
    if ($('#content').length) {
        // You can initialize a rich text editor here like TinyMCE, CKEditor, etc.
        // For now, using a simple textarea
        console.log('Initialize your preferred text editor here');
    }
});
</script>
@endpush