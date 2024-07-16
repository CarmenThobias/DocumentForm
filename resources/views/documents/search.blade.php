@extends('layouts.app')

@section('content')
<div class="page-content container-xxl">
    <div class="row">
        <div class="col-md-12">
            <div class="material-card card">
                <div class="card-body">
                    <div class="col-md-15 mb-9 d-flex justify-content-between">
                        <h4 class="text-uppercase">Search Documents</h4>
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('documents.create') }}">Upload New Document</a>
                    </div>
                    <br>

                    {{-- Search Form --}}
                    <form action="{{ route('documents.search') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3 d-flex justify-content-between">
                                <input type="text" name="query" id="query" class="form-control" placeholder="Search by title or category" value="{{ $searchQuery }}">
                                <button type="submit" class="btn btn-primary btn-sm col-md-2 mb-6" style="background-color:#071953;">Search</button>
                                @error('query')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </form>

                    {{-- Display Documents --}}
                    <div class="table-responsive">
                        <table id="file_export" class="table table-hover border display table-sm mb-0 no-wrap dataTable" role="grid">
                            <thead>
                                <tr role="row">
                                    <th>
                                        <a href="{{ route('documents.search', array_merge(request()->query(), ['sort' => 'title', 'direction' => $sortColumn === 'title' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}" class="text-secondary">
                                            Title
                                            @if($sortColumn === 'title')
                                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('documents.search', array_merge(request()->query(), ['sort' => 'category', 'direction' => $sortColumn === 'category' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}" class="text-secondary">
                                            Category
                                            @if($sortColumn === 'category')
                                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('documents.search', array_merge(request()->query(), ['sort' => 'upload_date', 'direction' => $sortColumn === 'upload_date' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}" class="text-secondary">
                                            Upload Date
                                            @if($sortColumn === 'upload_date')
                                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th ><div class="text-secondary">Actions</div></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($documents as $document)
                                    <tr>
                                        <td>{{ $document->title }}</td>
                                        <td>{{ $document->category->name }}</td>
                                        <td>
                                            @if($document->upload_date)
                                                {{ $document->upload_date->format('Y-m-d') }}
                                            @else
                                                Not Available
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-secondary">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No documents found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Display Pagination Links and Range Text --}}
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            {{-- Previous and Next Buttons --}}
                            <div>
                                @if ($currentPage > 1)
                                    <a href="{{ route('documents.search', array_merge(request()->query(), ['page' => $currentPage - 1])) }}" class="btn btn-secondary btn-sm">Previous</a>
                                @endif
                                @if ($totalDocuments > ($currentPage * $perPage))
                                    <a href="{{ route('documents.search', array_merge(request()->query(), ['page' => $currentPage + 1])) }}" class="btn btn-secondary btn-sm">Next</a>
                                @endif
                            </div>

                            {{-- Showing Text --}}
                            <div>
                                Showing {{ (($currentPage - 1) * $perPage) + 1 }} to {{ min($currentPage * $perPage, $totalDocuments) }} of {{ $totalDocuments }} entries
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Display All Categories -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="material-card card">
                <div class="card-body">
                    <h4 class="card-title text-uppercase mb-0 ">All Categories</h4>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover border display table-sm mb-0 no-wrap" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th >Category</th>
                                    <th>Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr data-toggle="collapse" data-target="#collapse{{ $category->id }}" aria-expanded="false" aria-controls="collapse{{ $category->id }}">
                                        <td class="dropdown-toggle">{{ $category->name }}</td>
                                        <td>{{ $category->documents_count }} documents</td>
                                    </tr>
                                    <tr class="collapse" id="collapse{{ $category->id }}">
                                        <td colspan="2">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Title</th>
                                                            <th>Upload Date</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($category->documents as $document)
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
                                                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-secondary">View</a>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3">No documents in this category.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
