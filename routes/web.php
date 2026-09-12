<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('/posts', 'posts.index')->middleware('can:create posts')->name('posts.index');
    Route::livewire('/posts/create', 'posts.create')->middleware('can:create posts')->name('posts.create');
    Route::livewire('/posts/{post}/edit', 'posts.edit')->name('posts.edit');

    Route::livewire('/users', 'users.index')->middleware('can:manage users')->name('users.index');
    Route::livewire('/users/create', 'users.create')->middleware('can:manage users')->name('users.create');
    Route::livewire('/users/{user}/edit', 'users.edit')->middleware('can:manage users')->name('users.edit');
});

require __DIR__.'/settings.php';
