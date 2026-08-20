<?php

use App\Http\Controllers\Admin\ActualiteController;
use App\Http\Controllers\Admin\AntenneController;
use App\Http\Controllers\Admin\CandidatureController as AdminCandidatureController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\FormationSessionController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\PartenaireController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\ActualitePublicController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FormationPublicController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/qui-sommes-nous', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactStore'])->name('contact.store');

Route::get('/formations', [FormationPublicController::class, 'index'])->name('formations.index');
Route::get('/formations/{formation:slug}', [FormationPublicController::class, 'show'])->name('formations.show');

Route::get('/actualites', [ActualitePublicController::class, 'index'])->name('actualites.index');
Route::get('/actualites/{actualite:slug}', [ActualitePublicController::class, 'show'])->name('actualites.show');
Route::post('/actualites/{actualite:slug}/commentaires', [CommentController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('actualites.comments.store');

Route::get('/inscription', [CandidatureController::class, 'create'])->name('candidatures.create');
Route::post('/inscription', [CandidatureController::class, 'store'])->name('candidatures.store');
Route::get('/inscription/merci/{candidature:tracking_token}', [CandidatureController::class, 'confirmation'])->name('candidatures.confirmation');
Route::get('/suivi/{token}', [CandidatureController::class, 'track'])->name('candidatures.track');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('antennes', AntenneController::class)->except('show');
    Route::resource('programmes', ProgrammeController::class)->except('show');
    Route::resource('formations', FormationController::class)->except('show');
    Route::resource('formation-sessions', FormationSessionController::class)->except('show');

    Route::get('candidatures/export', [AdminCandidatureController::class, 'export'])->name('candidatures.export');
    Route::resource('candidatures', AdminCandidatureController::class)->only(['index', 'show', 'update', 'destroy']);

    Route::resource('actualites', ActualiteController::class)->except('show');
    Route::resource('team-members', TeamMemberController::class)->except('show');
    Route::resource('partenaires', PartenaireController::class)->except('show');
    Route::resource('hero-slides', HeroSlideController::class)->except('show');

    Route::resource('comments', AdminCommentController::class)->only(['index', 'update', 'destroy']);

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
