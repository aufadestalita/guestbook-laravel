<?php

namespace App\Exports;

use App\Models\GuestBook;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GuestBookExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return GuestBook::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Tamu',
            'Instansi',
            'No HP',
            'Keperluan',
            'URL Foto (Cloudinary)',
            'Waktu Kunjungan',
        ];
    }

    /**
     * Mapping nama kolom sesuai database kamu
     */
    public function map($guest): array
    {
        return [
            $guest->id,
            $guest->nama,
            $guest->instansi,
            $guest->no_hp,
            $guest->keperluan,
            $guest->foto_wajah,
            $guest->created_at ? $guest->created_at->format('Y-m-d H:i:s') : '-',
        ];
    }
}