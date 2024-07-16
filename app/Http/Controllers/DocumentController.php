<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function create()
    {
        // Fetch all categories with their associated documents
        $categories = Category::with('documents')->get();
        return view('documents.create', compact('categories'));
    }

    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'title' => 'required|string|max:255',
        'category_id' => 'nullable|exists:categories,id',
        'new_category' => 'nullable|string|max:255',
        'document' => 'required|file|mimes:pdf,docx|max:10240',  // max 10MB file size
    ]);

    // Check if a new category was provided and create it
    if ($request->filled('new_category')) {
        $category = Category::firstOrCreate(['name' => $request->input('new_category')]);
    } else {
        $category = Category::find($request->input('category_id'));
    }

    if (!$category) {
        return back()->withErrors(['category_id' => 'Please select or create a valid category.']);
    }

    // Handle file upload
    if ($request->hasFile('document')) {
        $file = $request->file('document');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents', $fileName, 'public');
        $uploadDate = now();

        // Create a new document record
        Document::create([
            'title' => $request->title,
            'category_id' => $category->id,
            'file_path' => $path,
            'upload_date' => $uploadDate, 
        ]);

        return redirect()->route('documents.create')->with('success', 'Document uploaded successfully.');
    }

    return back()->withErrors(['document' => 'Failed to upload document.']);
}


public function search(Request $request)
{
    $searchQuery = $request->input('query');
    $sortColumn = $request->input('sort', 'title');  // Default to 'title' if no sort column is provided
    $sortDirection = $request->input('direction', 'asc');  // Default to 'asc' if no sort direction is provided

    // Validate the sort column
    $validColumns = ['title', 'category', 'upload_date'];
    if (!in_array($sortColumn, $validColumns)) {
        $sortColumn = 'title';
    }

    // Fetch documents based on the search query
    $documentsQuery = Document::query()
        ->where(function($query) use ($searchQuery) {
            $query->where('title', 'like', '%' . $searchQuery . '%')
                  ->orWhereHas('category', function($query) use ($searchQuery) {
                      $query->where('name', 'like', '%' . $searchQuery . '%');
                  });
        })
        ->join('categories', 'documents.category_id', '=', 'categories.id')  // Ensure join for sorting by category name
        ->orderBy($sortColumn === 'category' ? 'categories.name' : $sortColumn, $sortDirection);

    $totalDocuments = $documentsQuery->count();  // Get the total number of documents

    // Paginate manually
    $currentPage = $request->input('page', 1);  // Get the current page or default to 1
    $perPage = 5;  // Number of documents per page
    $documents = $documentsQuery->skip(($currentPage - 1) * $perPage)->take($perPage)->get();  // Fetch documents for the current page

    // Fetch categories for the sidebar
    $categories = Category::withCount('documents')->get();

    return view('documents.search', [
        'documents' => $documents,
        'sortColumn' => $sortColumn,
        'sortDirection' => $sortDirection,
        'categories' => $categories,
        'totalDocuments' => $totalDocuments,
        'currentPage' => $currentPage,
        'perPage' => $perPage,
        'searchQuery' => $searchQuery,  // Pass the search query to the view
    ]);
}


    public function showAll()
    {
        // Fetch all categories with their associated documents
        $categories = Category::with('documents')->get();
        return view('documents.create', compact('categories'));
    }

}
