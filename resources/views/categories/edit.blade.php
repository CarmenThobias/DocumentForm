
@extends('layouts.app')

@section('content')
<h4>Edit Category</h4>
<form action="{{ route('categories.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Category Name</label>
        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
    </div>
    <div class="col-md-15 mb-9 d-flex justify-content-between">
    <button type="submit" class="btn btn-outline-primary btn-sm">Update Category</button>
    <a href="{{ route('documents.search') }}" class="btn btn-outline-primary btn-sm" style="font-size: 0.875rem;">Back</a>
</div>
</form>
@endsection
