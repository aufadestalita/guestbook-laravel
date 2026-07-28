<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - KSOP Banten</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* Animasi Fade-In & Slide-Up Halus saat Halaman Dimuat */
        @keyframes fadeInSmooth {
            0% {
                opacity: 0;
                transform: translateY(24px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInSmooth 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed flex items-center justify-center p-4"
    style="background-image: url('https://images.unsplash.com/photo-1518837695005-2083093ee35b?q=80&w=1920');">

    <!-- Overlay Gelap Biar Teks & Kaca Tetap Kontras -->
    <div class="fixed inset-0 bg-slate-950/60 backdrop-brightness-75 -z-10"></div>

    <!-- Container Utama Card Transparan (Glassmorphism) -->
    <div class="w-full max-w-md p-8 rounded-3xl bg-blue-950/80 backdrop-blur-xl border border-white/20 shadow-2xl text-white animate-fade-in">
        
        <!-- Header Login -->
        <div class="text-center mb-6">
            <!-- Icon Gembok / Shield Admin -->
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/10 border border-white/20 mb-3 shadow-inner text-cyan-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-white drop-shadow">Login Admin</h2>
            <p class="text-xs text-blue-200/80 mt-1">Silakan masuk untuk memantau data tamu KSOP Banten</p>
        </div>

        <!-- Pesan Error jika password/email salah -->
        @if($errors->any())
            <div class="mb-6 p-3.5 rounded-xl bg-red-500/20 border border-red-400/40 text-red-200 text-xs font-medium flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Form Login Area -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Input Email -->
            <div>
                <label class="block text-xs font-medium mb-1.5 text-blue-100">Email Akses</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: admin@ksop.go.id"
                    class="w-full px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200/50 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:bg-white/20 transition">
            </div>
            
            <!-- Input Password dengan Toggle Mata -->
            <div>
                <label class="block text-xs font-medium mb-1.5 text-blue-100">Password</label>
                <div class="relative flex items-center">
                    <input type="password" id="inputPassword" name="password" required placeholder="Masukkan password Anda"
                        class="w-full px-4 py-2.5 pr-11 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200/50 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:bg-white/20 transition">
                    
                    <!-- Tombol Ikon Mata -->
                    <button type="button" id="btnTogglePassword" onclick="togglePasswordVisibility()" 
                        class="absolute right-3 text-blue-200/70 hover:text-white focus:outline-none transition p-1 rounded-lg hover:bg-white/10">
                        <!-- Ikon Mata Terbuka (Lihat Password) -->
                        <svg id="iconEyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <!-- Ikon Mata Coret (Sembunyikan Password) -->
                        <svg id="iconEyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.038 10.038 0 011.83-.178c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-semibold rounded-xl shadow-lg transition duration-300 border border-white/20 text-sm tracking-wide flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    <span>Masuk Dashboard</span>
                </button>
            </div>
        </form>

        <!-- Footer Kecil -->
        <p class="text-[11px] text-center text-blue-200/50 mt-6 border-t border-white/10 pt-4">
            System &copy; {{ date('Y') }} KSOP Banten. All rights reserved.
        </p>
    </div>

    <!-- Script JS untuk Toggle Password -->
    <script>
        function togglePasswordVisibility() {
            const inputPassword = document.getElementById('inputPassword');
            const iconEyeOpen = document.getElementById('iconEyeOpen');
            const iconEyeClosed = document.getElementById('iconEyeClosed');

            if (inputPassword.type === 'password') {
                inputPassword.type = 'text';
                iconEyeOpen.classList.add('hidden');
                iconEyeClosed.classList.remove('hidden');
            } else {
                inputPassword.type = 'password';
                iconEyeClosed.classList.add('hidden');
                iconEyeOpen.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>