<?php

use App\Http\Controllers\Admin\BillingPlanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JenisRhkController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\RhkController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserSubscriptionController;
use App\Http\Controllers\GpsPhotoController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanDownloadController;
use App\Http\Controllers\PelangganDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Models\BillingPlan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $plans = BillingPlan::public()->get();

    return view('welcome', compact('plans'));
});

Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->isPelanggan()) {
        return redirect()->route('pelanggan.dashboard');
    }

    return redirect()->route('laporan.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// ─── Superadmin ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('rhk', RhkController::class)->except(['show']);
    Route::resource('rhk.jenis-rhk', JenisRhkController::class)->except(['show'])->shallow();

    // Billing
    Route::resource('billing', BillingPlanController::class)->except(['show']);
    Route::resource('subscriptions', UserSubscriptionController::class)->except(['show']);

    // Profil admin
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
});

// ─── Pelanggan ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:pelanggan', 'subscription'])->group(function () {
    Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('pelanggan.dashboard');
    Route::get('/subscription/expired', [SubscriptionController::class, 'expired'])->name('subscription.expired');
    Route::get('/subscription/status', [SubscriptionController::class, 'status'])->name('subscription.status');

    Route::resource('laporan', LaporanController::class);
    Route::get('/rhk/{rhk}/jenis-rhk', [LaporanController::class, 'getJenisRhk'])->name('rhk.jenis-rhk');
    Route::get('/gps-photos', [LaporanController::class, 'getGpsPhotos'])->name('laporan.gps-photos');
    Route::get('/laporan/{laporan}/download/pdf', [LaporanDownloadController::class, 'downloadPdf'])->name('laporan.download.pdf');
    Route::get('/laporan/{laporan}/download/docx', [LaporanDownloadController::class, 'downloadDocx'])->name('laporan.download.docx');

    // Template
    Route::get('/templates', [LaporanController::class, 'templates'])->name('laporan.templates');
    Route::get('/templates/{laporan}/load', [LaporanController::class, 'loadTemplate'])->name('laporan.template.load');
    Route::post('/laporan/{laporan}/save-as-template', [LaporanController::class, 'saveAsTemplate'])->name('laporan.template.save');
    Route::delete('/templates/{laporan}', [LaporanController::class, 'destroyTemplate'])->name('laporan.template.destroy');

    // GPS Photo
    Route::get('/gps-photo', [GpsPhotoController::class, 'index'])->name('gps-photo.index');
    Route::get('/gps-photo/gallery', [GpsPhotoController::class, 'gallery'])->name('gps-photo.gallery');
    Route::post('/gps-photo', [GpsPhotoController::class, 'store'])->name('gps-photo.store');
    Route::delete('/gps-photo/{gpsPhoto}', [GpsPhotoController::class, 'destroy'])->name('gps-photo.destroy');
});

// ─── Profile (semua role) ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
