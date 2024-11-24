<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController; 
use App\Http\Controllers\TpsController;
use App\Http\Controllers\SettingController;

Route::get('/', function () {
    return view('home');
});

// Rute untuk dashboard user dan admin
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/user', [UserDashboardController::class, 'index'])->name('dashboard.user');
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin'); 
});

Route::post('/api/update-admin-phone', [SettingController::class, 'updateAdminPhone']);
Route::get('/api/get-admin-phone', [SettingController::class, 'getAdminPhone']);
Route::get('/settings/whatsapp', [SettingController::class, 'getWhatsApp']);
Route::put('/tps/{id}', [TpsController::class, 'update'])->name('tps.update');

Route::post('/tps', [TpsController::class, 'store'])->name('tps.store');
Route::delete('/tps/{id}', [TpsController::class, 'destroy'])->name('tps.destroy');

Route::post('/add-tps', [TpsController::class, 'store']);
Route::delete('/delete-tps/{id}', [TpsController::class, 'destroy']);

Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute untuk dashboard, dilindungi oleh middleware 'auth'
Route::get('/dashboard', function () {
    $user = auth()->user();

    // Cek role pengguna dan arahkan ke dashboard yang sesuai
    if ($user->role === 'admin') {
        return redirect()->route('dashboard.admin'); // Arahkan ke dashboard admin
    } elseif ($user->role === 'user') {
        return redirect()->route('dashboard.user');  // Arahkan ke dashboard user
    }

    abort(403, 'Unauthorized access'); // Untuk penanganan jika role tidak sesuai
})->middleware(['auth'])->name('dashboard');

// Rute untuk register
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

// Proteksi rute profile dengan middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Termasuk rute otentikasi yang dihasilkan oleh Laravel Breeze
require __DIR__.'/auth.php';
