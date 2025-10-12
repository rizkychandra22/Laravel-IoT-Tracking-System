<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ========== AUTH ========== //
Route::get('/login', [LoginController::class, 'index'])->name('view.login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// ========== ADMIN ROUTES ========== //

// Halaman admin peta
Route::get('/dashboard/admin', [TrackingController::class, 'index'])->name('indexAdmin');
Route::get('/dashboard/admin/last-tracking', [TrackingController::class, 'lastTracking'])->name('lastTracking');

// Endpoint ESP32 untuk update lokasi (tidak perlu pakai session)
Route::get('/tracking/update', [TrackingController::class, 'storeLocation'])->name('tracking.update');

// AJAX polling (opsional)
Route::get('/tracking/latest', [TrackingController::class, 'getLocation'])->name('getLocation');
Route::get('/tracking/json', [TrackingController::class, 'ajaxTrackingJSON'])->name('ajaxTrackingJSON');

// CRUD admin user (butuh auth)
Route::middleware(['auth', 'userAkses:Admin'])->prefix('dashboard/admin')->group(function () {
    Route::get('/data/user', [AdminUserController::class, 'adminDataUser'])->name('adminDataUser');
    Route::get('/create/user', [AdminUserController::class, 'adminCreateUser'])->name('adminCreateUser');
    Route::post('/store/user', [AdminUserController::class, 'adminStoreUser'])->name('adminStoreUser');
    Route::get('/edit/user/{id}', [AdminUserController::class, 'adminEditUser'])->name('adminEditUser');
    Route::post('/update/user/{id}', [AdminUserController::class, 'adminUpdateUser'])->name('adminUpdateUser');
    Route::delete('/delete/user/{id}', [AdminUserController::class, 'adminDeleteUser'])->name('adminDeleteUser');
});

// ========== USER ROUTES ========== //
Route::middleware(['auth', 'userAkses:User'])->prefix('dashboard/users')->group(function () {
    Route::get('/', [UserController::class, 'indexUser'])->name('indexUser');
});

// ========== DEFAULT ========== //
Route::get('/', function () {
    return view('welcome');
});
