<?php

use App\Http\Controllers\Admin\CultureItemController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeStatController;
use App\Http\Controllers\Admin\ProvinceController as AdminProvinceController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IslandController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProvinceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/islands', [IslandController::class, 'index'])->name('islands');
Route::get('/province/{province:slug}', [ProvinceController::class, 'show'])->name('province.show');

Route::redirect('/dashboard', '/admin')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('provinces', AdminProvinceController::class)->except(['show']);
    Route::post('provinces/{province}/culture-items', [CultureItemController::class, 'store'])->name('provinces.culture-items.store');
    Route::get('provinces/{province}/culture-items/create', [CultureItemController::class, 'create'])->name('provinces.culture-items.create');
    Route::get('culture-items/{cultureItem}/edit', [CultureItemController::class, 'edit'])->name('culture-items.edit');
    Route::put('culture-items/{cultureItem}', [CultureItemController::class, 'update'])->name('culture-items.update');
    Route::delete('culture-items/{cultureItem}', [CultureItemController::class, 'destroy'])->name('culture-items.destroy');

    Route::resource('team-members', AdminTeamMemberController::class)->except(['show']);

    Route::get('home-stats', [HomeStatController::class, 'index'])->name('home-stats.index');
    Route::get('home-stats/{homeStat}/edit', [HomeStatController::class, 'edit'])->name('home-stats.edit');
    Route::put('home-stats/{homeStat}', [HomeStatController::class, 'update'])->name('home-stats.update');
});

require __DIR__.'/auth.php';
