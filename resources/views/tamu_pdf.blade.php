<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Buku Tamu - KSOP Banten</title>
    <style>
      
        @page { 
            margin: 1.2cm; 
        }

        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            color: #333; 
            line-height: 1.4;
        }

        /* Kop / Header Laporan */
        .header { 
            text-align: center; 
            margin-bottom: 15px; 
            border-bottom: 2px solid #0f172a; 
            padding-bottom: 8px; 
        }
        .header h2 { 
            margin: 0; 
            text-transform: uppercase; 
            font-size: 16px; 
            color: #0f172a; 
            letter-spacing: 0.5px;
        }
        .header p { 
            margin: 4px 0 0; 
            font-size: 11px; 
            color: #475569; 
        }
        
        /* Ringkasan Filter */
        .card-summary { 
            background-color: #f8fafc; 
            border: 1px solid #cbd5e1; 
            padding: 8px 12px; 
            border-radius: 5px; 
            margin-bottom: 15px; 
        }
        .card-summary strong { 
            color: #0f172a; 
        }

        /* Styling Tabel DomPDF */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 5px; 
        }
        table, th, td { 
            border: 1px solid #94a3b8; 
        }
        
        /*supaua Header Tabel Mengulang Otomatis di Halaman Selanjutnya */
        thead { 
            display: table-header-group; 
        }
        tr { 
            page-break-inside: avoid; 
        }

        th { 
            background-color: #f1f5f9; 
            padding: 8px 6px; 
            text-align: left; 
            font-size: 10px; 
            text-transform: uppercase; 
            color: #1e293b; 
            font-weight: bold;
        }
        td { 
            padding: 6px; 
            vertical-align: top; 
            font-size: 10.5px;
        }
        .text-center { 
            text-align: center; 
        }

        /* Seksi Tanda Tangan Petugas */
        .footer-section {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-box {
            float: right;
            width: 230px;
            text-align: center;
        }
        .signature-space {
            height: 50px;
        }
    </style>
</head>
<body>

    <!-- Header Laporan -->
    <div class="header">
        <h2>REKAPITULASI BUKU TAMU KSOP BANTEN</h2>
        <p>Periode Rekap: <strong>{{ $periodeText }}</strong></p>
    </div>

    <!-- Ringkasan Data -->
    <div class="card-summary">
        <strong>Ringkasan Laporan:</strong><br>
        • Total Kunjungan Tamu: <strong>{{ $totalTamu }} Orang</strong>
    </div>

    <!-- Tabel Data Tamu -->
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">NO</th>
                <th width="15%">WAKTU DATANG</th>
                <th width="20%">NAMA TAMU</th>
                <th width="20%">INSTANSI</th>
                <th width="15%">NO. HP</th>
                <th width="25%">KEPERLUAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tamus as $index => $tamu)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $tamu->created_at ? $tamu->created_at->format('d/m/Y H:i') . ' WIB' : '-' }}</td>
                <td><strong>{{ $tamu->nama }}</strong></td>
                <td>{{ $tamu->instansi }}</td>
                <td>{{ $tamu->no_hp }}</td>
                <td>{{ $tamu->keperluan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px; color: #94a3b8;">
                    Tidak ada data kunjungan tamu pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan & Pengesahan -->
    <div class="footer-section">
        <div class="signature-box">
            <p>Banten, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><strong>Petugas / Admin KSOP Banten</strong></p>
            <div class="signature-space"></div>
            <p><strong>( ________________________ )</strong></p>
        </div>
    </div>

</body>
</html>