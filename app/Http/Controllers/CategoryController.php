<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function destroy(Request $request, $id)
    {
        $role = $request->session()->get('role');

        if ($role === 'Admin' || $role === 'Secretary') {
            $category = Category::findOrFail($id);
            if ($category->documents()->exists() || $category->subcategories()->exists()) {
                return redirect()->route('documents.search')->with('error', 'Please delete all documents and subcategories first.');
            }

            $category->delete();
            return redirect()->route('documents.search')->with('success', 'Category deleted successfully.');
        }

        return back()->with('error', 'You do not have permission to delete.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('documents.search')->with('success', 'Category updated successfully!');
    }
    

}
