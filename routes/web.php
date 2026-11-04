<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Rute Beranda Publik
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rute Jembatan Dashboard (Mengarahkan sesuai role agar tidak pernah 404)
Route::middleware(['auth'])->get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->name('dashboard');

// Rute Logout Darurat/Instan (Bisa diakses via link GET di URL untuk mempermudah dev)
Route::get('/force-logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login')->with('status', 'Berhasil logout!');
});

// Area Khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return '
            <div style="font-family: sans-serif; padding: 40px; background: #0f172a; color: white; min-height: 100vh;">
                <h1 style="color: #ef4444;">Admin Dashboard CineBook</h1>
                <p>Halo, <strong>' . e(auth()->user()->name) . '</strong> (' . e(auth()->user()->role) . ')</p>
                <p>Email: ' . e(auth()->user()->email) . '</p>
                <div style="margin-top: 20px;">
                    <a href="/force-logout" style="background: #dc2626; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold;">Logout</a>
                </div>
            </div>
        ';
    })->name('dashboard');
});