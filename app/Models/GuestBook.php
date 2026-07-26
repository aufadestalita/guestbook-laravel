<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestBook extends Model
{
    use HasFactory;

    // Menentukan nama tabel (opsional, tapi bagus untuk dokumentasi)
    protected $table = 'guest_books';

    // Mendaftarkan kolom yang boleh diisi massal
    protected $fillable = [
        'nama',
        'instansi',
        'keperluan',
        'foto_wajah',
        'no_hp'
        
    ];
}