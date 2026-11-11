<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Panel - CineBook' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-950 text-neutral-100 antialiased font-['Plus_Jakarta_Sans',sans-serif] min-h-screen flex flex-col">

    <!-- Top Admin Bar -->
    <header class="bg-neutral-900 border-b border-neutral-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <span class="text-2xl font-black tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-amber-500 font-['Bebas_Neue',sans-serif]">
                        CINEBOOK ADMIN
                    </span>
                </a>
                <span class="hidden sm:inline px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-red-600/20 text-red-400 border border-red-500/30">
                    Administrator
                </span>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-xs text-neutral-400 hover:text-white transition">
                    &larr; Lihat Website Utama
                </a>

                <div class="flex items-center gap-3 pl-4 border-l border-neutral-800">
                    <span class="text-xs font-semibold text-neutral-300 hidden sm:inline">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="/logout" class="inline">
                        @csrf
                        <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-neutral-300 font-medium transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-10">
        {{ $slot }}
    </main>

    <footer class="py-6 border-t border-neutral-900 text-center text-xs text-neutral-600">
        &copy; {{ date('Y') }} CineBook Cinema System Management.
    </footer>
</body>
</html>