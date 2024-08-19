<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use Illuminate\Database\QueryException;

class SubcategoryController extends Controller
{
    public function destroy(Request $request, $id)
    {
        $role = $request->session()->get('role');

        if ($role === 'Admin' || $role === 'Secretary') {
            {
                $subcategory = Subcategory::findOrFail($id);
                
                if ($subcategory->documents()->exists()) {
                    return redirect()->route('documents.search')->with('error', 'Please delete all documents in this subcategory first.');
                }
        
                $subcategory->delete();
                return redirect()->route('documents.search')->with('success', 'Subcategory deleted successfully.');
            } 
        }

        return back()->with('error', 'You do not have permission to delete.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name,' . $id,
        ]);

        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update(['name' => $request->input('name')]);

        return redirect()->route('documents.search')->with('success', 'Subcategory updated successfully.');
    }

    public function edit($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        return view('subcategories.edit', compact('subcategory'));
    }

}
