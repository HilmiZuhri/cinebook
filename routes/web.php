<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\DashboardController;

// Rute Publik Customer Film
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movies', [MovieController::class, 'catalog'])->name('movies.catalog');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

// Rute Pemesanan Tiket (Wajib Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/booking/{showtime}/seats', [BookingController::class, 'selectSeats'])->name('booking.seats');
    Route::post('/booking/{showtime}/seats', [BookingController::class, 'store'])->name('booking.store');

    // Rute Checkout & Pembayaran
    Route::get('/checkout/{booking}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{booking}/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::post('/checkout/{booking}/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

    // Rute E-Ticket & Riwayat Tiket Saya
    Route::get('/tickets/history', [TicketController::class, 'history'])->name('tickets.history');
    Route::get('/tickets/{booking}', [TicketController::class, 'show'])->name('tickets.show');
});

// Rute Jembatan Dashboard
Route::middleware(['auth'])->get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->name('dashboard');

// Rute Logout Darurat
Route::get('/force-logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login')->with('status', 'Berhasil logout!');
});

// Area Khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});