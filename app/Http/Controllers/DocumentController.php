<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;

class DocumentController extends BaseController
{
    public function create()
    {   
        $categories = Category::with('documents')->get();
        $roles = Role::all();

        // Clear the session role to ensure it starts as null
        if (!session()->has('role')) {
            session()->forget('role', null);
        }

        return view('documents.create', compact('categories', 'roles'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::find($request->input('role_id'));
        $request->session()->put('role', $role->title);

        return redirect()->route('documents.create');

        session()->forget('role');
        return redirect()->route('documents.create');
    }

    public function store(Request $request)
    {
        $role = Role::find($request->input('role_id'));

        $request->session()->put('role', $role->title);

        if (!$role || !in_array($role->title, ['admin', 'secretary'])) {
            return redirect()->route('documents.create')->withErrors(['role' => 'You do not have permission to upload documents.']);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'new_category' => 'nullable|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'document' => 'required|file|mimes:pdf,docx|max:10240',
        ]);

        // Handle category creation or selection
        if ($request->filled('new_category')) {
            $category = Category::firstOrCreate(['name' => $request->input('new_category')]);
        } else {
            $category = Category::find($request->input('category_id'));
        }

        if (!$category) {
            return back()->withErrors(['category_id' => 'Please select or create a valid category.']);
        }

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $fileName, 'public');

            Document::create([
                'title' => $request->title,
                'category_id' => $category->id,
                'role_id' => $request->role_id,
                'file_path' => $path,
                'upload_date' => now(),
            ]);

            return redirect()->route('documents.create')->with('success', 'Document uploaded successfully.');
        }

        return back()->withErrors(['document' => 'Failed to upload document.']);
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

        // Eager load documents with sorting and search query
        $categories = Category::with(['documents' => function ($query) use ($searchQuery, $sortColumn, $sortDirection) {
            if ($searchQuery) {
                $query->where('title', 'like', "%{$searchQuery}%");
            }
            $query->orderBy($sortColumn, $sortDirection);
        }])
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

        if ($role === 'admin' || $role === 'secretary') {
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
        $categories = Category::with('documents')->get();
        return view('documents.create', compact('categories'));
    }
}
