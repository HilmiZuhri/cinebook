<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\User;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Kartu Statistik Utama
        $totalRevenue = Booking::where('status', 'paid')->sum('total_price');
        
        $totalTicketsSold = BookingSeat::whereHas('booking', function ($q) {
            $q->where('status', 'paid');
        })->count();

        $totalBookings = Booking::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // 2. Daftar Transaksi Terkini (dengan filter status opsional)
        $statusFilter = $request->query('status');
        $recentBookings = Booking::with(['user', 'showtime.studio.cinema', 'bookingSeats.seat'])
            ->when($statusFilter, fn($q) => $q->where('status', $statusFilter))
            ->latest()
            ->paginate(10);

        // 3. Laporan Film Terlaris (Top Movies by Revenue)
        $topMovies = Showtime::select('movie_title', 'tmdb_movie_id')
            ->selectRaw('COUNT(booking_seats.id) as tickets_sold')
            ->selectRaw('SUM(bookings.total_price) as total_revenue')
            ->join('bookings', 'showtimes.id', '=', 'bookings.showtime_id')
            ->join('booking_seats', 'bookings.id', '=', 'booking_seats.booking_id')
            ->where('bookings.status', 'paid')
            ->groupBy('showtimes.movie_title', 'showtimes.tmdb_movie_id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalTicketsSold',
            'totalBookings',
            'totalCustomers',
            'recentBookings',
            'topMovies',
            'statusFilter'
        ));
    }
}