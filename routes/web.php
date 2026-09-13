<?php

use App\Livewire\PostList;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/blog');
})->name('home');

Route::get('/blog', PostList::class)->name('blog.index');
Route::livewire('/blog/{slug}', 'posts.show')->name('blog.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('/posts', 'posts.index')->middleware('can:create posts')->name('posts.index');
    Route::livewire('/posts/create', 'posts.create')->middleware('can:create posts')->name('posts.create');
    Route::livewire('/posts/{post}/edit', 'posts.edit')->name('posts.edit');

    Route::livewire('/users', 'users.index')->middleware('can:manage users')->name('users.index');
    Route::livewire('/users/create', 'users.create')->middleware('can:manage users')->name('users.create');
    Route::livewire('/users/{user}/edit', 'users.edit')->middleware('can:manage users')->name('users.edit');

    // Categories routes
    Route::livewire('/categories', 'categories.index')->middleware('can:manage roles')->name('categories.index');
    Route::livewire('/categories/create', 'categories.create')->middleware('can:manage roles')->name('categories.create');
    Route::livewire('/categories/{category}/edit', 'categories.edit')->middleware('can:manage roles')->name('categories.edit');
});

Route::view('/version', 'welcome')->name('version');

require __DIR__.'/settings.php';
