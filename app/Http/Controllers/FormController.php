<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    public function create(Request $request)
    {
        $categories = Category::with('subcategories')->whereNull('category_id')->get();
        $roles = Role::all();
        $document = null;

        if ($request->has('edit')) {
            $document = Document::find($request->input('edit'));
        }

        return view('documents.create', compact('categories', 'roles', 'document'));
    }

    private function handleCategory(Request $request)
    {
        if ($request->filled('new_category')) {
            return Category::firstOrCreate(['name' => $request->input('new_category')]);
        } elseif ($request->filled('category_id')) {
            return Category::find($request->input('category_id'));
        }
        return null;
    }

    private function handleSubcategory(Request $request, $category)
    {
        if ($request->filled('new_subcategory')) {
            return Subcategory::firstOrCreate([
                'name' => $request->input('new_subcategory'),
                'category_id' => $category->id,
            ]);
        } elseif ($request->filled('subcategory_id')) {
            return Subcategory::find($request->input('subcategory_id'));
        }
        return null;
    }

    private function handleFileUploads(Request $request, Document $document)
    {
        $fileData = [];

        if ($request->hasFile('file_path_pdf')) {
            $path = $request->file('file_path_pdf')->store('documents', 'public');
            $fileData['file_path_pdf'] = basename($path); // Store basename
        }

        if ($request->hasFile('file_path_doc')) {
            $path = $request->file('file_path_doc')->store('documents', 'public');
            $fileData['file_path_doc'] = basename($path); // Store basename
        }

    return $fileData;
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'new_category' => 'nullable|string|max:255',
            'new_subcategory' => 'nullable|string|max:255',
            'file_path_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'file_path_doc' => 'nullable|file|mimes:doc,docx|max:10240',
            
        ]);

        $roleName = session('role');
        $role = Role::where('title', $roleName)->first();

        if (!$role || !in_array($roleName, ['Admin', 'Secretary'])) {
            return redirect()->route('documents.create')->withErrors(['role' => 'You do not have permission to upload documents.']);
        }

        $isUpdate = $request->has('document_id');
        $document = $isUpdate ? Document::find($request->input('document_id')) : new Document;

        if ($isUpdate && !$document) {
            return back()->withErrors(['document_id' => 'Document not found.']);
        }

        $category = $this->handleCategory($request);
        if (!$category) {
            return back()->withErrors(['category_id' => 'Please select or create a valid category.']);
        }

        $subcategory = $this->handleSubcategory($request, $category);

        $fileData = $this->handleFileUploads($request, $document);

        $document->fill([
            'title' => $request->title,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory ? $subcategory->id : null,
            'role_id' => $role->id,
            'file_path_pdf' => $fileData['file_path_pdf'] ?? $document->file_path_pdf,
            'file_path_doc' => $fileData['file_path_doc'] ?? $document->file_path_doc,
            
        ]);

        $document->save();

        $message = $isUpdate ? 'Document updated successfully.' : 'Document uploaded successfully.';
        return redirect()->route('documents.create')->with('success', $message);
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $categories = Category::with('subcategories')->whereNull('category_id')->get();
        $roles = Role::all();

        return view('documents.edit', compact('document', 'categories', 'roles'));
    }

    public function update(Request $request, $id)
{
    // Validation for input fields
    $request->validate([
        'title' => 'required|string|max:255',
        'category_id' => 'nullable|exists:categories,id',
        'subcategory_id' => 'nullable|exists:subcategories,id',
        'new_category' => 'nullable|string|max:255',
        'new_subcategory' => 'nullable|string|max:255',
        'file_path_pdf' => 'nullable|file|mimes:pdf|max:10240',
        'file_path_doc' => 'nullable|file|mimes:doc,docx|max:10240',
    ]);

    // Retrieve the existing document
    $document = Document::findOrFail($id);

    // Handle category and subcategory logic
    if ($request->filled('new_category')) {
        $category = Category::firstOrCreate(['name' => $request->input('new_category')]);
    } elseif ($request->filled('category_id')) {
        $category = Category::find($request->input('category_id'));
    } else {
        $category = $document->category;
    }

    if ($request->filled('new_subcategory')) {
        $subcategory = Subcategory::firstOrCreate([
            'name' => $request->input('new_subcategory'),
            'category_id' => $category ? $category->id : null,
        ]);
    } elseif ($request->filled('subcategory_id')) {
        $subcategory = Subcategory::find($request->input('subcategory_id'));
    } else {
        $subcategory = $document->subcategory;
    }

    // Initialize document data to be updated
    $docData = [
        'title' => $request->input('title'),
        'category_id' => $category ? $category->id : null,
        'subcategory_id' => $subcategory ? $subcategory->id : null,
    ];

    // Handle file uploads and store the paths and original filenames
    $fileData = $this->handleFileUploads($request, $document);

    // Merge the uploaded file data with the document data
    $docData = array_merge($docData, $fileData);

    // Update the document with the merged data
    $document->update($docData);

    // Redirect with a success message
    return redirect()->route('documents.search')->with('success', 'Document updated successfully.');
}


    public function destroy(Request $request, $id)
    {
        $role = $request->session()->get('role');

        if ($role === 'Admin' || $role === 'Secretary') {
            $document = Document::findOrFail($id);
            if ($document->file_path_pdf && Storage::disk('public')->exists($document->file_path_pdf)) {
                Storage::disk('public')->delete($document->file_path_pdf);
            }
            if ($document->file_path_doc && Storage::disk('public')->exists($document->file_path_doc)) {
                Storage::disk('public')->delete($document->file_path_doc);
            }
            $document->delete();

            return redirect()->route('documents.search')->with('success', 'Document deleted successfully.');
        }

        return back()->with('error', 'You do not have permission to delete.');
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('query', '');
        $sortColumn = $request->input('sort', 'title');
        $sortDirection = $request->input('direction', 'asc');

        $validColumns = ['title', 'upload_date'];
        if (!in_array($sortColumn, $validColumns)) {
            $sortColumn = 'title';
        }

        $categories = Category::with(['subcategories', 'documents' => function ($query) use ($searchQuery, $sortColumn, $sortDirection) {
            if ($searchQuery) {
                $query->where('title', 'like', "%{$searchQuery}%");
            }
            $query->orderBy($sortColumn, $sortDirection);
        }])
            ->whereNull('category_id')
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
        ]);
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
}
