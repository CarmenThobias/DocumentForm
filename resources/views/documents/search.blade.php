@extends('layouts.app')

@section('content')
<div class="page-content container-xxl small-text">
    <div class="row">
        <div class="col-md-12">
            <div class="material-card card">
                <div class="card-body">
                    <div class="col-md-15 mb-9 d-flex justify-content-between">
                        <h4 class="text-uppercase" style="font-size: 0.875rem;">Search Forms</h4>
                        <div>
                        @if(session('role') === 'Admin' || session('role') === 'Secretary')
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('documents.create') }}" style="font-size: 0.875rem;">Upload New Form</a>
                        @endif
                        <!-- <a class="btn btn-outline-secondary btn-sm" href="{{ route('documents.create') }}" onclick="event.preventDefault(); document.getElementById('choose-role-form').submit();" style="font-size: 0.875rem;">Choose Role</a> -->
                    </div>
                    </div>
                    <br>

                    {{-- Search Form --}}
                    <form action="{{ route('documents.search') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3 d-flex justify-content-between">
                                <input type="text" name="query" id="query" class="form-control" placeholder="Search by title or category" value="{{ $searchQuery }}" style="font-size: 0.875rem;">
                                <button type="submit" class="btn btn-primary btn-sm col-md-2 mb-6" style="background-color:#071953; font-size: 0.875rem;">Search</button>
                                @error('query')
                                    <p class="text-danger" style="font-size: 0.75rem;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </form>

                    {{-- Display Categories and Documents --}}
                    <div class="table-responsive">
                        <table id="searchDocumentsTable" class="table table-hover border display table-sm mb-0 no-wrap" role="grid" style="font-size: 0.875rem;">
                            <thead>
                                <tr role="row">
                                    <th>
                                        <a href="javascript:void(0)" onclick="sortDocuments('category', '{{ $sortColumn === 'category' && $sortDirection === 'asc' ? 'desc' : 'asc' }}')" class="text-dark" style="font-size: 0.875rem;">
                                            Category
                                            @if($sortColumn === 'category')
                                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    @if($category->documents->count() > 0)
                                        <tr data-toggle="collapse" data-target="#collapse{{ $category->id }}" aria-expanded="false" aria-controls="collapse{{ $category->id }}">
                                            <td class="dropdown-toggle" style="font-size: 0.875rem;">{{ $category->name }}</td>
                                            <td colspan="3" style="font-size: 0.875rem;">{{ $category->documents->count() }} forms</td>
                                        </tr>
                                        <tr class="collapse" id="collapse{{ $category->id }}">
                                            <td colspan="4">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-sm mb-0" style="font-size: 0.875rem;">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <a href="javascript:void(0)" onclick="sortDocuments('title', '{{ $sortColumn === 'title' && $sortDirection === 'asc' ? 'desc' : 'asc' }}')" class="text-dark" style="font-size: 0.875rem;">
                                                                        Title
                                                                        @if($sortColumn === 'title')
                                                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                                        @else
                                                                            <i class="fas fa-sort"></i>
                                                                        @endif
                                                                    </a>
                                                                </th>
                                                                <th>Upload Date</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($category->documents as $document)
                                                                <tr>
                                                                    <td>{{ $document->title }}</td>
                                                                    <td>
                                                                        @if($document->upload_date)
                                                                            {{ $document->upload_date->format('Y-m-d') }}
                                                                        @else
                                                                            Not Available
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-secondary" style="font-size: 0.875rem;">View</a>
                                                                        @if(session('role') === 'Admin' || session('role') === 'Secretary')
                                                                            <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display: inline-block;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-link text-danger" style="font-size: 0.875rem;">Delete</button>
                                                                            </form>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3" style="font-size: 0.875rem;">
                        <div>
                            {{ $categories->links('vendor.pagination.custom') }}  {{-- Laravel custom pagination links --}}
                        </div>

                        <div>
                            Showing {{ (($categories->currentPage() - 1) * $categories->perPage()) + 1 }} to {{ min($categories->currentPage() * $categories->perPage(), $categories->total()) }} of {{ $categories->total() }} entries
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function sortDocuments(column, direction) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', column);
        url.searchParams.set('direction', direction);
        window.location.href = url.toString();
    }
</script>
@endsection
