<x-guest-layout title="Login - CineBook">
    <div class="w-full max-w-md bg-neutral-900/80 backdrop-blur-md border border-neutral-800 rounded-2xl p-8 shadow-2xl shadow-red-950/20">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-white tracking-tight">Selamat Datang Kembali</h1>
            <p class="text-sm text-neutral-400 mt-1">Masuk untuk memesan tiket film favoritmu</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-red-950/50 border border-red-800/60 text-red-200 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs uppercase tracking-wider font-semibold text-neutral-300 mb-2">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 rounded-xl bg-neutral-950 border border-neutral-800 text-white placeholder-neutral-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                    placeholder="nama@email.com">
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-xs uppercase tracking-wider font-semibold text-neutral-300">Kata Sandi</label>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 rounded-xl bg-neutral-950 border border-neutral-800 text-white placeholder-neutral-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                    placeholder="••••••••">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-neutral-950 border-neutral-800 text-red-600 focus:ring-0 focus:ring-offset-0">
                    <span class="text-neutral-400 text-xs">Ingat saya</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 font-semibold text-white tracking-wide shadow-lg shadow-red-600/30 hover:shadow-red-600/50 transition duration-200">
                Masuk
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-neutral-400">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-red-400 hover:text-red-300 font-semibold underline underline-offset-4">Daftar sekarang</a>
        </p>

        <!-- Dummy credentials helper for testing/portfolio -->
        <div class="mt-6 pt-5 border-t border-neutral-800/80 text-[11px] text-neutral-500 space-y-1">
            <p class="font-semibold text-neutral-400">Akun Pengujian (Demo):</p>
            <p>Admin: <code class="text-neutral-300">admin@cinebook.com</code> | pass: <code class="text-neutral-300">password123</code></p>
            <p>Customer: <code class="text-neutral-300">customer@cinebook.com</code> | pass: <code class="text-neutral-300">password123</code></p>
        </div>
    </div>
</x-guest-layout>