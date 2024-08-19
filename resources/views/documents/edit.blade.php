
@extends('layouts.app')

@section('content')
<h4>Edit Document</h4>
<form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="title">Document Title</label>
        <input type="text" name="title" class="form-control" value="{{ $document->title }}" required>
    </div>
    <div class="form-group">
        <label for="file">Upload New File</label>
        <input type="file" name="file" class="form-control">
    </div>
    <div class="col-md-15 mb-9 d-flex justify-content-between">
    <button type="submit" class="btn btn-outline-primary btn-sm">Update Document</button>
    <a href="{{ route('documents.search') }}" class="btn btn-outline-primary btn-sm" style="font-size: 0.875rem;">Back</a>
</div>
</form>
@endsection
