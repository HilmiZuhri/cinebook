<x-app-layout title="Pilih Kursi - {{ $showtime->movie_title }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Breadcrumb / Tombol Kembali -->
        <div class="mb-6">
            <a href="{{ route('movies.show', $showtime->tmdb_movie_id) }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-neutral-400 hover:text-white transition">
                &larr; Kembali ke Detail Film
            </a>
        </div>

        @if($errors->has('seats'))
            <div class="mb-6 p-4 rounded-xl bg-red-950/70 border border-red-800 text-red-200 text-sm">
                {{ $errors->first('seats') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">

            <!-- Area Denah Kursi (Kolom Kiri - Lebar 2 Kolom) -->
            <div class="lg:col-span-2 bg-neutral-900/80 border border-neutral-800/80 rounded-3xl p-6 sm:p-10 shadow-2xl backdrop-blur-sm">
                
                <!-- Efek Layar Bioskop -->
                <div class="w-full mb-14 text-center">
                    <div class="relative w-4/5 mx-auto h-3 bg-gradient-to-r from-red-600 via-rose-500 to-amber-500 rounded-full shadow-[0_15px_30px_rgba(239,68,68,0.35)]"></div>
                    <div class="w-3/4 mx-auto h-8 bg-gradient-to-b from-red-500/10 to-transparent blur-sm -mt-1"></div>
                    <p class="text-[11px] tracking-[0.3em] font-extrabold uppercase text-neutral-500 mt-3">Layar Bioskop</p>
                </div>

                <!-- Denah Kursi -->
                <div class="space-y-4 overflow-x-auto pb-4">
                    @foreach($seatsByRow as $row => $seats)
                        <div class="flex items-center justify-center gap-2 sm:gap-3 min-w-[340px]">
                            <!-- Label Baris Kiri -->
                            <span class="w-6 text-center text-xs font-bold text-neutral-500">{{ $row }}</span>

                            <!-- Deretan Kursi -->
                            <div class="flex items-center gap-2 sm:gap-3">
                                @foreach($seats as $index => $seat)
                                    @php
                                        $isBooked = in_array($seat->id, $bookedSeatIds);
                                    @endphp

                                    <!-- Tambahkan jeda lorong jalan di tengah baris -->
                                    @if($loop->count == 8 && $index == 4)
                                        <div class="w-4 sm:w-8"></div>
                                    @endif

                                    <button type="button"
                                        data-seat-id="{{ $seat->id }}"
                                        data-seat-code="{{ $seat->seat_code }}"
                                        @disabled($isBooked)
                                        class="seat-btn w-8 h-8 sm:w-10 sm:h-10 rounded-xl text-xs font-bold transition duration-200 flex items-center justify-center
                                            {{ $isBooked 
                                                ? 'bg-neutral-950 text-neutral-600 border border-neutral-900 cursor-not-allowed opacity-40 line-through' 
                                                : 'bg-neutral-800 text-neutral-200 hover:bg-neutral-700 hover:border-red-500/50 border border-neutral-700/80 cursor-pointer shadow' 
                                            }}">
                                        {{ $seat->number }}
                                    </button>
                                @endforeach
                            </div>

                            <!-- Label Baris Kanan -->
                            <span class="w-6 text-center text-xs font-bold text-neutral-500">{{ $row }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Legenda Status Kursi -->
                <div class="mt-12 pt-6 border-t border-neutral-800/80 flex flex-wrap items-center justify-center gap-6 text-xs text-neutral-400">
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-lg bg-neutral-800 border border-neutral-700"></div>
                        <span>Tersedia</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-lg bg-red-600 border border-red-500 shadow-md shadow-red-600/40"></div>
                        <span class="text-white font-medium">Dipilih</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-lg bg-neutral-950 border border-neutral-900 opacity-40 flex items-center justify-center text-[10px] text-neutral-600 line-through">✕</div>
                        <span>Terisi</span>
                    </div>
                </div>

            </div>

            <!-- Panel Ringkasan Booking (Kolom Kanan) -->
            <div class="bg-neutral-900/90 border border-neutral-800 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-sm sticky top-28">
                <h3 class="text-lg font-bold text-white pb-4 border-b border-neutral-800">Ringkasan Pesanan</h3>

                <div class="mt-5 space-y-4">
                    <div>
                        <h4 class="font-extrabold text-white text-base">{{ $showtime->movie_title }}</h4>
                        <p class="text-xs text-neutral-400 mt-1">{{ $showtime->studio->cinema->name }}</p>
                        <p class="text-xs text-neutral-400">{{ $showtime->studio->name }} ({{ $showtime->studio->type }})</p>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-neutral-950 border border-neutral-800/80 flex items-center justify-between text-xs">
                        <div>
                            <p class="text-neutral-500">Waktu Tayang</p>
                            <p class="font-bold text-white mt-0.5">{{ $showtime->start_time->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-neutral-500">Jam</p>
                            <p class="font-bold text-red-400 mt-0.5">{{ $showtime->start_time->format('H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="pt-2">
                        <div class="flex justify-between text-xs text-neutral-400 mb-1">
                            <span>Kursi Terpilih:</span>
                            <span id="selected-seats-label" class="font-bold text-white">-</span>
                        </div>
                        <div class="flex justify-between text-xs text-neutral-400">
                            <span>Harga Satuan:</span>
                            <span>Rp {{ number_format($showtime->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-neutral-800 flex justify-between items-baseline">
                        <span class="text-sm font-semibold text-neutral-300">Total Tagihan:</span>
                        <span id="total-price-label" class="text-2xl font-black text-amber-400 font-['Bebas_Neue',sans-serif] tracking-wider">
                            Rp 0
                        </span>
                    </div>

                    <!-- Form Submission -->
                    <form action="{{ route('booking.store', $showtime->id) }}" method="POST" id="booking-form" class="pt-4">
                        @csrf
                        <div id="hidden-inputs"></div>

                        <button type="submit" id="submit-btn" disabled
                            class="w-full py-4 rounded-xl font-bold text-sm tracking-wide transition duration-200 shadow-xl
                                bg-neutral-800 text-neutral-500 cursor-not-allowed">
                            Pilih Kursi Terlebih Dahulu
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- JavaScript Interaktif Denah Kursi -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const seatButtons = document.querySelectorAll('.seat-btn:not(:disabled)');
            const selectedSeatsLabel = document.getElementById('selected-seats-label');
            const totalPriceLabel = document.getElementById('total-price-label');
            const submitBtn = document.getElementById('submit-btn');
            const hiddenInputs = document.getElementById('hidden-inputs');

            const pricePerSeat = {{ $showtime->price }};
            let selectedSeats = new Map(); // key: seatId, value: seatCode

            seatButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-seat-id');
                    const code = btn.getAttribute('data-seat-code');

                    if (selectedSeats.has(id)) {
                        // Batal pilih kursi
                        selectedSeats.delete(id);
                        btn.classList.remove('bg-red-600', 'border-red-500', 'text-white', 'shadow-lg', 'shadow-red-600/50');
                        btn.classList.add('bg-neutral-800', 'text-neutral-200', 'border-neutral-700/80');
                    } else {
                        // Maksimal booking 6 kursi sekaligus
                        if (selectedSeats.size >= 6) {
                            alert('Anda dapat memilih maksimal 6 kursi per transaksi.');
                            return;
                        }

                        // Pilih kursi
                        selectedSeats.set(id, code);
                        btn.classList.remove('bg-neutral-800', 'text-neutral-200', 'border-neutral-700/80');
                        btn.classList.add('bg-red-600', 'border-red-500', 'text-white', 'shadow-lg', 'shadow-red-600/50');
                    }

                    updateSummary();
                });
            });

            function updateSummary() {
                // Update Label Kursi Terpilih
                if (selectedSeats.size === 0) {
                    selectedSeatsLabel.textContent = '-';
                    totalPriceLabel.textContent = 'Rp 0';
                    submitBtn.disabled = true;
                    submitBtn.className = 'w-full py-4 rounded-xl font-bold text-sm tracking-wide bg-neutral-800 text-neutral-500 cursor-not-allowed';
                    submitBtn.textContent = 'Pilih Kursi Terlebih Dahulu';
                    hiddenInputs.innerHTML = '';
                    return;
                }

                const codes = Array.from(selectedSeats.values()).sort().join(', ');
                selectedSeatsLabel.textContent = codes;

                // Update Total Harga
                const total = selectedSeats.size * pricePerSeat;
                totalPriceLabel.textContent = 'Rp ' + total.toLocaleString('id-ID');

                // Render Input Tersembunyi untuk Dikirim ke Backend
                hiddenInputs.innerHTML = '';
                selectedSeats.forEach((code, id) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'seat_ids[]';
                    input.value = id;
                    hiddenInputs.appendChild(input);
                });

                // Aktifkan Tombol Submit
                submitBtn.disabled = false;
                submitBtn.className = 'w-full py-4 rounded-xl font-bold text-sm tracking-wide text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 shadow-xl shadow-red-600/40 cursor-pointer transition';
                submitBtn.textContent = `Lanjut Bayar (${selectedSeats.size} Kursi)`;
            }
        });
    </script>
</x-app-layout>