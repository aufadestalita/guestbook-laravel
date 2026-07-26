<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Buku Tamu - KSOP Banten</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col items-center justify-center min-h-screen py-10 px-4">

    <!-- Container Utama -->
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl overflow-hidden border-t-4 border-blue-800">
        
        <!-- Header -->
        <div class="bg-blue-50 px-8 py-6 border-b border-blue-100 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-blue-900">Formulir Buku Tamu</h2>
                <p class="text-sm text-blue-700 mt-1">Silakan lengkapi data dan ambil foto wajah Anda.</p>
            </div>
            <!-- Tombol Kembali ke Portal -->
            <a href="{{ url('/') }}" class="text-blue-800 hover:text-blue-900 bg-white border border-blue-200 px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition">
                &larr; Kembali
            </a>
        </div>

        <!-- Notifikasi Sukses -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-8 mt-6 rounded-r-lg shadow-sm" role="alert">
            <p class="font-bold">Berhasil!</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <!-- Form Area -->
        <form action="{{ route('bukutamu.store') }}" method="POST" id="formTamu" class="p-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- KOLOM KIRI: Inputan Teks -->
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" required placeholder="Contoh: Budi Santoso"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Instansi / Perusahaan</label>
                        <input type="text" name="instansi" required placeholder="Contoh: PT Pelindo"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>

                    <!-- INPUTAN BARU: Nomor HP Tamu -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" required placeholder="Contoh: 08123456789"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Keperluan</label>
                        <textarea name="keperluan" required rows="3" placeholder="Contoh: Mengurus dokumen kapal..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"></textarea>
                    </div>
                </div>

                <!-- KOLOM KANAN: Kamera Wajah -->
                <div class="flex flex-col items-center bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 w-full text-center">Verifikasi Wajah</label>
                    
                    <!-- Kotak Layar Kamera -->
                    <div class="relative w-full aspect-video bg-black rounded-lg overflow-hidden shadow-inner flex items-center justify-center">
                        <video id="kamera" class="w-full h-full object-cover" autoplay playsinline></video>
                        <img id="hasilFoto" class="w-full h-full object-cover hidden absolute top-0 left-0" alt="Hasil Jepretan" />
                        <p id="teksLoading" class="text-white absolute text-sm animate-pulse">Menghubungkan ke kamera...</p>
                    </div>

                    <!-- Input Tersembunyi untuk menyimpan teks base64 gambar -->
                    <input type="hidden" name="foto_wajah" id="inputFoto" required>

                    <!-- Tombol Kontrol Kamera -->
                    <div class="mt-4 w-full flex space-x-2">
                        <button type="button" id="btnJepret" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Ambil Foto
                        </button>
                        <button type="button" id="btnUlang" class="hidden flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Ulangi
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-3 text-center">Pastikan wajah terlihat jelas dan pencahayaan cukup.</p>
                </div>
            </div>

            <!-- Area Submit Utama -->
            <div class="mt-8 pt-6 border-t border-gray-100">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-lg font-bold py-4 rounded-xl shadow-lg transition duration-300 flex items-center justify-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Data Kehadiran
                </button>
            </div>
        </form>
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
                teksLoading.classList.add('hidden'); // Sembunyikan teks loading kalau kamera sukses menyala
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
                alert("Wajib ambil foto wajah terlebih dahulu, cuy!");
            }
        });
    </script>
</body>
</html>