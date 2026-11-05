<x-app-layout title="{{ $movie['title'] }} - CineBook">
    @php
        $backdropUrl = $tmdb->getImageUrl($movie['backdrop_path'] ?? null, 'original');
        $posterUrl = $tmdb->getImageUrl($movie['poster_path'] ?? null, 'w500');
    @endphp

    <!-- Backdrop Header Section -->
    <div class="relative w-full min-h-[460px] lg:min-h-[520px] bg-neutral-950 overflow-hidden">
        @if($backdropUrl)
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $backdropUrl }}');"></div>
            <div class="absolute inset-0 bg-neutral-950/85 backdrop-blur-sm"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-transparent to-neutral-950/60"></div>
        @endif

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col md:flex-row items-center md:items-start gap-8">
            <!-- Poster Film -->
            <div class="w-56 sm:w-64 flex-shrink-0 rounded-2xl overflow-hidden border-2 border-neutral-700/60 shadow-2xl shadow-black/80">
                @if($posterUrl)
                    <img src="{{ $posterUrl }}" alt="{{ $movie['title'] }}" class="w-full h-auto">
                @else
                    <div class="aspect-[2/3] bg-neutral-900 flex items-center justify-center text-xs text-neutral-500">Poster N/A</div>
                @endif
            </div>

            <!-- Detail Info -->
            <div class="flex-1 space-y-4 text-center md:text-left">
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                    @foreach($movie['genres'] ?? [] as $genre)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-neutral-800 text-neutral-300 border border-neutral-700">
                            {{ $genre['name'] }}
                        </span>
                    @endforeach
                </div>

                <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">
                    {{ $movie['title'] }}
                </h1>

                @if(!empty($movie['tagline']))
                    <p class="text-sm italic text-neutral-400">"{{ $movie['tagline'] }}"</p>
                @endif

                <div class="flex flex-wrap items-center justify-center md:justify-start gap-6 text-sm text-neutral-300 pt-2">
                    <span class="flex items-center gap-1.5 text-amber-400 font-bold">
                        ★ {{ number_format($movie['vote_average'], 1) }} / 10
                    </span>
                    <span>•</span>
                    <span>{{ $movie['runtime'] ?? 0 }} Menit</span>
                    <span>•</span>
                    <span>{{ date('d M Y', strtotime($movie['release_date'] ?? 'now')) }}</span>
                </div>

                <div class="pt-4">
                    <h3 class="text-xs uppercase tracking-wider font-bold text-neutral-400 mb-2">Sinopsis</h3>
                    <p class="text-sm text-neutral-300 leading-relaxed max-w-3xl">
                        {{ $movie['overview'] ?: 'Sinopsis belum tersedia.' }}
                    </p>
                </div>

                <!-- CTA Tombol Pesan Jadwal (Akan dihubungkan ke Step 06 & 07) -->
                <div class="pt-6">
                    <a href="#jadwal-tayang" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 font-bold text-white text-sm shadow-lg shadow-red-600/40 transition">
                        <span>Pesan Tiket Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Konten Tambahan: Trailer & Pemain -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-14">
        <!-- Trailer Resmi YouTube (Jika Ada) -->
        @if($trailerKey)
            <div>
                <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-red-600 rounded-full inline-block"></span>
                    Trailer Resmi
                </h2>
                <div class="aspect-video w-full max-w-4xl rounded-2xl overflow-hidden border border-neutral-800 shadow-2xl bg-neutral-900">
                    <iframe class="w-full h-full"
                            src="https://www.youtube.com/embed/{{ $trailerKey }}"
                            title="Trailer {{ $movie['title'] }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                    </iframe>
                </div>
            </div>
        @endif

        <!-- Daftar Pemeran (Cast) -->
        @if(!empty($movie['credits']['cast']))
            <div>
                <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-amber-500 rounded-full inline-block"></span>
                    Pemeran Utama
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                    @foreach(array_slice($movie['credits']['cast'], 0, 6) as $cast)
                        <div class="bg-neutral-900 border border-neutral-800/80 rounded-xl p-3 text-center">
                            <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-neutral-800 mb-3 border border-neutral-700">
                                @if(!empty($cast['profile_path']))
                                    <img src="{{ $tmdb->getImageUrl($cast['profile_path'], 'w185') }}" alt="{{ $cast['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-neutral-500">N/A</div>
                                @endif
                            </div>
                            <h4 class="font-bold text-xs text-white truncate">{{ $cast['name'] }}</h4>
                            <p class="text-[10px] text-neutral-400 truncate mt-0.5">{{ $cast['character'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>