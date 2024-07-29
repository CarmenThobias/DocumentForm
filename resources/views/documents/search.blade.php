@extends('layouts.app')

@section('content')
<div class="page-content container-xxl small-text">
    <div class="row">
        <div class="col-md-12">
            <div class="material-card card">
                <div class="card-body">
                    {{-- Display Error Message --}}
                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="col-md-15 mb-9 d-flex justify-content-between">
                        <h4 class="text-uppercase" style="font-size: 0.875rem;">Search Forms</h4>
                        <div>
                            @if(session('role') === 'Admin' || session('role') === 'Secretary')
                                <a class="btn btn-outline-secondary btn-sm" href="{{ route('documents.create') }}" style="font-size: 0.875rem;">Upload New Form</a>
                            @endif
                        </div>
                    </div>
                    <br>

                    {{-- Search Form --}}
                    <form action="{{ route('documents.search') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3 d-flex justify-content-between">
                                <input type="text" name="query" id="query" class="form-control" placeholder="Search by title or category" value="{{ $searchQuery }}" style="font-size: 0.875rem;">
                                <button type="submit" class="btn btn-primary btn-sm col-md-2 mb-6" style="background-color:#1b2c5d; font-size: 0.875rem;">Search</button>
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
                                    @if($category->documents->count() > 0 || $category->subcategories->count() > 0)
                                        <tr data-toggle="collapse" data-target="#collapse{{ $category->id }}" aria-expanded="false" aria-controls="collapse{{ $category->id }}">
                                            <td class="dropdown-toggle" style="font-size: 0.875rem;">{{ $category->name }}</td>
                                            <td colspan="3" style="font-size: 0.875rem;">
                                                {{ $category->documents->whereNull('subcategory_id')->count() }} forms
                                                @if($category->subcategories->count() > 0)
                                                    , {{ $category->subcategories->count() }} subcategories
                                                @endif
                                            </td>
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
                                                            @foreach($category->documents->whereNull('subcategory_id') as $document)
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
                                                                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-secondary" style="font-size: 0.875rem;">Download</a>
                                                                        @if(session('role') === 'Admin' || session('role') === 'Secretary')
                                                                            <a href="{{ route('documents.create', ['edit' => $document->id]) }}" class="text-warning" style="font-size: 0.875rem; margin-left: 10px;">Edit</a>
                                                                            <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display: inline-block; margin-left: 10px;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-link text-danger" style="font-size: 0.875rem; padding: 0;">Delete</button>
                                                                            </form>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            @foreach($category->subcategories as $subcategory)
                                                                <tr data-toggle="collapse" data-target="#collapseSub{{ $subcategory->id }}" aria-expanded="false" aria-controls="collapseSub{{ $subcategory->id }}">
                                                                    <td class="dropdown-toggle" style="font-size: 0.875rem;">{{ $subcategory->name }}</td>
                                                                    <td colspan="3" style="font-size: 0.875rem;">
                                                                        {{ $subcategory->documents->count() }} forms
                                                                         @if(session('role') === 'Admin' || session('role') === 'Secretary')
                                                                            <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST" style="display: inline-block; margin-left: 10px;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-link text-danger" style="font-size: 0.875rem; padding: 0;">Delete</button>
                                                                            </form>
                                                                            <a href="{{ route('documents.create', ['subcategory_id' => $subcategory->id]) }}" class="text-warning" style="font-size: 0.875rem; margin-left: 10px;">Edit</a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr class="collapse" id="collapseSub{{ $subcategory->id }}">
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
                                                                                    @foreach($subcategory->documents as $document)
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
                                                                                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-secondary" style="font-size: 0.875rem;">Download</a>
                                                                                                @if(session('role') === 'Admin' || session('role') === 'Secretary')
                                                                                                    <a href="{{ route('documents.create', ['edit' => $document->id]) }}" class="text-warning" style="font-size: 0.875rem; margin-left: 10px;">Edit</a>
                                                                                                    <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display: inline-block; margin-left: 10px;">
                                                                                                        @csrf
                                                                                                        @method('DELETE')
                                                                                                        <button type="submit" class="btn btn-link text-danger" style="font-size: 0.875rem; padding: 0;">Delete</button>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
