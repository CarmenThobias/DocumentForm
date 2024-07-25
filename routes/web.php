<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;



// Route for the upload document form

Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
Route::post('/documents/store', [DocumentController::class, 'store'])->name('documents.store');
Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');


// Route for the search documents page
Route::get('/documents/search', [DocumentController::class, 'search'])->name('documents.search');

// Route for showing all documents (if needed for any reason)
Route::get('/documents/show-all', [DocumentController::class, 'showAll'])->name('documents.showAll');

Route::post('/documents/storeRole', [DocumentController::class, 'storeRole'])->name('documents.storeRole');

