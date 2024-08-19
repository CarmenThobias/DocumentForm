@extends('layouts.app')

@section('content')
<div class="page-content container-xxl small-text">
    <div class="row">
        <div class="col-md-12">
            <div class="material-card card">
                <div class="card-body">
                    {{-- Display Error and Success Messages --}}
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
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    @if($category->documents->count() > 0 || $category->subcategories->count() > 0)
                                        <tr data-toggle="collapse" data-target="#collapse{{ $category->id }}" aria-expanded="false" aria-controls="collapse{{ $category->id }}">
                                            <td class="dropdown-toggle" style="font-size: 0.875rem;">{{ $category->name }}</td>
                                            <td>
                                                {{-- Actions for Category --}}
                                                @if(session('role') === 'Admin' || session('role') === 'Secretary')
                                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="collapse" id="collapse{{ $category->id }}">
                                            <td colspan="2">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-sm mb-0" style="font-size: 0.875rem;">
                                                        <thead>
                                                            <tr>
                                                                <th>Subcategory</th>
                                                               
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {{-- Documents within Category --}}
                                                            @foreach($category->documents->whereNull('subcategory_id') as $document)
                                                                <tr>
                                                                    <td>{{ $document->title }}</td>
                                                                    <td>
                                                                        {{-- Document download links --}}
                                                                        @if ($document->file_path_pdf)
                                                                            <a href="{{ asset('storage/documents/' . $document->file_path_pdf) }}" target="_blank" class="text-secondary" style="font-size: 0.875rem;">
                                                                            {{ $document->file_path_pdf }}
                                                                            </a>
                                                                        @endif

                                                                        @if ($document->file_path_doc)
                                                                            <a href="{{ asset('storage/documents/' . $document->file_path_doc) }}" target="_blank" class="text-secondary" style="font-size: 0.875rem;">
                                                                            {{ $document->file_path_doc }}
                                                                            </a>
                                                                        @endif

                                                                        </td>

                                                                </tr>
                                                            @endforeach
                                                            
                                                            {{-- Subcategories within Category --}}
                                                            @foreach($category->subcategories as $subcategory)
                                                                <tr data-toggle="collapse" data-target="#collapseSub{{ $subcategory->id }}" aria-expanded="false" aria-controls="collapseSub{{ $subcategory->id }}">
                                                                    <td class="dropdown-toggle">{{ $subcategory->name }}</td>
                                                                    <td>
                                                                        {{-- Actions for Subcategory --}}
                                                                        @if(session('role') === 'Admin' || session('role') === 'Secretary')
                                                                            <a href="{{ route('subcategories.edit', $subcategory->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                                                                            <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST" style="display:inline-block;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                                                            </form>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr class="collapse" id="collapseSub{{ $subcategory->id }}">
                                                                    <td colspan="2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover table-sm mb-0">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Form</th>
                                                                                        <th>Download</th>
                                                                                        <th>Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    {{-- Documents within Subcategory --}}
                                                                                    @foreach($subcategory->documents as $document)
                                                                                    <tr>
                                                                                    <td>{{ $document->title }}</td>
                                                                                     <td>
                                                                                    @if ($document->file_path_pdf)
                                                                                    <a href="{{ asset('storage/documents/' . $document->file_path_pdf) }}" target="_blank" class="text-secondary" style="font-size: 0.875rem;">
                                                                                    {{ $document->file_path_pdf }}
                                                                                    </a>
                                                                                    @endif

                                                                                    @if ($document->file_path_doc)
                                                                                    <a href="{{ asset('storage/documents/' . $document->file_path_doc) }}" target="_blank" class="text-secondary" style="font-size: 0.875rem;">
                                                                                    {{ $document->file_path_doc }}
                                                                                    </a>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                        {{-- Actions for Document --}}
                                                                        @if(session('role') === 'Admin' || session('role') === 'Secretary')
                                                                            <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                                                                            <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display:inline-block;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
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
