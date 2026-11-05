<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'CineBook - Pengalaman Nonton Terbaik' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-950 text-neutral-100 antialiased font-['Plus_Jakarta_Sans',sans-serif] selection:bg-red-600 selection:text-white min-h-screen flex flex-col justify-between">

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-neutral-950/80 border-b border-neutral-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
                <span class="text-3xl font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-red-600 via-rose-500 to-amber-500 font-['Bebas_Neue',sans-serif]">
                    CINEBOOK
                </span>
            </a>

            <!-- Search Bar -->
            <form action="{{ route('movies.catalog') }}" method="GET" class="hidden md:flex items-center flex-1 max-w-md mx-6">
                <div class="relative w-full">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul film favoritmu..."
                        class="w-full pl-10 pr-4 py-2 rounded-full bg-neutral-900 border border-neutral-800 text-sm text-neutral-200 placeholder-neutral-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition">
                    <svg class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>

            <!-- Navigation Links & User Menu -->
            <nav class="flex items-center gap-4 sm:gap-6">
                <a href="{{ route('home') }}" class="text-sm font-medium hover:text-red-500 transition {{ request()->routeIs('home') ? 'text-red-500' : 'text-neutral-300' }}">
                    Beranda
                </a>
                <a href="{{ route('movies.catalog') }}" class="text-sm font-medium hover:text-red-500 transition {{ request()->routeIs('movies.catalog') ? 'text-red-500' : 'text-neutral-300' }}">
                    Katalog Film
                </a>

                @auth
                    <!-- User Dropdown / Profile -->
                    <div class="flex items-center gap-3 pl-2 sm:pl-4 border-l border-neutral-800">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-xs px-2.5 py-1 rounded bg-amber-500/20 text-amber-400 font-semibold border border-amber-500/30">
                                Panel Admin
                            </a>
                        @endif

                        <span class="text-sm font-semibold text-neutral-300 hidden sm:inline">
                            {{ auth()->user()->name }}
                        </span>

                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-neutral-300 font-medium transition">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Auth Links -->
                    <div class="flex items-center gap-2 sm:gap-3 pl-2 sm:pl-4 border-l border-neutral-800">
                        <a href="{{ route('login') }}" class="text-sm text-neutral-300 hover:text-white font-medium px-3 py-2 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-xs sm:text-sm font-semibold px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white shadow-lg shadow-red-600/30 transition duration-200">
                            Daftar
                        </a>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer Cinematic -->
    <footer class="bg-neutral-950 border-t border-neutral-900 mt-20 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <span class="text-2xl font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-amber-500 font-['Bebas_Neue',sans-serif]">
                    CINEBOOK
                </span>
                <p class="text-xs text-neutral-500 mt-1 max-w-sm">
                    Platform reservasi tiket bioskop modern dengan data film resmi dari The Movie Database (TMDB).
                </p>
            </div>
            <div class="text-xs text-neutral-600 text-center md:text-right">
                <p>&copy; {{ date('Y') }} CineBook. Dibuat untuk portfolio pengembangan web modern.</p>
                <p class="mt-1 text-neutral-500">Data & poster disediakan oleh TMDB API.</p>
            </div>
        </div>
    </footer>

</body>
</html>