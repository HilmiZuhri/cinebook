<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'CineBook - Cinema Experience' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-950 text-neutral-100 antialiased font-['Plus_Jakarta_Sans',sans-serif] selection:bg-red-600 selection:text-white min-h-screen flex flex-col justify-between">
    <!-- Background Glow Effect -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-red-600/15 rounded-full blur-[130px]"></div>
    </div>

    <!-- Header / Brand -->
    <header class="py-6 px-6 sm:px-12 flex justify-between items-center">
        <a href="/" class="flex items-center gap-2 group">
            <span class="text-3xl tracking-wider font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-rose-500 to-amber-500 font-['Bebas_Neue',sans-serif]">
                CINEBOOK
            </span>
        </a>
        <a href="/" class="text-xs uppercase tracking-widest text-neutral-400 hover:text-white transition">
            &larr; Kembali ke Beranda
        </a>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center p-4">
        {{ $slot }}
    </main>

    <!-- Simple Footer -->
    <footer class="py-6 text-center text-xs text-neutral-600">
        &copy; {{ date('Y') }} CineBook Cinema System. Portfolio Project.
    </footer>
</body>
</html>