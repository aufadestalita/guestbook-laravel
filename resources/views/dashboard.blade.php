<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Buku Tamu KSOP Banten</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        /* Custom scrollbar for dark theme */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.6); }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.4); }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 bg-cover bg-center bg-fixed antialiased"
    style="background-image: url('https://images.unsplash.com/photo-1518837695005-2083093ee35b?q=80&w=1920');">

    <!-- Background Overlay Dark Glass -->
    <div class="min-h-screen w-full bg-slate-950/80 backdrop-brightness-75 pb-16">

        <!-- NAVBAR UTAMA -->
        <nav class="w-full bg-slate-900/80 backdrop-blur-xl border-b border-white/10 px-4 sm:px-8 py-3.5 shadow-2xl sticky top-0 z-40">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-500/20 border border-blue-400/30 rounded-xl text-cyan-400">
                        <i class="fa-solid fa-ship text-xl"></i>
                    </div>
                    <span class="font-bold text-base sm:text-lg tracking-wider text-white">KSOP BANTEN <span class="text-cyan-400 font-normal">| ADMIN PANEL</span></span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-xs sm:text-sm text-slate-300 hidden sm:inline">Halo, <strong class="text-white font-semibold">{{ Auth::user()->name ?? 'Admin' }}</strong></span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600/80 hover:bg-red-500 text-white text-xs font-semibold px-3.5 py-2 rounded-xl border border-white/20 transition flex items-center shadow-lg gap-1.5">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- CONTAINER CONTENT -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-6">

            <!-- FLASH SESSION SUCCESS ALERT -->
            @if(session('success'))
                <div class="p-4 bg-emerald-950/80 border-l-4 border-emerald-400 border-y border-r border-emerald-500/30 text-emerald-200 rounded-r-2xl backdrop-blur-xl shadow-xl flex items-center justify-between animate-fade-in">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-2xl text-emerald-400"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white font-bold text-xl px-2">&times;</button>
                </div>
            @endif

            <!-- PAGE HEADER & ACTION BUTTONS -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-wide drop-shadow-md">Data Kunjungan Buku Tamu</h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">Kelola dan pantau seluruh riwayat tamu di KSOP Banten secara real-time.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="openModal('modalTambah')" class="bg-blue-600/90 hover:bg-blue-500 text-white px-4 py-2.5 rounded-xl font-semibold text-sm shadow-lg border border-white/20 flex items-center space-x-2 transition hover:scale-105">
                        <i class="fa-solid fa-user-plus text-cyan-300"></i>
                        <span>Tambah Tamu</span>
                    </button>
                    <a href="{{ route('laporan.exportPdf', request()->query()) }}" class="bg-red-600/90 hover:bg-red-500 text-white px-4 py-2.5 rounded-xl font-semibold text-sm shadow-lg border border-white/20 flex items-center space-x-2 transition hover:scale-105">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>Ekspor PDF</span>
                    </a>
                </div>
            </div>

            <!-- CARDS STATISTIK (GLASSMORPISM) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-slate-900/70 backdrop-blur-xl p-5 rounded-2xl border border-white/10 shadow-2xl flex items-center space-x-4">
                    <div class="p-3.5 bg-blue-500/20 text-cyan-400 rounded-xl border border-cyan-400/30">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-400 uppercase font-bold tracking-wider">Total Tamu (Filter)</p>
                        <p class="text-2xl font-bold text-white mt-0.5">{{ $tamus->count() }} Orang</p>
                    </div>
                </div>
                <div class="bg-slate-900/70 backdrop-blur-xl p-5 rounded-2xl border border-white/10 shadow-2xl flex items-center space-x-4">
                    <div class="p-3.5 bg-emerald-500/20 text-emerald-400 rounded-xl border border-emerald-400/30">
                        <i class="fa-solid fa-filter text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-400 uppercase font-bold tracking-wider">Status Filter</p>
                        <p class="text-base font-semibold text-emerald-300 capitalize mt-0.5">{{ $filterType }}</p>
                    </div>
                </div>
                <div class="bg-slate-900/70 backdrop-blur-xl p-5 rounded-2xl border border-white/10 shadow-2xl flex items-center space-x-4">
                    <div class="p-3.5 bg-purple-500/20 text-purple-300 rounded-xl border border-purple-400/30">
                        <i class="fa-solid fa-calendar-day text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-400 uppercase font-bold tracking-wider">Hari Ini</p>
                        <p class="text-base font-semibold text-purple-200 mt-0.5">{{ now()->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- AREA FILTER & SEARCH BAR -->
            <div class="bg-slate-900/70 backdrop-blur-xl p-5 rounded-2xl border border-white/10 shadow-2xl">
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                    
                    <div class="flex flex-wrap items-end gap-3 flex-1">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tipe Filter</label>
                            <select name="filter_type" id="filter_type" onchange="switchFilterView(this.value)" class="bg-slate-800/90 border border-white/20 text-white rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-cyan-400 focus:outline-none">
                                <option value="semua" class="bg-slate-900" {{ $filterType == 'semua' ? 'selected' : '' }}>Semua Data</option>
                                <option value="hari" class="bg-slate-900" {{ $filterType == 'hari' ? 'selected' : '' }}>Harian</option>
                                <option value="minggu" class="bg-slate-900" {{ $filterType == 'minggu' ? 'selected' : '' }}>Mingguan</option>
                                <option value="bulanan" class="bg-slate-900" {{ $filterType == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            </select>
                        </div>

                        <!-- Input Filter Harian -->
                        <div id="filter_hari_group" class="filter-input-group {{ $filterType == 'hari' ? '' : 'hidden' }}">
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Pilih Tanggal</label>
                            <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}" class="bg-slate-800/90 border border-white/20 text-white rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-cyan-400 focus:outline-none">
                        </div>

                        <!-- Input Filter Mingguan -->
                        <div id="filter_minggu_group" class="filter-input-group flex items-center gap-2 {{ $filterType == 'minggu' ? '' : 'hidden' }}">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Dari Tanggal</label>
                                <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai', now()->startOfWeek()->format('Y-m-d')) }}" class="bg-slate-800/90 border border-white/20 text-white rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-cyan-400 focus:outline-none">
                            </div>
                            <span class="text-xs text-slate-400 self-end mb-2.5">s/d</span>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Sampai Tanggal</label>
                                <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai', now()->endOfWeek()->format('Y-m-d')) }}" class="bg-slate-800/90 border border-white/20 text-white rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-cyan-400 focus:outline-none">
                            </div>
                        </div>

                        <!-- Input Filter Bulanan -->
                        <div id="filter_bulan_group" class="filter-input-group flex items-center gap-2 {{ $filterType == 'bulanan' ? '' : 'hidden' }}">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Bulan</label>
                                <select name="bulan" class="bg-slate-800/90 border border-white/20 text-white rounded-xl px-3.5 py-2 text-sm focus:ring-2 focus:ring-cyan-400 focus:outline-none">
                                    @for($m=1; $m<=12; $m++)
                                        <option value="{{ sprintf('%02d', $m) }}" class="bg-slate-900" {{ request('bulan', date('m')) == sprintf('%02d', $m) ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tahun</label>
                                <input type="number" name="tahun" value="{{ request('tahun', date('Y')) }}" class="bg-slate-800/90 border border-white/20 text-white rounded-xl px-3.5 py-2 text-sm w-24 focus:ring-2 focus:ring-cyan-400 focus:outline-none">
                            </div>
                        </div>

                        <!-- Tombol Terapkan & Reset Filter -->
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-xl text-sm font-semibold border border-white/20 transition">
                                <i class="fa-solid fa-magnifying-glass mr-1 text-cyan-400"></i> Filter
                            </button>
                            <a href="{{ route('dashboard') }}" class="bg-white/10 hover:bg-white/20 text-slate-300 px-3.5 py-2 rounded-xl text-sm font-semibold border border-white/10 transition">
                                Reset
                            </a>
                        </div>
                    </div>

                    <!-- Live Client Search Bar -->
                    <div class="w-full lg:w-72">
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Cari Tamu (Realtime)</label>
                        <div class="relative">
                            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari nama / instansi..." class="w-full bg-slate-800/90 border border-white/20 rounded-xl pl-9 pr-3 py-2 text-sm text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-400 focus:outline-none transition">
                            <i class="fa-solid fa-search absolute left-3 top-3 text-slate-400 text-sm"></i>
                        </div>
                    </div>
                </form>
            </div>

            <!-- TABEL DATA TAMU -->
            <div class="bg-slate-900/70 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm text-slate-200" id="tamuTable">
                        <thead>
                            <tr class="bg-white/10 text-white uppercase text-xs font-semibold border-b border-white/10">
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
                        <tbody class="divide-y divide-white/10">
                            @forelse($tamus as $index => $tamu)
                            <tr class="hover:bg-white/5 transition">
                                <td class="p-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                                <td class="p-4 text-xs font-medium text-slate-300 whitespace-nowrap">
                                    {{ $tamu->created_at ? $tamu->created_at->format('d/m/Y H:i') : '-' }} WIB
                                </td>
                                <td class="p-4 text-center">
                                    @if($tamu->foto_wajah)
                                        <img src="{{ asset('storage/' . $tamu->foto_wajah) }}" onclick="openImageModal('{{ asset('storage/' . $tamu->foto_wajah) }}')" class="w-10 h-10 object-cover rounded-full mx-auto border-2 border-cyan-400 shadow-md cursor-pointer hover:scale-110 transition" alt="Foto Tamu">
                                    @else
                                        <div class="w-10 h-10 bg-slate-800 text-slate-400 border border-white/10 rounded-full flex items-center justify-center mx-auto text-xs shadow-inner">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 font-bold text-white whitespace-nowrap">{{ $tamu->nama }}</td>
                                <td class="p-4 text-slate-300 whitespace-nowrap">{{ $tamu->instansi }}</td>
                                <td class="p-4 text-slate-300 whitespace-nowrap">{{ $tamu->no_hp }}</td>
                                <td class="p-4 text-slate-300 max-w-xs truncate" title="{{ $tamu->keperluan }}">{{ $tamu->keperluan }}</td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <!-- Button Detail -->
                                        <button onclick="openModal('modalDetail{{ $tamu->id }}')" class="p-2 bg-blue-500/20 text-cyan-300 hover:bg-blue-500/40 border border-blue-400/30 rounded-xl text-xs font-semibold transition" title="Lihat Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <!-- Button Edit -->
                                        <button onclick="openModal('modalEdit{{ $tamu->id }}')" class="p-2 bg-amber-500/20 text-amber-300 hover:bg-amber-500/40 border border-amber-400/30 rounded-xl text-xs font-semibold transition" title="Edit Data">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <!-- Button Hapus -->
                                        <form action="{{ route('tamu.destroy', $tamu->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tamu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-500/20 text-red-300 hover:bg-red-500/40 border border-red-400/30 rounded-xl text-xs font-semibold transition" title="Hapus Data">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400">
                                    <i class="fa-solid fa-folder-open text-4xl mb-2 block text-slate-500"></i>
                                    Tidak ada data kunjungan tamu ditemukan pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- ================= MODAL TAMBAH TAMU MANUAL ================= -->
    <div id="modalTambah" class="fixed inset-0 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4 z-50">
        <div class="bg-slate-900 border border-white/20 rounded-2xl max-w-md w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto text-white">
            <div class="flex justify-between items-center mb-4 border-b border-white/10 pb-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-cyan-400"></i> Tambah Tamu Manual
                </h3>
                <button onclick="closeModal('modalTambah')" class="text-slate-400 hover:text-white font-bold text-xl">&times;</button>
            </div>
            
            <form action="{{ route('tamu.store') }}" method="POST">
                @csrf
                
                <!-- Hidden input untuk menyimpan string Base64 foto -->
                <input type="hidden" name="foto_wajah" id="foto_base64_input">

                <div class="space-y-4 text-sm">
                    <!-- Input Foto Wajah & Live Kamera -->
                    <div>
                        <label class="block font-medium text-slate-300 mb-1.5">Foto Tamu (Opsional)</label>
                        
                        <div class="flex items-center gap-2 mb-2">
                            <input type="file" id="foto_file" accept="image/*" onchange="convertImageToBase64(this)" class="w-full text-xs text-slate-300 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600/30 file:text-cyan-300 hover:file:bg-blue-600/50 bg-slate-800/80 border border-white/20 rounded-xl p-1">
                            <button type="button" onclick="startCamera()" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold px-3 py-2 rounded-xl border border-white/20 transition whitespace-nowrap flex items-center gap-1.5 shadow-md">
                                <i class="fa-solid fa-camera"></i> Kamera
                            </button>
                        </div>

                        <!-- Stream Kamera Live -->
                        <div id="webcam_container" class="hidden mb-3 text-center bg-slate-950 rounded-xl p-2 border border-white/20 shadow-inner">
                            <video id="webcam_video" autoplay playsinline class="w-full h-48 object-cover rounded-lg border border-slate-800"></video>
                            <div class="mt-2.5 flex justify-center gap-2">
                                <button type="button" onclick="takeSnapshot()" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg border border-white/20 shadow flex items-center gap-1">
                                    <i class="fa-solid fa-circle-dot text-cyan-300"></i> Jepret Foto
                                </button>
                                <button type="button" onclick="stopCamera()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold px-3 py-1.5 rounded-lg border border-white/10">
                                    Batal
                                </button>
                            </div>
                        </div>

                        <canvas id="webcam_canvas" class="hidden"></canvas>

                        <!-- Preview Hasil Foto -->
                        <div id="preview_container" class="mt-2 hidden text-center">
                            <img id="foto_preview" src="" class="w-24 h-24 object-cover rounded-xl border border-cyan-400 mx-auto shadow-lg">
                            <button type="button" onclick="removePhoto()" class="text-xs text-red-400 hover:underline mt-1.5 inline-block font-medium">Hapus Foto</button>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-slate-300 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama" required class="w-full bg-slate-800/80 border border-white/20 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-cyan-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-300 mb-1">Instansi / Perusahaan *</label>
                        <input type="text" name="instansi" required class="w-full bg-slate-800/80 border border-white/20 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-cyan-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-300 mb-1">Nomor WhatsApp / HP *</label>
                        <input type="text" name="no_hp" required class="w-full bg-slate-800/80 border border-white/20 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-cyan-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-300 mb-1">Keperluan Kunjungan *</label>
                        <textarea name="keperluan" rows="3" required class="w-full bg-slate-800/80 border border-white/20 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-cyan-400 focus:outline-none"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3 border-t border-white/10 pt-4">
                    <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl font-semibold text-sm border border-white/10">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-semibold text-sm border border-white/20 shadow-lg">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL DETAIL & EDIT (LOOPING) ================= -->
    @foreach($tamus as $tamu)
        <!-- MODAL DETAIL -->
        <div id="modalDetail{{ $tamu->id }}" class="fixed inset-0 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4 z-50">
            <div class="bg-slate-900 border border-white/20 rounded-2xl max-w-md w-full p-6 shadow-2xl relative text-white">
                <div class="flex justify-between items-center mb-4 border-b border-white/10 pb-3">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-address-card text-cyan-400"></i> Detail Kunjungan
                    </h3>
                    <button onclick="closeModal('modalDetail{{ $tamu->id }}')" class="text-slate-400 hover:text-white font-bold text-xl">&times;</button>
                </div>
                <div class="space-y-3.5 text-sm">
                    @if($tamu->foto_wajah)
                        <div class="text-center mb-3">
                            <img src="{{ asset('storage/' . $tamu->foto_wajah) }}" class="w-24 h-24 object-cover rounded-2xl border-2 border-cyan-400 mx-auto shadow-lg">
                        </div>
                    @endif
                    <div><span class="text-slate-400 block text-xs mb-0.5">Waktu Kedatangan:</span> <strong class="text-white">{{ $tamu->created_at ? $tamu->created_at->format('d F Y - H:i') : '-' }} WIB</strong></div>
                    <div><span class="text-slate-400 block text-xs mb-0.5">Nama Tamu:</span> <strong class="text-white">{{ $tamu->nama }}</strong></div>
                    <div><span class="text-slate-400 block text-xs mb-0.5">Instansi:</span> <strong class="text-white">{{ $tamu->instansi }}</strong></div>
                    <div><span class="text-slate-400 block text-xs mb-0.5">No. HP:</span> <strong class="text-white">{{ $tamu->no_hp }}</strong></div>
                    <div><span class="text-slate-400 block text-xs mb-0.5">Keperluan:</span> <p class="bg-white/5 p-3 rounded-xl border border-white/10 text-slate-200 mt-1 leading-relaxed">{{ $tamu->keperluan }}</p></div>
                </div>
                <div class="mt-6 text-right border-t border-white/10 pt-4">
                    <button onclick="closeModal('modalDetail{{ $tamu->id }}')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-semibold text-sm border border-white/10">Tutup</button>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT -->
        <div id="modalEdit{{ $tamu->id }}" class="fixed inset-0 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4 z-50">
            <div class="bg-slate-900 border border-white/20 rounded-2xl max-w-md w-full p-6 shadow-2xl relative text-white">
                <div class="flex justify-between items-center mb-4 border-b border-white/10 pb-3">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-amber-400"></i> Edit Data Tamu
                    </h3>
                    <button onclick="closeModal('modalEdit{{ $tamu->id }}')" class="text-slate-400 hover:text-white font-bold text-xl">&times;</button>
                </div>
                <form action="{{ route('tamu.update', $tamu->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4 text-sm">
                        <div>
                            <label class="block font-medium text-slate-300 mb-1">Nama Lengkap *</label>
                            <input type="text" name="nama" value="{{ $tamu->nama }}" required class="w-full bg-slate-800/80 border border-white/20 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-amber-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-300 mb-1">Instansi / Perusahaan *</label>
                            <input type="text" name="instansi" value="{{ $tamu->instansi }}" required class="w-full bg-slate-800/80 border border-white/20 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-amber-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-300 mb-1">Nomor WhatsApp / HP *</label>
                            <input type="text" name="no_hp" value="{{ $tamu->no_hp }}" required class="w-full bg-slate-800/80 border border-white/20 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-amber-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-300 mb-1">Keperluan Kunjungan *</label>
                            <textarea name="keperluan" rows="3" required class="w-full bg-slate-800/80 border border-white/20 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-amber-400 focus:outline-none">{{ $tamu->keperluan }}</textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3 border-t border-white/10 pt-4">
                        <button type="button" onclick="closeModal('modalEdit{{ $tamu->id }}')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl font-semibold text-sm border border-white/10">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-xl font-semibold text-sm border border-white/20 shadow-lg">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- MODAL ZOOM PREVIEW FOTO FULLSIZE -->
    <div id="modalFoto" class="fixed inset-0 bg-black/90 backdrop-blur-md hidden flex items-center justify-center p-4 z-50" onclick="closeModal('modalFoto')">
        <div class="max-w-lg w-full p-2 relative text-center">
            <img id="imgZoom" src="" class="w-full h-auto max-h-[80vh] object-contain rounded-2xl shadow-2xl border-2 border-cyan-400 mx-auto">
            <p class="text-xs text-slate-400 mt-2 font-medium">Klik di mana saja untuk menutup</p>
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