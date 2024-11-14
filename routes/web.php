<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\website\HomeController;
use App\Http\Controllers\website\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home'); 
Route::resource('categories', HomeController::class); 

Route::get('/category/{id}',  [HomeController::class, 'showCategory'])->name('show-category');
Route::get('/tag/{id}',  [HomeController::class, 'showTag'])->name('show-tag');
Route::get('/search/posts', [HomeController::class, 'searchPosts'])->name('search.posts');
Route::get('/post/{id}', [HomeController::class, 'showPost'])->name('show.post');
Route::get('/author/post/{author}', [HomeController::class, 'showAuthorPost'])->name('show.author.post');


require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';