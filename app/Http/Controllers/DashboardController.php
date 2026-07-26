<?php

namespace App\Http\Controllers;

use App\Models\GuestBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Index Dashboard dengan Fitur Filter (Hari, Minggu, Bulanan, Semua)
    public function index(Request $request)
    {
        $filterType = $request->get('filter_type', 'semua');
        $query = GuestBook::query();

        if ($filterType === 'hari') {
            // Filter Per Tanggal
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            $query->whereDate('created_at', $tanggal);

        } elseif ($filterType === 'minggu') {
            // Filter Rentang Tanggal
            $tglMulai = $request->get('tgl_mulai', now()->startOfWeek()->format('Y-m-d'));
            $tglSelesai = $request->get('tgl_selesai', now()->endOfWeek()->format('Y-m-d'));

            $query->whereBetween('created_at', [
                $tglMulai . ' 00:00:00',
                $tglSelesai . ' 23:59:59'
            ]);

        } elseif ($filterType === 'bulanan') {
            // Filter Bulan & Tahun
            $bulan = $request->get('bulan', date('m'));
            $tahun = $request->get('tahun', date('Y'));

            $query->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun);
        }

        $tamus = $query->latest()->get();

        return view('dashboard', compact('tamus', 'filterType'));
    }

    // Ekspor PDF dengan Filter Harian, Mingguan, Bulanan, & Semua Data
    public function exportPdf(Request $request)
    {
        Carbon::setLocale('id'); // format Bahasa Indonesia
        
        $filterType = $request->get('filter_type', 'semua');
        $query = GuestBook::query();
        $periodeText = 'Semua Data Kunjungan';

        if ($filterType === 'hari') {
            // Filter Tanggal Spesifik
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            $query->whereDate('created_at', $tanggal);

            $tglFormat = Carbon::parse($tanggal)->translatedFormat('d F Y');
            $periodeText = "Harian ($tglFormat)";

        } elseif ($filterType === 'minggu') {
            // Filter Rentang Tanggal
            $tglMulai = $request->get('tgl_mulai', now()->startOfWeek()->format('Y-m-d'));
            $tglSelesai = $request->get('tgl_selesai', now()->endOfWeek()->format('Y-m-d'));

            $query->whereBetween('created_at', [
                $tglMulai . ' 00:00:00',
                $tglSelesai . ' 23:59:59'
            ]);

            $tgl1 = Carbon::parse($tglMulai)->translatedFormat('d M Y');
            $tgl2 = Carbon::parse($tglSelesai)->translatedFormat('d M Y');
            $periodeText = "Mingguan ($tgl1 s/d $tgl2)";

        } elseif ($filterType === 'bulanan') {
            // Filter Bulan & Tahun
            $bulan = $request->get('bulan', date('m'));
            $tahun = $request->get('tahun', date('Y'));

            $query->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun);

            $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');
            $periodeText = "Bulanan ($namaBulan $tahun)";
        }

       
        $tamus = $query->orderBy('created_at', 'asc')->get();
        $totalTamu = $tamus->count();

        // Render ke View PDF
        $pdf = Pdf::loadView('tamu_pdf', compact('tamus', 'periodeText', 'totalTamu'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('rekap-buku-tamu-' . $filterType . '-' . time() . '.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'instansi'   => 'required|string|max:255',
            'no_hp'      => 'required|string|max:20',
            'keperluan'  => 'required|string',
            'foto_wajah' => 'nullable|string',
        ]);

        $fotoPath = null;

        if ($request->foto_wajah && str_contains($request->foto_wajah, 'data:image')) {
            $imageParts = explode(";base64,", $request->foto_wajah);
            $imageBase64 = base64_decode($imageParts[1]);
            $fileName = 'tamu_' . time() . '_' . uniqid() . '.png';

            Storage::disk('public')->put('foto_tamu/' . $fileName, $imageBase64);
            $fotoPath = 'foto_tamu/' . $fileName;
        }

        GuestBook::create([
            'nama'       => $request->nama,
            'instansi'   => $request->instansi,
            'no_hp'      => $request->no_hp,
            'keperluan'  => $request->keperluan,
            'foto_wajah' => $fotoPath,
        ]);

        return redirect()->back()->with('success', 'Data tamu berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'instansi'  => 'required|string|max:255',
            'no_hp'     => 'required|string|max:20',
            'keperluan' => 'required|string',
        ]);

        $tamu = GuestBook::findOrFail($id);
        $tamu->update([
            'nama'      => $request->nama,
            'instansi'  => $request->instansi,
            'no_hp'     => $request->no_hp,
            'keperluan' => $request->keperluan,
        ]);

        return redirect()->back()->with('success', 'Data tamu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tamu = GuestBook::findOrFail($id);

        if ($tamu->foto_wajah && Storage::disk('public')->exists($tamu->foto_wajah)) {
            Storage::disk('public')->delete($tamu->foto_wajah);
        }

        $tamu->delete();

        return redirect()->back()->with('success', 'Data tamu berhasil dihapus!');
    }
}