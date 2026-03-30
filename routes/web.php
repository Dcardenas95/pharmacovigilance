<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', function () {
    return redirect()->route('order-search');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('order-search', function () {
    return Inertia::render('OrderSearch');
})->middleware(['auth', 'verified'])->name('order-search');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
