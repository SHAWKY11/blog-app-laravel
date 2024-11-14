<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;


Route::get('/dashboard',[DashboardController ::class,'show_details'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{id}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggleActive');

Route::resource('comments', CommentController::class);
Route::post('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::post('/comments/{id}/toggle-active', [CommentController::class, 'toggleActive'])->name('comments.toggleActive');

Route::resource('tags', TagController::class);
Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');
Route::post('/tags/store-or-fetch', [TagController::class, 'storeOrFetch'])->name('tags.storeOrFetch');
Route::post('/tags/{id}/toggle-active', [TagController::class, 'toggleActive'])->name('tags.toggleActive');



Route::resource('posts', PostController::class);
Route::post('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::post('/posts/{id}/toggle-active', [PostController::class, 'toggleActive'])->name('posts.toggleActive');
Route::get('/search', [PostController::class, 'search'])->name('posts.search');

Route::resource('media', MediaController::class);
Route::post('/media/store', [MediaController::class, 'store'])->name('media.store');
Route::post('/media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');

Route::resource('admins', AdminController::class);
Route::post('/admins/{id}/toggle-active', [AdminController::class, 'toggleActive'])->name('admins.toggleActive');

});