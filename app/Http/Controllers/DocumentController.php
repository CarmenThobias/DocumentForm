<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Subcategory;


class DocumentController extends BaseController
{
    public function create(Request $request)
{
    $categories = Category::with('documents','subcategories')->whereNull('category_id')->get();
    $roles = Role::all();
    $document = null;

    if ($request->has('edit')) {
        $document = Document::find($request->input('edit'));
    }

    // Clear the session role to ensure it starts as null
    if (!session()->has('role')) {
        session()->forget('role', null);
    }

    return view('documents.create', compact('categories', 'roles', 'document'));
}


    public function storeRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::find($request->input('role_id'));
        if ($role) {
            $request->session()->put('role', $role->title);
        }
        
        return redirect()->route('documents.create');
    }

    public function store(Request $request)
{
    $roleName = session('role');

    if (!$roleName || !in_array($roleName, ['Admin', 'Secretary'])) {
        return redirect()->route('documents.create')->withErrors(['role' => 'You do not have permission to upload documents.']);
    }
    
    $role = Role::where('title', $roleName)->first();
    if (!$role) {
        return redirect()->route('documents.create')->withErrors(['role' => 'Invalid role specified.']);
    }

    // Determine if we're creating a new document or updating an existing one
    $isUpdate = $request->has('document_id');

    $request->validate([
        'title' => 'required|string|max:255',
        'category_id' => 'nullable|exists:categories,id',
        'subcategory_id' => 'nullable|exists:subcategories,id',
        'new_category' => 'nullable|string|max:255',
        'new_subcategory' => 'nullable|string|max:255',
        'document' => $isUpdate ? 'nullable|file|mimes:pdf,docx|max:10240' : 'required|file|mimes:pdf,docx|max:10240',
    ]);

    // Handle category creation or selection
    if ($request->filled('new_category')) {
        $category = Category::firstOrCreate(['name' => $request->input('new_category')]);
    } elseif ($request->filled('category_id')) {
        $category = Category::find($request->input('category_id'));
    } else {
        return back()->withErrors(['category_id' => 'Please select or create a valid category.']);
    }

    // Handle subcategory creation or selection
    if ($request->filled('new_subcategory')) {
        $subcategory = Subcategory::firstOrCreate(['name' => $request->input('new_subcategory'),
            'category_id' => $category->id
            // 'subcategory_id' => $request->input('subcategory_id', null)
        ]);
    } elseif ($request->filled('subcategory_id')) {
        $subcategory = Subcategory::find($request->input('subcategory_id'));
    } else {
        $subcategory = null;
    }

    // Check if editing an existing document
    if ($isUpdate) {
        $document = Document::find($request->input('document_id'));
        if (!$document) {
            return back()->withErrors(['document_id' => 'Document not found.']);
        }

        $document->title = $request->title;
        $document->category_id = $category->id;
        $document->subcategory_id = $subcategory ? $subcategory->id : null;

        if ($request->hasFile('document')) {
            
            // Delete old file if needed
            if ($document->file_path && Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }

            $document->file_path = $path;
        }

        $document->save();
        return redirect()->route('documents.search')->with('success', 'Document updated successfully.');
    } else {
        // Creating a new document
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $fileName, 'public');

            Document::create([
                'title' => $request->title,
                'category_id' => $category->id,
                'subcategory_id' => $subcategory ? $subcategory->id : null,
                'role_id' => $role->id,
                'file_path' => $path,
                'upload_date' => now(),
            ]);

            return redirect()->route('documents.create')->with('success', 'Document uploaded successfully.');
        }

        return back()->withErrors(['document' => 'Failed to upload document.']);
    }
}

public function search(Request $request)
{
    $searchQuery = $request->input('query', '');
    $sortColumn = $request->input('sort', 'title');
    $sortDirection = $request->input('direction', 'asc');

    // Define valid columns for sorting
    $validColumns = ['title', 'upload_date'];

    if (!in_array($sortColumn, $validColumns)) {
        $sortColumn = 'title';
    }

    // Eager load documents and subcategories with sorting and search query
    $categories = Category::with(['subcategories','documents' => function ($query) use ($searchQuery, $sortColumn, $sortDirection) {
            if ($searchQuery) {
                $query->where('title', 'like', "%{$searchQuery}%");
            }
            $query->orderBy($sortColumn, $sortDirection);
        }])
        
        ->whereNull('category_id') // Get only parent categories
        ->when($searchQuery, function ($query) use ($searchQuery) {
            $query->whereHas('documents', function ($query) use ($searchQuery) {
                $query->where('title', 'like', "%{$searchQuery}%");
            });
        })
        ->paginate(5);

    return view('documents.search', [
        'categories' => $categories,
        'sortColumn' => $sortColumn,
        'sortDirection' => $sortDirection,
        'searchQuery' => $searchQuery,
        'currentPage' => $categories->currentPage(),
        'perPage' => $categories->perPage(),
        'totalCategories' => $categories->total()
    ]);
}


    public function destroy(Request $request, $id)
    {
        $role = $request->session()->get('role');

        if ($role === 'Admin' || $role === 'Secretary') {
            $document = Document::findOrFail($id);
            $document->delete();

            $category = $document->category;
            if ($category && $category->documents()->count() === 0) {
                $category->delete();
            }

            return redirect()->route('documents.search')->with('success', 'Document deleted successfully');
        }

        return back()->with('error', 'You do not have permission to delete documents.');
    }

    public function showAll()
    {
        $categories = Category::with('documents','subcategories')->get();
        return view('documents.create', compact('categories'));
    }

    public function getSubcategories($categoryId)
    {
        $subcategories = Subcategory::where('category_id', $categoryId)->get();
        return response()->json(['subcategories' => $subcategories]);
    }
    
    public function editSubcategory(Request $request, $id)
    {
        $role = $request->session()->get('role');
    
        if ($role === 'Admin' || $role === 'Secretary') {
            $subcategory = Subcategory::findOrFail($id);
            return view('documents.create', compact('subcategory'));
        }
    
        // Optionally, handle unauthorized access here
        return redirect()->route('search')->with('error', 'Unauthorized access.');
    }

    // Method to update subcategory
    public function updateSubcategory(Request $request, $id)
    {
        $role = $request->session()->get('role');

        if ($role === 'Admin' || $role === 'Secretary') {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $subcategory = Subcategory::findOrFail($id);
        $subcategory->name = $request->input('name');
        $subcategory->save();

        return redirect()->route('documents.search')->with('success', 'Subcategory updated successfully');
    }}

    // Method to delete subcategory
    public function destroySubcategory(Request $request, $id)
    {
        $role = $request->session()->get('role');

        if ($role === 'Admin' || $role === 'Secretary') {
            try {
                $subcategory = Subcategory::findOrFail($id);
                
                // Check if there are documents associated with this subcategory
                if ($subcategory->documents()->exists()) {
                    return redirect()->route('documents.search')->with('error', 'First delete documents under the subcategory.');
                }
        
                $subcategory->delete();
                return redirect()->route('documents.search')->with('success', 'Subcategory deleted successfully.');
            } catch (QueryException $e) {
                if ($e->getCode() == '23000') {
                    return redirect()->route('documents.search')->with('error', 'First delete documents under the subcategory.');
                }
                return redirect()->route('documents.search')->with('error', 'An error occurred while deleting the subcategory.');
            }
    
    
}

    
}
}
