@extends('layouts.app')

@section('content')
    <div class="card mb-4 ">
        <div class="col-md-3 mt-3 mb-9 d-flex justify-content-between" style="background-color:#ffffff;">
            <h4 class="text-uppercase">Upload Document</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- Document Upload Form --}}
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter Document Title" required>
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">Select Existing Category</label>
                    <select name="category_id" id="category_id" class="form-select" style="background-color:#eff4fa;">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="new_category" class="form-label">Or Add New Category</label>
                    <input type="text" name="new_category" id="new_category" class="form-control" placeholder="Enter New Category">
                </div>
                <div class="mb-3">
                    <label for="document" class="form-label">Document</label>
                    <input type="file" name="document" id="document" class="form-control" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-secondary">Upload Document</button>
                    <a href="{{ route('search.documents') }}" class="btn btn-secondary">View Documents</a>
                </div>
            </form>
        </div>
    </div>
@endsection
