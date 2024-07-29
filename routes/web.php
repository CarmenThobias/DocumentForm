<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;



// Route for the upload document form

Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
Route::post('/documents/store', [DocumentController::class, 'store'])->name('documents.store');
Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
// Route to delete a document

// Route for the search documents page
Route::get('/documents/search', [DocumentController::class, 'search'])->name('documents.search');

// Route for showing all documents (if needed for any reason)
Route::get('/documents/show-all', [DocumentController::class, 'showAll'])->name('documents.showAll');

Route::post('/documents/storeRole', [DocumentController::class, 'storeRole'])->name('documents.storeRole');

Route::get('documents/{id}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
Route::put('documents/{id}', [DocumentController::class, 'update'])->name('documents.update');


Route::get('/documents/subcategories', [DocumentController::class, 'getSubcategories'])->name('subcategories.getSubcategories');

Route::get('/subcategories/{subcategory}/edit', [DocumentController::class, 'editSubcategory'])->name('subcategories.edit');
Route::put('/subcategories/{subcategory}', [DocumentController::class, 'updateSubcategory'])->name('subcategories.update');
Route::delete('/subcategories/{id}', [DocumentController::class, 'destroySubcategory'])->name('subcategories.destroy');

