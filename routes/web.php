<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\FaqController;


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
Route::get('/faq', [FaqController::class,'index'])->name('faq.index');
Route::get('/faq/hr', [FaqController::class, 'showHR'])->name('faq.hr');
Route::get('/faq/it', [FaqController::class, 'showIT'])->name('faq.it');
Route::get('/faq/hs', [FaqController::class, 'showHS'])->name('faq.hs');
Route::get('/faq/ln', [FaqController::class, 'showLN'])->name('faq.ln');
Route::get('/faq/er', [FaqController::class, 'showER'])->name('faq.er');
Route::get('/faq/ut', [FaqController::class, 'showUT'])->name('faq.ut');
Route::get('/faqform', [FaqController::class, 'faqform'])->name('faq.form');
Route::post('/faq/store', [FaqController::class, 'store'])->name('faq.store');
Route::get('/faq/{id}', [FaqController::class, 'show'])->name('faq.show');
Route::get('/faq/{subjectSlug}', [FaqController::class, 'showSubject'])->name('faq.subject');
Route::get('/faq/create', [FaqController::class, 'create'])->name('faq.create');







