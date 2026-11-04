<x-guest-layout title="Daftar Akun - CineBook">
    <div class="w-full max-w-md bg-neutral-900/80 backdrop-blur-md border border-neutral-800 rounded-2xl p-8 shadow-2xl shadow-red-950/20">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-white tracking-tight">Buat Akun CineBook</h1>
            <p class="text-sm text-neutral-400 mt-1">Daftar untuk menikmati kemudahan nonton bioskop</p>
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

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Nama -->
            <div>
                <label for="name" class="block text-xs uppercase tracking-wider font-semibold text-neutral-300 mb-1.5">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full px-4 py-2.5 rounded-xl bg-neutral-950 border border-neutral-800 text-white placeholder-neutral-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                    placeholder="John Doe">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs uppercase tracking-wider font-semibold text-neutral-300 mb-1.5">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2.5 rounded-xl bg-neutral-950 border border-neutral-800 text-white placeholder-neutral-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                    placeholder="nama@email.com">
            </div>

            <!-- No Telepon -->
            <div>
                <label for="phone" class="block text-xs uppercase tracking-wider font-semibold text-neutral-300 mb-1.5">Nomor WhatsApp / HP</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-2.5 rounded-xl bg-neutral-950 border border-neutral-800 text-white placeholder-neutral-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                    placeholder="081234567890">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs uppercase tracking-wider font-semibold text-neutral-300 mb-1.5">Kata Sandi</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full px-4 py-2.5 rounded-xl bg-neutral-950 border border-neutral-800 text-white placeholder-neutral-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                    placeholder="Minimal 8 karakter">
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirmation" class="block text-xs uppercase tracking-wider font-semibold text-neutral-300 mb-1.5">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full px-4 py-2.5 rounded-xl bg-neutral-950 border border-neutral-800 text-white placeholder-neutral-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                    placeholder="Ulangi kata sandi">
            </div>

            <!-- Tombol Register -->
            <button type="submit"
                class="w-full mt-2 py-3 px-4 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 font-semibold text-white tracking-wide shadow-lg shadow-red-600/30 hover:shadow-red-600/50 transition duration-200">
                Daftar Akun
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-neutral-400">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="text-red-400 hover:text-red-300 font-semibold underline underline-offset-4">Masuk di sini</a>
        </p>
    </div>
</x-guest-layout>