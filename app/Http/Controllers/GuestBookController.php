<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuestBook; 
use Cloudinary\Cloudinary; // 1. Import SDK Cloudinary

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

            // Pastikan formatnya sudah berawalan Data URI (data:image/png;base64,...)
            if (!str_starts_with($imageData, 'data:image')) {
                $imageData = 'data:image/png;base64,' . $imageData;
            }

            // Upload langsung string Base64 webcam ke Cloudinary
            $upload = $cloudinary->uploadApi()->upload($imageData, [
                'folder' => 'guestbook_photos' // Folder simpan di Cloudinary
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
            'foto_wajah' => $pathFoto, // Sekarang berisi URL: https://res.cloudinary.com/...
        ]);

        return redirect()->back()->with('success', 'Data kunjungan tamu berhasil disimpan!');
    }
}