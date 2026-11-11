<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Tampilkan E-Ticket Resmi
     */
    public function show(Booking $booking, TmdbService $tmdb)
    {
        // Pastikan hanya pemilik tiket atau admin yang bisa mengakses
        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $booking->load(['showtime.studio.cinema', 'bookingSeats.seat', 'user']);

        return view('tickets.show', [
            'booking' => $booking,
            'tmdb' => $tmdb,
        ]);
    }

    /**
     * Halaman Riwayat Pemesanan Customer
     */
    public function history()
    {
        $bookings = Booking::with(['showtime.studio.cinema', 'bookingSeats.seat'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('tickets.history', compact('bookings'));
    }
}