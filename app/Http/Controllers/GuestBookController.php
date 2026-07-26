<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\GuestBook; // Ganti dengan nama Model lu kalau berbeda (misal Tamu)

class GuestBookController extends Controller
{
    // 1. Fungsi index() yang kehapus kita balikin lagi di sini
    public function index()
    {
        // Ganti 'bukutamu' di bawah ini dengan nama file blade form lu 
        // (Misal nama filenya 'isi-buku-tamu.blade.php', berarti tulis 'isi-buku-tamu')
        return view('buku_tamu'); 
    }

    // 2. Fungsi store() untuk memproses webcam dan simpan ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'instansi'  => 'required|string|max:255',
            'no_hp'     => 'required|string|max:20',
            'keperluan' => 'required|string',
        ]);

        $pathFoto = null;

        if ($request->foto_wajah) {
            $image = $request->foto_wajah; 
            
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            
            $imageName = 'foto_' . time() . '_' . uniqid() . '.png';
            
            Storage::disk('public')->put('foto_tamu/' . $imageName, base64_decode($image));
            
            $pathFoto = 'foto_tamu/' . $imageName;
        }

        GuestBook::create([
            'nama'       => $request->nama,       
            'instansi'   => $request->instansi,
            'no_hp'      => $request->no_hp,
            'keperluan'  => $request->keperluan,
            'foto_wajah' => $pathFoto, 
        ]);

        return redirect()->back()->with('success', 'Data kunjungan tamu berhasil disimpan!');
    }
}