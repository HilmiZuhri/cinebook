<x-app-layout title="Checkout Pembayaran - {{ $booking->booking_code }}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Banner Hitung Mundur Kedaluwarsa -->
        <div class="mb-8 p-4 rounded-2xl bg-gradient-to-r from-amber-500/10 via-amber-600/10 to-red-500/10 border border-amber-500/30 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-amber-500 animate-ping"></span>
                <div>
                    <h4 class="text-sm font-bold text-white">Selesaikan Pembayaran Segera</h4>
                    <p class="text-xs text-neutral-400">Kursi Anda ditahan sementara hingga batas waktu berakhir.</p>
                </div>
            </div>
            <div class="text-center sm:text-right">
                <span class="text-xs text-neutral-400">Sisa Waktu:</span>
                <div id="countdown" class="text-2xl font-black text-amber-400 font-['Bebas_Neue',sans-serif] tracking-wider">
                    --:--
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

            <!-- Detail Pesanan (2 Kolom Kiri) -->
            <div class="md:col-span-2 space-y-6">
                <!-- Rincian Tiket -->
                <div class="bg-neutral-900 border border-neutral-800 rounded-3xl p-6 sm:p-8 shadow-xl">
                    <div class="flex items-start justify-between border-b border-neutral-800 pb-5">
                        <div>
                            <span class="text-xs text-neutral-500 uppercase tracking-widest font-bold">Kode Booking</span>
                            <h2 class="text-xl font-black text-red-500 font-['Bebas_Neue',sans-serif] tracking-wider mt-0.5">
                                {{ $booking->booking_code }}
                            </h2>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-500/20 text-amber-400 border border-amber-500/30">
                            {{ $booking->status }}
                        </span>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div>
                            <h3 class="text-xl font-bold text-white">{{ $booking->showtime->movie_title }}</h3>
                            <p class="text-xs text-neutral-400 mt-1">
                                {{ $booking->showtime->studio->cinema->name }} • {{ $booking->showtime->studio->name }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 py-3 text-xs bg-neutral-950 p-4 rounded-2xl border border-neutral-800/80">
                            <div>
                                <span class="text-neutral-500 block">Jadwal Tayang</span>
                                <span class="font-bold text-neutral-200 mt-0.5 block">
                                    {{ $booking->showtime->start_time->format('d M Y') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-neutral-500 block">Jam</span>
                                <span class="font-bold text-red-400 mt-0.5 block">
                                    {{ $booking->showtime->start_time->format('H:i') }} WIB
                                </span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs text-neutral-500 block mb-1">Kursi yang Dipesan:</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($booking->bookingSeats as $bSeat)
                                    <span class="px-3 py-1 rounded-lg bg-neutral-800 border border-neutral-700 text-xs font-bold text-white">
                                        {{ $bSeat->seat->seat_code }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran Simulasi -->
                <div class="bg-neutral-900 border border-neutral-800 rounded-3xl p-6 sm:p-8 shadow-xl">
                    <h3 class="text-base font-bold text-white mb-4">Pilih Metode Pembayaran</h3>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-4 rounded-2xl bg-neutral-950 border-2 border-red-600/80 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" checked class="text-red-600 focus:ring-0">
                                <div>
                                    <p class="text-sm font-bold text-white">QRIS (Gopay / OVO / ShopeePay / BCA)</p>
                                    <p class="text-xs text-neutral-400">Instan & Verifikasi Otomatis</p>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-green-400">Bebas Biaya</span>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-2xl bg-neutral-950 border border-neutral-800 opacity-60 cursor-not-allowed">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" disabled class="text-red-600 focus:ring-0">
                                <div>
                                    <p class="text-sm font-bold text-neutral-300">Virtual Account Bank</p>
                                    <p class="text-xs text-neutral-500">BCA, Mandiri, BRI, BNI</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pembayaran & Tombol Aksi (1 Kolom Kanan) -->
            <div class="bg-neutral-900 border border-neutral-800 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
                <h3 class="text-base font-bold text-white border-b border-neutral-800 pb-3">Rincian Biaya</h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between text-neutral-400">
                        <span>Tiket ({{ $booking->bookingSeats->count() }}x)</span>
                        <span class="text-white font-medium">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-neutral-400">
                        <span>Biaya Layanan</span>
                        <span class="text-green-400 font-semibold">GRATIS</span>
                    </div>
                    <div class="pt-3 border-t border-neutral-800 flex justify-between items-baseline">
                        <span class="text-sm font-bold text-white">Total Tagihan:</span>
                        <span class="text-2xl font-black text-amber-400 font-['Bebas_Neue',sans-serif] tracking-wider">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Form Simulasi Pembayaran -->
                <form action="{{ route('checkout.pay', $booking->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 font-bold text-sm text-white shadow-xl shadow-red-600/40 transition duration-200 cursor-pointer">
                        Simulasi Bayar Sekarang
                    </button>
                </form>

                <!-- Tombol Batalkan Pesanan -->
                <form action="{{ route('checkout.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Kursi akan dilepas kembali.');">
                    @csrf
                    <button type="submit" class="w-full py-2.5 text-xs text-neutral-500 hover:text-red-400 font-semibold transition text-center">
                        Batalkan Pesanan Ini
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Countdown Timer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const expiresAt = new Date("{{ $booking->expires_at->toIso8601String() }}").getTime();
            const countdownEl = document.getElementById('countdown');

            const timer = setInterval(() => {
                const now = new Date().getTime();
                const distance = expiresAt - now;

                if (distance <= 0) {
                    clearInterval(timer);
                    countdownEl.textContent = "00:00 (EXPIRED)";
                    alert('Batas waktu pembayaran telah habis. Pesanan dibatalkan otomatis.');
                    window.location.reload();
                    return;
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownEl.textContent = 
                    (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                    (seconds < 10 ? "0" + seconds : seconds);
            }, 1000);
        });
    </script>
</x-app-layout>