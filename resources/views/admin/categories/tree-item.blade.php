<!-- resources/views/admin/categories/partials/tree-item.blade.php -->
<div class="tree-item level-{{ $level }} mb-2">
    <div class="category-card">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                @if($category->icon)
                <div class="me-3 text-primary">
                    <i class="{{ $category->icon }}"></i>
                </div>
                @endif
                <div>
                    <h6 class="mb-1">{{ $category->name }}</h6>
                    <small class="text-muted">
                        {{ $category->products_count ?? 0 }} products • 
                        <span class="badge {{ $category->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $category->status ? 'Active' : 'Inactive' }}
                        </span>
                    </small>
                </div>
            </div>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('admin.categories.show', $category->id) }}" 
                   class="btn btn-outline-info" title="View">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                   class="btn btn-outline-warning" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('admin.categories.create') }}?parent_id={{ $category->id }}" 
                   class="btn btn-outline-primary" title="Add Subcategory">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
    </div>

    @if($category->children->count() > 0)
    <div class="tree-children mt-2">
        @foreach($category->children as $child)
            @include('admin.categories.tree-item', ['category' => $child, 'level' => $level + 1])
        @endforeach
    </div>
    @endif
</div>