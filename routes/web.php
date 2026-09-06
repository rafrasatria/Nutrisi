<?php

use App\Http\Controllers\GuruAuthController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\MisiController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SiswaAuthController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Middleware\EnsureGuruLoggedIn;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Siswa (guest)
Route::middleware('guest')->group(function () {
    Route::get('/register', [SiswaAuthController::class, 'showRegister'])->name('siswa.register');
    Route::post('/register', [SiswaAuthController::class, 'register']);
    Route::get('/login', [SiswaAuthController::class, 'showLogin'])->name('siswa.login');
    Route::post('/login', [SiswaAuthController::class, 'login']);
});

// Siswa (auth)
Route::middleware('auth')->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/hasil.pdf', [SiswaDashboardController::class, 'pdf'])->name('pdf');
    Route::get('/kuis', [QuizController::class, 'index'])->name('kuis');
    Route::post('/kuis', [QuizController::class, 'answer'])->name('kuis.jawab');
    Route::get('/misi', [MisiController::class, 'hub'])->name('misi.hub');
    Route::get('/misi/lab-mi', [MisiController::class, 'labMi'])->name('misi.lab-mi');
    Route::post('/misi/lab-mi', [MisiController::class, 'labMiStore'])->name('misi.lab-mi.simpan');
    Route::get('/misi/mencocokkan', [MisiController::class, 'match'])->name('misi.match');
    Route::post('/misi/mencocokkan', [MisiController::class, 'matchStore'])->name('misi.match.simpan');
    Route::get('/misi/perjalanan-mi', [MisiController::class, 'organ'])->name('misi.organ');
    Route::post('/misi/perjalanan-mi', [MisiController::class, 'organStore'])->name('misi.organ.simpan');
    Route::get('/misi/kuis-organ', [MisiController::class, 'organKuis'])->name('misi.organ-kuis');
    Route::post('/misi/kuis-organ', [MisiController::class, 'organKuisStore'])->name('misi.organ-kuis.simpan');
    Route::get('/misi/susun-jalur', [MisiController::class, 'jalur'])->name('misi.jalur');
    Route::post('/misi/susun-jalur', [MisiController::class, 'jalurStore'])->name('misi.jalur.simpan');
    Route::get('/refleksi', [QuizController::class, 'showReflection'])->name('refleksi');
    Route::post('/refleksi', [QuizController::class, 'storeReflection'])->name('refleksi.simpan');
    Route::post('/logout', [SiswaAuthController::class, 'logout'])->name('logout');
});

// Guru
Route::prefix('guru')->name('guru.')->group(function () {
    Route::get('/login', [GuruAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [GuruAuthController::class, 'login']);
    Route::middleware(EnsureGuruLoggedIn::class)->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::get('/rekap.pdf', [GuruDashboardController::class, 'pdf'])->name('rekap-pdf');
        Route::get('/siswa/{id}', [GuruDashboardController::class, 'show'])->name('detail');
        Route::post('/logout', [GuruAuthController::class, 'logout'])->name('logout');
    });
});
