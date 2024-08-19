@extends('layouts.app')

@section('content')
<div class="card mb-4">
    <div class="col-md-3 mt-3 mb-9 d-flex justify-content-between" style="background-color:#ffffff; font-size: 0.875rem;">
        <h4 class="text-uppercase" style="font-size: 0.875rem;">{{ isset($document) ? 'Edit Document' : 'Upload Form' }}</h4>
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
                    <label for="role_id" class="col-md-4 col-form-label text-md-right">Select Role</label>
                    <div class="col-md-6">
                        <select id="role_id" class="form-control @error('role') is-invalid @enderror" name="role_id" required>
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
            @if (session('role') == 'Admin' || session('role') == 'Secretary')
                <form id="uploadForm" action="{{ isset($document) ? route('documents.update', $document->id) : route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($document))
                        @method('PUT')
                    @endif
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter Form Title" required style="font-size: 0.875rem;" value="{{ old('title', isset($document) ? $document->title : '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Select Existing Category</label>
                        <select name="category_id" id="category_id" class="form-select" style="background-color:#eff4fa; font-size: 0.875rem;" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', isset($document) && $document->category_id == $category->id ? 'selected' : '') }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="new_category" class="form-label">Or Add New Category</label>
                        <input type="text" name="new_category" id="new_category" class="form-control" placeholder="Enter New Category" style="font-size: 0.875rem;" value="{{ old('new_category') }}">
                    </div>
                    <div class="mb-3">
                        <label for="subcategory_id" class="form-label">Select Subcategory</label>
                        <select name="subcategory_id" id="subcategory_id" class="form-select" style="background-color:#eff4fa; font-size: 0.875rem;" required>
                            <option value="">Select Subcategory</option>
                            @foreach ($categories as $category)
                                @foreach ($category->subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('subcategory_id', isset($document) && $document->subcategory_id == $subcategory->id ? 'selected' : '') }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="new_subcategory" class="form-label">Or Add New Subcategory</label>
                        <input type="text" name="new_subcategory" id="new_subcategory" class="form-control" placeholder="Enter New Subcategory" style="font-size: 0.875rem;" value="{{ old('new_subcategory') }}">
                    </div>
                    @if (isset($document))
                        <div class="mb-3">
                            <label for="file_path_doc" class="form-label">Update DOC</label>
                            <input type="file" name="file_path_doc" id="file_path_doc" class="form-control" accept=".doc,.docx" placeholder="Upload .doc" required>
                            <button type="button" class="btn btn-secondary add_input_button ms-2">
                                    <span>+</span> 
                                </button>
                        </div>
                        @else
                        <div class="field_wrapper">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <label for="file_path_doc" class="form-label">Upload DOC</label>
                                <input type="file" id="file_path_doc" name="file_path_doc" class="form-control" accept=".doc,.docx" placeholder="Upload .doc" required>
                                <button type="button" class="btn btn-secondary add_input_button ms-2">
                                    <span>+</span> 
                                </button>
                            </div>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-secondary" style="font-size: 0.875rem;">{{ isset($document) ? 'Update Form' : 'Upload Form' }}</button>
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

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    var max_fields = 2; // Maximum input boxes allowed
    var add_input_button = document.querySelector('.add_input_button'); // Add button selector
    var field_wrapper = document.querySelector('.field_wrapper'); // Input field wrapper
    var input_count = 1; // Initial input count

    // Function to add new input field
    function addInputField() {
        if (input_count < max_fields) {
            input_count++;
            var new_field = document.createElement('div');
            new_field.classList.add('mb-3', 'd-flex', 'justify-content-between', 'align-items-center');
            new_field.innerHTML = `
                <label for="file_path_pdf" class="form-label">Upload PDF</label>
                <input type="file" id="file_path_pdf" name="file_path_pdf" class="form-control" accept=".pdf" placeholder="Upload .pdf">
                <button type="button" class="btn btn-outline-danger btn-sm remove_input_button ms-2">Remove</button>
            `;
            field_wrapper.appendChild(new_field);

            // Add event listener to remove button
            new_field.querySelector('.remove_input_button').addEventListener('click', function() {
                field_wrapper.removeChild(new_field);
                input_count--;
            });
        }
    }

    // Add new input field on plus button click
    add_input_button.addEventListener('click', addInputField);
});
</script>
@endsection
