<x-app-layout title="{{ $title }} - CineBook">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Header & Input Pencarian -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-6 border-b border-neutral-800">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ $title }}</h1>
                <p class="text-xs text-neutral-400 mt-1">Jelajahi dan temukan film yang ingin kamu tonton</p>
            </div>

            <!-- Form Pencarian Khusus Mobile & Desktop -->
            <form action="{{ route('movies.catalog') }}" method="GET" class="w-full md:w-80">
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari film lain..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-neutral-900 border border-neutral-800 text-sm text-neutral-200 placeholder-neutral-500 focus:outline-none focus:border-red-500 transition">
                    <svg class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>
        </div>

        @if(empty($movies))
            <div class="text-center py-20 bg-neutral-900/50 rounded-2xl border border-neutral-800">
                <p class="text-neutral-400 text-base">Tidak ada film ditemukan untuk kueri ini.</p>
                <a href="{{ route('movies.catalog') }}" class="mt-4 inline-block px-4 py-2 text-xs font-semibold text-red-400 border border-red-500/40 rounded-xl hover:bg-red-500/10 transition">
                    Lihat Film Populer
                </a>
            </div>
        @else
            <!-- Grid Katalog Film -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($movies as $movie)
                    <a href="{{ route('movies.show', $movie['id']) }}" class="group block bg-neutral-900 border border-neutral-800/80 rounded-2xl overflow-hidden hover:border-red-600/50 hover:shadow-xl transition duration-300">
                        <div class="aspect-[2/3] w-full overflow-hidden bg-neutral-950 relative">
                            @if(!empty($movie['poster_path']))
                                <img src="{{ $tmdb->getImageUrl($movie['poster_path'], 'w500') }}"
                                     alt="{{ $movie['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xs text-neutral-600">Poster N/A</div>
                            @endif
                            @if(!empty($movie['vote_average']))
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded-lg bg-neutral-950/80 backdrop-blur-md text-[11px] font-bold text-amber-400">
                                    ★ {{ number_format($movie['vote_average'], 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="p-3.5">
                            <h3 class="font-bold text-white text-sm group-hover:text-red-400 transition truncate">
                                {{ $movie['title'] }}
                            </h3>
                            <p class="text-[11px] text-neutral-500 mt-1">
                                {{ date('Y', strtotime($movie['release_date'] ?? 'now')) }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>