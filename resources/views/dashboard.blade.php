<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Buku Tamu KSOP Banten</title>
   
    <script src="https://cdn.tailwindcss.com"></script>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans antialiased text-slate-800">

   
    <nav class="bg-slate-900 text-white shadow-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-ship text-2xl text-blue-400"></i>
                    <span class="font-bold text-lg tracking-wide">KSOP BANTEN - ADMIN PANEL</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-slate-300">Halo, <strong class="text-white">{{ Auth::user()->name ?? 'Admin' }}</strong></span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition duration-200">
                            <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

  
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-xl text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
            </div>
        @endif

       
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Data Kunjungan Buku Tamu</h1>
                <p class="text-sm text-slate-500">Kelola dan pantau seluruh riwayat tamu di KSOP Banten.</p>
            </div>
            <div class="flex items-center space-x-3">
                
                <button onclick="openModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold text-sm shadow-sm flex items-center space-x-2 transition">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>+ Tambah Tamu</span>
                </button>
                
                <a href="{{ route('laporan.exportPdf', request()->query()) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold text-sm shadow-sm flex items-center space-x-2 transition">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Ekspor PDF</span>
                </a>
            </div>
        </div>

       
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                    <i class="fa-solid fa-users text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Total Tamu (Filter)</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $tamus->count() }} Orang</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-emerald-100 text-emerald-600 rounded-lg">
                    <i class="fa-solid fa-filter text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Status Filter</p>
                    <p class="text-base font-semibold text-slate-800 capitalize">{{ $filterType }}</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-indigo-100 text-indigo-600 rounded-lg">
                    <i class="fa-solid fa-calendar-day text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Hari Ini</p>
                    <p class="text-base font-semibold text-slate-800">{{ now()->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </div>

        
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm mb-6">
            <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
              
                <div class="flex flex-wrap items-end gap-3 flex-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tipe Filter</label>
                        <select name="filter_type" id="filter_type" onchange="switchFilterView(this.value)" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 bg-slate-50">
                            <option value="semua" {{ $filterType == 'semua' ? 'selected' : '' }}>Semua Data</option>
                            <option value="hari" {{ $filterType == 'hari' ? 'selected' : '' }}>Harian</option>
                            <option value="minggu" {{ $filterType == 'minggu' ? 'selected' : '' }}>Mingguan</option>
                            <option value="bulanan" {{ $filterType == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                    </div>

                    <!-- Input Filter Harian -->
                    <div id="filter_hari_group" class="filter-input-group {{ $filterType == 'hari' ? '' : 'hidden' }}">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}" class="border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    </div>

                    <!-- Input Filter Mingguan -->
                    <div id="filter_minggu_group" class="filter-input-group flex items-center gap-2 {{ $filterType == 'minggu' ? '' : 'hidden' }}">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Dari Tanggal</label>
                            <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai', now()->startOfWeek()->format('Y-m-d')) }}" class="border border-slate-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <span class="text-xs text-slate-400 self-end mb-2">s/d</span>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
                            <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai', now()->endOfWeek()->format('Y-m-d')) }}" class="border border-slate-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>

                     <!-- ini filter bulanan--> 
                    <div id="filter_bulan_group" class="filter-input-group flex items-center gap-2 {{ $filterType == 'bulanan' ? '' : 'hidden' }}">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Bulan</label>
                            <select name="bulan" class="border border-slate-300 rounded-lg px-3 py-2 text-sm">
                                @for($m=1; $m<=12; $m++)
                                    <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan', date('m')) == sprintf('%02d', $m) ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tahun</label>
                            <input type="number" name="tahun" value="{{ request('tahun', date('Y')) }}" class="border border-slate-300 rounded-lg px-3 py-2 text-sm w-24">
                        </div>
                    </div>

                    <!-- Tombol Terapkan & Reset Filter -->
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                            <i class="fa-solid fa-magnifying-glass mr-1"></i> Filter
                        </button>
                        <a href="{{ route('dashboard') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-3 py-2 rounded-lg text-sm font-semibold transition">
                            Reset
                        </a>
                    </div>
                </div>

                <!-- Live Client Search Bar -->
                <div class="w-full lg:w-72">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Cari Tamu (Realtime)</label>
                    <div class="relative">
                        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari nama / instansi..." class="w-full border border-slate-300 rounded-lg pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <i class="fa-solid fa-search absolute left-3 top-3 text-slate-400 text-sm"></i>
                    </div>
                </div>
            </form>
        </div>

        <!-- TABEL DATA TAMU -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm" id="tamuTable">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 uppercase text-xs font-bold border-b border-slate-200">
                            <th class="p-4 text-center w-12">NO</th>
                            <th class="p-4">WAKTU DATANG</th>
                            <th class="p-4 text-center">FOTO</th>
                            <th class="p-4">NAMA TAMU</th>
                            <th class="p-4">INSTANSI</th>
                            <th class="p-4">NO. HP</th>
                            <th class="p-4">KEPERLUAN</th>
                            <th class="p-4 text-center w-36">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($tamus as $index => $tamu)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 text-center font-medium text-slate-500">{{ $index + 1 }}</td>
                            <td class="p-4 text-slate-600 text-xs font-medium">
                                {{ $tamu->created_at ? $tamu->created_at->format('d/m/Y H:i') : '-' }} WIB
                            </td>
                            <td class="p-4 text-center">
                                @if($tamu->foto_wajah)
                                    <img src="{{ asset('storage/' . $tamu->foto_wajah) }}" onclick="openImageModal('{{ asset('storage/' . $tamu->foto_wajah) }}')" class="w-10 h-10 object-cover rounded-full mx-auto border-2 border-slate-200 cursor-pointer hover:opacity-80 transition" alt="Foto Tamu">
                                @else
                                    <div class="w-10 h-10 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center mx-auto text-xs">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="p-4 font-bold text-slate-900">{{ $tamu->nama }}</td>
                            <td class="p-4 text-slate-700">{{ $tamu->instansi }}</td>
                            <td class="p-4 text-slate-600">{{ $tamu->no_hp }}</td>
                            <td class="p-4 text-slate-600 max-w-xs truncate">{{ $tamu->keperluan }}</td>
                            <td class="p-4 text-center space-x-1">
                                <!-- Button Detail -->
                                <button onclick="openModal('modalDetail{{ $tamu->id }}')" class="p-2 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg text-xs font-semibold transition" title="Lihat Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <!-- Button Edit -->
                                <button onclick="openModal('modalEdit{{ $tamu->id }}')" class="p-2 bg-amber-100 text-amber-600 hover:bg-amber-200 rounded-lg text-xs font-semibold transition" title="Edit Data">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <!-- Button Hapus -->
                                <form action="{{ route('tamu.destroy', $tamu->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tamu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg text-xs font-semibold transition" title="Hapus Data">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-4xl mb-2 block"></i>
                                Tidak ada data kunjungan tamu ditemukan pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

   
    <!-- ini untuk TAMBAH TAMU MANUAL (FILE UPLOAD & KAMERA LIVE) -->
   
    <div id="modalTambah" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 border-b pb-3">
                <h3 class="text-lg font-bold text-slate-900"><i class="fa-solid fa-user-plus text-blue-600 mr-2"></i>Tambah Tamu Manual</h3>
                <button onclick="closeModal('modalTambah')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">&times;</button>
            </div>
            
            <form action="{{ route('tamu.store') }}" method="POST">
                @csrf
                
                <!-- Hidden input untuk menyimpan string Base64 foto -->
                <input type="hidden" name="foto_wajah" id="foto_base64_input">

                <div class="space-y-4 text-sm">
                    <!-- Input Foto Wajah & Live Kamera -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Foto Tamu (Opsional)</label>
                        
                        <!-- Tombol Opsi: Choose File & Buka Kamera -->
                        <div class="flex items-center gap-2 mb-2">
                            <input type="file" id="foto_file" accept="image/*" onchange="convertImageToBase64(this)" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-300 rounded-lg p-1">
                            <button type="button" onclick="startCamera()" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition whitespace-nowrap flex items-center gap-1 shadow-sm">
                                <i class="fa-solid fa-camera"></i> Kamera
                            </button>
                        </div>

                        <!-- Stream Kamera Live -->
                        <div id="webcam_container" class="hidden mb-3 text-center bg-slate-900 rounded-xl p-2 relative shadow-inner">
                            <video id="webcam_video" autoplay playsinline class="w-full h-48 object-cover rounded-lg border border-slate-700"></video>
                            <div class="mt-2 flex justify-center gap-2">
                                <button type="button" onclick="takeSnapshot()" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow flex items-center gap-1">
                                    <i class="fa-solid fa-circle-dot"></i> Jepret Foto
                                </button>
                                <button type="button" onclick="stopCamera()" class="bg-slate-700 hover:bg-slate-800 text-slate-200 text-xs font-semibold px-3 py-1.5 rounded-lg">
                                    Batal
                                </button>
                            </div>
                        </div>

                        <!-- Canvas untuk Capture Foto -->
                        <canvas id="webcam_canvas" class="hidden"></canvas>

                        <!-- Preview Hasil Foto -->
                        <div id="preview_container" class="mt-2 hidden text-center">
                            <img id="foto_preview" src="" class="w-24 h-24 object-cover rounded-xl border mx-auto shadow-sm">
                            <button type="button" onclick="removePhoto()" class="text-xs text-red-600 hover:underline mt-1 inline-block font-medium">Hapus Foto</button>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama" required class="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Instansi / Perusahaan *</label>
                        <input type="text" name="instansi" required class="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP *</label>
                        <input type="text" name="no_hp" required class="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Keperluan Kunjungan *</label>
                        <textarea name="keperluan" rows="3" required class="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-semibold text-sm hover:bg-slate-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    
    <!-- ini untuk DETAIL & EDIT TAMU (LOOPING)  -->
   
    @foreach($tamus as $tamu)
        <!-- MODAL DETAIL -->
        <div id="modalDetail{{ $tamu->id }}" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
                <div class="flex justify-between items-center mb-4 border-b pb-3">
                    <h3 class="text-lg font-bold text-slate-900"><i class="fa-solid fa-address-card text-blue-600 mr-2"></i>Detail Kunjungan</h3>
                    <button onclick="closeModal('modalDetail{{ $tamu->id }}')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">&times;</button>
                </div>
                <div class="space-y-3 text-sm">
                    @if($tamu->foto_wajah)
                        <div class="text-center mb-3">
                            <img src="{{ asset('storage/' . $tamu->foto_wajah) }}" class="w-24 h-24 object-cover rounded-xl border mx-auto shadow-sm">
                        </div>
                    @endif
                    <div><span class="text-slate-400 block text-xs">Waktu Kedatangan:</span> <strong>{{ $tamu->created_at ? $tamu->created_at->format('d F Y - H:i') : '-' }} WIB</strong></div>
                    <div><span class="text-slate-400 block text-xs">Nama Tamu:</span> <strong>{{ $tamu->nama }}</strong></div>
                    <div><span class="text-slate-400 block text-xs">Instansi:</span> <strong>{{ $tamu->instansi }}</strong></div>
                    <div><span class="text-slate-400 block text-xs">No. HP:</span> <strong>{{ $tamu->no_hp }}</strong></div>
                    <div><span class="text-slate-400 block text-xs">Keperluan:</span> <p class="bg-slate-50 p-3 rounded-lg border text-slate-700 mt-1">{{ $tamu->keperluan }}</p></div>
                </div>
                <div class="mt-6 text-right">
                    <button onclick="closeModal('modalDetail{{ $tamu->id }}')" class="px-4 py-2 bg-slate-800 text-white rounded-lg font-semibold text-sm">Tutup</button>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT -->
        <div id="modalEdit{{ $tamu->id }}" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
                <div class="flex justify-between items-center mb-4 border-b pb-3">
                    <h3 class="text-lg font-bold text-slate-900"><i class="fa-solid fa-pen-to-square text-amber-600 mr-2"></i>Edit Data Tamu</h3>
                    <button onclick="closeModal('modalEdit{{ $tamu->id }}')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">&times;</button>
                </div>
                <form action="{{ route('tamu.update', $tamu->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4 text-sm">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                            <input type="text" name="nama" value="{{ $tamu->nama }}" required class="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Instansi / Perusahaan *</label>
                            <input type="text" name="instansi" value="{{ $tamu->instansi }}" required class="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP *</label>
                            <input type="text" name="no_hp" value="{{ $tamu->no_hp }}" required class="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Keperluan Kunjungan *</label>
                            <textarea name="keperluan" rows="3" required class="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-amber-500">{{ $tamu->keperluan }}</textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('modalEdit{{ $tamu->id }}')" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-semibold text-sm hover:bg-slate-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg font-semibold text-sm hover:bg-amber-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- MODAL ZOOM PREVIEW FOTO -->
    <div id="modalFoto" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50" onclick="closeModal('modalFoto')">
        <div class="max-w-lg w-full p-2 relative">
            <img id="imgZoom" src="" class="w-full h-auto max-h-[80vh] object-contain rounded-2xl shadow-2xl border-2 border-white">
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        let webcamStream = null;

        // Switch Input Filter berdasarkan Pilihan
        function switchFilterView(val) {
            document.querySelectorAll('.filter-input-group').forEach(el => el.classList.add('hidden'));
            if (val === 'hari') document.getElementById('filter_hari_group').classList.remove('hidden');
            if (val === 'minggu') document.getElementById('filter_minggu_group').classList.remove('hidden');
            if (val === 'bulanan') document.getElementById('filter_bulan_group').classList.remove('hidden');
        }

        // Fungsi Buka & Tutup Modal
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            if (id === 'modalTambah') {
                stopCamera();
            }
            document.getElementById(id).classList.add('hidden');
        }

        // Buka Zoom Foto
        function openImageModal(url) {
            document.getElementById('imgZoom').src = url;
            openModal('modalFoto');
        }

        // Live Realtime Table Search
        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#tamuTable tbody tr");

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }

        // 1. Start Webcam
        async function startCamera() {
            const video = document.getElementById('webcam_video');
            const container = document.getElementById('webcam_container');
            
            try {
                webcamStream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: "user" }, 
                    audio: false 
                });
                video.srcObject = webcamStream;
                container.classList.remove('hidden');
            } catch (err) {
                alert("Gagal mengakses kamera. Pastikan browser diizinkan mengakses webcam!");
                console.error(err);
            }
        }

        // 2. Take Photo Snapshot
        function takeSnapshot() {
            const video = document.getElementById('webcam_video');
            const canvas = document.getElementById('webcam_canvas');
            const context = canvas.getContext('2d');

            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;

            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const base64Data = canvas.toDataURL('image/jpeg');

            document.getElementById('foto_base64_input').value = base64Data;
            document.getElementById('foto_preview').src = base64Data;
            document.getElementById('preview_container').classList.remove('hidden');

            stopCamera();
        }

        // 3. Stop Webcam
        function stopCamera() {
            if (webcamStream) {
                webcamStream.getTracks().forEach(track => track.stop());
                webcamStream = null;
            }
            document.getElementById('webcam_container').classList.add('hidden');
        }

        // 4. Convert File Image to Base64
        function convertImageToBase64(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('foto_base64_input').value = e.target.result;
                    document.getElementById('foto_preview').src = e.target.result;
                    document.getElementById('preview_container').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // 5. Reset Photo Selection
        function removePhoto() {
            document.getElementById('foto_file').value = '';
            document.getElementById('foto_base64_input').value = '';
            document.getElementById('preview_container').classList.add('hidden');
            stopCamera();
        }
    </script>
</body>
</html>