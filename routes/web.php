<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Register page (public)
Route::get('/register', function () {
    return view('register');
})->name('register');

// ================= AUTH ================= //
Route::get('/login', [LoginController::class, 'index'])->name('view.login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| TRACKING & API ROUTES (TANPA AUTH)
|--------------------------------------------------------------------------
| Digunakan oleh ESP32 & AJAX
*/

// ESP32 kirim lokasi
Route::get('/tracking/update', [TrackingController::class, 'storeLocation'])
    ->name('tracking.update');

// Ambil lokasi terakhir (AJAX map)
Route::get('/tracking/latest', [TrackingController::class, 'getLocation'])
    ->name('getLocation');

// JSON semua tracking (opsional)
Route::get('/tracking/json', [TrackingController::class, 'ajaxTrackingJSON'])
    ->name('ajaxTrackingJSON');

// 🔥 ALGORITMA DIJKSTRA (WAJIB TANPA AUTH)
Route::get('/tracking/dijkstra', [TrackingController::class, 'dijkstraRoute'])
    ->name('tracking.dijkstra');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'userAkses:Admin'])
    ->prefix('dashboard/admin')
    ->group(function () {

    // Dashboard admin
    Route::get('/', [TrackingController::class, 'index'])
        ->name('indexAdmin');

    Route::get('/last-tracking', [TrackingController::class, 'lastTracking'])
        ->name('lastTracking');

    // CRUD Admin User
    Route::get('/data/user', [AdminUserController::class, 'adminDataUser'])
        ->name('adminDataUser');

    Route::get('/create/user', [AdminUserController::class, 'adminCreateUser'])
        ->name('adminCreateUser');

    Route::post('/register', [AdminUserController::class, 'adminStoreUser'])
        ->name('register.store');

    Route::get('/edit/user/{id}', [AdminUserController::class, 'adminEditUser'])
        ->name('adminEditUser');

    Route::post('/update/user/{id}', [AdminUserController::class, 'adminUpdateUser'])
        ->name('adminUpdateUser');

    Route::delete('/delete/user/{id}', [AdminUserController::class, 'adminDeleteUser'])
        ->name('adminDeleteUser');
});

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'userAkses:User'])
    ->prefix('dashboard/users')
    ->group(function () {

    Route::get('/', [UserController::class, 'indexUser'])
        ->name('indexUser');
});
