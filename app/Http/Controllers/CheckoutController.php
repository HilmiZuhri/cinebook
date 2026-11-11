<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Tampilkan Halaman Checkout
     */
    public function show(Booking $booking)
    {
        // Pastikan hanya pemilik booking yang bisa melihat
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Cek apakah booking sudah expired
        if ($booking->status === 'pending' && $booking->expires_at && now()->greaterThan($booking->expires_at)) {
            $booking->update(['status' => 'expired']);
        }

        // Jika sudah lunas atau dibatalkan, arahkan ke halaman sesuai
        if ($booking->status === 'paid') {
            return redirect()->route('tickets.show', $booking->id);
        }

        if ($booking->status === 'cancelled' || $booking->status === 'expired') {
            return redirect()->route('home')->with('error', 'Pesanan ini sudah kedaluwarsa atau dibatalkan.');
        }

        $booking->load(['showtime.studio.cinema', 'bookingSeats.seat']);

        return view('booking.checkout', compact('booking'));
    }

    /**
     * Simulasi Pembayaran Berhasil
     */
    public function pay(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('home')->with('error', 'Pesanan tidak valid untuk dibayar.');
        }

        // Simulasi berhasil dibayar
        $booking->update([
            'status' => 'paid',
        ]);

        return redirect()->route('tickets.show', $booking->id)->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    /**
     * Batalkan Pesanan (Melepas Kursi)
     */
    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'pending') {
            $booking->update(['status' => 'cancelled']);
        }

        return redirect()->route('home')->with('info', 'Pesanan berhasil dibatalkan dan kursi telah dilepas.');
    }
}