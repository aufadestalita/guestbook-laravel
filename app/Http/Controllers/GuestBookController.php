<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuestBook; 
use Cloudinary\Cloudinary;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class GuestBookController extends Controller
{
    // 1. Fungsi index() untuk menampilkan form buku tamu
    public function index()
    {
        return view('buku_tamu'); 
    }

    // 2. Fungsi store() untuk memproses webcam & simpan ke Cloudinary + DB
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'instansi'  => 'required|string|max:255',
            'no_hp'     => 'required|string|max:20',
            'keperluan' => 'required|string',
        ]);

        $pathFoto = null;

        // Jika ada string foto dari webcam
        if ($request->foto_wajah) {
            $cloudinary = new Cloudinary();

            $imageData = $request->foto_wajah;

            // Pastikan formatnya sudah berawalan Data URI
            if (!str_starts_with($imageData, 'data:image')) {
                $imageData = 'data:image/png;base64,' . $imageData;
            }

            // Upload langsung string Base64 webcam ke Cloudinary
            $upload = $cloudinary->uploadApi()->upload($imageData, [
                'folder' => 'guestbook_photos'
            ]);

            // Ambil URL HTTPS permanen dari Cloudinary
            $pathFoto = $upload['secure_url'];
        }

        // Simpan data tamu & URL gambar Cloudinary ke Database MySQL
        GuestBook::create([
            'nama'       => $request->nama,       
            'instansi'   => $request->instansi,
            'no_hp'      => $request->no_hp,
            'keperluan'  => $request->keperluan,
            'foto_wajah' => $pathFoto,
        ]);

        return redirect()->back()->with('success', 'Data kunjungan tamu berhasil disimpan!');
    }

    // 3. Fungsi exportPdf() untuk cetak rekap PDF
    public function exportPdf(Request $request)
    {
        Carbon::setLocale('id');

        $tamus = GuestBook::latest()->get();
        $totalTamu = $tamus->count();
        $periodeText = 'Semua Periode';

        // Sesuaikan nama folder/file view template PDF kamu di sini 
        // (contoh jika nama filenya: resources/views/rekap_pdf.blade.php)
        $pdf = Pdf::loadView('rekap_pdf', compact('tamus', 'totalTamu', 'periodeText'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Rekap-Buku-Tamu-KSOP-' . date('Y-m-d') . '.pdf');
    }
}