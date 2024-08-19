<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;


// FormController routes
Route::get('/documents/create', [FormController::class, 'create'])->name('documents.create');
Route::post('/documents/store', [FormController::class, 'store'])->name('documents.store');
Route::get('/documents/edit/{id}', [FormController::class, 'edit'])->name('documents.edit');
Route::put('/documents/update/{id}', [FormController::class, 'update'])->name('documents.update');
Route::delete('/documents/{id}', [FormController::class, 'destroy'])->name('documents.destroy');
Route::get('/documents/search', [FormController::class, 'search'])->name('documents.search');
Route::post('/documents/store-role', [FormController::class, 'storeRole'])->name('documents.storeRole');

// CategoryController routes
Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// SubcategoryController routes
Route::get('/subcategories/edit/{id}', [SubcategoryController::class, 'edit'])->name('subcategories.edit');
Route::put('/subcategories/update/{id}', [SubcategoryController::class, 'update'])->name('subcategories.update');
Route::delete('/subcategories/{id}', [SubcategoryController::class, 'destroy'])->name('subcategories.destroy');





