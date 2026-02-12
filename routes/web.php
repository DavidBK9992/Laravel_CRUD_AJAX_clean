<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// // Home Page
Route::view('/', 'home')->name('home');

// AJAX routes first
Route::get('/posts/data', [PostController::class, 'getData'])->name('posts.data');
Route::post('/posts/status-update', [PostController::class, 'statusUpdate'])->name('posts.status.update');
Route::post('/posts/delete-ajax', [PostController::class, 'deleteAjax'])->name('posts.delete.ajax');

// Resource routes
Route::resource('posts', PostController::class);

// Contact Page
Route::view('/contact', 'contact')->name('contact');


