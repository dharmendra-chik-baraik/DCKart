@php
    $activePages = App\Models\Page::where('status', true)->get();
@endphp

@if($activePages->count() > 0)
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" 
       data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-file me-1"></i> Pages
    </a>
    <ul class="dropdown-menu" aria-labelledby="pagesDropdown">
        @foreach($activePages as $page)
        <li>
            <a class="dropdown-item" href="{{ route('pages.show', $page->slug) }}">
                {{ $page->title }}
            </a>
        </li>
        @endforeach
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item" href="{{ route('pages.index') }}">
                <i class="fas fa-list me-1"></i> All Pages
            </a>
        </li>
    </ul>
</li>
@endif