<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Buku Tamu - KSOP Banten</title>
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
<body class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed"
    style="background-image: url('https://images.unsplash.com/photo-1578575437130-527eed3abbec?q=80&w=1920');">

    <!-- Overlay Gelap Biar Teks Tetap Kelihatan Kontras & Clear -->
    <div class="min-h-screen w-full bg-slate-950/50 backdrop-brightness-75 py-10 px-4 flex items-center justify-center">

        <!-- Container Utama Card Transparan (Glassmorphism) + Animasi Fade In -->
        <div class="w-full max-w-4xl p-6 md:p-8 rounded-3xl bg-blue-950/80 backdrop-blur-xl border border-white/20 shadow-2xl text-white animate-fade-in">
            
            <!-- Header (Rapi tanpa tombol Kembali) -->
            <div class="pb-6 mb-6 border-b border-white/10 text-center md:text-left">
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow">
                    Formulir Buku Tamu
                </h2>
                <p class="text-blue-200 text-xs md:text-sm mt-1">
                    Silakan lengkapi data dan ambil foto wajah Anda.
                </p>
            </div>

            <!-- Notifikasi Sukses -->
            @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 text-sm" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <!-- Form Area -->
            <form action="{{ route('bukutamu.store') }}" method="POST" id="formTamu">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- KOLOM KIRI: Inputan Teks -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs md:text-sm font-medium mb-1.5 text-blue-100">Nama Lengkap</label>
                            <input type="text" name="nama" required placeholder="Contoh: Budi Santoso"
                                class="w-full px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200/50 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:bg-white/20 transition">
                        </div>

                        <div>
                            <label class="block text-xs md:text-sm font-medium mb-1.5 text-blue-100">Instansi / Perusahaan</label>
                            <input type="text" name="instansi" required placeholder="Contoh: PT Pelindo"
                                class="w-full px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200/50 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:bg-white/20 transition">
                        </div>

                        <!-- INPUTAN: Nomor HP Tamu -->
                        <div>
                            <label class="block text-xs md:text-sm font-medium mb-1.5 text-blue-100">Nomor HP / WhatsApp</label>
                            <input type="text" name="no_hp" required placeholder="Contoh: 08123456789"
                                class="w-full px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200/50 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:bg-white/20 transition">
                        </div>

                        <div>
                            <label class="block text-xs md:text-sm font-medium mb-1.5 text-blue-100">Keperluan</label>
                            <textarea name="keperluan" required rows="3" placeholder="Contoh: Mengurus dokumen kapal..."
                                class="w-full px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200/50 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:bg-white/20 transition resize-none"></textarea>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Kamera Wajah -->
                    <div class="flex flex-col justify-between p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md">
                        <div>
                            <label class="block text-sm font-semibold text-center mb-3 text-blue-100">Verifikasi Wajah</label>
                            
                            <!-- Kotak Layar Kamera -->
                            <div class="relative w-full aspect-video bg-black/60 rounded-xl border border-white/20 overflow-hidden shadow-inner flex items-center justify-center">
                                <video id="kamera" class="w-full h-full object-cover" autoplay playsinline></video>
                                <img id="hasilFoto" class="w-full h-full object-cover hidden absolute top-0 left-0" alt="Hasil Jepretan" />
                                <p id="teksLoading" class="text-white absolute text-xs md:text-sm animate-pulse">Menghubungkan ke kamera...</p>
                            </div>

                            <!-- Input Tersembunyi untuk menyimpan teks base64 gambar -->
                            <input type="hidden" name="foto_wajah" id="inputFoto" required>

                            <p class="text-[11px] text-blue-200/70 text-center mt-2">Pastikan wajah terlihat jelas dan pencahayaan cukup.</p>
                        </div>

                        <!-- Tombol Kontrol Kamera -->
                        <div class="mt-4 w-full flex space-x-2">
                            <button type="button" id="btnJepret" class="flex-1 bg-blue-600/80 hover:bg-blue-500 text-white text-sm font-medium py-2.5 px-4 rounded-xl border border-white/20 transition shadow-md flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Ambil Foto</span>
                            </button>
                            <button type="button" id="btnUlang" class="hidden flex-1 bg-slate-600/80 hover:bg-slate-500 text-white text-sm font-medium py-2.5 px-4 rounded-xl border border-white/20 transition shadow-md">
                                Ulangi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Area Submit Utama -->
                <div class="mt-8 pt-4 border-t border-white/10">
                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-semibold rounded-xl shadow-lg transition duration-300 border border-white/20 text-sm tracking-wide flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Data Kehadiran
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Script Kamera (WebRTC API) -->
    <script>
        const video = document.getElementById('kamera');
        const canvas = document.createElement('canvas');
        const hasilFoto = document.getElementById('hasilFoto');
        const inputFoto = document.getElementById('inputFoto');
        const btnJepret = document.getElementById('btnJepret');
        const btnUlang = document.getElementById('btnUlang');
        const teksLoading = document.getElementById('teksLoading');
        const formTamu = document.getElementById('formTamu');

        // Nyalakan Kamera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                teksLoading.classList.add('hidden');
            })
            .catch(err => {
                teksLoading.textContent = "Kamera tidak terdeteksi / akses ditolak.";
                teksLoading.classList.remove('animate-pulse');
                teksLoading.classList.add('text-red-400');
                console.error("Error akses kamera: ", err);
            });

        // Event saat tombol "Ambil Foto" diklik
        btnJepret.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = canvas.toDataURL('image/png');
            
            inputFoto.value = imageData;
            hasilFoto.src = imageData;
            
            video.classList.add('hidden');
            hasilFoto.classList.remove('hidden');
            
            btnJepret.classList.add('hidden');
            btnUlang.classList.remove('hidden');
        });

        // Event saat tombol "Ulangi" diklik
        btnUlang.addEventListener('click', () => {
            inputFoto.value = "";
            
            hasilFoto.classList.add('hidden');
            video.classList.remove('hidden');
            
            btnUlang.classList.add('hidden');
            btnJepret.classList.remove('hidden');
        });

        // Validasi form agar tamu tidak bisa klik simpan kalau belum foto
        formTamu.addEventListener('submit', function(e) {
            if(inputFoto.value === "") {
                e.preventDefault();
                alert("Wajib ambil foto wajah terlebih dahulu!");
            }
        });
    </script>
</body>
</html>