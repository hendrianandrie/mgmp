<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AiGeneratorController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Storage Link Web Helper
Route::get('/symlink', function () {
    try {
        Artisan::call('storage:link');
        return 'Storage link berhasil dibuat!';
    } catch (\Exception $e) {
        $target = storage_path('app/public');
        $shortcut = public_path('storage');
        if (!file_exists($shortcut)) {
            @symlink($target, $shortcut);
            return 'Storage link symlink() berhasil!';
        }
        return 'Storage link sudah ada: ' . $e->getMessage();
    }
});

// Public Landing Page & Tools
Route::get('/', [PublicController::class, 'index'])->name('home');

Route::get('/generator-modul', [AiGeneratorController::class, 'index'])->name('generator.index');
Route::post('/generator-modul', [AiGeneratorController::class, 'generate'])->name('generator.generate');
Route::post('/generator-soal', [AiGeneratorController::class, 'generateSoal'])->name('generator.generateSoal');
Route::get('/bank-soal', [QuestionController::class, 'index'])->name('questions.index');
Route::get('/bank-soal/download/{id}', [QuestionController::class, 'download'])->name('questions.download');
Route::get('/bahan-ajar', [MaterialController::class, 'index'])->name('materials.index');

// Public Dokumentasi Kegiatan
Route::get('/dokumentasi', [DocumentationController::class, 'index'])->name('documentations.index');
Route::get('/dokumentasi/{id}', [DocumentationController::class, 'show'])->name('documentations.show');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Akun User (Admin)
    Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/users/{id}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');

    // Keanggotaan & Sekolah
    Route::resource('schools', SchoolController::class)->only(['index', 'store', 'destroy']);
    Route::resource('members', MemberController::class)->only(['index', 'store', 'destroy']);
    Route::resource('positions', PositionController::class)->only(['index', 'store']);

    // Kegiatan & Presensi
    Route::resource('activities', ActivityController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::get('/presensi/scan/{token}', [AttendanceController::class, 'scan'])->name('presensi.scan');

    // Dokumentasi Kegiatan (Kelola / Upload)
    Route::post('/dokumentasi', [DocumentationController::class, 'store'])->name('documentations.store');
    Route::put('/dokumentasi/{id}', [DocumentationController::class, 'update'])->name('documentations.update');
    Route::post('/dokumentasi/{id}/photos', [DocumentationController::class, 'storePhoto'])->name('documentations.storePhoto');
    Route::delete('/dokumentasi/{id}', [DocumentationController::class, 'destroy'])->name('documentations.destroy');
    Route::delete('/dokumentasi/photo/{id}', [DocumentationController::class, 'destroyPhoto'])->name('documentations.destroyPhoto');

    // Pembelajaran & Bank Soal
    Route::post('/bahan-ajar', [MaterialController::class, 'store'])->name('materials.store');
    Route::put('/bahan-ajar/{id}', [MaterialController::class, 'update'])->name('materials.update');
    Route::delete('/bahan-ajar/{id}', [MaterialController::class, 'destroy'])->name('materials.destroy');
    Route::post('/bank-soal', [QuestionController::class, 'store'])->name('questions.store');
    Route::delete('/bank-soal/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Informasi & Pengumuman
    Route::resource('announcements', AnnouncementController::class)->only(['index', 'store', 'update', 'destroy']);
});
