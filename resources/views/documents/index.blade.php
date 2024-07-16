<form action="{{ route('documents.search') }}" method="GET">
    <label for="category_search">Search by Category:</label>
    <input type="text" name="category_search" id="category_search" required>
    <button type="submit">Search</button>
</form>

@foreach ($documents as $document)
    <div>
        <h3>{{ $document->title }}</h3>
        <p>Category: {{ $document->category }}</p>
        <a href="{{ Storage::url($document->file_path) }}" target="_blank">View Document</a>
    </div>
@endforeach
