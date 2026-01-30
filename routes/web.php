<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Home-Seite
Route::view('/', 'home')->name('home');

// Posts CRUD
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/add', [PostController::class, 'create'])->name('posts.add');
Route::post('/posts/store', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/edit/{post}', [PostController::class, 'edit'])->name('posts.edit');
Route::patch('/posts/update/{post}', [PostController::class, 'update'])->name('posts.update');

Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::view('/contact', 'contact')->name('contact');
