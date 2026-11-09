<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Halaman Pemilihan Kursi
     */
    public function selectSeats(Showtime $showtime)
    {
        // Ambil relasi studio, bioskop, dan semua kursi di studio tersebut
        $showtime->load(['studio.cinema', 'studio.seats']);

        // Ambil ID kursi yang sudah dipesan untuk jadwal ini (status pending atau paid)
        $bookedSeatIds = BookingSeat::whereHas('booking', function ($query) use ($showtime) {
            $query->where('showtime_id', $showtime->id)
                  ->whereIn('status', ['pending', 'paid']);
        })->pluck('seat_id')->toArray();

        // Kelompokkan kursi per baris (A, B, C, dst)
        $seatsByRow = $showtime->studio->seats->groupBy('row');

        return view('booking.seats', [
            'showtime' => $showtime,
            'seatsByRow' => $seatsByRow,
            'bookedSeatIds' => $bookedSeatIds,
        ]);
    }

    /**
     * Proses Reservasi Kursi dengan Pencegahan Double Booking
     */
    public function store(Request $request, Showtime $showtime)
    {
        $request->validate([
            'seat_ids' => ['required', 'array', 'min:1'],
            'seat_ids.*' => ['integer', 'exists:seats,id'],
        ]);

        $seatIds = $request->input('seat_ids');

        try {
            // Gunakan Database Transaction untuk memastikan integritas data
            $booking = DB::transaction(function () use ($showtime, $seatIds) {
                // KUNCI PENTING: Lock pengecekan kursi agar tidak bisa diambil user lain bersamaan
                $alreadyBooked = BookingSeat::whereHas('booking', function ($q) use ($showtime) {
                    $q->where('showtime_id', $showtime->id)
                      ->whereIn('status', ['pending', 'paid']);
                })->whereIn('seat_id', $seatIds)
                  ->lockForUpdate()
                  ->exists();

                if ($alreadyBooked) {
                    throw new \Exception('Salah satu atau lebih kursi yang Anda pilih baru saja dipesan orang lain. Silakan pilih kursi lain.');
                }

                $totalPrice = count($seatIds) * $showtime->price;

                // 1. Buat Data Booking
                $booking = Booking::create([
                    'user_id' => auth()->id(),
                    'showtime_id' => $showtime->id,
                    'booking_code' => 'CB-' . strtoupper(Str::random(8)),
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(15), // Kedaluwarsa dalam 15 menit
                ]);

                // 2. Simpan Kursi yang Dipesan
                foreach ($seatIds as $seatId) {
                    BookingSeat::create([
                        'booking_id' => $booking->id,
                        'seat_id' => $seatId,
                        'price' => $showtime->price,
                    ]);
                }

                return $booking;
            });

            // Redirect ke halaman simulasi pembayaran (Tahap 08)
            return redirect()->route('checkout.show', $booking->id);

        } catch (\Exception $e) {
            return back()->withErrors(['seats' => $e->getMessage()]);
        }
    }
}