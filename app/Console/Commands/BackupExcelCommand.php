<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\GuestBookExport;
use Maatwebsite\Excel\Facades\Excel;

class BackupExcelCommand extends Command
{
    /**
     * Nama command yang dipanggil via terminal / scheduler
     */
    protected $signature = 'backup:excel';

    /**
     * Deskripsi singkat command
     */
    protected $description = 'Backup data buku tamu ke file Excel secara otomatis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Buat folder & nama file berdasarkan waktu
        $fileName = 'backups/buku_tamu_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Simpan file Excel ke storage/app/public/backups/
        Excel::store(new GuestBookExport, $fileName, 'public');

        $this->info("Backup berhasil disimpan: {$fileName}");
    }
}