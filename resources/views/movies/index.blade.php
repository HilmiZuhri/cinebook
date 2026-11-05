<x-app-layout title="CineBook - Beranda Film Bioskop">
    <!-- Hero Banner Film Utama -->
    @if($heroMovie)
        @php
            $backdropUrl = $tmdb->getImageUrl($heroMovie['backdrop_path'] ?? null, 'original');
        @endphp
        <div class="relative w-full h-[520px] lg:h-[620px] overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 scale-105"
                 style="background-image: url('{{ $backdropUrl ?? 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=1920' }}');">
            </div>

            <!-- Gradient Overlays Cinematic -->
            <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/70 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-neutral-950 via-neutral-950/40 to-transparent"></div>

            <!-- Hero Content -->
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-end pb-16">
                <div class="max-w-2xl space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-600/20 text-red-400 border border-red-500/30 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                        Sedang Tayang Unggulan
                    </span>

                    <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight uppercase font-['Bebas_Neue',sans-serif]">
                        {{ $heroMovie['title'] }}
                    </h1>

                    <div class="flex items-center gap-4 text-xs sm:text-sm text-neutral-300 font-medium">
                        <span class="flex items-center gap-1 text-amber-400 font-bold">
                            <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ number_format($heroMovie['vote_average'], 1) }}
                        </span>
                        <span>•</span>
                        <span>Rilis: {{ date('d M Y', strtotime($heroMovie['release_date'] ?? 'now')) }}</span>
                    </div>

                    <p class="text-sm text-neutral-300 line-clamp-3 leading-relaxed">
                        {{ $heroMovie['overview'] ?: 'Sinopsis film belum tersedia dalam bahasa ini.' }}
                    </p>

                    <div class="pt-2 flex items-center gap-3">
                        <a href="{{ route('movies.show', $heroMovie['id']) }}"
                           class="px-6 py-3 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 font-bold text-white text-sm shadow-lg shadow-red-600/40 transition duration-200">
                            Lihat Jadwal & Tiket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 space-y-16">
        <!-- Section: Sedang Tayang (Now Playing) -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-red-600 rounded-full inline-block"></span>
                        Sedang Tayang di Bioskop
                    </h2>
                    <p class="text-xs text-neutral-400 mt-1">Pilihan film terpopuler hari ini</p>
                </div>
                <a href="{{ route('movies.catalog') }}" class="text-xs text-neutral-400 hover:text-red-400 transition font-semibold">
                    Lihat Semua &rarr;
                </a>
            </div>

            <!-- Grid Film -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @foreach($nowPlaying as $movie)
                    <a href="{{ route('movies.show', $movie['id']) }}" class="group block bg-neutral-900 border border-neutral-800/80 rounded-2xl overflow-hidden hover:border-red-600/50 hover:shadow-xl hover:shadow-red-950/20 transition-all duration-300">
                        <div class="aspect-[2/3] w-full overflow-hidden bg-neutral-950 relative">
                            @if(!empty($movie['poster_path']))
                                <img src="{{ $tmdb->getImageUrl($movie['poster_path'], 'w500') }}"
                                     alt="{{ $movie['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xs text-neutral-600">Poster N/A</div>
                            @endif
                            <div class="absolute top-2 right-2 px-2 py-0.5 rounded-lg bg-neutral-950/80 backdrop-blur-md text-[11px] font-bold text-amber-400 flex items-center gap-1">
                                ★ {{ number_format($movie['vote_average'], 1) }}
                            </div>
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
        </section>

        <!-- Section: Akan Datang (Upcoming) -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-amber-500 rounded-full inline-block"></span>
                        Segera Hadir
                    </h2>
                    <p class="text-xs text-neutral-400 mt-1">Nantikan penayangannya di CineBook</p>
                </div>
            </div>

            <!-- Grid Film Upcoming -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @foreach($upcoming as $movie)
                    <a href="{{ route('movies.show', $movie['id']) }}" class="group block bg-neutral-900 border border-neutral-800/80 rounded-2xl overflow-hidden hover:border-amber-500/50 transition-all duration-300">
                        <div class="aspect-[2/3] w-full overflow-hidden bg-neutral-950 relative">
                            @if(!empty($movie['poster_path']))
                                <img src="{{ $tmdb->getImageUrl($movie['poster_path'], 'w500') }}"
                                     alt="{{ $movie['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xs text-neutral-600">Poster N/A</div>
                            @endif
                        </div>
                        <div class="p-3.5">
                            <h3 class="font-bold text-white text-sm group-hover:text-amber-400 transition truncate">
                                {{ $movie['title'] }}
                            </h3>
                            <p class="text-[11px] text-neutral-500 mt-1">
                                Rilis: {{ date('d M Y', strtotime($movie['release_date'] ?? 'now')) }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>