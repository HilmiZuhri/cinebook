<x-app-layout title="Riwayat Tiket Saya - CineBook">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="border-b border-neutral-800 pb-5 mb-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Riwayat Pemesanan</h1>
            <p class="text-xs text-neutral-400 mt-1">Daftar semua transaksi dan e-ticket bioskop yang pernah kamu pesan</p>
        </div>

        @if($bookings->isEmpty())
            <div class="text-center py-20 bg-neutral-900/60 rounded-3xl border border-neutral-800">
                <svg class="w-12 h-12 text-neutral-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                <p class="text-neutral-400 text-sm">Kamu belum memiliki riwayat pemesanan tiket.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs tracking-wide transition shadow-lg shadow-red-600/30">
                    Pesan Tiket Sekarang
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-5 sm:p-6 shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 hover:border-neutral-700 transition">
                        
                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-xs font-bold text-neutral-400">#{{ $booking->booking_code }}</span>
                                
                                @if($booking->status === 'paid')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-green-500/20 text-green-400 border border-green-500/30">Lunas</span>
                                @elseif($booking->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-500/20 text-amber-400 border border-amber-500/30">Menunggu Bayar</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-neutral-800 text-neutral-400 border border-neutral-700">{{ $booking->status }}</span>
                                @endif
                            </div>

                            <h3 class="text-lg font-bold text-white">{{ $booking->showtime->movie_title }}</h3>
                            
                            <p class="text-xs text-neutral-400">
                                {{ $booking->showtime->studio->cinema->name }} • {{ $booking->showtime->studio->name }}
                            </p>

                            <div class="flex flex-wrap items-center gap-3 text-xs text-neutral-400 pt-1">
                                <span>{{ $booking->showtime->start_time->format('d M Y') }} ({{ $booking->showtime->start_time->format('H:i') }} WIB)</span>
                                <span>•</span>
                                <span>Kursi: <strong class="text-white">{{ $booking->bookingSeats->map(fn($b) => $b->seat->seat_code)->join(', ') }}</strong></span>
                            </div>
                        </div>

                        <!-- Harga & Tombol Buka Tiket -->
                        <div class="sm:text-right flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto gap-4 pt-3 sm:pt-0 border-t sm:border-t-0 border-neutral-800">
                            <div>
                                <span class="text-[11px] text-neutral-500 block">Total Bayar</span>
                                <span class="text-lg font-extrabold text-amber-400 font-['Bebas_Neue',sans-serif] tracking-wider block">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </span>
                            </div>

                            @if($booking->status === 'paid')
                                <a href="{{ route('tickets.show', $booking->id) }}" class="px-4 py-2 rounded-xl bg-neutral-800 hover:bg-neutral-700 text-white font-bold text-xs tracking-wide border border-neutral-700 transition">
                                    Lihat E-Ticket
                                </a>
                            @elseif($booking->status === 'pending')
                                <a href="{{ route('checkout.show', $booking->id) }}" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs tracking-wide transition shadow-lg shadow-red-600/30">
                                    Lanjut Bayar
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @endif

    </div>
</x-app-layout>