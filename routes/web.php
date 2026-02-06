<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// // Home Page
// Route::view('/', 'home')->name('home');

// // Posts CRUD
// Route::resource('posts', PostController::class);

// // Contact Page
// Route::view('/contact', 'contact')->name('contact');


// // Home Page
Route::view('/', 'home')->name('home');

// Posts CRUD
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/add', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts/store', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/edit/{post}', [PostController::class, 'edit'])->name('posts.edit');
Route::get('/posts/show/{post}', [PostController::class, 'show'])->name('posts.show');
Route::patch('/posts/update/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/delete/{post}', [PostController::class, 'destroy'])->name('posts.delete');

// **Part-2 AJAX Routes**
Route::get('/posts/data', [PostController::class, 'getData'])->name('posts.data');
Route::post('/posts/status-update', [PostController::class, 'statusUpdate'])->name('posts.status.update');
Route::post('/posts/delete-ajax', [PostController::class, 'deleteAjax'])->name('posts.delete.ajax');

// Contact Page
Route::view('/contact', 'contact')->name('contact');


