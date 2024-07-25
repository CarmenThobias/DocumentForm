@extends('layouts.app')

@section('content')
<div class="card mb-4">
    <div class="col-md-3 mt-3 mb-9 d-flex justify-content-between" style="background-color:#ffffff; font-size: 0.875rem;">
        <h4 class="text-uppercase" style="font-size: 0.875rem;">Upload Form</h4>
    </div>
    <div class="card-body" style="font-size: 0.875rem;">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Role Selection Form --}}
        @if (!session('role'))
            <form method="POST" action="{{ route('documents.storeRole') }}">
                @csrf
                <div class="form-group row">
                    <label for="role" class="col-md-4 col-form-label text-md-right">Select Role</label>
                    <div class="col-md-6">
                        <select id="role" class="form-control @error('role') is-invalid @enderror" name="role_id" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->title }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Continue
                        </button>
                    </div>
                </div>
            </form>
        @else
            {{-- Document Upload Form --}}
            @if (session('role') == 'Admin' || session('role') == 'Secretary')
                <form id="uploadForm" action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter Form Title" required style="font-size: 0.875rem;">
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Select Existing Category</label>
                        <select name="category_id" id="category_id" class="form-select" style="background-color:#eff4fa; font-size: 0.875rem;">
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
                        <input type="text" name="new_category" id="new_category" class="form-control" placeholder="Enter New Category" style="font-size: 0.875rem;">
                    </div>
                    <div class="mb-3">
                        <label for="document" class="form-label">Form</label>
                        <input type="file" name="document" id="document" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-secondary" style="font-size: 0.875rem;">Upload Form</button>
                        <a href="{{ route('documents.search') }}" class="btn btn-secondary" style="font-size: 0.875rem;">View Forms</a>
                    </div>
                </form>
            @else
                <p class="text-danger">You do not have permission to upload documents.</p>
                <a href="{{ route('documents.search') }}" class="btn btn-secondary" style="font-size: 0.875rem;">View Forms</a>
            @endif
        @endif
    </div>
</div>
@endsection
