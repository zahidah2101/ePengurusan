<?php

use App\Livewire\Categories\Index as CategoriesIndex;
use App\Livewire\Complaints\Index as ComplaintsIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('complaints', ComplaintsIndex::class)->name('complaints.index');
    Route::livewire('categories', CategoriesIndex::class)->name('categories.index');
});

require __DIR__.'/settings.php';
