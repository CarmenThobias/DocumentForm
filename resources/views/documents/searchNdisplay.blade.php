@extends('layouts.app')

@section('content')
<div class="page-content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="material-card card">
                <div class="card-body">
                    <div class="col-md-15 mb-9 d-flex justify-content-between">
                        <h4 class="text-uppercase">Search Documents</h4>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('documents.create') }}">Upload New Document</a>
                    </div>
                    <br>

                    {{-- Search Form --}}
                    <form action="{{ route('documents.search') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3 d-flex justify-content-between">
                                <input type="text" name="query" id="query" class="form-control" placeholder="Search by title or category" value="{{ request('query') }}">
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
                                        <a href="{{ route('documents.search', array_merge(request()->query(), ['sort' => 'title', 'direction' => $sortColumn === 'title' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}"  style="color:#071953;">
                                            Title
                                            @if($sortColumn === 'title')
                                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('documents.search', array_merge(request()->query(), ['sort' => 'category', 'direction' => $sortColumn === 'category' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}"  style="color:#071953;">
                                            Category
                                            @if($sortColumn === 'category')
                                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('documents.search', array_merge(request()->query(), ['sort' => 'upload_date', 'direction' => $sortColumn === 'upload_date' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}"  style="color:#071953;">
                                            Upload Date
                                            @if($sortColumn === 'upload_date')
                                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Actions</th>
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
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-primary btn-sm" style="background-color:#071953;">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No documents found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{-- Display pagination links --}}
                        <div class="dataTables_paginate paging_simple_numbers" id="file_export_paginate">
                            {{ $documents->appends(request()->query())->links('pagination::bootstrap-4') }}
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
                    <h4 class="card-title text-uppercase mb-0">All Categories</h4>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover border display table-sm mb-0 no-wrap" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr data-toggle="collapse" data-target="#collapse{{ $category->id }}" aria-expanded="false" aria-controls="collapse{{ $category->id }}">
                                        <td>{{ $category->name }}</td>
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
                                                        @forelse($category->documents as $document)
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
                                                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-info btn-sm">View</a>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3">No documents available for this category.</td>
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

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#file_export').DataTable({
            paging: true,
            searching: false,
            info: false,
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'csv', 'pdf'
            ]
        });

        // Initialize the table for categories
        $('#categoriesTable').on('click', 'tr[data-toggle="collapse"]', function() {
            $(this).nextUntil('tr[data-toggle="collapse"]').each(function() {
                $(this).collapse('toggle');
            });
        });
    });
</script>
@endsection
