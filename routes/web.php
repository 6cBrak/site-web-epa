<?php

use App\Http\Controllers\Admin\AntenneController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\FormationSessionController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('antennes', AntenneController::class)->except('show');
    Route::resource('programmes', ProgrammeController::class)->except('show');
    Route::resource('formations', FormationController::class)->except('show');
    Route::resource('formation-sessions', FormationSessionController::class)->except('show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
