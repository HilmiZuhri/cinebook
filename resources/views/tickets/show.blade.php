<x-app-layout title="E-Ticket - {{ $booking->booking_code }}">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Tombol Aksi Atas (Akan tersembunyi saat dicetak) -->
        <div class="no-print flex items-center justify-between mb-8">
            <a href="{{ route('tickets.history') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-neutral-400 hover:text-white transition">
                &larr; Riwayat Tiket Saya
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-neutral-800 hover:bg-neutral-700 text-white font-bold text-xs tracking-wide border border-neutral-700 transition flex items-center gap-2 shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Tiket / PDF</span>
            </button>
        </div>

        <!-- Kartu E-Ticket Fisik Bioskop -->
        <div class="ticket-card bg-neutral-900 border-2 border-neutral-800 rounded-3xl overflow-hidden shadow-2xl relative">
            
            <!-- Header Tiket -->
            <div class="bg-gradient-to-r from-red-700 via-rose-700 to-amber-600 px-8 py-5 text-white flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black tracking-wider font-['Bebas_Neue',sans-serif]">CINEBOOK E-TICKET</span>
                </div>
                <div class="px-3 py-1 rounded-full bg-black/30 backdrop-blur-md text-[11px] font-extrabold tracking-widest uppercase border border-white/20">
                    {{ $booking->status === 'paid' ? 'LUNAS (CONFIRMED)' : strtoupper($booking->status) }}
                </div>
            </div>

            <!-- Badan Tiket -->
            <div class="p-8 space-y-6">
                <!-- Judul Film & Bioskop -->
                <div class="border-b border-neutral-800 pb-6 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-white">{{ $booking->showtime->movie_title }}</h1>
                        <p class="text-sm text-neutral-400 mt-1 font-medium">
                            {{ $booking->showtime->studio->cinema->name }}
                        </p>
                        <p class="text-xs text-neutral-500 mt-0.5">
                            {{ $booking->showtime->studio->cinema->address }}
                        </p>
                    </div>

                    <div class="sm:text-right flex-shrink-0">
                        <span class="text-xs text-neutral-500 uppercase tracking-widest font-semibold">Tipe Studio</span>
                        <div class="text-lg font-black text-red-500 font-['Bebas_Neue',sans-serif] tracking-wider mt-0.5">
                            {{ $booking->showtime->studio->name }}
                        </div>
                    </div>
                </div>

                <!-- Detail Jadwal & Kursi (Grid Kotak) -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-neutral-950 p-5 rounded-2xl border border-neutral-800/80">
                    <div>
                        <span class="text-[11px] text-neutral-500 uppercase tracking-wider block">Tanggal</span>
                        <span class="text-sm font-bold text-white mt-1 block">
                            {{ $booking->showtime->start_time->format('d M Y') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[11px] text-neutral-500 uppercase tracking-wider block">Waktu</span>
                        <span class="text-sm font-bold text-red-400 mt-1 block">
                            {{ $booking->showtime->start_time->format('H:i') }} WIB
                        </span>
                    </div>
                    <div>
                        <span class="text-[11px] text-neutral-500 uppercase tracking-wider block">Nomor Kursi</span>
                        <span class="text-sm font-extrabold text-amber-400 mt-1 block">
                            {{ $booking->bookingSeats->map(fn($b) => $b->seat->seat_code)->join(', ') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[11px] text-neutral-500 uppercase tracking-wider block">Total Tiket</span>
                        <span class="text-sm font-bold text-white mt-1 block">
                            {{ $booking->bookingSeats->count() }} Orang
                        </span>
                    </div>
                </div>

                <!-- Info Pemesan & Biaya -->
                <div class="flex justify-between items-center text-xs text-neutral-400 pt-2 border-b border-neutral-800 pb-6">
                    <div>
                        <span>Nama Pemesan:</span>
                        <span class="text-white font-bold ml-1">{{ $booking->user->name }}</span>
                    </div>
                    <div>
                        <span>Total Bayar:</span>
                        <span class="text-base font-black text-white ml-1 font-['Bebas_Neue',sans-serif] tracking-wider">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Bagian Bawah: Barcode & Kode Unik Booking -->
                <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-6 text-center sm:text-left">
                    <div>
                        <span class="text-[11px] text-neutral-500 uppercase tracking-widest block font-bold">Kode Booking</span>
                        <span class="text-3xl font-black text-white tracking-widest font-mono mt-1 block">
                            {{ $booking->booking_code }}
                        </span>
                        <p class="text-[11px] text-neutral-500 mt-1">Tunjukkan kode ini kepada petugas bioskop di lokasi.</p>
                    </div>

                    <!-- Simulasi Barcode Grafis Menggunakan CSS -->
                    <div class="flex flex-col items-center">
                        <div class="flex items-center gap-1 h-14 bg-white p-2 rounded-lg">
                            <div class="w-1.5 h-full bg-black"></div>
                            <div class="w-1 h-full bg-black"></div>
                            <div class="w-2.5 h-full bg-black"></div>
                            <div class="w-1 h-full bg-black"></div>
                            <div class="w-2 h-full bg-black"></div>
                            <div class="w-1 h-full bg-black"></div>
                            <div class="w-3 h-full bg-black"></div>
                            <div class="w-1.5 h-full bg-black"></div>
                            <div class="w-2 h-full bg-black"></div>
                            <div class="w-1 h-full bg-black"></div>
                            <div class="w-2.5 h-full bg-black"></div>
                        </div>
                        <span class="text-[10px] text-neutral-500 font-mono mt-1 tracking-widest">{{ $booking->booking_code }}</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="no-print mt-8 text-center">
            <a href="{{ route('home') }}" class="text-xs text-neutral-400 hover:text-red-400 transition font-semibold">
                &larr; Kembali ke Beranda Film
            </a>
        </div>
    </div>

    <!-- Style Khusus Print / Cetak Kertas -->
    <style>
        @media print {
            body { background: white !important; color: black !important; }
            header, footer, .no-print { display: none !important; }
            .ticket-card {
                border: 2px solid #000 !important;
                background: white !important;
                color: black !important;
                box-shadow: none !important;
            }
            .ticket-card * {
                color: black !important;
                border-color: #ccc !important;
            }
        }
    </style>
</x-app-layout>