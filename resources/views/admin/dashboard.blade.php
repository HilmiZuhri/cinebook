<x-admin-layout title="Admin Dashboard - CineBook">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Title -->
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Ikhtisar & Statistik Bisnis</h1>
            <p class="text-xs text-neutral-400 mt-1">Pantau penjualan tiket bioskop dan aktivitas transaksi secara langsung</p>
        </div>

        <!-- 4 KPI Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Revenue -->
            <div class="bg-neutral-900 border border-neutral-800 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <span class="text-xs font-bold text-neutral-400 uppercase tracking-wider block">Total Pendapatan</span>
                <div class="text-3xl font-black text-amber-400 font-['Bebas_Neue',sans-serif] tracking-wider mt-2">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-green-400 mt-2 font-medium">✓ Dari tiket terkonfirmasi (paid)</p>
            </div>

            <!-- Tiket Terjual -->
            <div class="bg-neutral-900 border border-neutral-800 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <span class="text-xs font-bold text-neutral-400 uppercase tracking-wider block">Tiket Terjual</span>
                <div class="text-3xl font-black text-red-500 font-['Bebas_Neue',sans-serif] tracking-wider mt-2">
                    {{ number_format($totalTicketsSold) }} Kursi
                </div>
                <p class="text-[11px] text-neutral-500 mt-2">Seluruh studio & jadwal</p>
            </div>

            <!-- Total Transaksi -->
            <div class="bg-neutral-900 border border-neutral-800 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <span class="text-xs font-bold text-neutral-400 uppercase tracking-wider block">Total Pesanan</span>
                <div class="text-3xl font-black text-white font-['Bebas_Neue',sans-serif] tracking-wider mt-2">
                    {{ number_format($totalBookings) }} Transaksi
                </div>
                <p class="text-[11px] text-neutral-500 mt-2">Semua status pemesanan</p>
            </div>

            <!-- Total Customers -->
            <div class="bg-neutral-900 border border-neutral-800 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <span class="text-xs font-bold text-neutral-400 uppercase tracking-wider block">Customer Terdaftar</span>
                <div class="text-3xl font-black text-white font-['Bebas_Neue',sans-serif] tracking-wider mt-2">
                    {{ number_format($totalCustomers) }} User
                </div>
                <p class="text-[11px] text-neutral-500 mt-2">Akun terdaftar di sistem</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- Tabel Transaksi Terkini (2 Kolom Kiri) -->
            <div class="lg:col-span-2 bg-neutral-900 border border-neutral-800 rounded-3xl p-6 sm:p-8 shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-neutral-800">
                    <div>
                        <h2 class="text-lg font-bold text-white">Transaksi Pemesanan</h2>
                        <p class="text-xs text-neutral-400 mt-0.5">Daftar booking tiket oleh customer</p>
                    </div>

                    <!-- Filter Status -->
                    <div class="flex items-center gap-2 text-xs">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-3 py-1.5 rounded-lg border {{ empty($statusFilter) ? 'bg-red-600 border-red-500 text-white font-bold' : 'bg-neutral-800 border-neutral-700 text-neutral-400' }}">
                            Semua
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'paid']) }}" 
                           class="px-3 py-1.5 rounded-lg border {{ $statusFilter === 'paid' ? 'bg-green-600 border-green-500 text-white font-bold' : 'bg-neutral-800 border-neutral-700 text-neutral-400' }}">
                            Lunas
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" 
                           class="px-3 py-1.5 rounded-lg border {{ $statusFilter === 'pending' ? 'bg-amber-600 border-amber-500 text-white font-bold' : 'bg-neutral-800 border-neutral-700 text-neutral-400' }}">
                            Pending
                        </a>
                    </div>
                </div>

                @if($recentBookings->isEmpty())
                    <p class="text-xs text-neutral-500 text-center py-8">Belum ada transaksi dengan status ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="text-neutral-500 uppercase tracking-wider border-b border-neutral-800">
                                <tr>
                                    <th class="pb-3">Kode & Customer</th>
                                    <th class="pb-3">Film & Bioskop</th>
                                    <th class="pb-3">Total</th>
                                    <th class="pb-3">Status</th>
                                    <th class="pb-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-800/80">
                                @foreach($recentBookings as $booking)
                                    <tr class="hover:bg-neutral-800/30 transition">
                                        <td class="py-3.5">
                                            <span class="font-mono font-bold text-white block">{{ $booking->booking_code }}</span>
                                            <span class="text-neutral-400 text-[11px]">{{ $booking->user->name }}</span>
                                        </td>
                                        <td class="py-3.5 max-w-[200px]">
                                            <span class="font-semibold text-white truncate block">{{ $booking->showtime->movie_title }}</span>
                                            <span class="text-neutral-500 text-[11px] block">{{ $booking->showtime->studio->cinema->name }}</span>
                                        </td>
                                        <td class="py-3.5 font-bold text-amber-400">
                                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5">
                                            @if($booking->status === 'paid')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-500/20 text-green-400 border border-green-500/30">Lunas</span>
                                            @elseif($booking->status === 'pending')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">Pending</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-neutral-800 text-neutral-400">{{ $booking->status }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 text-right">
                                            @if($booking->status === 'paid')
                                                <a href="{{ route('tickets.show', $booking->id) }}" target="_blank" class="text-red-400 hover:text-red-300 font-semibold underline underline-offset-2">
                                                    Tiket
                                                </a>
                                            @else
                                                <span class="text-neutral-600">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $recentBookings->withQueryString()->links() }}
                    </div>
                @endif
            </div>

            <!-- Laporan Film Terlaris (1 Kolom Kanan) -->
            <div class="bg-neutral-900 border border-neutral-800 rounded-3xl p-6 sm:p-8 shadow-xl">
                <h2 class="text-lg font-bold text-white mb-2">Film Terlaris</h2>
                <p class="text-xs text-neutral-400 mb-6">Peringkat berdasarkan omset tiket terjual</p>

                @if($topMovies->isEmpty())
                    <p class="text-xs text-neutral-500 py-6 text-center">Belum ada data penjualan tiket lunas.</p>
                @else
                    <div class="space-y-4">
                        @foreach($topMovies as $index => $movie)
                            <div class="p-4 rounded-2xl bg-neutral-950 border border-neutral-800/80 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 font-bold text-xs flex items-center justify-center border border-red-500/30">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <h4 class="text-xs font-bold text-white line-clamp-1">{{ $movie->movie_title }}</h4>
                                        <p class="text-[10px] text-neutral-500">{{ $movie->tickets_sold }} Tiket Terjual</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-black text-amber-400 font-['Bebas_Neue',sans-serif] tracking-wider block">
                                        Rp {{ number_format($movie->total_revenue, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-admin-layout>